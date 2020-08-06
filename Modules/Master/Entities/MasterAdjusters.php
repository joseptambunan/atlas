<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\MasterConfigs;
use Modules\Adjuster\Entities\IouLists;
use App\Approvals;
use App\ApprovalDetails;
use Modules\Master\Entities\MasterCaseNumbers;
use Modules\CaseNumbers\Entities\Invoices;
use Modules\Adjuster\Entities\CaseExpenses;

class MasterAdjusters extends Model
{
    protected $fillable = [];

    public function position(){
    	return $this->belongsTo("Modules\Master\Entities\MasterPositions","position_id");
    }

    public function cases(){
    	return $this->hasMany("Modules\CaseNumbers\Entities\AdjusterCasenumbers","adjuster_id");
    }

    public function ious(){
    	return $this->hasMany("Modules\Adjuster\Entities\IouLists","adjuster_id")->where("deleted_at",NULL)->orderBy("updated_at","desc");
    }

    public function user_detail(){
        return $this->hasOne("App\User","adjuster_id");
    }

    public function getIouNotCompleteAttribute(){
        $array = array();
        $i = 0;
        $config_iou = MasterConfigs::where("name","submit_expenses")->get()->first()->value;
        foreach ($this->ious as $key => $value) {
            if ( count($value->status) > 0 ){
                if ($value->status['status'] == 3 ){
                    $remaining = round (( strtotime("now") - strtotime($value->created_at)) / 86400);

                    if ( ($value->expenses_approval['total_approval'] != $value->expenses_approval['total_expenses']) || count($value->expenses) <= 0 ){    
                        if ( $remaining > (0.8 * $config_iou)) {
                               $array[$i] = array(
                                "title" => $value->title,
                                "created_at" => $value->created_at,
                                "ammount" => $value->total,
                                "remaining" => round($remaining),
                                "status" => $value->status['label'],
                                "created" => $value->created->adjusters->name,
                                "id" => $value->id,
                                "client" => $value->client,
                                "cases" => $value->cases,
                                "total_expenses" => $value->expenses_approval['total_expenses'],
                                "total_approval" => $value->expenses_approval['total_approval']
                            );
                            $i++;
                        }
                    }
                }
            }
        }

        return $array;
    }

    public function getToDoAttribute(){
        $arary_todolist = array(
            "approval" => array(
                "total" => count($this->list_approval),
                "label" => "Request Approve",
                "class" => "label label-warning",
                "link" => "/approval/index"
            ),
            "pending_iou" => array (
                "total" => count($this->iou_not_complete_team),
                "label" => "IOU Not Complete by Team",
                "class" => "label label-danger",
                "link" => "/approval/iou/team"
            ),
            "iou" => array (
                "total" => count($this->iou_not_complete),
                "label" => "IOU Not Complete",
                "class" => "label label-danger",
                "link" => "/adjuster/iou/index"
            ),
            "expired" => array(
                "total" => count($this->iou_expired),
                "label" => "IOU Expired",
                "class" => "label label-danger",
                "link" => "/adjuster/iou/expired"
            ),
            "invoice" => array(
                "total" => count($this->pending_invoice),
                "label" => "Pending Invoice",
                "class" => "label label-danger",
                "link" => "/approval/invoice/index"
            ),
            "confirm_invoice" => array(
                "total" => count($this->confirm_invoice),
                "label" => "Invoice to be confirm",
                "class" => "label label-danger",
                "link" => "/adjuster/invoice"
            )
        );

        
        return $arary_todolist;
    }

    public function getIouNotCompleteTeamAttribute(){
        $detail_jabatan = $this->position;
        $array = array();
        $masterIou = IouLists::where("deleted_at",NULL)->get();
        $config_iou = MasterConfigs::where("name","submit_expenses")->get()->first()->value;
        $i = 0;

        if ( count($detail_jabatan->approval) > 0 ){
            foreach ($masterIou as $key => $value) {
                if ( count($value->status) > 0 ){
                    if ($value->status['status'] == 3 ){
                        $remaining = round (( strtotime("now") - strtotime($value->created_at)) / 86400);

                        if ( $value->expenses_approval['total_approval'] != $value->expenses_approval['total_expenses'] || ( count($value->expenses) <= 0) ){    
                            if ( $remaining > (0.8 * $config_iou)) {
                                   $array[$i] = array(
                                    "title" => $value->title,
                                    "created_at" => $value->created_at,
                                    "ammount" => $value->total,
                                    "remaining" => round($remaining),
                                    "status" => $value->status['label'],
                                    "created_by" => $value->created->adjusters->name,
                                    "id" => $value->id,
                                    "client" => $value->client,
                                    "cases" => $value->cases
                                );
                                $i++;
                            }
                        }
                    }
                }
            }
        }

        return $array;
    }

