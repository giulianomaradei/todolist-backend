<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\CustomRequest;

class TaskCreateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'task' => 'required|string',
            'user_id' => 'required|integer',
            'description' => 'required|string',
            'type' => 'required|string',
        ];
    }

}
