<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterCaseNumbers;

class CaseNumbersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_case_numbers = MasterCaseNumbers::where("deleted_at","!=","")->get();
        
        return view('master::case.index',compact("user","config_sidebar","master_case_numbers"));
    }

    
}
