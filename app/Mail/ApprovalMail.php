<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\ApprovalDetails;

class ApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    public function __construct(ApprovalDetails $approval_detail){
        $this->approval_detail = $approval_detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->approval_detail;
        return $this->view('emails.approval',compact("data"));
    }
}
