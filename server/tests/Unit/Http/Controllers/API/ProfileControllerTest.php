<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')
             ->json('GET', '/profile')
             ->assertStatus(200)
             ->assertExactJson($user->toArray());
    }

    public function testUpdate()
    {
        $user = factory(\App\Models\User::class)->create();

        $response = $this->actingAs($user, 'api')
             ->json('PUT', '/profile', [
                 'name' => 'Jason Mathius',
                 'email' => 'jasonmathius@domain.com',
             ])
             ->assertStatus(200);

        $user->name = 'Jason Mathius';
        $user->email = 'jasonmathius@domain.com';

        $response->assertJson($user->toArray());

        $this->actingAs($user, 'api')
             ->json('PUT', '/profile', [
                 'name' => 'Jonas Duarte',
             ])
             ->assertStatus(200)
             ->assertJsonFragment([
                 'name' => 'Jonas Duarte',
             ]);

         $this->actingAs($user, 'api')
              ->json('PUT', '/profile', [
                  'password' => '123456',
              ])
              ->assertStatus(422);

          $this->actingAs($user, 'api')
               ->json('PUT', '/profile', [
                   'password' => '12345678',
               ])
               ->assertStatus(200);

          $this->assertTrue(\Hash::check('12345678', $user->password));
    }
}
