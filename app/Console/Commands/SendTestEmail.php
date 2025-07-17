<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Send a test email';

    public function handle()
    {
        $email = $this->argument('email');
        Mail::to($email)->send(new TestEmail());
        $this->info("Test email sent to {$email}!");
    }
}
