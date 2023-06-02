<?php

namespace Tests\Feature;

use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InviterRsvpControllerTest extends TestCase {
    use WithFaker, RefreshDatabase;

    public function test_an_unauth_user_cannot_retrieve_rsvps() {
        $user = User::factory()->create();
        
        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $response = $this->getJson('api/inviter/rsvp');
        $response->assertStatus(401);
    }

    public function test_a_user_can_retrieve_rsvps() {
        $user = User::factory()->create();
        $this->login($user);

        $numberRsvps = $this->faker->numberBetween(5, 15);
        $rsvps = Rsvp::factory($numberRsvps)->create();

        $response = $this->getJson('api/inviter/rsvp');
        $response->assertOk();

        $this->assertCount($numberRsvps, $response->getData()->data);
    }

    public function test_an_unauth_user_cannot_create_rsvps() {
        $user = User::factory()->create();
        $numberRsvps = $this->faker->numberBetween(5, 15);

        $body = [
            'rsvps' => $this->generateRsvpData($numberRsvps)
        ];

        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(401);
    }

    public function test_a_user_cannot_create_rsvps_with_incorrect_data() {
        $user = User::factory()->create();
        $this->login($user);

        $numberRsvps = $this->faker->numberBetween(5, 15);

        $body = [
            'rsvps' => [
                ['name' => "", 'number' => $this->faker->numerify('##########')],
            ]
        ];
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(422);

        $body = [
            'rsvps' => [
                ['name' => 1, 'number' => $this->faker->numerify('##########')],
            ]
        ];
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(422);

        $body = [
            'rsvps' => [
                ['name' => $this->faker->name(), 'number' => $this->faker->numerify('########')],
            ]
        ];
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(422);

        $body = [
            'rsvps' => [
                ['name' => $this->faker->name(), 'number' => $this->faker->numerify('asffgygopz')],
            ]
        ];
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(422);

        $body = [
            'rsvps' => [
                ['name' => $this->faker->name(), 'number' => $this->faker->numerify('##########')],
                ['name' => $this->faker->name(), 'number' => $this->faker->numerify('##########')],
                ['name' => "as", 'number' => "!"]
            ]
        ];
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertStatus(422);
    }

    public function test_an_user_can_create_rsvps() {
        Notification::fake();

        $user = User::factory()->create();
        $this->login($user);

        $numberRsvps = $this->faker->numberBetween(5, 15);
        $body = [
            'rsvps' => $this->generateRsvpData($numberRsvps)
        ];

        $this->assertDatabaseCount('rsvps', 0);
        
        $response = $this->postJson('api/inviter/rsvp', $body);
        $response->assertOk();

        $this->assertDatabaseCount('rsvps', $numberRsvps);
        $this->assertCount($numberRsvps, $response->getData()->data);
    }

    public function test_storing_rsvps_returns_rsvp_resource() {
        $user = User::factory()->create();
        $this->login($user);

        $numberRsvps = $this->faker->numberBetween(5, 15);
        $body = [
            'rsvps' => $this->generateRsvpData($numberRsvps)
        ];
        
        $response = $this->postJson('api/inviter/rsvp', $body);
        
        foreach ($response->getData()->data as $index => $rsvpResource) {
            $this->assertEquals($rsvpResource->name, $body['rsvps'][$index]['name']);
            $this->assertEquals($rsvpResource->number, $body['rsvps'][$index]['number']);
        }
    }







    private function generateRsvpData($numberRsvps) {
        $list = [];
        $i = 0;

        while ($i < $numberRsvps) {
            array_push($list, ['name' => $this->faker->name(), 'number' => $this->faker->numerify('##########')]);
            $i++;
        }

        return $list;
    }
}
