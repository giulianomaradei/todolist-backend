<?php

namespace App\Http\Requests;

use App\Http\Requests\CustomRequest;

class ListRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'simple'      => 'nullable|in:0,1',
            'take'        => 'nullable|numeric|min:0|max:1000',
            'skip'        => 'nullable|numeric|min:0',
            'search'      => 'nullable|string',
            'order_type'  => 'required_with:order_field|in:desc,asc',
            'order_field' => 'nullable|string',
        ];
    }

}
