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


class IousController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('adjuster::index');
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
        return view('adjuster::iou.add',compact("user","config_sidebar","adjuster_data"));
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

        $check_approval = "";
        if ( in_array($iou_data->status['status'], $array_approval)){
            $check_approval = true;
        }
        return view('adjuster::iou.show',compact("user","config_sidebar","iou_data","adjuster_data","check_approval"));
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

    public function approval(Request $request){
        $data['status'] = 0;
        $approval = new Approvals;
        $approval->document_type = "iou";
        $approval->document_id = $request->id;
        $approval->status = 1;
        $approval->created_by = Auth::user()->id;
        $approval->created_at = date("Y-m-d H:i:s");
        $approval->save();
        echo json_encode($data);
    }
}