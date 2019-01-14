<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')->json('GET', '/categories')->assertStatus(200)->assertExactJson([]);

        $categories = factory(\App\Models\Category::class, 10)->create();

        $this->actingAs($user, 'api')->json('GET', '/categories')->assertStatus(200)->assertJson($categories->toArray());
    }

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->make();

        $this->actingAs($user, 'api')
             ->json('POST', '/categories', $category->toArray())
             ->assertStatus(201)
             ->assertJson($category->toArray());

        $this->json('POST', '/categories', [])
             ->assertStatus(422)
             ->assertJsonFragment([
                 'errors',
                 'message' => 'The given data was invalid.',
             ]);

        $this->assertDatabaseHas('categories', $category->toArray());
    }
}
