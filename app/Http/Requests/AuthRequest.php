<?php

namespace App\Http\Requests;

use App\Http\Requests\CustomRequest;

class AuthRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }

}
