<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateLoanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\User
     */
    protected $admin;

    /**
     * @var \App\Models\Loan
     */
    protected $loan;

    /**
     * @var string
     */
    protected $tokenUser;

    /**
     * @var string
     */
    protected $tokenAdmin;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->tokenUser = $this->user->createToken('test-token')->plainTextToken;
        $this->tokenAdmin = $this->admin->createToken('test-token')->plainTextToken;

        Role::create(['name' => 'admin']);
        $this->admin->assignRole('admin');

        $this->loan = Loan::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @return void
     */
    public function test_approve_loan_unauthenticated()
    {
        $response = $this->json('PUT', "/api/loan/{$this->loan->id}");

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_approve_loan_unauthorized()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenUser
        ])->json('PUT', "/api/loan/{$this->loan->id}");

        $response->assertStatus(403)
            ->assertJson(['message' => 'This action is unauthorized.']);
    }

    /**
     * @return void
     */
    public function test_approve_loan_approved_success()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ])->json('PUT', "/api/loan/{$this->loan->id}", [
            'status' => Loan::STATUSES['approved']
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $this->loan->id)
            ->assertJsonPath('data.status', Loan::STATUSES['approved']);
    }

    /**
     * @return void
     */
    public function test_approve_loan_rejected_success()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenAdmin
        ])->json('PUT', "/api/loan/{$this->loan->id}", [
            'status' => Loan::STATUSES['rejected']
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $this->loan->id)
            ->assertJsonPath('data.status', Loan::STATUSES['rejected']);
    }
}
