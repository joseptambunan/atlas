<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $password;

    public function __construct(User $user, $password){
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $data = $this->user;
	$password = $this->password;
	error_log($password.'   disi');
        return $this->view('emails.register_user',compact("data","password"));
    }
}
