<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class JabatanApprovals extends Model
{
    protected $fillable = [];

    public function jabatan(){
    	return $this->belongsTo("Modules\Master\Entities\MasterPositions");
    }
}
