<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterCasenumbers extends Model
{
    protected $fillable = [];

    public function getCreatedAttribute(){
    	return User::find($this->created_by)->adjusters->name;
    }

    public function adjusters(){
    	return $this->hasMany("Modules\CaseNumbers\Entities\AdjusterCasenumbers","case_number_id");
    }

    public function case_expenses(){
    	return $this->hasMany("Modules\Adjuster\Entities\CaseExpenses");
    }

    public function getStatusAttribute(){
        $data['label'] = "In Progress";
        $data['class']  = "label label-info";

        if ( $this->deleted_at != "" && isset($this->invoice)){
            $data['label'] = "Finish";
            $data['class'] = "label label-success";
        }
        return $data;
    }


    public function getTotalIouAttribute(){
        $array_status = array(
            "in_progress" => 0,
            "expenses_complete" => 0,
            "total" => 0,
            "expenses_approval" => 0
        );

        foreach ($this->adjusters as $key => $value) {
            foreach ($value->ious as $key_ious => $value_ious) {
                $array_status['total'] = $array_status['total'] + 1;
                if ( isset($value_ious->iou)) {
                    if ( isset($value_ious->iou->expenses) ){
                        if ( count($value_ious->iou->expenses) > 0 ){
                            $array_status['expenses_complete'] = $array_status['expenses_complete'] + 1;
                            if ( $value_ious->iou->expenses_approval['total_approval'] == $value_ious->iou->expenses_approval['total_expenses'] ){
                                $array_status['expenses_approval'] = $array_status['expenses_approval'] + 1;
                            }
                        }else{
                            $array_status['in_progress'] = $array_status['in_progress'] + 1;
                        }
                    }
                }
            }
        }

        return $array_status;
    }

    public function invoice(){
        return $this->hasOne("Modules\CaseNumbers\Entities\Invoices","invoice_number");
    }

    public function getTotalExpensesAttribute(){
        $total = 0;
        foreach ($this->case_expenses as $key => $value) {
            $total = $total + $value->ammount;
        }

        return $total;
    }

    public function getTotalIouPlannedAttribute(){
        $total = 0;
        foreach ($this->adjusters as $key => $value) {
            foreach ($value->ious as $key_ious => $value_ious) {
                if ( isset($value_ious->iou )){
                    $total = $total + $value_ious->iou->total;
                }
            }
        }

        return $total;
    }

    public function insurance(){
        return $this->belongsTo("Modules\Master\Entities\MasterInsurance");
    }

    public function rembes(){
        return $this->hasMany("Modules\CaseNumbers\Entities\Reiumberses");
    }

    public function getTotalRembesAttribute(){
        $total = 0;
        foreach ($this->rembes as $key => $value) {
            $total = $value->total + $total;
        }

        return $total;
    }

    public function allow_finish($user_id = ""){
        $data['total_expenses'] = count($this->case_expenses);
        $data['total_expenses_approval'] = 0;
        $data['total_iou_approval'] = 0;

        foreach ($this->case_expenses as $key => $value) {
            if ( $value->status['status'] == 3 ){
                $data['total_expenses_approval'] = $data['total_expenses_approval'] + 1;
            }
        }
//return $this->total_iou['total'].",".$this->total_iou['expenses_approval'];
        if ( ( $data['total_expenses'] == $data['total_expenses_approval'] ) && ( $this->total_iou['total'] == $this->total_iou['expenses_approval'] )) {
            return "OK";
        }else{
            foreach ($this->adjusters as $key => $value) {
                if ( ($value->adjuster_id == $user_id ) && $value->updated_by != ""){
                    return "Finish at ".date("d/M/Y", strtotime($value->updated_at));
                }else{
                    return "Please complete expenses and iou";
                }
            }
        }

        return false;
    }

    public function getAllowCloseAttribute(){

        $flag_all = count($this->adjusters);
        $flag_adjuster = 0;
        foreach ($this->adjusters as $key => $value) {
            if ( $value->updated_by != "" ){
                $flag_adjuster++;
            }
        }

        if ( $flag_adjuster == $flag_all ){
            return true;
        }

        return false;
    }
}
