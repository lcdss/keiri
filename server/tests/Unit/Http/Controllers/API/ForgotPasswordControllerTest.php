<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSendResetLinkEmail()
    {
        Notification::fake();

        $user = factory(\App\Models\User::class)->create([
            'password' => bcrypt('mypassword'),
        ]);

        $this->json('POST', '/auth/reset', ['email' => $user->email])
             ->assertStatus(200);

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
