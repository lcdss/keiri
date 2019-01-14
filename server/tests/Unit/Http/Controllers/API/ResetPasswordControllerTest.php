<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testReset()
    {
        $user = factory(\App\Models\User::class)->create([
            'password' => bcrypt('mypassword'),
        ]);

        $token = Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => bcrypt($token),
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        $this->json('POST', "/auth/reset/$token", [
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token,
        ])
             ->assertStatus(200)
             ->assertJsonFragment([
                 'status' => trans('passwords.reset')
             ]);
    }
}
