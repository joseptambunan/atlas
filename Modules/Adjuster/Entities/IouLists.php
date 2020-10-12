<?php

namespace Modules\Adjuster\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Adjuster\Entities\IouLists;
use App\Approvals;
use App\ApprovalHistories;
use App\User;
use Modules\Master\Entities\MasterConfigs;

class IouLists extends Model
{
    protected $fillable = [];

    public function cases(){
    	return $this->hasMany("Modules\Adjuster\Entities\IouCases");
    }

    public function getStatusAttribute(){
    	$iou_list = IouLists::find($this->id);
    	$array_status = array(
    		"0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
    		"1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
    		"2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
    		"3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
    	);

    	$check_apprival = Approvals::where("document_type",1)->where("document_id",$iou_list->id)->get();
    	if ( count($check_apprival) <= 0){
    		return $array_status[0];
    	}else{
    		$approval = Approvals::find($check_apprival->first()->id);
    		return $array_status[$approval->status];
    	}

    }

    public function details(){
        return $this->hasMany("Modules\Adjuster\Entities\IouDetails","iou_id");
    }

    public function getTotalAttribute(){
        $total = 0;
        if ( count($this->details) > 0 ){
            foreach ($this->details as $key => $value) {
                $total = $total + $value->ammount;
            }
        }

        return $total;
    }

    public function getCreatedAttribute(){
        $user = "";
        $user = User::find($this->created_by);
        return $user;
    }

    public function expenses(){
        return $this->hasMany("Modules\Adjuster\Entities\CaseExpenses");
    }

    public function getApprovalAttribute(){
        $check_apprival = Approvals::where("document_type",1)->where("document_id",$this->id)->get();
        if ( count($check_apprival) > 0 ){
            return $check_apprival;
        }else{
            return false;
        }
    }

    public function getTotalExpensesAttribute(){
        $total = 0;

        foreach ($this->cases as $key => $value) {
            foreach ($value->expenses as $key_expenses => $value_expenses) {
                $total = $total +  $value_expenses->ammount;
            }
        }

        return $total;
    }

    public function getExpensesApprovalAttribute(){
        $array = array();
        $data['total_approval'] = 0;
        $data['total_expenses'] = 0;

        if (count($this->cases) > 0 ){
            foreach ($this->cases as $key => $value) {
                if ( count($value->expenses) > 0 ){
                    $data['total_expenses'] += count($value->expenses);
                    $start_approval = 0;
                    foreach ($value->expenses as $key_expenses => $value_expenses) {
                        if ( $value_expenses->status['status'] == 3 ){
                            $array[] = array(
                                "id" => $value_expenses->id,
                                "type" => $value_expenses->type,
                                "ammount" => $value_expenses->ammount,
                                "description" => $value_expenses->description,
                                "created_at" => date("d-M-Y", strtotime($value_expenses->created_at))
                            );
                            $start_approval++;
                        }
                    }
                    $data['total_approval'] = $start_approval + $data['total_approval'];
                }
            }
        }

        $data['detail'] = $array;
        return $data;
    }

    public function client_name(){
        return $this->belongsTo("Modules\Master\Entities\MasterInsurance","client");
    }

    public function division_name(){
        return $this->belongsTo("Modules\Master\Entities\MasterDivision","division");
    }

    public function getUserTransferAttribute(){
        $user = User::find($this->document_upload_by);
        return $user;
    }
}
