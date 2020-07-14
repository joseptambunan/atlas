<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;

class MasterApprovals extends Model
{
    protected $fillable = [];

    public function jabatan_approvals(){
    	return $this->hasOne("Modules\Setting\Entities\JabatanApprovals","approval_id");
    }

    public function document(){
    	return $this->belongsTo("Modules\Master\Entities\MasterDocument");
    }
}
