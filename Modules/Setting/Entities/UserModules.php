<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class UserModules extends Model
{
    protected $fillable = [];

    public function access_modules(){
    	return $this->belongsTo("Modules\Setting\Entities\AccessModules","access_approval_id");
    }
}
