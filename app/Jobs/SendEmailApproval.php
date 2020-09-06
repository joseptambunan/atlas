<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\ApprovalDetails;
use App\Mail\ApprovalMail;
use Illuminate\Support\Facades\Mail;

class SendEmailApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $approval_detail;

    public function __construct(ApprovalDetails $approval_detail){
        $this->approval_detail = $approval_detail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $approval_detail = $this->approval_detail;
        $mail = Mail::to($approval_detail->user_detail->adjusters->email)->send(new ApprovalMail($approval_detail));
    }
}
