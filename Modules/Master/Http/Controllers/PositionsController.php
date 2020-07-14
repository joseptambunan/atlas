<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\MasterPositions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterModules;

class PositionsController extends Controller
{
    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_position = MasterPositions::get();
        return view('master::position.index',compact("user","config_sidebar","master_position"));
    } 

    public function create(Request $request){
        $position = new MasterPositions;
        $position->position_name = $request->position;
        $position->created_at = date("Y-m-d H:i:s");
        $position->created_by = Auth::user()->id;
        $position->save();

        return redirect("master/position/show/".$position->id);
    } 

    public function show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $position = MasterPositions::find($request->id);
        $modules = MasterModules::get();

        $checked = "checked";
        if ( $position->deleted_at != ""){
            $checked = "";
        }
        return view('master::position.show',compact("user","config_sidebar","position","checked","modules"));
    }

    public function update(Request $request){
        $position = MasterPositions::find($request->id);
        $position->position_name = $request->position;
        $position->updated_at = date("Y-m-d H:i:s");
        $position->updated_by = Auth::user()->id;
        if ( $request->active == ""){
            $position->deleted_at = date("Y-m-d H:i:s");
            $position->deleted_by = Auth::user()->id;
        }
        $position->save();

        return redirect("master/position/show/".$position->id);
    }
}
