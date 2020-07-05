<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterAdjusters extends Model
{
    protected $fillable = [];

    public function position(){
    	return $this->belongsTo("Modules\Master\Entities\MasterPositions","position_id");
    }
}
