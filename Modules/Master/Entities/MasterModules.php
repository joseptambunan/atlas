<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterModules extends Model
{
    protected $fillable = [];

    public function access_modules(){
    	return $this->hasMany("Modules\Setting\Entities\AccessModules","modules_id");
    }
}
