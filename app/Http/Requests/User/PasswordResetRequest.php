<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class PasswordResetRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|confirmed|min:8',
        ];
    }

}
