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
     */
    public function update(UpdateLoanRequest $request, $id)
    {
        $user = $request->user();
        $loan = $user->loans()->findOrFail($id);
        $this->loanService->update($loan, $request->all());

        return new LoanResource($loan);
    }
}
