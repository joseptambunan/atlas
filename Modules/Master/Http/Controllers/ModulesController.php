<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterModules;

class ModulesController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_modules = MasterModules::get();
        return view('master::modules.index',compact("master_modules","config_sidebar","user"));
    }

    public function create(Request $request){
        if ( trim($request->module_id) == "" ){
            $modules = new MasterModules;
        }else{
            $modules = MasterModules::find($request->module_id);
        }

        $modules->modules_name = $request->module;
        $modules->created_at = date("Y-m-d H:i:s");
        $modules->created_by = Auth::user()->id;
        $modules->save();

        return redirect("master/modules");
    }   

    public function delete (Request $request){
        $modules = MasterModules::find($request->id);
        $modules->deleted_at = date("Y-m-d H:i:s");
        $modules->deleted_by = Auth::user()->id;
        $modules->save();
        
        $data['status'] = 0;
        echo json_encode($data);
    }
}
