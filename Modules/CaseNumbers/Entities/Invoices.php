<?php

namespace Modules\CaseNumbers\Entities;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $fillable = [];

    public function cases(){
    	return $this->belongsTo("Modules\Master\Entities\MasterCasenumbers","invoice_number");
    }

    public function getCreatedAttribute(){
    	return $this->belongsTo("App\User","created_by");
    }

    public function user_name(){
    	return $this->belongsTo("App\User","created_by");
    }
}
