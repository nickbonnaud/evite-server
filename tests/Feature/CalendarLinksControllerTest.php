<?php

namespace Tests\Feature;

use App\Models\Rsvp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalendarLinksControllerTest extends TestCase {
    use WithFaker, RefreshDatabase;

    public function test_calendar_links_cannot_be_retrieved_without_short_id_or_incorrect_short_id() {
        $rsvp = Rsvp::factory()->create();

        $response = $this->getJson("api/invitee/calendar");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('id');

        $response = $this->getJson("api/invitee/calendar?id=1234");
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('id');
    }

    public function test_retrieving_calendar_links_returns_calendar_links_resource() {
        $rsvp = Rsvp::factory()->create();
        $response = $this->getJson("api/invitee/calendar?id={$rsvp->short_id}");
        $response->assertOk();

        $response = $response->getData()->data;
        $this->assertNotNull($response->ios);
        $this->assertNotNull($response->google);
        $this->assertNotNull($response->yahoo);
        $this->assertNotNull($response->outlook);
        $this->assertNotNull($response->outlook_web);
    }
}
