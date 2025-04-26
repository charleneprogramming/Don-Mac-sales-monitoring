<?php

namespace App\Http\Controllers\Auth\API;

use App\Application\User\RegisterUser;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthAPIController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $credentials = ['username' => $request->username, 'password' => $request->password];
            $result = $this->registerUser->login($credentials);

            if (! $result) {
                return response()->json([
                    'error' => 'Invalid credentials',
                ], 401);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices successfully',
        ]);

    }

    public function user(Request $request)
    {
        $user = UserModel::where('api_token', $request->bearerToken())->first();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
        ]);
    }
}
