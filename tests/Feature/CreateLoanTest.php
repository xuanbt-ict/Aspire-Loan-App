<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLoanTest extends TestCase
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
    public function test_create_loan_unauthenticated()
    {
        $response = $this->json('POST', "/api/loan");

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_create_loan_success()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/loan', [
            'amount' => 100000,
            'loan_term' => 10
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.amount', 100000)
            ->assertJsonPath('data.loan_term', 10);
    }

    /**
     * @return void
     */
    public function test_create_loan_validate()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/loan', [
            'amount' => null,
            'loan_term' => null
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'amount' => [
                        'The amount field is required.'
                    ],
                    'loan_term' => [
                        'The loan term field is required.'
                    ]
                ]
            ]);
    }
}
