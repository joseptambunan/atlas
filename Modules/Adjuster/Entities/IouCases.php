<?php

namespace Modules\Adjuster\Entities;

use Illuminate\Database\Eloquent\Model;

class IouCases extends Model
{
    protected $fillable = [];

    public function adjuster_casenumber(){
    	return $this->belongsTo("Modules\CaseNumbers\Entities\AdjusterCasenumbers");
    }
}
