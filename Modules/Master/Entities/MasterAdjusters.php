<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterAdjusters extends Model
{
    protected $fillable = [];

    public function position(){
    	return $this->belongsTo("Modules\Master\Entities\MasterPositions","position_id");
    }

    public function cases(){
    	return $this->hasMany("Modules\CaseNumbers\Entities\AdjusterCasenumbers","adjuster_id");
    }

    public function ious(){
    	return $this->hasMany("Modules\Adjuster\Entities\IouLists","adjuster_id");
    }

    public function user_detail(){
        return $this->hasOne("App\User","adjuster_id");
    }
}
