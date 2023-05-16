<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class PasswordUpdateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'password'     => 'required|confirmed|min:8',
        ];
    }

}
