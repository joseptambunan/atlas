<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MasterCasenumbers extends Model
{
    protected $fillable = [];

    public function getCreatedAttribute(){
    	return User::find($this->created_by)->adjusters->name;
    }

    public function adjusters(){
    	return $this->hasMany("Modules\Casenumbers\Entities\AdjusterCasenumbers","case_number_id");
    }
}
