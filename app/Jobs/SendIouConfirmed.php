<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Adjuster\Entities\IouLists;

class SendIouConfirmed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $iou;
    public function __construct(IouLists $ioulist){
        $this->iou = $ioulist;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $iou = $this->iou;
        $mail = Mail::to($iou->created->email)->send(new IouConfirmedMail($iou));
    }
}
