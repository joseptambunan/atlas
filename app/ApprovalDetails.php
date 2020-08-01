<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ApprovalDetails extends Model
{
    //
    public function user_detail(){
    	return $this->belongsTo("App\User","approval_by");
    }

    public function getStatusDescriptionAttribute(){
    	$array_status = array(
    		"0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
    		"1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
    		"2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
    		"3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
    	);


    	return $array_status[$this->status];
    }

    public function approval(){
        return $this->belongsTo("App\Approvals");
    }

    public function getCreatedAttribute(){
        $user = User::find($this->created_by);
        return $user;
    }

}
