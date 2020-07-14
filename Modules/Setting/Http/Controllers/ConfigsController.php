<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Setting\Entities\MasterConfigs;

class ConfigsController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    { 
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_config = MasterConfigs::get();
        return view('setting::config.index',compact("user","config_sidebar","master_config"));
    }

    public function create(Request $request){
        $master_config = new MasterConfigs;
        $master_config->name = strtolower(str_replace(" ","_",$request->value_name));
        $master_config->value = $request->value_config;
        $master_config->created_by = Auth::user()->id;
        $master_config->save();

        return redirect("setting/config");
    }
}
