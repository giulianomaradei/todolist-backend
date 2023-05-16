<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\CustomRequest;

class CommentCreateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'comment' => 'required|string',
            'user_id' => 'required|integer',
            'task_id' => 'required|integer',
        ];
    }

}
