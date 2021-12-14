<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetListLoansTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * @return void
     */
    public function test_get_list_loan_unauthenticated()
    {
        $response = $this->json('GET', '/api/loan');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_get_list_empty_loan()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('GET', '/api/loan');

        $response->assertStatus(200)
            ->assertJsonPath('data', []);
    }

    public function test_get_list_loans()
    {
        for ($i=0; $i < 10; $i++) { 
            Loan::factory()->create([
                'user_id' => $this->user->id
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('GET', '/api/loan');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 10);
    }
}
