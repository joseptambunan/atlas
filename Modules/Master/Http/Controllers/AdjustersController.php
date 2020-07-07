<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Master\Entities\MasterPositions;

class AdjustersController extends Controller
{
     public function _construct(){
        $this->middleware("auth");
    }

    public function index(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjusters = MasterAdjusters::get();
        return view('master::adjuster.index',compact("user","config_sidebar","master_adjusters"));
    }

    public function add(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjusters = MasterAdjusters::get();
        $master_positions = MasterPositions::where("id","!=","1")->get();
        return view('master::adjuster.add',compact("user","config_sidebar","master_adjusters","master_positions"));   
    }

    public function create(Request $request){
        $adjuster = new MasterAdjusters;
        $adjuster->nik = $request->nik;
        $adjuster->name = $request->name;
        $adjuster->email = $request->email;
        $adjuster->phone = $request->phone;
        $adjuster->position_id = $request->positions;
        $adjuster->created_at = date("Y-m-d H:i:s");
        $adjuster->created_by = Auth::user()->id;
        $adjuster->save();

        if ( $request->thumbnail != ""){
            $update_adjuster = MasterAdjusters::find($adjuster->id);
            $update_adjuster->thumbnail = $request->thumbnail;
            $update_adjuster->save();
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        

        $user = new User;
        $user->name = $request->email;
        $user->email = $request->email;
        $user->password = bcrypt($randomString);
        $user->created_at = date("Y-m-d H:i:s");
        $user->created_by = Auth::user()->id;
        $user->adjuster_id = $adjuster->id;
        $user->save();

        return redirect("master/adjusters/show/".$adjuster->id);
    }

    public function show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster = MasterAdjusters::find($request->id);
        $master_positions = MasterPositions::where("id","!=","1")->get();
        $array_position_id = array();

        foreach ($master_positions as $key => $value) {
            $array_position_id[$value->id] = "";
        }

        foreach ($array_position_id as $key => $value) {
            if ( $key == $adjuster->position_id){
                $array_position_id[$key] = "selected";
            }
        }

        return view('master::adjuster.show',compact("user","config_sidebar","adjuster","array_position_id","master_positions"));    
    }

    public function update(Request $request){
        $adjuster = MasterAdjusters::find($request->adjuster_id);
        $adjuster->nik = $request->nik;
        $adjuster->name = $request->name;
        $adjuster->email = $request->email;
        $adjuster->phone = $request->phone;
        $adjuster->position_id = $request->positions;
        $adjuster->updated_at = date("Y-m-d H:i:s");
        $adjuster->updated_by = Auth::user()->id;

        if ( $request->is_active == ""){
            $adjuster->deleted_at = date("Y-m-d H:i:s");
            $adjuster->deleted_by = Auth::user()->id;
        }else{
            $adjuster->deleted_at = NULL;
            $adjuster->deleted_by = NULL;
        }
        $adjuster->save();
        return redirect("master/adjusters/show/".$adjuster->id);
    }

}
