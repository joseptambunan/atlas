<?php

namespace Modules\Adjuster\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCaseNumbers;
use Modules\Master\Entities\MasterAdjusters;
use Modules\Adjuster\Entities\IouCases;
use Modules\Adjuster\Entities\CaseExpenses;
use Modules\Adjuster\Entities\IouLists;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\MasterPositions;
use Modules\Master\Entities\MasterDocument;
use App\Approvals;
use App\ApprovalDetails;
use App\User;

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

    public function save_expenses(Request $request){
        $iou_case = IouCases::find($request->iou_list_id);
        $path = "";
        if ( $request->file('receipt') != ""){
            $path = Storage::putFile('cases/'.$iou_case->adjuster_casenumber->case->id, $request->file('receipt'));
        }

        $case_expenses = new CaseExpenses;
        $case_expenses->iou_lists_id = $iou_case->id;
        $case_expenses->master_casenumbers_id = $iou_case->adjuster_casenumber->case->id;
        $case_expenses->type = $request->type_expenses;
        $case_expenses->ammount = str_replace(",","",$request->ammount_expenses);
        $case_expenses->description = $request->description;
        $case_expenses->created_at = date("Y-m-d H:i:s");
        $case_expenses->created_by = Auth::user()->id;
        $case_expenses->receipt = $path;
        $case_expenses->save();

        $iou_list = IouLists::find($iou_case->iou->id);
        $iou_list->updated_at = date("Y-m-d H:i:s");
        $iou_list->updated_by = Auth::user()->id;
        $iou_list->save();
            
        $data['status'] = 0;
        echo json_encode($data);
    }

    public function remove_expenses(Request $request){
        $case_expenses = CaseExpenses::find($request->id);
        $case_expenses->delete();

        $data['status'] = 0;
        echo json_encode($data);
    }

    public function revisi_expenses(Request $request){

        $case_expenses = CaseExpenses::find($request->expenses_id);
        $path = "";
        if ( $request->file('receipt_revisi') != ""){
            $path = Storage::putFile('cases/'.$case_expenses->iou_lists->adjuster_casenumber->case->id, $request->file('receipt_revisi'));
        }


        $update_cases =  CaseExpenses::find($request->expenses_id);
        $update_cases->type = $request->type_revisi;
        $update_cases->ammount = str_replace(",","",$request->ammount_revisi);
        $update_cases->description = $request->desc_revisi;
        $update_cases->updated_at = date("Y-m-d H:i:s");
        $update_cases->updated_by = Auth::user()->id;
        $update_cases->receipt = $path;
        $update_cases->save();

        $approval = $case_expenses->list_approva;
        foreach ($approval->details as $key => $value) {
            $approval_detail = ApprovalDetails::find($value->id);
            $approval_detail->status = 1;
            $approval_detail->updated_at = date("Y-m-d H:i:s");
            $approval_detail->updated_by = Auth::user()->id;
            $approval_detail->save();
        }

        $update_approval = Approvals::find($approval->id);
        $update_approval->status = 1;
        $update_approval->updated_by = Auth::user()->id;
        $update_approval->updated_at = date("Y-m-d H:i:s");
        $update_approval->save();

        $data['status'] = 0;
        echo json_encode($data);
    }
}
