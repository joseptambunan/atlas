<?php

namespace Modules\Approval\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Adjuster\Entities\IouLists;
use Modules\Master\Entities\MasterDocument;
use Modules\Adjuster\Entities\CaseExpenses;
use App\Approvals;
use App\ApprovalDetails;
use App\ApprovalHistories;
use Modules\Master\Entities\MasterCasenumbers;
use Modules\Master\Entities\MasterPositions;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    
    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $start = 0 ;
        return view('approval::approval.index',compact("user","config_sidebar","adjuster_data","start"));
    }

    
    public function show($type,$id,$approval_id){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $approval_detail = ApprovalDetails::find($approval_id);
        $approval = "";

        $array_status = array(
            "0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
            "1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
            "2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
            "3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
        );

        switch ($type) {
            case 'iou':
                $iou_data = IouLists::find($id);

                $approval = Approvals::find($approval_detail->approval->id);
                foreach ($approval->details as $key => $value) {
                    $approval_histories[] = array(
                        "name" => $value->user_detail->adjusters->name,
                        "status" => $value->status_description['label'],
                        "class" => $value->status_description['class'],
                        "message" => $value->description
                    );

                }
                return view("approval::iou.show",compact("user","config_sidebar","adjuster_data","iou_data","approval_histories","approval_detail","array_status"));
                break;
            case "expenses":
                $case_expenses = CaseExpenses::find($id);
                $iou_id = $case_expenses->iou_lists->iou->id;

                return redirect("/approval/iou/show/".$iou_id);
            default:
                # code...
                break;
        }
    }

    public function submit(Request $request){


        $approval_detail = ApprovalDetails::find($request->approval_id);
        $approval_detail->status = $request->status;
        $approval_detail->approval_at = date("Y-m-d H:i:s");
        $approval_detail->updated_at = date("Y-m-d H:i:s");
        $approval_detail->updated_by = Auth::user()->id;
        $approval_detail->description = $request->description;
        $approval_detail->save();

        $approval_histories = new ApprovalHistories;
        $approval_histories->approval_id = $request->approval_id;
        $approval_histories->approval_by = Auth::user()->id;
        $approval_histories->approval_at = date("Y-m-d H:i:s");
        $approval_histories->status = $request->status;
        $approval_histories->description = $request->description;
        $approval_histories->created_at =  date("Y-m-d H:i:s");
        $approval_histories->created_by = Auth::user()->id;
        $approval_histories->save();

        $approval = Approvals::find($approval_detail->approval->id);
        $array_jabatan = array();
        foreach ($approval->details as $key => $value) {
            array_push($array_jabatan, $value->level );
        }

        $highest_level = min($array_jabatan);

        $master_document = MasterDocument::find($approval->document_type);
        $document_type = strtolower($master_document->document);

        if ( $request->status == 2 ){
            $approval = Approvals::find($approval_detail->approval->id);
            $approval->status = $request->status;
            $approval->approval_at = date("Y-m-d H:i:s");
            $approval->updated_at = date("Y-m-d H:i:s");
            $approval->updated_by = Auth::user()->id;
            $approval->description = "Rejected by Manager with message : ".$request->description;
            $approval->save();

            if ( $document_type == "iou"){
                $iou_list = IouLists::find($approval->document_id);
                $iou_list->updated_at = date("Y-m-d H:i:s");
                $iou_list->updated_by = Auth::user()->id;
                $iou_list->save();
            }

        }else{
            if ( $highest_level == $approval_detail->level ){
                $approval = Approvals::find($approval_detail->approval->id);
                $approval->status = $request->status;
                $approval->approval_at = date("Y-m-d H:i:s");
                $approval->updated_at = date("Y-m-d H:i:s");
                $approval->updated_by = Auth::user()->id;
                $approval->description = $request->description;
                $approval->save();


            }else{
                $approval_histories = new ApprovalHistories;
                $approval_histories->approval_id = $request->approval_id;
                $approval_histories->approval_by = Auth::user()->id;
                $approval_histories->approval_at = date("Y-m-d H:i:s");
                $approval_histories->status = $request->status;
                $approval_histories->description = "Request to Director because manager has approve";
                $approval_histories->created_at =  date("Y-m-d H:i:s");
                $approval_histories->created_by = Auth::user()->id;
                $approval_histories->save();

            }

            if ( $document_type == "iou"){
                $iou_list = IouLists::find($approval->document_id);
                $iou_list->updated_at = date("Y-m-d H:i:s");
                $iou_list->updated_by = Auth::user()->id;
                $iou_list->save();
            }
        }
        

        $data['status'] = 0;
        echo json_encode($data);

        if ( $request->status == 2 && $approval->document_type == 1 ){
            return redirect("/approval/show/iou/".$approval->document_id.'/'.$approval_detail->id);
        }
    }

    public function ioupending(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        return view("approval::iou.index",compact("user","config_sidebar","adjuster_data"));
    }

    public function ioushow(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $iou_data = IouLists::find($request->id);
        $check_approval = Approvals::where("document_type",1)->where("document_id",$iou_data->id)->get();

        if ( count($check_approval) > 0 ){

            $approval = Approvals::find($check_approval->first()->id);
            foreach ($approval->details as $key => $value) {
                $approval_histories[] = array(
                    "name" => $value->user_detail->adjusters->name,
                    "status" => $value->status_description['label'],
                    "class" => $value->status_description['class'],
                    "message" => $value->description
                );

            }

            $approval_id = "";
            foreach ($approval->details as $key => $value) {
                if ( $value->approval_by == $user->id ){
                    $approval_id = $value->id;
                }
            }

            $approval_detail = ApprovalDetails::find($approval_id);
            $array_status = array(
                "0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
                "1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
                "2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
                "3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
                "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
            );
            $start = "approval/iou/team";
            return view("approval::iou.show",compact("user","config_sidebar","adjuster_data","iou_data","approval_histories","approval_detail","array_status","start"));
        }else{
            return redirect("approval/iou/team");
        }
    }

    public function invoice(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        return view("approval::invoice.index",compact("user","config_sidebar","adjuster_data"));
    }

    public function invoice_show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $casenumber = MasterCasenumbers::find($request->id);
        return view("approval::invoice.show",compact("user","config_sidebar","adjuster_data","casenumber"));
    }

    public function request_approval(Request $request){
        $checklist = $request->checklist;
        $position = MasterPositions::get();
        $master_document = MasterDocument::find($request->document_type);
        $redirect = array(
            "1" => "adjuster/iou/show/",
            "2" => "adjuster/iou/show/"
        );
        $approval_id = "";
        if ( isset($request->approval_id) && ($request->approval_id != "" )){
            $approval_id = $request->approval_id;
        }

        $array_document_id = array();
        if ( isset($request->checklist)){
            foreach ($request->checklist as $key => $value) {
                array_push($array_document_id, $value);
            }
        }else{
            array_push($array_document_id, $request->document_id);
        }


        if ( count($array_document_id) > 0 ) {
            foreach ($array_document_id as $key_checklist => $value_checklist) {
                foreach ($master_document->approvals as $key => $value) {
                    foreach ($value->jabatan_approvals->jabatan->adjusters as $key_adjusters => $value_adjusters) {
                        if ( $key == 0 && $key_adjusters == 0 ){
                            if ( $approval_id != ""){
                                $approval = Approvals::find($approval_id);
                            }else{
                                $approval = new Approvals;
                            }   
                            $approval->document_type = $master_document->id;
                            $approval->document_id = $value_checklist;
                            $approval->status = 1;
                            $approval->approval_by = $value_adjusters->user_detail->id;
                            $approval->created_at = date("Y-m-d H:i:s");
                            $approval->created_by = Auth::user()->id;
                            $approval->save();
                        }

                        if ( count($approval->details) <= 0 ){
                            $approval_detail = new ApprovalDetails;
                            $approval_detail->approval_id = $approval->id;
                            $approval_detail->status = 1;
                            $approval_detail->approval_by = $value_adjusters->user_detail->id;
                            $approval_detail->created_at = date("Y-m-d H:i:s");
                            $approval_detail->created_by = Auth::user()->id;
                            $approval_detail->level = $value->level;
                            $approval_detail->save();
                        }else{
                            foreach ($approval->details as $key_detail => $value_detail) {
                                $approval_detail = ApprovalDetails::find($value_detail->id);
                                $approval_detail->status = 1;
                                $approval_detail->updated_at = date("Y-m-d H:i:s");
                                $approval_detail->updated_by = Auth::user()->id;
                                $approval_detail->save();
                            }
                        }
                    }
                }
            }
        }
        
        if ( $request->document_type == 1 ){
            $data['status'] = "0";
            echo json_encode($data);
        }else{
            return redirect($redirect[$master_document->id].$request->document_id);
        }
    }

    public function download($id){
        $case_expenses = CaseExpenses::find($id);
        $receipt = $case_expenses->receipt;
        return Storage::download($receipt);
    }

    public function expenses_approval($id){
        $case_expenses = CaseExpenses::find($id);
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $start = 0 ;
        $array_status = array(
            "0" => array( "label" => "Not Finish", "class" => "label label-info", "status" => 0 ),
            "1" => array( "label" => "Waiting for Approval", "class" => "label label-warning", "status" => 1  ),
            "2" => array( "label" => "Reject", "class" => "label label-danger", "status" => 2  ),
            "3" => array( "label" => "Approval", "class" => "label label-success", "status" => 3  ),
            "4" => array( "label" => "Expired", "class" => "label label-danger", "status" => 4  )
        );

        $approval_detail = "";
        foreach ($case_expenses->list_approva->details as $key => $value) {
            if ( $value->approval_by == $user->id ){
                $approval_detail = $value->id;
            }
        }
        $approval_detail = ApprovalDetails::find($approval_detail);
        return view('approval::iou.expenses',compact("user","config_sidebar","adjuster_data","start","case_expenses","array_status","approval_detail"));
    }
}
