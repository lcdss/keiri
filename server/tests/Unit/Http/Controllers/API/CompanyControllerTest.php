<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')->json('GET', '/companies')->assertStatus(200)->assertExactJson([]);

        $companies = factory(\App\Models\Company::class, 10)->create();

        $this->actingAs($user, 'api')->json('GET', '/companies')->assertStatus(200)->assertJson($companies->toArray());
    }

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Company::class)->make();

        $this->actingAs($user, 'api')
             ->json('POST', '/companies', $category->toArray())
             ->assertStatus(201)
             ->assertJson($category->toArray());

        $this->json('POST', '/companies', [])
             ->assertStatus(422)
             ->assertJsonFragment([
                 'errors',
                 'message' => 'The given data was invalid.',
             ]);

        $this->assertDatabaseHas('companies', $category->toArray());
    }
}
