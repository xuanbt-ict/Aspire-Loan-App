<?php

namespace App\Exceptions;

use Exception;

class CreatePaymentForUnApprovedLoanException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Can not pay for un-approved loan.'
        ], 400);
    }
}
