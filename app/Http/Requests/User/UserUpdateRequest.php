<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class UserUpdateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'nullable|string|confirmed|min:8',
            'name'     => 'nullable|string',
        ];
    }

}
