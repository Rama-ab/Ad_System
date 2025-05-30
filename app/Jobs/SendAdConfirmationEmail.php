<?php

namespace App\Jobs;

use App\Mail\AdConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Ad $ad) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->ad->user->email)->send(new AdConfirmationMail($this->ad));
    }
}
