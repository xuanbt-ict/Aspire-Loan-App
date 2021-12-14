<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Resources\PaymentResource;
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
     * 
     * @OA\Post(
     * path="/api/payment",
     * tags={"Payment"},
     * security={ {"sanctum": {} }},
     * 
     * @OA\Parameter(
     *     name="loan_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *     )
     * ),
     * @OA\Parameter(
     *     name="amount",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     * ),
     *
     * @OA\Response(
     *     response=200,
     *     description="Create payment",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *   response=400,
     *   description="Can not pay for un-approved loan."
     * ),
     * @OA\Response(
     *   response=401,
     *   description="Unauthorized"
     * ),
     * @OA\Response(
     *   response=404,
     *   description="Not found"
     * ),
     * @OA\Response(
     *   response=422,
     *   description="Validation Error Messages",
     *   @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="false"),
     *      @OA\Property(property="result", type="string", example="[]"),
     *   )
     * ),
     * 
     * )
     */
    public function store(CreatePaymentRequest $request)
    {
        $payment = $this->paymentService->create($request->user(), $request->all());

        return new PaymentResource($payment);
    }
}
