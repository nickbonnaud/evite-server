<?php

namespace Tests\Unit;

use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase {
    use RefreshDatabase;

    public function test_user_auto_generates_uuid_when_created() {
        $user = User::factory()->create();
        $this->assertTrue(Str::isUuid($user->id));
    }
    
    public function test_user_auto_hashes_password() {
        $password = 'ncdjs3yBUIFV7373%%#$#hdbnd.?';
        $user = User::factory()->create(['password' => $password]);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_user_has_many_rsvps() {
        $user = User::factory()->create();
        Rsvp::factory(3)->create(['user_id' => $user->id]);

        foreach ($user->rsvps as $rsvp) {
            $this->assertInstanceOf(Rsvp::class, $rsvp);
        }
    }
}
