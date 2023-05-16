<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class UserCreateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

}
