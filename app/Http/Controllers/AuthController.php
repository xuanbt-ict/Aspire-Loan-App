<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('getProfile');
    }

    /**
     *
     * @param LoginRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     * path="/api/login",
     * tags={"Authentication"},
     *
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string",
     *     )
     * ),
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Login Successful",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *   response=422,
     *   description="Validation Error Messages",
     *   @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="false"),
     *      @OA\Property(property="result", type="string", example="[]"),
     *   )
     * ),
     *)
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password, [])) {
            return response()->json([
                'message' => 'Email or password invalid'
            ], 401);
        }

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/profile",
     * tags={"Authentication"},
     * security={ {"sanctum": {} }},
     *
     * @OA\Response(
     *     response=200,
     *     description="Get profile info",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * )
     *)
     */
    public function getProfile(Request $request)
    {
        return new UserResource($request->user());
    }
}
