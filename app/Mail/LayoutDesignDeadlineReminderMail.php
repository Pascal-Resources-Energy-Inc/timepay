<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LayoutDesignDeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dueSoon;
    public $dueToday;
    public $actionUrl;

    public function __construct($dueSoon, $dueToday)
    {
        $this->dueSoon = collect($dueSoon);
        $this->dueToday = collect($dueToday);
        $this->actionUrl = route('ldr-approval.index');
    }

    public function build()
    {
        $requestCount = $this->dueSoon->count() + $this->dueToday->count();
        $label = $requestCount === 1 ? 'Request' : 'Requests';

        return $this
            ->subject("Layout Design Deadline Reminder | {$requestCount} {$label}")
            ->view('email.layout_design_deadline_reminder');
    }
}
