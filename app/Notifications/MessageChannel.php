<?php

namespace App\Notifications;

use App\Models\Rsvp;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class MessageChannel {

  public function send(Rsvp $notifiable, RsvpAdded $notification): void {
    $number = $notifiable->number;
    $message = $notification->toMessage($notifiable);

    $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'), null, 'us1');

    $client->messages->create(
      $number,
      [
        'from' => env('TWILIO_NUMBER'),
        'body' => $message
      ]
    );
  }
}