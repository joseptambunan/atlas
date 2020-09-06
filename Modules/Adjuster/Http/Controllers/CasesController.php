<?php

namespace Modules\Adjuster\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCasenumbers;
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
use App\Jobs\SendEmailApproval;

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
        $casenumber = MasterCasenumbers::find($id);
        $finish_status = "";

        foreach ($casenumber->adjusters as $key => $value) {
            if ( $value->adjuster_id == $adjuster_data->id){
                if ( $value->updated_by != ""){
                    $finish_status = "Finish";
                }
            }
        }

        $expenses = 0;
        foreach ($casenumber->case_expenses as $key_expenses => $value_expenses) {
            if ( $value_expenses->created_by == $user->id ){
                if ( $value_expenses->status['status'] == 3 ){
                    $expenses++;
                }
            }
        }
        return view('adjuster::case.show',compact("user","config_sidebar","adjuster_data","casenumber","finish_status","expenses"));
    }

    public function save_expenses(Request $request){

        $iou_case_id = NULL;

        if ( $request->iou_number != "" ){

            $iou_list = IouLists::find($request->iou_number);
            $iou_list->updated_at = date("Y-m-d H:i:s");
            $iou_list->updated_by = Auth::user()->id;
            $iou_list->save();

            $iou_list_ = IouLists::find($request->iou_number);
            foreach ($iou_list_->cases as $key => $value) {
                if ( $value->adjuster_casenumber){
                    if ( $value->adjuster_casenumber->case_number_id == $request->iou_list_id){
                        $iou_case_id = $value->id;
                        $case_id = $request->iou_list_id;
                    }
                }
            }

        }elseif ( $request->iou_list_id != "" ){
            
            $iou_case = IouCases::find($request->iou_list_id);
            $iou_case_id = $iou_case->id;
            $case_id = $iou_case->adjuster_casenumber->case->id;
        }else{
            $case_id = $request->iou_list_id;
        }

        $path = "";
        if ( $request->file('receipt') != ""){
            $path = Storage::putFile('cases/'.$case_id, $request->file('receipt'));
        }

        $case_expenses = new CaseExpenses;
        $case_expenses->iou_lists_id = $iou_case_id;
        $case_expenses->master_casenumbers_id = $case_id;
        $case_expenses->type = $request->type_expenses;
        $case_expenses->ammount = str_replace(",","",$request->ammount_expenses);
        $case_expenses->description = $request->description;
        $case_expenses->created_at = date("Y-m-d H:i:s");
        $case_expenses->created_by = Auth::user()->id;
        $case_expenses->receipt = $path;
        $case_expenses->save();

            
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

    public function loadcases(Request $request){
        $iou_list = IouLists::find($request->id);

        $html_case .= "<select name='iou_list_id' id='iou_list_id' class='form-control' required>";
        foreach ($iou_list->cases as $key_cases => $value_cases) {
            $html_case .= "<option value='".$value_cases->id."'>".$value_cases->adjuster_casenumber->case->title."</option>";
        }
        $html_case .= "</select>";

        $data['status'] = 0;
        $data['html_case'] = $html_case;
        echo json_encode($data);
    }

    public function request_approval(Request $request){

        $approval = new Approvals;
        if ( isset($request->checklist)){
            foreach ($request->checklist as $key => $value) {
                $status_approval = CaseExpenses::find($value);
                $is_exist = false;
                if ( $status_approval['status'] > 0 ){
                    $is_exist = true;
                }
                $approval->request_approval(2, $value, $is_exist, Auth::user()->id);
            }
        }

        return redirect("adjuster/case/show/".$request->case_show);
    }
}
