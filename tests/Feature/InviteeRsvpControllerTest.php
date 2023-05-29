<?php

namespace Tests\Feature;

use App\Http\Resources\InviteeRsvpResource;
use App\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InviteeRsvpControllerTest extends TestCase {
    use WithFaker, RefreshDatabase;

    public function test_rsvp_cannot_be_retrieved_with_incorrect_short_id() {
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $response = $this->getJson('api/invitee/rsvp?id=2h7bdj3');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('id');
    }

    public function test_rsvp_can_be_retrieved_with_correct_short_id() {
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $selectedRsvp = $rsvps->random();

        $response = $this->getJson("api/invitee/rsvp?id={$selectedRsvp->short_id}");
        $response->assertOk();

        $this->assertEquals($selectedRsvp->id, $response->getData()->data->id);
    }

    public function test_retrieving_rsvp_returns_invitee_rsvp_resource() {
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $selectedRsvp = $rsvps->random();

        $response = $this->getJson("api/invitee/rsvp?id={$selectedRsvp->short_id}");
        
        $selectedResource = new InviteeRsvpResource($selectedRsvp);
        $this->assertEquals($selectedResource->response()->getData()->data, $response->getData()->data);
    }

    public function test_retrieving_rsvp_updates_rsvp_viewed_and_viewed_on_if_rsvp_viewed_false() {
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $selectedRsvp = $rsvps->random();

        $this->assertFalse($selectedRsvp->fresh()->viewed);
        $this->assertNull($selectedRsvp->fresh()->viewed_on);
        
        $this->getJson("api/invitee/rsvp?id={$selectedRsvp->short_id}");
        
        $this->assertTrue($selectedRsvp->fresh()->viewed);
        $this->assertNotNull($selectedRsvp->fresh()->viewed_on);
    }

    public function test_retrieving_rsvp_does_not_update_rsvp_viewed_and_viewed_on_if_rsvp_viewed_true() {
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $viewedOnDate = Carbon::now()->subDays(3);

        $selectedRsvp = $rsvps->random();
        $selectedRsvp->update(['viewed' => true, 'viewed_on' => $viewedOnDate]);
        
        $this->getJson("api/invitee/rsvp?id={$selectedRsvp->short_id}");
        $this->assertEquals($selectedRsvp->fresh()->viewed_on, $viewedOnDate);
    }

    public function test_short_id_must_correspond_to_id() {
        $rsvp = Rsvp::factory()->create(['viewed' => true, 'viewed_on' =>  Carbon::now()->subDays(2)]);

        $body = [
            'short_id' => "3h5d",
            'will_attend' => true,
            'number_attending' => 4
        ];

        $response = $this->patchJson("api/invitee/rsvp/{$rsvp->id}", $body);
        $response->assertStatus(403);
    }
    
    public function test_updating_rsvp_required_correct_data() {
        $rsvp = Rsvp::factory()->create(['viewed' => true, 'viewed_on' =>  Carbon::now()->subDays(2)]);

        $body = [
            'short_id' => $rsvp->short_id,
            'will_attend' => 'yes',
            'number_attending' => '!'
        ];

        $response = $this->patchJson("api/invitee/rsvp/{$rsvp->id}", $body);
        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('will_attend');
        $response->assertJsonValidationErrorFor('number_attending');
    }

    public function test_an_rsvp_can_be_updated() {
        $rsvp = Rsvp::factory()->create(['viewed' => true, 'viewed_on' =>  Carbon::now()->subDays(2)]);

        $body = [
            'short_id' => $rsvp->short_id,
            'will_attend' => true,
            'number_attending' => 3
        ];

        $this->assertDatabaseMissing('rsvps', $body);
        
        $response = $this->patchJson("api/invitee/rsvp/{$rsvp->id}", $body);

        $this->assertDatabaseHas('rsvps', $body);
    }

    public function test_updating_rsvp_returns_updated_rsvp_resource() {
        $rsvp = Rsvp::factory()->create(['viewed' => true, 'viewed_on' =>  Carbon::now()->subDays(2)]);

        $body = [
            'short_id' => $rsvp->short_id,
            'will_attend' => true,
            'number_attending' => '3'
        ];

        $response = $this->patchJson("api/invitee/rsvp/{$rsvp->id}", $body);
        $response->assertOk();

        $rsvpResource = new InviteeRsvpResource($rsvp->fresh());
        $this->assertEquals($rsvpResource->response()->getData()->data, $response->getData()->data);
        
        $this->assertTrue($response->getData()->data->will_attend);
        $this->assertEquals(3, $response->getData()->data->number_attending);
    }
}
