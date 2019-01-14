<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')->json('GET', '/people')->assertStatus(200)->assertExactJson([]);

        $people = factory(\App\Models\Person::class, 10)->create();

        $this->actingAs($user, 'api')->json('GET', '/people')->assertStatus(200)->assertJson($people->toArray());
    }

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        $person = factory(\App\Models\Person::class)->make();

        $this->actingAs($user, 'api')
             ->json('POST', '/people', $person->toArray())
             ->assertStatus(201)
             ->assertJson($person->toArray());

        $this->json('POST', '/people', [])
             ->assertStatus(422)
             ->assertJsonFragment([
                 'errors',
                 'message' => 'The given data was invalid.',
             ]);

        $this->assertDatabaseHas('people', $person->toArray());
    }
}
