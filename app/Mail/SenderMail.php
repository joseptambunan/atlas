<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Casenumbers\Entities\AdjusterCasenumbers;

class SenderMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $case;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AdjusterCasenumbers $AdjusterCasenumbers)
    {
        $this->adjuster_case = $AdjusterCasenumbers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $adjuster_case = $this->adjuster_case;
        return $this->view('emails.case',compact("adjuster_case"));
    }
}
