<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    protected $loanService;

    /**
     * @param LoanService $loanService
     */
    public function __construct(LoanService $loanService)
    {
        $this->middleware('auth:sanctum');
        $this->loanService = $loanService;
    }

    /**
     * Show all user's loan
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Get(
     * path="/api/loan",
     * tags={"Loan"},
     * security={ {"sanctum": {} }},
     *
     * @OA\Response(
     *     response=200,
     *     description="Get list loans of user",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     *
     * @OA\Response(
     *   response=401,
     *   description="Unauthorized"
     * ),
     * )
     */
    public function index(Request $request)
    {
        $loans = $this->loanService->getAllPaginate($request->user());

        return LoanResource::collection($loans);
    }

    /**
     * Create loan request
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Post(
     * path="/api/loan",
     * tags={"Loan"},
     * security={ {"sanctum": {} }},
     *
     * @OA\Parameter(
     *     name="amount",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer",
     *     )
     * ),
     * @OA\Parameter(
     *     name="loan_term",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     * ),
     *
     * @OA\Response(
     *     response=200,
     *     description="Create loan",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *   response=401,
     *   description="Unauthorized"
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
    public function store(CreateLoanRequest $request)
    {
        $loan = $this->loanService->create($request->user(), $request->all());

        return new LoanResource($loan);
    }

    /**
     * Update loan request
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Put(
     * path="/api/loan/{id}",
     * tags={"Loan"},
     * security={ {"sanctum": {} }},
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *          type="number",
     *     )
     * ),
     * @OA\Parameter(
     *     name="status",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string",
     *          enum={"created", "approved", "rejected"},
     *          default="created"
     *     )
     * ),
     *
     * @OA\Response(
     *     response=200,
     *     description="Update loan status",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *   response=401,
     *   description="Unauthorized"
     * ),
     * @OA\Response(
     *   response=403,
     *   description="Forbidden"
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
    public function update(UpdateLoanRequest $request, $id)
    {
        $loan = $this->loanService->update($id, $request->all());

        if (!$loan) {
            return abort(404, 'Can not find the loan record');
        }

        return new LoanResource($loan);
    }
}
