<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    /**
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('auth:sanctum');
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(CreatePaymentRequest $request)
    {
        $payment = $this->paymentService->create($request->user(), $request->all());

        return new PaymentResponse($payment);
    }
}
