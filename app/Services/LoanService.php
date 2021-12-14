<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\LoanRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

            $amount = $data['amount'] ?? 0;

            $loan = $this->loanRepository->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance' => $amount,
                'loan_term' => $data['loan_term'] ?? 0,
                'status' => Loan::STATUSES['created']
            ]);
            
            DB::commit();

            return $loan;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param integer $id
     * @param array $data
     * @return \App\Models\Loan
     */
    public function update($id, array $data = [])
    {
        try {
            DB::beginTransaction();

            $loan = $this->loanRepository->update($id, $data);
            
            DB::commit();

            return $loan;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
