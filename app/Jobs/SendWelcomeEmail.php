<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  User $user;
    public bool $includeVerification;
    public string $password;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, bool $includeVerification = false, string $password)
    {
        $this->user = $user;
        $this->includeVerification = $includeVerification;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->user->email;

        if ($email) {
            Log::info("Sending welcome email to {$email}");

            Mail::to($email)->send(new WelcomeEmail(
                $this->user,
                $this->includeVerification,
                $this->password,
            ));

            Log::info("Welcome email sent to {$email}");
        }
    }
}
