<?php

namespace App\Exceptions;

use Exception;

class PayAmountBiggerRemainDebtException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Can not pay amount bigger than remaining debt.'
        ], 400);
    }
}
