<?php

namespace App\Services;

use App\Exceptions\CreatePaymentForUnApprovedLoanException;
use App\Exceptions\PayAmountBiggerRemainDebtException;
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
            DB::beginTransaction();

            $loanId = $data['loan_id'];
            $amount = $data['amount'];

            $loan = $this->loanRepository->find($loanId, [
                'user_id' => $user->id
            ]);

            if (!$loan) {
                abort(404, 'Can not find the loan record');
            }

            if ($loan->status !== Loan::STATUSES['approved']) {
                throw new CreatePaymentForUnApprovedLoanException();
            }

            if ($loan->balance < $amount) {
                throw new PayAmountBiggerRemainDebtException();
            }

            $payment = $this->paymentRepository->create([
                'loan_id' => $loan->id,
                'amount' => $amount
            ]);

            $loan->fill(['balance' => $loan->balance - $amount]);
            $this->loanRepository->save($loan);

            DB::commit();

            return $payment;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
