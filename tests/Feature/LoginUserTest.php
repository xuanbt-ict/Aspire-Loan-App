<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678')
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);
    }

    /**
     * @return void
     */
    public function test_login_failed()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678')
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456789'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Email or password invalid'
            ]);
    }

    /**
     * @return void
     */
    public function test_login_validate()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678')
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => null
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]);
    }
}
