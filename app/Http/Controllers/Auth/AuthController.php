<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(AuthRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        if( ! $user || ! Hash::check( $request->password, $user->password ) )
            return $this->error( __('passwords.error'), 422);

        $user->tokens()->delete();

        $token = $user->createToken('Personal access Token');

        return [
            'token_type'        => 'Bearer',
            'expires_in'        => $token->accessToken->expires_at ? $token->accessToken->expires_at->getTimestamp() - time() : '',
            'access_token'      => $token->plainTextToken,
            'refresh_token'     => ''
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}