<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterDocument extends Model
{
    protected $fillable = [];

    public function approvals(){
    	return $this->hasMany("Modules\Master\Entities\MasterApprovals","document_id");
    }
}
