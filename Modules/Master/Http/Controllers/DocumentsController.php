<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Modules\Master\Entities\MasterDocument;
use Modules\Master\Entities\MasterApprovals;
use Modules\Master\Entities\MasterPositions;
use Modules\Setting\Entities\JabatanApprovals;

class DocumentsController extends Controller
{

    public function _construct(){
        $this->middleware("auth");
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_document = MasterDocument::get();
        return view('master::document.index',compact("user","config_sidebar","master_document"));
    }

    public function create(Request $request){
    	$master_document = new MasterDocument;
    	$master_document->document = $request->document;
    	$master_document->created_by = Auth::user()->id;
    	$master_document->created_at = date("Y-m-d H:i:s");
    	$master_document->save();

    	return redirect("master/document/show/".$master_document->id);
    }

    public function show(Request $request){
    	$user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
    	$master_document = MasterDocument::find($request->id);
    	$master_position = MasterPositions::get();

    	$checked = "checked";
    	if ( $master_document->deleted_at != ""){
    		$checked = "";
    	}

    	$start =0;
    	return view("master::document.show",compact("user","master_document","checked","config_sidebar","master_position","start"));
    }

    public function update(Request $request){
    	$master_document = MasterDocument::find($request->id);
    	$master_document->document = $request->document;
    	$master_document->updated_at = date("Y-m-d H:i:s");
    	$master_document->updated_by = Auth::user()->id;
    	if ( $request->active == "" ){
    		$master_document->deleted_at = date("Y-m-d H:i:s");
    		$master_document->deleted_by = Auth::user()->id;
    	}else{
    		$master_document->deleted_at = NULL;
    	}
    	$master_document->save();
    	return redirect("master/document/show/".$master_document->id);
    }

    public function approval(Request $request){
        $master_approval = new MasterApprovals;
        $master_approval->document_id = $request->id;
        $master_approval->level = $request->level;
        $master_approval->created_at = date('Y-m-d H:i:s');
        $master_approval->created_by = Auth::user()->id;
        $master_approval->save();

        $jabatan_approval = new JabatanApprovals;
        $jabatan_approval->approval_id = $master_approval->id;
        $jabatan_approval->jabatan_id = $request->position;
        $jabatan_approval->created_at = date("Y-m-d H:i:s");
        $jabatan_approval->created_by = Auth::user()->id;
        $jabatan_approval->save();
        return redirect("master/document/show/".$request->id);

    }
    
}
