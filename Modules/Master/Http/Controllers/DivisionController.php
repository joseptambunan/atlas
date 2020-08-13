<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\MasterDivision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;


class DivisionController extends Controller
{
    
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_division = MasterDivision::get();
        return view('master::division.index',compact("user","config_sidebar","master_division"));
    }

    public function store(Request $request){
        $master_division = new MasterDivision;
        if ( $request->division_id != ""){
           $master_division =  MasterDivision::find($request->division_id);
        }

        $master_division->division_name = $request->division;
        $master_division->created_at = date("Y-m-d H:i:s");
        $master_division->created_by = Auth::user()->id;
        $master_division->save();

        return redirect("master/division");
    }
   
}
