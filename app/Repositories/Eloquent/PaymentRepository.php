<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Eloquent\Repository;

class PaymentRepository extends Repository implements PaymentRepositoryInterface
{
    /**
     * @return string
     */
    public function getModel() : string
    {
        return Payment::class;
    }
}
