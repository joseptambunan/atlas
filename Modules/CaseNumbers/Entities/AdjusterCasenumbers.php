<?php

namespace Modules\CaseNumbers\Entities;

use Illuminate\Database\Eloquent\Model;

class AdjusterCasenumbers extends Model
{
    protected $fillable = [];

    public function adjuster(){
    	return $this->belongsTo("Modules\Master\Entities\MasterAdjusters");
    }

    public function case(){
    	return $this->belongsTo("Modules\Master\Entities\MasterCasenumbers","case_number_id");
    }

    
    public function ious(){
        return $this->hasMany("Modules\Adjuster\Entities\IouCases","adjuster_casenumber_id");
    }
}
