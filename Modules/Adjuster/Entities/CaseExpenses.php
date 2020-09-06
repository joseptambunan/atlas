<?php

namespace Modules\Adjuster\Entities;
use Modules\Adjuster\Entities\CaseExpenses;
use Illuminate\Database\Eloquent\Model;
use App\Approvals;
use App\User;

class CaseExpenses extends Model
{
    protected $fillable = [];

    public function status_approval($user_id){
    	$case_expenses = CaseExpenses::find($this->id);
    	$array_status = array(
    		"0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
    		"1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
    		"2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
    		"3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
    	);

    	$check_approval = Approvals::where("document_type",2)->where("document_id",$case_expenses->id)->get();
    	if ( count($check_approval) <= 0){
    		return $array_status[0];
    	}else{
    		$approval = Approvals::find($check_approval->first()->id);
            if ( $approval->created_by == $user_id){
                return $array_status[$approval->status];
            }
            /*foreach ($approval->details as $key => $value) {
                if ( $value->approval_by == $user_id){
                    return $array_status[$value->status];
                }
            }*/
            return $array_status[1];
    	}
    }

    public function iou_lists(){
        return $this->belongsTo("Modules\Adjuster\Entities\IouCases");
    }

    public function getCreatedAttribute(){
        $user = "";
        $user = User::find($this->created_by);
        return $user;
    }

    public function approval_data($user_id){
        $array = array(
            "approval_id" => "" ,
            "approval_detail_id" => ""
        );

        $check_approval = Approvals::where("document_type",2)->where("document_id",$this->id)->get();
        if ( count($check_approval) > 0 ){
            $approval = Approvals::find($check_approval->first()->id);
            foreach ($approval->details as $key => $value) {
                if ( $value->approval_by == $user_id ) {
                    $array['approval_id'] = $approval->id;
                    $array['approval_detail_id'] = $value->id;
                }
            }
        }

        return $array;
    }

    public function getListApprovaAttribute(){
        $case_expenses = CaseExpenses::find($this->id);
        $check_approval = Approvals::where("document_type",2)->where("document_id",$case_expenses->id)->get();
        $approval = Approvals::find($check_approval->first()->id);
        return $approval;
    }

    public function getStatusAttribute(){
        $case_expenses = CaseExpenses::find($this->id);
        $array_status = array(
            "0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
            "1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
            "2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
            "3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
        );

        $check_approval = Approvals::where("document_type",2)->where("document_id",$case_expenses->id)->get();
        if ( count($check_approval) <= 0){
            return $array_status[0];
        }else{
            $approval = Approvals::find($check_approval->first()->id);
            return $array_status[$approval->status];
        }
    }

    public function master_casenumbers(){
        return $this->belongsTo("Modules\Master\Entities\MasterCasenumbers");
    }
}
