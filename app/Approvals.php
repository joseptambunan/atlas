<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\Adjuster\Entities\IouLists;
use Modules\Adjuster\Entities\CaseExpenses;
use Modules\Master\Entities\MasterDocument;
use App\User;
use App\Approvals;
use App\ApprovalDetails;
use App\Jobs\SendEmailApproval;

class Approvals extends Model
{

    public function _construct(){
        $this->middleware("auth");
    }

    public function details(){
    	return $this->hasMany("App\ApprovalDetails","approval_id")->orderBy("id","desc");
    }

    public function document(){
    	return $this->belongsTo("Modules\Master\Entities\MasterDocument","document_type");
    }

    public function getDetailDocumentAttribute(){
    	$detail = array(
    		"document_type" => "",
    		"document_title" => "",
    		"document_created_by" => ""
    	);

    	if ( $this->document != "" ){
    		switch($this->document->id){
    			case "1":
    			$detail['document_type'] = $this->document->document;
    			$detail['document_title'] = IouLists::find($this->document_id)->title;
    			break;
    			case "2":
    			$detail['document_type'] = $this->document->document;
    			$detail['document_title'] = CaseExpenses::find($this->document_id)->title;
    			break;
    		}
    	}

    	return $detail;

    }

    public function getCreatedDataAttribute(){
        $user = User::find($this->created_by)->adjusters;
        return $user;
    }

    public function getDetailDataAttribute(){
        $data = array(
            "status" => "",
            "latest_update" => "",
            "description" => "",
            "class" => ""
        );

        $array_status = array(
            "2" => array(
                "label" => "Rejected",
                "class" => "font-weight:bolder;color:red"    
            ),
            "3" => array(
                "label" => "Approved",
                "class" => "font-weight:bolder;color:green"
            )
        );

        $data = array(
            "status" => $array_status[$this->status]['label'],
            "class"  => $array_status[$this->status]['class'],
            "description" => $this->description,
            "latest_update" => $this->updated_at,
            "document_type" => $this->document->document,
        );

        return $data;
    }

    public function request_approval($document_type,$document_id,$is_exist,$user_id){
        $detail_document = MasterDocument::find($document_type);

        $approval = new Approvals;
        if ( $is_exist == true ){
            $check = Approvals::where("document_type",$detail_document->id)->where("document_id",$document_id)->get();
            if ( count($check)){
                $approval = Approvals::find($check->first()->id);
            }
        }else{
            $approval->created_at = date("Y-m-d H:i:s");
        }

        $approval->created_by = $user_id;
        $approval->document_id = $document_id;
        $approval->document_type = $detail_document->id;
        $approval->status = 1;
        $approval->save();

        $detail_approval = Approvals::find($approval->id);
        if ( $detail_approval != "" ){
            foreach ($detail_document->approvals as $key => $value) {
                foreach ($value->jabatan_approvals->jabatan->adjusters as $key_detail => $value_detail) {
                    
                    if ( $key == 0 && $key_detail == 0 ){
                        $detail_approval->approval_by = $value_detail->user_detail->id;
                        $detail_approval->save();
                    }

                    $save_approval_detail = new ApprovalDetails;
                    $save_approval_detail->approval_id = $detail_approval->id;
                    $save_approval_detail->status = 1;
                    $save_approval_detail->approval_by = $value_detail->user_detail->id;
                    $save_approval_detail->approval_at = NULL;
                    $save_approval_detail->level = $value->level;
                    $save_approval_detail->created_at = date("Y-m-d H:i:s");
                    $save_approval_detail->created_by = $user_id;
                    $save_approval_detail->save();

                    $approval_detail_ = ApprovalDetails::find($save_approval_detail->id);
                    SendEmailApproval::dispatch($approval_detail_);
               }  
            }
        }else{
            foreach ($detail_approval->details as $key => $value) {
                $approval_detail = ApprovalDetails::find($value->id);
                $approval_detail->status = 1;
                $approval_detail->updated_at = date("Y-m-d H:i:s");
                $approval_detail->save();

                $approval_detail_ = ApprovalDetails::find($approval_detail->id);
                SendEmailApproval::dispatch($approval_detail_);
            }
        }
        return true;
    }
}
