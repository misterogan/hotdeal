<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/admin/login",
     * summary="adminlogin",
     * description="Admin Login",
     * operationId="Admin login",
     * tags={"Admin"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }

        if(Auth::attempt($request->only('email','password'))){
            $data = [
                'data' => Auth::user()
            ];
            return $this->successResponse($data);
        }

        throw ValidationException::withMessages(
            [
                'email' => ['The provided credentials are incorrect.'],
            ]
        );
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success'    => true
        ], 200);
    }
}
