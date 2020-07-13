<?php

namespace Modules\Adjuster\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterAdjusters;

class AdjusterController extends Controller
{
    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        return view('adjuster::index',compact("user","config_sidebar","adjuster_data"));
    }

    public function update(Request $request){

        $master_adjuster = MasterAdjusters::find($request->adjuster_id);
        $master_adjuster->name = $request->name;
        $master_adjuster->email = $request->email;
        $master_adjuster->phone = $request->phone;
        $master_adjuster->updated_at = date("Y-m-d H:i:s");
        $master_adjuster->updated_by = Auth::user()->id;
        $master_adjuster->save();

        if ( $request->password != ""){
            $user = User::find(Auth::user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect("adjuster/index");
    }
}
