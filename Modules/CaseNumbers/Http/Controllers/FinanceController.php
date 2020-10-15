<?php

namespace Modules\CaseNumbers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Modules\Master\Entities\MasterCasenumbers;
use Modules\Master\Entities\MasterAdjusters;
use Modules\CaseNumbers\Entities\AdjusterCasenumbers;
use Modules\Adjuster\Entities\IouLists;
use Modules\CaseNumbers\Entities\Invoices;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Master\Entities\MasterInsurance;
use Modules\Master\Entities\MasterDivision;
use Modules\Adjuster\Entities\CaseExpenses;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\MasterDocument;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Approvals;
use App\ApprovalDetails;
use App\Export;
use App\Jobs\SendEmailCase;
use App\Jobs\SendIouConfirmed;
use Modules\CaseNumbers\Entities\Reiumberses;
use Modules\CaseNumbers\Entities\ReiumberseDetails;

class FinanceController extends Controller
{
    
    public function _construct(){
        $this->middleware("auth");
    }

    public function index(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $invoices = Invoices::get();
        return view('casenumbers::finance.invoice',compact("user","config_sidebar","invoices"));
    }

    public function case_show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $casenumber = MasterCasenumbers::find($request->id);
        return view('casenumbers::finance.show',compact("user","config_sidebar","casenumber"));
    }

    public function search_case(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = array();
        $search_by = $request->search_by;
        $keyword = $request->keyword;

        switch ( $search_by ){
            case 'client':
                $array_id = array();
                $master_iou = IouLists::where("client",'like','%'.$keyword.'%')->get();
                if ( count($master_iou) > 0 ){
                    foreach ($master_iou as $key => $value) {
                        $iou_list = IouLists::find($value->id);
                        foreach ($iou_list->cases as $key_cases => $value_cases) {
                            array_push($array_id, $value_cases->iou_lists_id);
                        }
                    }
                }
                $master_casenumbers = MasterCasenumbers::whereIn("id",$array_id)->get();
            break;

            case 'title':
                $master_casenumbers = MasterCasenumbers::where("title",'like','%'.$keyword.'%')->get();
            break;

            case 'adjusters':
                $array_id = array();
                $check_user = MasterAdjusters::where("name",'like','%'.$keyword.'%')->get();
                if ( count($check_user) > 0 ){
                    $adjuster = MasterAdjusters::find($check_user->first()->id);
                    foreach ($adjuster->cases as $key => $value) {
                        array_push($array_id, $value->case_number_id);
                    }
                }

                $master_casenumbers = MasterCasenumbers::whereIn("id",$array_id)->get();
            break;

            case 'case':
                $master_casenumbers = MasterCasenumbers::where("case_number",'like','%'.$keyword.'%')->get();
            break;
        }

        $total_iou = 0;
        $iou_list = IouLists::where("document_number",NULL)->get();
        foreach ($iou_list as $key => $value) {
            if ( $value->status['status'] == 3 ){
                $total_iou  += 1;
            }
        }

        return view('casenumbers::finance.case',compact("user","config_sidebar","master_casenumbers","total_iou"));
    }

    public function iou(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $ious = IouLists::where("document_number",NULL)->orderBy("id","DESC")->get();
        $master_iou = array();
        foreach ( $ious as $key => $value ){
            if ( $value->status['status'] == 3 && $value->document_number == "" ){
                $array_master_iou['id'] =  $value->id;
                $array_master_iou['title'] =  $value->title;
                $array_master_iou['insurance_name'] =  $value->client_name->insurance_name;
                $array_master_iou['created_at'] = date("d-M-Y", strtotime($value->created_at));
                $array_master_iou['created_by'] =  $value->created->adjusters->name;
                $array_master_iou['class_status'] =  $value->status['class'];
                $array_master_iou['class_label'] =  $value->status['label'];
                $array_master_iou['total'] = number_format($value->total);
                $master_iou[] = $array_master_iou;
            }
        }
        return view("casenumbers::finance.iou",compact("user","config_sidebar","master_iou"));
    }

    public function iou_show(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $iou_data = IouLists::find($request->id);
        $check_approval = "";
        $approval_histories = array();
        $check_approval_id = Approvals::where("document_type",1)->where("document_id",$iou_data->id)->get();
        if ( count($check_approval_id) > 0 ){
            $approval = Approvals::find($check_approval_id->first()->id);
            foreach ($approval->details as $key => $value) {
                $approval_histories[] = array(
                    "name" => $value->user_detail->adjusters->name,
                    "status" => $value->status_description['label'],
                    "class" => $value->status_description['class'],
                    "message" => $value->description,
                    "date" => $value->updated_at
                );
            }
        }
        return view("casenumbers::finance.iou_show",compact("user","config_sidebar","iou_data","check_approval","approval_histories"));
    }

    public function update_reference(Request $request){
        $iou_ = IouLists::find($request->iou_id);

        if ( $request->file('receipt') != ""){
            $path = Storage::putFile('iou/'.$iou_->id, $request->file('receipt'));
        }

        $iou_data = IouLists::find($request->iou_id);
        $iou_data->document_number = $path;
        $iou_data->updated_at = date("Y-m-d H:i:s");
        $iou_data->updated_by = Auth::user()->id;
        $iou_data->document_upload_at = date("Y-m-d H:i:s");
        $iou_data->document_upload_by = Auth::user()->id;
        $iou_data->save();

        $iou_data_ = IouLists::find($request->iou_id);
        SendIouConfirmed::dispatch($iou_data_);
        return redirect("casenumbers/iou/show/".$iou_data->id);

    }

    public function add_expenses(Request $request){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = MasterCasenumbers::where("deleted_at",NULL)->orderBy('created_at', 'DESC')->get();
        return view('casenumbers::finance.expenses',compact("user","config_sidebar","master_casenumbers"));
    }

    public function save_expenses(Request $request){

        $master_document = MasterDocument::find(2);
        $array_expenses = $request->expenses;
        $array_case = $request->case;
        $path = "";
        foreach ($array_expenses as $key => $value) {
            if ( $value != "" ){
                if ( isset($array_case[$key])){
                    if ( $request->file('receipt') != ""){
                        $path = Storage::putFile('cases/'.$array_case[$key], $request->file('receipt'));
                    }

                    $expenses = new CaseExpenses;
                    $expenses->type = strtoupper($request->type_expenses);
                    $expenses->ammount = str_replace(",", "", $value);
                    $expenses->description = strtoupper($request->description);
                    $expenses->master_casenumbers_id = $array_case[$key];
                    $expenses->receipt = $path;
                    $expenses->created_at = date("Y-m-d H:i:s");
                    $expenses->created_by = Auth::user()->id;
                    $expenses->save();

                    foreach ($master_document->approvals as $key => $value) {
                        foreach ($value->jabatan_approvals->jabatan->adjusters as $key_adjusters => $value_adjusters) {
                            if ( $key == 0 && $key_adjusters == 0 ){
                                $approval = new Approvals;
                                $approval->document_type = $master_document->id;
                                $approval->document_id = $expenses->id;
                                $approval->status = 3;
                                $approval->approval_by = $value_adjusters->user_detail->id;
                                $approval->created_at = date("Y-m-d H:i:s");
                                $approval->created_by = Auth::user()->id;
                                $approval->save();
                            }

                            if ( count($approval->details) <= 0 ){
                                $approval_detail = new ApprovalDetails;
                                $approval_detail->approval_id = $approval->id;
                                $approval_detail->status = 3;
                                $approval_detail->approval_by = $value_adjusters->user_detail->id;
                                $approval_detail->created_at = date("Y-m-d H:i:s");
                                $approval_detail->created_by = Auth::user()->id;
                                $approval_detail->level = $value->level;
                                $approval_detail->save();
                            }
                        }
                    }

                }
            }
        }

        return redirect("casenumbers/case/all");
    }

    public function update_expenses(Request $request){

        $case_expenses = CaseExpenses::find($request->expenses_id);
        $case_expenses->type = $request->type_revisi;
        $case_expenses->ammount = str_replace(",", "", $request->ammount_revisi);
        $case_expenses->description = $request->desc_revisi;
        $case_expenses->updated_by = Auth::user()->id;
        $case_expenses->updated_at = date("Y-m-d H:i:s");
        $case_expenses->save();

        $check_approval = Approvals::where("document_type",2)->where("document_id",$case_expenses->id)->get();
        if ( count($check_approval) > 0 ){
            $approval = Approvals::find($check_approval->first()->id);
            $approval->status = 1;
            $approval->updated_at = date("Y-m-d H:i:s");
            $approval->updated_by = Auth::user()->id;
            $approval->save();

            foreach ($approval->details as $key => $value) {
                $approval_detail = ApprovalDetails::find($value->id);
                $approval_detail->status = 1;
                $approval_detail->save();
            }
        }
    
        return redirect("casenumbers/show/".$case_expenses->master_casenumbers_id);
    }

    public function download($id){
        $master_casenumbers = MasterCasenumbers::find($id);
        return Excel::download(new Export($master_casenumbers->id), $master_casenumbers->case_number.'.xlsx');
    }

    public function update_return(Request $request){
        $path = "";
        if ( $request->file('receipt') != ""){
            $path = Storage::putFile('iou/'.$request->iou_id, $request->file('receipt'));
        }

        $iou_list = IouLists::find($request->iou_id);
        $iou_list->finish_document = $path;
        $iou_list->deleted_by = Auth::user()->id;
        $iou_list->deleted_at = date('Y-m-d H:i:s');
        $iou_list->finish_at = date('Y-m-d H:i:s');
        $iou_list->save();

        $data['status'] = 0;
        return redirect("casenumbers/case/show/".$request->case_id);
    }

    public function createinvoice(Request $request){
        $invoice = new Invoices;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->created_at = date("Y-m-d H:i:s");
        $invoice->created_by = Auth::user()->id;
        $invoice->save();

        $case_number = MasterCasenumbers::find($request->case_id);
        $case_number->invoice_number = $invoice->id;
        $case_number->invoice_number_by = Auth::user()->id;
        $case_number->invoice_number_at = date("Y-m-d H:i:s");
        $case_number->save() ;

        $data['status'] = 0;
        echo json_encode($data);
    }

    public function search(Request $request){
        $search_by = $request->search_by;
        $keyword = $request->keyword;
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_iou = array();

        switch ( $search_by ){
            case 'client':
                $ious = IouLists::where("client",'like','%'.$keyword.'%')->get();
            break;

            case 'title':
                $ious = IouLists::where("title",'like','%'.$keyword.'%')->get();
            break;

            case 'adjusters':
                $check_user = MasterAdjusters::where("name",'like','%'.$keyword.'%')->get();
                if ( count($check_user) > 0 ){
                    $adjuster = MasterAdjusters::find($check_user->first()->id);
                    $ious = IouLists::where("created_by",$adjuster->user_detail->id)->get();
                }
            break;

            case 'case':
                $check_case = MasterCasenumbers::where("title",'like','%'.$keyword.'%')->get();
                if ( count($check_case) > 0 ){
                    $iou_id = array();
                    $data_case = MasterCasenumbers::find($check_case->first()->id);
                    foreach ($data_case->adjusters as $key => $value) {
                        foreach ($value->ious as $key_ious => $value_ious) {
                            array_push ($iou_id, $value_ious->iou_lists_id);
                        }
                    }

                    $ious = IouLists::whereIn("id",$iou_id)->get();
                }
            break;
        }

        foreach ( $ious as $key => $value ){
            $array_master_iou['id'] =  $value->id;
            $array_master_iou['title'] =  $value->title;
            $array_master_iou['insurance_name'] =  $value->client_name->insurance_name;
            $array_master_iou['created_at'] = date("d-M-Y", strtotime($value->created_at));
            $array_master_iou['created_by'] =  $value->created->adjusters->name;
            $array_master_iou['class_status'] =  $value->status['class'];
            $array_master_iou['class_label'] =  $value->status['label'];
            $array_master_iou['total'] = number_format($value->total);
            $master_iou[] = $array_master_iou;
        }
        return view("casenumbers::finance.iou",compact("user","config_sidebar","master_iou"));

    }

    public function case_all(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $master_casenumbers = MasterCasenumbers::get();
        return view("casenumbers::finance.case",compact("user","config_sidebar","master_casenumbers"));
    }

    public function download_return($id){
        $iou = IouLists::find($id);
        $receipt = $iou->finish_document;
        return Storage::download($receipt);
    }

    public function reiumberse(Request $request){

        $path = "";
        if ( $request->file('receipt') != ""){
            $path = Storage::putFile('cases/'.$request->case_id, $request->file('receipt'));
        }

        $reiumberse = new Reiumberses;
        $reiumberse->master_casenumbers_id = $request->case_id;
        $reiumberse->document_number = $path;
        $reiumberse->created_at = date("Y-m-d H:i:s");
        $reiumberse->created_by = Auth::user()->id;
        $reiumberse->save();

        foreach ($request->case_rembes_id as $key => $value) {
            $reiumberse_detail = new ReiumberseDetails;
            $reiumberse_detail->reiumberses_id = $reiumberse->id;
            $reiumberse_detail->case_expenses_id = $value;
            $reiumberse_detail->created_at = date("Y-m-d H:i:s");
            $reiumberse_detail->created_by = Auth::user()->id;
            $reiumberse_detail->save();
        }


        return redirect("casenumbers/case/show/".$request->case_id);
    }

}
