<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'remember_token' => Str::random(6),
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'email'=>'admin444@gmail.com',
            'password' => 'password',
        ]);

        $this->actingAs($user, 'web')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->actingAs($user, 'web')
            ->post('/logout');
        $this->assertGuest();
    }
}
