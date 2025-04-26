<?php

namespace App\Http\Middleware;

use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Closure;
use Illuminate\Http\Request;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        $user = UserModel::where('api_token', $token)->first();

        if (! $user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Add user to request for later use
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
