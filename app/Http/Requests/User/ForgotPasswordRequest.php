<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class ForgotPasswordRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

}
