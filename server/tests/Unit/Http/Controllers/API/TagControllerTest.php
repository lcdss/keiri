<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')->json('GET', '/references')->assertStatus(200)->assertExactJson([]);

        $references = factory(\App\Models\Reference::class, 10)->create();

        $this->actingAs($user, 'api')->json('GET', '/references')->assertStatus(200)->assertJson($references->toArray());
    }

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        $reference = factory(\App\Models\Reference::class)->make();

        $this->actingAs($user, 'api')
             ->json('POST', '/references', $reference->toArray())
             ->assertStatus(201)
             ->assertJson($reference->toArray());

        $this->json('POST', '/references', [])
             ->assertStatus(422)
             ->assertJsonFragment([
                 'errors',
                 'message' => 'The given data was invalid.',
             ]);

        $this->assertDatabaseHas('references', $reference->toArray());
    }
}
