<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterAdjusters;

class SettingController extends Controller
{
    
    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        return view('setting::index');
    }

    public function user(){
        $user = User::find(Auth::user()->id);
        $list_user = User::where("id","!=",1)->get();
        $config_sidebar = Config::get('sidebar');
        return view('setting::user.index',compact("user","list_user","config_sidebar"));
    }

    public function add(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_adjuster = MasterAdjusters::get();
        return view('setting::user.add',compact("user","config_sidebar","master_adjuster"));
    }

    public function show(Request $request){
        $user = User::find(Auth::user()->id);
        $detail_user = User::find($request->id);
        $config_sidebar = Config::get('sidebar');
        return view('setting::user.show',compact("user","detail_user","config_sidebar"));
    }
}
