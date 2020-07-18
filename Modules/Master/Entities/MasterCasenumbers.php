<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MasterCasenumbers extends Model
{
    protected $fillable = [];

    public function getCreatedAttribute(){
    	return User::find($this->created_by)->adjusters->name;
    }

    public function adjusters(){
    	return $this->hasMany("Modules\CaseNumbers\Entities\AdjusterCasenumbers","case_number_id");
    }

    public function expenses(){
    	return $this->hasMany("Modules\Adjuster\Entities\CaseExpenses");
    }

    public function getStatusAttribute(){
        $data['label'] = "In Progress";
        $data['class']  = "label label-info";

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
            }
        }

        return $array_status;
    }
}
