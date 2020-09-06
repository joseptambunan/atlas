<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CaseNumbers\Entities\AdjusterCasenumbers;
use App\Mail\SenderMail;
use Illuminate\Support\Facades\Mail;


class SendEmailCase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $adjuster;

    public function __construct(AdjusterCasenumbers $adjuster){
        $this->adjuster = $adjuster ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        
        $adjuster = $this->adjuster;
        $mail = Mail::to($adjuster->adjuster->email)->send(new SenderMail($adjuster));
    }
}
