<?php

namespace Modules\Adjuster\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Adjuster\Entities\IouLists;
use Modules\Adjuster\Entities\IouCases;
use Modules\Adjuster\Entities\IouDetails;
use App\Approvals;
use Modules\Master\Entities\MasterDocument;
use Modules\Master\Entities\MasterApprovals;
use App\ApprovalDetails;
use App\ApprovalHistories;
use Modules\Master\Entities\MasterInsurance;
use Modules\Master\Entities\MasterDivision;
use Modules\Master\Entities\MasterConfigs;
use Modules\Adjuster\Entities\CaseExpenses;

class IousController extends Controller
{
    
     public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {   
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $i = 0;
        return view('adjuster::iou.index',compact("user","config_sidebar","adjuster_data","i"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $master_insurance = MasterInsurance::get();
        $master_division = MasterDivision::get();
        return view('adjuster::iou.add',compact("user","config_sidebar","adjuster_data","master_insurance","master_division"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $new_iou = new IouLists;
        $new_iou->client = $request->client;
        $new_iou->title = $request->title;
        $new_iou->division = $request->division;
        $new_iou->type_of_survey = $request->tos;
        $new_iou->location = $request->location;
        $new_iou->starttime = date("Y-m-d H:i:s", strtotime($request->datepicker_start));
        $new_iou->endtime = date("Y-m-d H:i:s", strtotime($request->datepicker_end));
        $new_iou->created_at = date("Y-m-d H:i:s");
        $new_iou->created_by = Auth::user()->id;
        $new_iou->adjuster_id = $user->adjuster_id;
        $new_iou->save();

        foreach ($request->case_id as $key => $value) {
            $iou_case = new IouCases;
            $iou_case->adjuster_casenumber_id = $value;
            $iou_case->iou_lists_id = $new_iou->id;
            $iou_case->created_at = date("Y-m-d H:i:s");
            $iou_case->created_by = Auth::user()->id;
            $iou_case->adjuster_id = $user->adjuster_id;
            $iou_case->save();
        }

        return redirect("adjuster/iou/show/".$new_iou->id);
    }

    
    public function show($id)
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $iou_data = IouLists::find($id);
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $array_approval = array("1","3");
        $limit_balance = MasterConfigs::where("name","limit_balance")->get()->first();
        $approval_id = "";

        $check_approval = "";
        if ( in_array($iou_data->status['status'], $array_approval)){
            $check_approval = "exist";
        }

        $approval_histories = array();
        $check_approval_id = Approvals::where("document_type",1)->where("document_id",$iou_data->id)->get();
        if ( count($check_approval_id) > 0 ){
            $approval = Approvals::find($check_approval_id->first()->id);
            $approval_id = $approval->id;
            foreach ($approval->details as $key => $value) {
                $approval_histories[] = array(
                    "name" => $value->user_detail->adjusters->name,
                    "status" => $value->status_description['label'],
                    "class" => $value->status_description['class'],
                    "message" => $value->description
                );
            }
        }

        $balance = $limit_balance->value - $adjuster_data->balance_iou; 
        return view('adjuster::iou.show',compact("user","config_sidebar","iou_data","adjuster_data","check_approval","approval_histories","approval_id","balance"));
    }

    
    public function update(Request $request){
        $iou_data = IouLists::find($request->iou_id);
        $iou_data->client = $request->client;
        $iou_data->title = $request->title;
        $iou_data->division = $request->division;
        $iou_data->type_of_survey = $request->tos;
        $iou_data->location = $request->location;
        $iou_data->updated_at = date("Y-m-d H:i:s");
        $iou_data->updated_by = Auth::user()->id;
        if ( $request->datepicker_start != ""){
            $iou_data->starttime = date("Y-m-d H:i:s", strtotime($request->datepicker_start));
        }

        if ( $request->datepicker_end){
            $iou_data->endtime = date("Y-m-d H:i:s", strtotime($request->datepicker_end));
        }
        $iou_data->save();


        if ( $request->case_id != "" ){
            foreach ($request->case_id as $key => $value) {
                $iou_case = new IouCases;
                $iou_case->adjuster_casenumber_id = $value;
                $iou_case->iou_lists_id = $iou_data->id;
                $iou_case->created_at = date("Y-m-d H:i:s");
                $iou_case->created_by = Auth::user()->id;
                $iou_case->adjuster_id = $user->adjuster_id;
                $iou_case->save();
            }
        }

        return redirect("adjuster/iou/show/".$iou_data->id);
    }

    public function savedetail(Request $request){
        $save_iou_detail = new IouDetails;
        $save_iou_detail->iou_id = $request->iou_id;
        $save_iou_detail->type = $request->type;
        $save_iou_detail->ammount = str_replace(",","",$request->ammount);
        $save_iou_detail->description = $request->desc;
        $save_iou_detail->created_at = date("Y-m-d H:i:s");
        $save_iou_detail->created_by = Auth::user()->id;
        $save_iou_detail->save();

        return redirect("adjuster/iou/show/".$request->iou_id);
    }

    public function delete(Request $request){
        $iou_detail = IouDetails::find($request->id);
        $iou_detail->delete();

        $data['status'] = 0;
        echo json_encode($data);
    }

    public function request_approval(Request $request){
        $approval = new Approvals;
        $is_exist = false;
        if ( $request->approval_id != ""){
            $is_exist = true;
        }
        $approval->request_approval($request->document_type, $request->document_id, $is_exist, Auth::user()->id);
        $data['status'] = 0;
        echo json_encode($data);
    }

    public function request_expenses_approval(Request $request){

        $approval = new Approvals;
        if ( isset($request->checklist)){
            foreach ($request->checklist as $key => $value) {
                $status_approval = CaseExpenses::find($value);
                $is_exist = false;
                if ( $status_approval['status'] > 0 ){
                    $is_exist = true;
                }
                $approval->request_approval($request->document_type, $value, $is_exist, Auth::user()->id);
            }
        }

        return redirect("adjuster/iou/show/".$request->document_id);
    }
    
}
