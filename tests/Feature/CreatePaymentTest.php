<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\Loan
     */
    protected $loan;

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
        $this->loan = Loan::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @return void
     */
    public function test_create_payment_unauthenticated()
    {
        $response = $this->json('POST', "/api/payment");

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_create_payment_success()
    {
        $this->loan->update([
            'status' => Loan::STATUSES['approved']
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/payment', [
            'loan_id' => $this->loan->id,
            'amount' => 1000
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.amount', 1000)
            ->assertJsonPath('data.loan_id', $this->loan->id);
    }

    /**
     * @return void
     */
    public function test_create_payment_validate()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/payment', [
            'amount' => null,
            'loan_id' => null
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'loan_id' => [
                        'The loan id field is required.'
                    ],
                    'amount' => [
                        'The amount field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_create_payment_for_unapproved_loan()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/payment', [
            'loan_id' => $this->loan->id,
            'amount' => 1000
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => true,
                'message' => 'Can not pay for un-approved loan.'
            ]);
    }

    /**
     * @return void
     */
    public function test_create_payment_for_notfound_loan()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('POST', '/api/payment', [
            'loan_id' => 10,
            'amount' => 1000
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Can not find the loan record'
            ]);
    }
}
