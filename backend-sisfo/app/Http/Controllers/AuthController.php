<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        try {
            if (!Auth::attempt($credentials)) {
                throw new \Exception(__('auth.failed'), 404);
            }

            $token = Auth::user()->createToken('SanctumApi')->plainTextToken;

            $data = [
                'user' => Auth::user(),
                'token' => $token
            ];

            return $this->sendResponse('Login berhasil', $data);
        } catch (\Exception $th) {
            return $this->sendResponse($th->getMessage(), [], $th->getCode(), $th->getTraceAsString());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([], 204);
    }
}
