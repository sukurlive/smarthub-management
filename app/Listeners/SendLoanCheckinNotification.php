<?php

namespace App\Listeners;

use App\Events\LoanCheckedIn;
use App\Mail\LoanReminderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendLoanCheckinNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Mail::to($event->loan->user->email)
            ->send(new LoanReminderMail($event->loan));
    }
}
