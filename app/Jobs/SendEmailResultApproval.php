<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Approvals;
use App\Mail\ResultApprovalMail;
use Illuminate\Support\Facades\Mail;

class SendEmailResultApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $approval;


    public function __construct(Approvals $approval){
        $this->approval = $approval;
    }

    public function handle(){
        $approval = $this->approval;
        $mail = Mail::to($approval->created_data->email)->send(new ResultApprovalMail($approval));
    }
}
