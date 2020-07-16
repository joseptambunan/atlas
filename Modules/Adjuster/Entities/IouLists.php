<?php

namespace Modules\Adjuster\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Adjuster\Entities\IouLists;
use App\Approvals;
use App\ApprovalHistories;
use App\User;

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
    		"1" => array( "label" => "Waiting for Approval", "class" => "label label-info", "status" => 1  ),
    		"2" => array( "label" => "Reject", "class" => "label label-info", "status" => 2  ),
    		"3" => array( "label" => "Approval", "class" => "label label-info", "status" => 3  )
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
}
