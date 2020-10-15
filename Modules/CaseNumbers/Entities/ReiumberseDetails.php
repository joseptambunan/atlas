<?php

namespace Modules\CaseNumbers\Entities;

use Illuminate\Database\Eloquent\Model;

class ReiumberseDetails extends Model
{
    protected $fillable = [];

    public function expenses(){
    	return $this->belongsTo("Modules\Adjuster\Entities\CaseExpenses","case_expenses_id");
    }
}
