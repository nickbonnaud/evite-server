<?php

namespace App\Services;

use App\Models\Rsvp;

class InviterRsvpService {

  public function getRsvps() {
    return Rsvp::all();
  }

  public function createRsvps($user, $rsvpsData) {
    return $user->rsvps()->createMany($rsvpsData['rsvps']);
  }
}