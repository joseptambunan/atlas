<?php

namespace Modules\Access\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCasenumbers;

class AccessController extends Controller
{

    public function index(){
        if ( isset(Auth::user()->id)){
            return redirect("access/home");
        }
        return view("access::index");
    }

    public function logout(){
        Auth::logout();
        return redirect("/");
    }

    public function reset(){
        return view("access::reset");
    }

    public function submit(Request $request){
    	$credentials = $request->only('email','password');
    	
    	
        if (Auth::attempt($credentials)) {
        	$user = User::find(Auth::user()->id);
            $user->updated_at = date("Y-m-d H:i:s");
            $user->save();
            return redirect("access/home");
            
        }else{
            return redirect("access/fail");
        }
    }

    public function fail(){
    	return view("access::fail");
    }

    public function home(){
        $config_sidebar = Config::get('services');
        $user = User::find(Auth::user()->id);
        $master_casenumbers = MasterCasenumbers::get();

        if ( count($user->user_modules) > 0 ){
            $menus = strtolower(trim($user->user_modules->first()->access_modules->modules->modules_name));
        }
        
        return redirect($config_sidebar['menus'][$menus]);
    }

    public function master(){
        $config_sidebar = Config::get('sidebar');
        $user = User::find(Auth::user()->id);
        $master_casenumbers = MasterCasenumbers::get();
        return view("access::home",compact("user","config_sidebar","master_casenumbers"));

    }

    public function submitreset(Request $request){
        $user = User::where("email",$request->email)->get();
        if ( count($user) > 0 ){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            
            return redirect("access/reset/success");
        }else{
            return redirect("access/reset/fail");
        }
    }

    public function successreset(){
        return view("access::sucess_reset");
    }

    public function failreset(){
         return view("access::fail_reset");
    }

}
