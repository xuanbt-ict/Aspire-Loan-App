<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => 1000000,
            'balance' => 1000000,
            'loan_term' => 10,
            'status' => Loan::STATUSES['created']
        ];
    }
}
