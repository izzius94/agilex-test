<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Login $request): JsonResponse
    {
        $request->authenticate();

        return response()->json([
            'message' => 'Logged in successfully.',
            'token' => Auth::user()->createToken(config('app.name'))->plainTextToken,
        ]);
    }
}
