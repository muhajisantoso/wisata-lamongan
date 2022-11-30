<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();

        $this->json('post', route('api.auth.login'), [
            'username' => $user->username,
            'password' => 'password'
        ])
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user',
                    'access_token'
                ]
            ]);
    }

    public function test_user_cant_login_with_invalid_credentials()
    {
        $this->json('post', route('api.auth.login'), [
            'username' => '123',
            'password' => '123123'
        ])
            ->assertNotFound()
            ->assertJsonFragment([
                'message' => __('auth.failed')
            ]);
    }

    public function test_user_logged_in_can_logout()
    {
        $user = User::factory()->make();

        Sanctum::actingAs($user);

        $this->json('post', route('api.auth.logout'))
            ->assertNoContent();
    }
}
