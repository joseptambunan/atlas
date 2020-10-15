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
            "total" => 0
        );

        foreach ($this->adjusters as $key => $value) {
            foreach ($value->ious as $key_ious => $value_ious) {
                $array_status['total'] = $array_status['total'] + 1;
                if ( isset($value_ious->iou)) {
                    if ( isset($value_ious->iou->expenses) ){
                        if ( count($value_ious->iou->expenses) > 0 ){
                            $array_status['expenses_complete'] = $array_status['expenses_complete'] + 1;
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
        return $this->belongsTo("Modules\CaseNumbers\Entities\Invoices","invoice_number");
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

    public function getTotalRembesAttribue(){
        $total = 0;
        foreach ($this->rembes as $key => $value) {
            $total = $value->total + $total;
        }

        return $total;
    }
}
