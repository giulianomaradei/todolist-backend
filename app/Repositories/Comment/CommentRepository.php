<?php

namespace App\Repositories\Comment;

use App\Repositories\Comment\Interfaces\CommentInterface;
use App\Repositories\Base\BaseRepository;
use App\Models\Comment\Comment;

class CommentRepository extends BaseRepository implements CommentInterface
{

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

}