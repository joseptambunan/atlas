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

class CaseNumbersController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }


    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = MasterCasenumbers::orderBy('invoice_number', 'DESC')->get();
        return view('casenumbers::index',compact("user","config_sidebar","master_casenumbers"));
    }

    public function add(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjuster = MasterAdjusters::get();
        return view('casenumbers::add',compact("user","config_sidebar","master_adjuster"));
    }

    public function create(Request $request){
        $casenumber = new MasterCasenumbers;
        $casenumber->case_number = $request->casenumber;
        $casenumber->title = $request->title;
        $casenumber->created_at = date("Y-m-d H:i:s");
        $casenumber->created_by = Auth::user()->id;
        $casenumber->save();

        return redirect("/casenumbers/show/".$casenumber->id);
    }

    public function show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $casenumber = MasterCasenumbers::find($request->id);
        $adjuster_list = MasterAdjusters::get();

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
        return view('casenumbers::show',compact("user","config_sidebar","casenumber","status","class","adjuster_list"));

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
        $case_number = MasterCasenumbers::find($request->case_id);
        $case_number->invoice_number = $request->invoice_number;
        $case_number->invoice_number_by = Auth::user()->id;
        $case_number->invoice_number_at = date("Y-m-d H:i:s");
        $case_number->save() ;

        $data['status'] = 0;
        echo json_encode($data);
    }
}
