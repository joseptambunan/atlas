<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\MasterInsurance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;

class InsuranceController extends Controller
{
    
    public function index(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_insurance = MasterInsurance::get();
        return view('master::insurance.index',compact("user","config_sidebar","master_insurance"));
    }

    
    public function store(Request $request){

        $insurance = new MasterInsurance;
        if ( $request->insurance_id != ""){
            $insurance = MasterInsurance::find($request->insurance_id);
        }
        $insurance->insurance_name = $request->insurance;
        $insurance->created_at = date("Y-m-d H:i:s");
        $insurance->created_by = Auth::user()->id;
        $insurance->save();

        return redirect("master/insurance");
    }

    
}
