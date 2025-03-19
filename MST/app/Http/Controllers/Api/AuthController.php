<?php
namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{    

    public function register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(['status' => true,'message' => 'User registered Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken], 200);

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Rate limiting the login attempts
    if (RateLimiter::tooManyAttempts('login:' . $request->email, 5)) {
        return response()->json(['message' => 'Too many login attempts. Please try again later.'], 429);
    }

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        RateLimiter::hit('login:' . $request->email);
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Reset login attempts on successful login
    RateLimiter::clear('login:' . $request->email);

    $accessToken = $user->createToken('access_token', ['*'], Carbon::now()->addHours(2))->plainTextToken;
    return response()->json(['access_token' => $accessToken, 'token_type' => 'Bearer']);
}

public function logout(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // Revoke all user tokens
    $user->tokens->each(function ($token) {
        $token->delete();
    });

    return response()->json(['message' => 'Logged out successfully']);
}



    public function refreshToken(Request $request)
    {
        $currentRefreshToken = $request->bearerToken();
        $refreshToken = PersonalAccessToken::findToken($currentRefreshToken);

        if (!$refreshToken || !$refreshToken->can('refresh') || $refreshToken->expires_at->isPast()) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }

        $user = $refreshToken->tokenable;
        $refreshToken->delete();

        $accessTokenExpiresAt = Carbon::now()->addDays(1);
        $refreshTokenExpiresAt = Carbon::now()->addDays(7);

        $newAccessToken = $user->createToken('access_token', ['*'], Carbon::now()->addHours(2))->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', ['refresh'], $refreshTokenExpiresAt)->plainTextToken;

        return response()->json([
            'access_token' => $newAccessToken,
            'access_token_expires_at' => $accessTokenExpiresAt,
            'refresh_token' => $newRefreshToken,
            'refresh_token_expires_at' => $refreshTokenExpiresAt,
            'token_type' => 'Bearer',
        ]);
    }


    


}