<?php

namespace Modules\CaseNumbers\Entities;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $fillable = [];

    public function cases(){
    	return $this->hasMany("Modules\Master\Entities\MasterCasenumbers","invoice_number");
    }
}
