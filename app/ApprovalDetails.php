<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalDetails extends Model
{
    //
    public function user_detail(){
    	return $this->belongsTo("App\User","approval_by");
    }

    public function getStatusDescriptionAttribute(){
    	$array_status = array(
    		"0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
    		"1" => array( "label" => "Waiting for Approval", "class" => "label label-info", "status" => 1  ),
    		"2" => array( "label" => "Reject", "class" => "label label-info", "status" => 2  ),
    		"3" => array( "label" => "Approval", "class" => "label label-info", "status" => 3  )
    	);


    	return $array_status[$this->status];
    }
}
