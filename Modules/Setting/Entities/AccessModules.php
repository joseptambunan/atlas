<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class AccessModules extends Model
{
    protected $fillable = [];

    public function modules(){
    	return $this->belongsTo("Modules\Master\Entities\MasterModules");
    }
}
