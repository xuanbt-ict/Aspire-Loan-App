<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\LoanRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * @var PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var LoanRepositoryInterface
     */
    protected $loanRepository;

    /**
     * @param PaymentRepositoryInterface $paymentRepository
     * @param LoanRepositoryInterface $loanRepository
     */
    public function __construct(PaymentRepositoryInterface $paymentRepository, LoanRepositoryInterface $loanRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->loanRepository = $loanRepository;
    }

    /**
     * @param User $user
     * @param array $data
     * @return \App\Models\Payment
     */
    public function create(User $user, array $data)
    {
        try {
            DB::startTransaction();

            $loanId = $data['loan_id'];
            $amount = $data['amount'];

            $loan = $user->loans()->where('status', Loan::STATUSES['approved'])->findOrFail($loanId);

            $payment = $this->paymentRepository->create([
                'loan_id' => $loan->id,
                'amount' => $amount
            ]);

            $loan->fill(['balance' => $loan->balance - $amount]);
            $this->loanRepository->save($loan);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $payment;
    }
}
