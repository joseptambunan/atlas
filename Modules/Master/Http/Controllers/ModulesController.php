<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterModules;
use Modules\Setting\Entities\AccessModules;

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

        $modules_data = MasterModules::find($modules->id);

        if ( count($modules_data->access_modules) <= 0 ){
            $access_modules = new AccessModules;
            $access_modules->created = 1;
            $access_modules->read = 1;
            $access_modules->update = 1;
            $access_modules->insert = 1;
            $access_modules->modules_id = $modules->id;
            $access_modules->save();
        }

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
