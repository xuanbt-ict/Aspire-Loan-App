<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\LoanRepositoryInterface;

class LoanService
{
    /**
     * @var LoanRepositoryInterface
     */
    protected $loanRepository;

    /**
     * @param LoanRepositoryInterface $loanRepository
     */
    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    /**
     * @param User $user
     * @param array $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginate(User $user, $filter = [])
    {
        return $this->loanRepository->getAllPaginate($user, $filter);
    }

    /**
     * @param User $user
     * @param array $data
     * @return \App\Models\Loan
     */
    public function create(User $user, array $data = [])
    {
        $amount = $data['amount'] ?? 0;

        return $this->loanRepository->create([
            'user_id' => $user->id,
            'amount' => $amount,
            'balance' => $amount,
            'loan_term' => $data['loan_term'] ?? 0,
            'status' => Loan::STATUSES['created']
        ]);
    }

    /**
     * @param Loan $loan
     * @param array $data
     * @return \App\Models\Loan
     */
    public function update(Loan $loan, array $data = [])
    {
        $loan->fill([
            'status' => $data['status']
        ]);

        return $this->loanRepository->save($loan);
    }
}
