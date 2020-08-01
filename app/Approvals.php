<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approvals extends Model
{
    //
    public function details(){
    	return $this->hasMany("App\ApprovalDetails","approval_id");
    }

    public function document(){
    	return $this->belongsTo("Modules\Master\Entities\MasterDocument","document_type");
    }
}
