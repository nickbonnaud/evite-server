<?php

namespace App\Notifications;

use App\Models\Rsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RsvpAdded extends Notification implements ShouldQueue {
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct() {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [MessageChannel::class];
    }

    public function toMessage(Rsvp $notifiable) {
        $message = "Your RSVP for Michelle and Nick's celebration of marriage. Please RSVP at https://bonnaud-meyer.com/#rsvp?id={$notifiable->short_id}";
    }
}
