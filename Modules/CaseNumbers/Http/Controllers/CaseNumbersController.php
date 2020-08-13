<?php

namespace Modules\CaseNumbers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCasenumbers;
use Modules\Master\Entities\MasterAdjusters;
use Modules\CaseNumbers\Entities\AdjusterCasenumbers;
use Modules\Adjuster\Entities\IouLists;
use App\Approvals;
use Modules\CaseNumbers\Entities\Invoices;
use Maatwebsite\Excel\Facades\Excel;
use App\Export;
use Modules\Master\Entities\MasterInsurance;
use Modules\Master\Entities\MasterDivision;

class CaseNumbersController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }


    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = MasterCasenumbers::where("invoice_number",NULL)->orderBy('invoice_number', 'DESC')->get();

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
        $casenumber->title = $request->title;
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
    
    public function createinvoice(Request $request){
        $invoice = new Invoices;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->created_at = date("Y-m-d H:i:s");
        $invoice->created_by = Auth::user()->id;
        $invoice->save();

        $case_number = MasterCasenumbers::find($request->case_id);
        $case_number->invoice_number = $invoice->id;
        $case_number->invoice_number_by = Auth::user()->id;
        $case_number->invoice_number_at = date("Y-m-d H:i:s");
        $case_number->save() ;

        $data['status'] = 0;
        echo json_encode($data);
    }

    public function iou(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_iou = IouLists::where("document_number",NULL)->orderBy("id","DESC")->get();
        return view("casenumbers::iou",compact("user","config_sidebar","master_iou"));
    }

    public function iou_show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $iou_data = IouLists::find($request->id);
        $check_approval = "";
        $approval_histories = array();
        $check_approval_id = Approvals::where("document_type",1)->where("document_id",$iou_data->id)->get();
        if ( count($check_approval_id) > 0 ){
            $approval = Approvals::find($check_approval_id->first()->id);
            foreach ($approval->details as $key => $value) {
                $approval_histories[] = array(
                    "name" => $value->user_detail->adjusters->name,
                    "status" => $value->status_description['label'],
                    "class" => $value->status_description['class'],
                    "message" => $value->description
                );
            }
        }
        return view("casenumbers::iou_show",compact("user","config_sidebar","iou_data","check_approval","approval_histories"));
    }

    public function update_reference(Request $request){
        $iou_data = IouLists::find($request->iou_id);
        $iou_data->document_number = $request->doc_reference;
        $iou_data->updated_at = date("Y-m-d H:i:s");
        $iou_data->updated_by = Auth::user()->id;
        $iou_data->save();

        return redirect("casenumbers/iou/show/".$iou_data->id);

    }

    public function search(Request $request){
        $search_by = $request->search_by;
        $keyword = $request->keyword;
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_iou = array();

        switch ( $search_by ){
            case 'client':
                $master_iou = IouLists::where("client",'like','%'.$keyword.'%')->get();
            break;

            case 'title':
                $master_iou = IouLists::where("title",'like','%'.$keyword.'%')->get();
            break;

            case 'adjusters':
                $check_user = MasterAdjusters::where("name",'like','%'.$keyword.'%')->get();
                if ( count($check_user) > 0 ){
                    $adjuster = MasterAdjusters::find($check_user->first()->id);
                    $master_iou = IouLists::where("created_by",$adjuster->user_detail->id)->get();
                }
            break;

            case 'case':
                $check_case = MasterCasenumbers::where("title",'like','%'.$keyword.'%')->get();
                if ( count($check_case) > 0 ){
                    $iou_id = array();
                    $data_case = MasterCasenumbers::find($check_case->first()->id);
                    foreach ($data_case->adjusters as $key => $value) {
                        foreach ($value->ious as $key_ious => $value_ious) {
                            array_push ($iou_id, $value_ious->iou_lists_id);
                        }
                    }

                    $master_iou = IouLists::whereIn("id",$iou_id)->get();
                }
            break;
        }

        return view("casenumbers::iou",compact("user","config_sidebar","master_iou"));

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

    public function download($id){
        $master_casenumbers = MasterCasenumbers::find($id);
        return Excel::download(new Export($master_casenumbers->id), $master_casenumbers->case_number.'.xlsx');
    }

}
