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
        $list_approval = array();

        $i = 0 ;
        foreach ($user->approval_detail as $key => $value) {
            $master_document = MasterDocument::find($value->approval->document_type);
            switch (strtolower(trim($master_document->document))) {
                case 'iou':
                    $approval_data = IouLists::find($value->approval->document_id);
                    if ( $value->approval_by == $user->id ){
                        $list_approval[$i] = array(
                            "title" => $approval_data->title,
                            "document_type" => strtolower(trim($master_document->document)),
                            "created_at" => date("d-M-Y",strtotime($approval_data->created_at)),
                            "created_by" => $approval_data->created->adjusters->name,
                            "status" => $approval_data->status['label'],
                            "document_id" => $approval_data->id,
                            "approval_id" => $value->id
                        );
                        $i++;
                    }

                    break;
                case 'expenses':
                    $approval_data = CaseExpenses::find($value->document_id);
                    break;
            }

        }

        return view('approval::approval.index',compact("user","config_sidebar","adjuster_data","list_approval"));
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
            "3" => array( "label" => "Approval", "class" => "label label-info", "status" => 3  ),
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

        $approval = Approvals::find($approval_detail->approval->id);
        $array_jabatan = array();
        foreach ($approval->details as $key => $value) {
            array_push($array_jabatan, $value->level );
        }

        ksort($array_jabatan);
        foreach ($array_jabatan as $key => $value) {
            if ( $value == $approval_detail->level ){
                $approval->status = $request->status;
                $approval->approval_at = date("Y-m-d H:i:s");
                $approval->updated_at = date("Y-m-d H:i:s");
                $approval->updated_by = Auth::user()->id;
                $approval->description = $request->description;
                //$approval_detail->save();
            }
        }

        error_log("[APPROVAL_DETAIL]".$approval_detail);
        error_log("[APPROVAL]".$approval);
        $data['status'] = 0;
        echo json_encode($data);
    }
}
