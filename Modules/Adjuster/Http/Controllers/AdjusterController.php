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
use Modules\Master\Entities\MasterCaseNumbers;
use Modules\Adjuster\Entities\IouCases;
use Modules\CaseNumbers\Entities\Invoices;
use Modules\CaseNumbers\Entities\AdjusterCasenumbers;

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

        return redirect("access/logout");
    }

    public function user_detail(){
        $this->belongsTo("App\User");
    }

    public function todolist(Request $request){
        $data['html'] = "<h4> -Not Pending Todo List- </h4>";
        $data['status'] = 0;
        $data['total'] = 0;
        $html = "";
        $html_iou = "";
        $html_case = "";

        $adjuster_data = MasterAdjusters::find($request->adjuster_id);
        foreach ($adjuster_data->to_do as $key => $value) {
            if ( $value['total'] > 0 ){
                $html .= "<li>";
                $html .= "<span class='text'><a href='".url('/').$value['link']."'>".$value['label']."</a></span>";
                $html .= "<span class='".$value['class']."'>".$value['total']."</span>";
                $html .= "</li>";
                $data['total'] = $data['total'] + $value['total'];
            }
        }

        if ( $data['total'] > 0 ){
            $data['html'] = "<ul class='todo-list'>". $html. "</ul>";
        }

        $html_case .= "<select name='iou_list_id' id='iou_list_id' class='form-control' required>";
        $html_iou .= "<select name='iou_number' id='iou_number' class='form-control' required>";
        if ( count($adjuster_data->ious) > 0 ){
            foreach ($adjuster_data->ious as $key => $value) {
                $html_iou .= "<option value='".$value->id."'>".$value->title."</option>";
                foreach ($value->cases as $key_cases => $value_cases) {
                    $html_case .= "<option value='".$value_cases->id."'>".$value_cases->adjuster_casenumber->case->title."</option>";
                }
            }
        }else{
            $html_iou .= "<option>No Active IOU</option>";
        }
        $html_iou .= "</select>";
        $html_case .= "</select>";
        
        $data['html_iou'] = $html_iou;
        $data['html_case'] = $html_case;
        echo json_encode($data);
    }

    public function invoice(){
        $user = User::find(Auth::user()->id);
        $config_sidebar = Config::get('sidebar');
        $adjuster_data = MasterAdjusters::find($user->adjuster_id);
        return view('adjuster::invoice',compact("user","config_sidebar","adjuster_data"));
    }

    public function finish_invoice(Request $request){
        $user = User::find(Auth::user()->id);
        $invoice = Invoices::find($request->id);
        $start_flag = 0;
        $start_count = 0;
        $expenses = 0;
        foreach ($invoice->cases as $key => $value) {
            $start_flag = count($value->adjusters);
            foreach ($value->adjusters as $key_adjuster => $value_adjuster) {
                if ( $value_adjuster->adjuster_id == $user->adjuster_id){
                    $start_count++;
                    $adjuster_casenumber = AdjusterCasenumbers::find($value_adjuster->id);
                    $adjuster_casenumber->updated_by = $user->id;
                    $adjuster_casenumber->updated_at = date("Y-m-d H:i:s");
                    $adjuster_casenumber->save();
                }
            }
        }

        if ( $start_flag > 0 ){
            if ( $start_flag == $start_count ){
                $invoice = Invoices::find($request->id);
                $invoice->updated_by = Auth::user()->id;
                $invoice->save();   
            }
        }

        $data['status'] = 0;
        echo json_encode($data);
    }
}
