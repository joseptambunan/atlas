<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_modules(){
        return $this->hasMany("Modules\Setting\Entities\UserModules");
    }

    public function adjusters(){
        return $this->belongsTo("Modules\Master\Entities\MasterAdjusters","adjuster_id");
    }

    public function approval_detail(){
        return $this->hasMany("App\ApprovalDetails","approval_by")->where("status",1);
    }

    public function ious(){
        return $this->hasMany("Modules\Adjuster\Entities\IouLists","created_by")->orderBy("id","desc");
    }

    public function getModuleCheckAttribute(){
        
        return redirect("access/logout");
    }

}