    public function getIouExpiredAttribute(){
        $array = array();
        $config_manager = MasterConfigs::where("name","approval_manager")->get()->first()->value;
        $config_direksi = MasterConfigs::where("name","approval_direksi")->get()->first()->value;
        $expired_day = $config_direksi + $config_manager;
        $i = 0;
        foreach ($this->ious as $key => $value) {
            if ( $value->status['status'] == 1 || $value->status['status'] == 4 ){
                if ( $value->deleted_by == "" ){
                    $remaining = round (( strtotime("now") - strtotime($value->created_at)) / 86400);
                    if ( $remaining > $expired_day ) {
                        $iou_data = IouLists::find($value->id);
                        if ( $iou_data->deleted_at == "" ){
                            $iou_data->deleted_at = date("Y-m-d H:i:s");
                            $iou_data->deleted_by = $this->user_detail->id;
                            $iou_data->save();
                        }

                        if ( $iou_data->approval != false ){
                            $approval = Approvals::find($iou_data->approval->first()->id);
                            foreach ($approval->details as $key => $value) {
                                $approval_detail = ApprovalDetails::find($value->id);
                                if ( $approval_detail->status == 1 ){
                                    $approval_detail->status = 4;
                                    $approval_detail->description = "This document has expired because not any response from this User";
                                    $approval_detail->approval_at = date("Y-m-d H:i:s");
                                    $approval_detail->save(); 
                                }
                            }
                            $approval->status = 4;
                            $approval->description = "This document has expired because not any response from this User";
                            $approval->approval_at = date("Y-m-d H:i:s");
                            $approval->save(); 
                        }

                        $array[$i] = array(
                            "title" => $iou_data->title,
                            "created_at" => $iou_data->created_at,
                            "ammount" => $iou_data->total,
                            "remaining" => round($remaining)
                        );
                        $i++;
                    }
                }
            }
        }

        return $array;
    }

    public function getPendingInvoiceAttribute(){
        $array = array();
        return $array;
        $detail_jabatan = $this->position;
        if ( count($detail_jabatan->approval) > 0 ){
            $invoice = Invoices::get();
            foreach ($invoice as $key => $value) {
                foreach ($value->cases as $key_cases => $value_cases) {
                    foreach ($value_cases->expenses as $key_expenses => $value_expenses) {
                        
                    }
                }
            }
        }

        return $array;
    }

    public function getConfirmInvoiceAttribute(){
        $array = array();
        $i=0;
        foreach ($this->cases as $key => $value) {
            if ( $value->case->invoice_number != "" ){
                if ( $value->case->invoice->updated_by == "" ){
                    $array[$i] = array(
                        "id" => $value->case->id,
                        "title" => $value->case->title,
                        "created_at" => $value->case->created_at
                    );
                    $i++;
                }
            }
        }

        return $array;
    }

    public function getListApprovalAttribute(){
        $array_approval = array();
        $title = "";
        $array_filter = array();

        foreach ($this->user_detail->approval_detail as $key => $value) {
            if ( $value->status == 1){
                $document_type = trim(strtolower($value->approval->document->document));
                if ( $document_type == "iou"){
                    $title = IouLists::find($value->approval->document_id)->title;
                }elseif ( $document_type == "expenses"){
                    $expenses = CaseExpenses::find($value->approval->document_id);
                    $title =" Expenses from ".$expenses->iou_lists->iou->title;
                }

                if ( !isset($array_filter[strtolower(trim($title))])){
                    $array_approval[] = array(
                        "id" => $value->id,
                        "document_type" => strtolower(trim($value->approval->document->document)),
                        "document_id" => $value->approval->document_id,
                        "title" => $title,
                        "created_at" => date("d-M-Y", strtotime($value->created_at)),
                        "created_by" => $value->created->adjusters->name,
                        "status" => "waiting for approval",
                        "approval_id" => $value->id
                    );
                    $array_filter[strtolower(trim($title))] = array();
                }
            }
        }


        return $array_approval;
    }

}
