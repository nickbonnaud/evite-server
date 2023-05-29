<?php

namespace Tests\Unit;

use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class RsvpTest extends TestCase {
    use RefreshDatabase;

    public function test_rsvp_auto_generates_unique_short_id() {
        $rsvp = Rsvp::factory()->create();
        $this->assertNotNull($rsvp->short_id);
    }
    
    public function test_rsvp_auto_generates_uuid_when_created() {
        $rsvp = Rsvp::factory()->create();
        $this->assertTrue(Str::isUuid($rsvp->id));
    }

    public function test_an_rsvp_belongs_to_a_user() {
        $rsvp = Rsvp::factory()->create();
        $this->assertInstanceOf(User::class, $rsvp->user);
    }
}
