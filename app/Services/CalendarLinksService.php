<?php

namespace App\Services;

use DateTime;
use Spatie\CalendarLinks\Link;

class CalendarLinksService {

  public function getLinks() {
    $from = $from = DateTime::createFromFormat('Y-m-d H:i', '2023-08-19 12:00');
    $to = DateTime::createFromFormat('Y-m-d H:i', '2023-08-19 16:00');

    $link = Link::create("Michelle & Nick's Party", $from, $to)
      ->description("Celebrate Michelle & Nick's marriage with a BBQ.")
      ->address('784 Avenida Salvador, San Clemente, CA 92672');

    return [
      'ios' => $link->ics(),
      'google' => $link->google(),
      'yahoo' => $link->yahoo(),
      'outlook' => $link->ics(),
      'outlook_web' => $link->webOutlook()
    ];
  }
}