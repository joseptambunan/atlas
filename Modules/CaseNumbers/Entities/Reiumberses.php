<?php

namespace Modules\CaseNumbers\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Reiumberses extends Model
{
    protected $fillable = [];

    public function details(){
    	return $this->hasMany("Modules\CaseNumbers\Entities\ReiumberseDetails");
    }

    public function getTotalAttribute(){
    	$total = 0;
    	foreach ($this->details as $key => $value) {
    		$total = $total + $value->expenses->ammount;
    	}

    	return $total;
    }

    public function getUserTransferAttribute(){
        $user = User::find($this->created_by);
        return $user;
    }
}
