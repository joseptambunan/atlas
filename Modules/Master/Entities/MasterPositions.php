<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterPositions extends Model
{
    protected $fillable = [];

    public function approval(){
    	return $this->hasMany("Modules\Setting\Entities\JabatanApprovals","jabatan_id");
    }

    public function menus(){
    	return $this->hasMany("Modules\Master\Entities\MasterModules");
    }
}
