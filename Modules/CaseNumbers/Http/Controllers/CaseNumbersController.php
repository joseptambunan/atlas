<?php

namespace Modules\CaseNumbers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCasenumbers;
use Modules\Master\Entities\MasterAdjusters;
use Modules\CaseNumbers\Entities\AdjusterCasenumbers;
use Modules\Adjuster\Entities\IouLists;
use Modules\CaseNumbers\Entities\Invoices;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Master\Entities\MasterInsurance;
use Modules\Master\Entities\MasterDivision;
use Modules\Adjuster\Entities\CaseExpenses;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\MasterDocument;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Approvals;
use App\ApprovalDetails;
use App\Export;
use App\Jobs\SendEmailCase;
use App\Jobs\SendIouConfirmed;

class CaseNumbersController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {

        if ( !(Auth::check())){
            return redirect("access/logout");
        }

        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = MasterCasenumbers::orderBy('created_at', 'DESC')->get();

        $total_iou = 0;
        $iou_list = IouLists::where("document_number",NULL)->get();
        foreach ($iou_list as $key => $value) {
            if ( $value->status['status'] == 3 ){
                $total_iou  += 1;
            }
        }
        return view('casenumbers::index',compact("user","config_sidebar","master_casenumbers","total_iou"));
    }

    public function add(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjuster = MasterAdjusters::get();
        $master_insurance = MasterInsurance::get();
        $master_division = MasterDivision::get();
        return view('casenumbers::add',compact("user","config_sidebar","master_adjuster","master_insurance","master_division"));
    }

    public function create(Request $request){
        $casenumber = new MasterCasenumbers;
        $casenumber->case_number = $request->casenumber;
        $casenumber->title = strtoupper($request->title);
        $casenumber->created_at = date("Y-m-d H:i:s");
        $casenumber->created_by = Auth::user()->id;
        $casenumber->insurance_id = $request->insurance;
        $casenumber->division_id = $request->division;
        $casenumber->save();

        return redirect("/casenumbers/show/".$casenumber->id);
    }

    public function show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $casenumber = MasterCasenumbers::find($request->id);
        $adjuster_list = MasterAdjusters::get();
        $master_insurance = MasterInsurance::get();
        $master_division = MasterDivision::get();

        $status = 'offline';
        if ( $casenumber->deleted_at != ""){
            $status = 'online';
        }

        $class = array(
            "online" => array(
                "button" => "btn-success"
            ),
            "offline" => array(
                "button" => "btn-danger"
            )
        );
        return view('casenumbers::show',compact("user","config_sidebar","casenumber","status","class","adjuster_list","master_insurance","master_division"));

    }

    public function update(Request $request){
        $casenumber = MasterCasenumbers::find($request->casenumber_id);
        $casenumber->case_number = $request->casenumber;
        $casenumber->title = $request->title;
        $casenumber->updated_at = date("Y-m-d H:i:s");
        $casenumber->updated_by = Auth::user()->id;
        $casenumber->save();
        return redirect("/casenumbers/show/".$casenumber->id);
    }

    public function delete(Request $request){
        $casenumber = MasterCasenumbers::find($request->id);
        if ( $request->status == "online"){
            $casenumber->deleted_at = NULL;
        }else{
            $casenumber->deleted_at = date("Y-m-d H:i:s");
        }

        $casenumber->save();
        $data['status'] = 0;
        echo json_encode($data);
    }

    public function saveadjusters(Request $request){

        $case_number = MasterCasenumbers::find($request->casenumber_id);
        if ( count($request->adjuster) <= 0 ){
            return redirect("/casenumbers/adjuster/all/".$request->casenumber_id);
        }

        foreach ($request->adjuster as $key => $value) {
            $adjuster_case = new AdjusterCasenumbers;
            $adjuster_case->adjuster_id = $value;
            $adjuster_case->case_number_id = $request->casenumber_id;
            $adjuster_case->created_by = Auth::user()->id;
            $adjuster_case->created_at = date("Y-m-d H:i:s");
            $adjuster_case->save();
            $adjuster = AdjusterCasenumbers::find($adjuster_case->id);
            SendEmailCase::dispatch($adjuster);
        }


        return redirect("/casenumbers/adjuster/all/".$request->casenumber_id);
    }

    public function alladjuster(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjuster = MasterAdjusters::whereIn("position_id",[2,3,4])->get();
        $case_number = MasterCasenumbers::find($request->id);
        return view('casenumbers::adjuster',compact("user","config_sidebar","master_adjuster","case_number"));
    }

    public function removeadjuster(Request $request){
        $adjuster_case = AdjusterCasenumbers::find($request->id);
        $adjuster_case->deleted_at = date("Y-m-d H:i:s");
        $adjuster_case->deleted_by = Auth::user()->id;
        $adjuster_case->save();
        return redirect("/casenumbers/adjuster/all/".$adjuster_case->case_number_id);

    }
    

    public function search_case(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = array();
        $search_by = $request->search_by;
        $keyword = $request->keyword;

        switch ( $search_by ){
            case 'client':
                $array_id = array();
                $master_iou = IouLists::where("client",'like','%'.$keyword.'%')->get();
                if ( count($master_iou) > 0 ){
                    foreach ($master_iou as $key => $value) {
                        $iou_list = IouLists::find($value->id);
                        foreach ($iou_list->cases as $key_cases => $value_cases) {
                            array_push($array_id, $value_cases->iou_lists_id);
                        }
                    }
                }
                $master_casenumbers = MasterCasenumbers::whereIn("id",$array_id)->get();
            break;

            case 'title':
                $master_casenumbers = MasterCasenumbers::where("title",'like','%'.$keyword.'%')->get();
            break;

            case 'adjusters':
                $array_id = array();
                $check_user = MasterAdjusters::where("name",'like','%'.$keyword.'%')->get();
                if ( count($check_user) > 0 ){
                    $adjuster = MasterAdjusters::find($check_user->first()->id);
                    foreach ($adjuster->cases as $key => $value) {
                        array_push($array_id, $value->case_number_id);
                    }
                }

                $master_casenumbers = MasterCasenumbers::whereIn("id",$array_id)->get();
            break;

            case 'case':
                $master_casenumbers = MasterCasenumbers::where("case_number",'like','%'.$keyword.'%')->get();
            break;
        }

        $total_iou = 0;
        $iou_list = IouLists::where("document_number",NULL)->get();
        foreach ($iou_list as $key => $value) {
            if ( $value->status['status'] == 3 ){
                $total_iou  += 1;
            }
        }

        return view('casenumbers::index',compact("user","config_sidebar","master_casenumbers","total_iou"));
    }

    
}
