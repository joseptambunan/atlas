<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\MasterConfigs;
use Modules\Adjuster\Entities\IouLists;
use App\Approvals;
use App\ApprovalDetails;

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
    	return $this->hasMany("Modules\Adjuster\Entities\IouLists","adjuster_id")->where("deleted_at",NULL);
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
                if ( $value->status['status'] == 3 ){
    
                    $remaining = round (( strtotime("now") - strtotime($value->created_at)) / 86400);
                   
                    if ( $remaining > (0.8 * $config_iou)) {
                           $array[$i] = array(
                            "title" => $value->title,
                            "created_at" => $value->created_at,
                            "ammount" => $value->total,
                            "remaining" => round($remaining)
                        );
                        $i++;
                    }
                }
            }
        }

        return $array;
    }

    public function getToDoAttribute(){
        $arary_todolist = array(
            "approval" => array(
                "total" => count($this->user_detail->approval_detail),
                "label" => "Request Approve",
                "class" => "label label-warning",
                "link" => "/approval/index"
            ),
            "pending_iou" => array (
                "total" => count($this->iou_not_complete_team),
                "label" => "IOU Not Complete by Team",
                "class" => "label label-danger",
                "link" => "/adjuster/iou/team"
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
                "total" => 0,
                "label" => "Pending Invoice",
                "class" => "label label-danger",
                "link" => "/adjuster/invoice/index"
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
                    if ( $value->status['status'] == 3 ){
                        $remaining = round (( strtotime("now") - strtotime($value->created_at)) / 86400);
                   
                        if ( $remaining > (0.8 * $config_iou)) {
                               $array[$i] = array(
                                "title" => $value->title,
                                "created_at" => $value->created_at,
                                "ammount" => $value->total,
                                "remaining" => round($remaining)
                            );
                            $i++;
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
}
