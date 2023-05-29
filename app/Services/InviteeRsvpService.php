<?php

namespace App\Services;

use App\Models\Rsvp;
use Carbon\Carbon;

class InviteeRsvpService {

  public function getRsvp($shortId) {
    $rsvp = Rsvp::where('short_id', $shortId)->first();
    $this->updateViewed($rsvp);
    return $rsvp;
  }

  public function updateRsvp($rsvp, $rsvpData) {
    $rsvp->update(['will_attend' => $rsvpData['will_attend'], 'number_attending' => $rsvpData['number_attending']]);
  }

  private function updateViewed($rsvp) {
    if (!$rsvp->viewed) {
      $rsvp->update(['viewed' => true, 'viewed_on' => Carbon::now()]);
    }
  }
}