<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to=ifti3061@gmail.com}', function (string $to) {
    Mail::raw('SMTP test email from Conscious Food Summit.', function ($message) use ($to) {
        $message->to($to)->subject('SMTP Test');
    });

    $this->info("Test email sent to {$to}");
})->purpose('Send a test email using current SMTP configuration');

Schedule::command('payments:sync-dashticket')
    ->everyFiveMinutes()
    ->withoutOverlapping(10)
    ->runInBackground();
