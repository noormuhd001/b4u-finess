<?php

namespace App\Jobs\Auth;

use App\Mail\Auth\SendForgotPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $user;
    public $resetUrl;
    public function __construct($user,$resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new SendForgotPasswordMail($this->user, $this->resetUrl));
    }
}
