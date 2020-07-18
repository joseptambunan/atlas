<?php

namespace Modules\Adjuster\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCaseNumbers;
use Modules\Master\Entities\MasterAdjusters;


class CasesController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }
   
    public function show($id)
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        $casenumber = MasterCaseNumbers::find($id);
        return view('adjuster::case.show',compact("user","config_sidebar","adjuster_data","casenumber"));
    }

    
}
