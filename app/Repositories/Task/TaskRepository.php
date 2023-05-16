<?php

namespace App\Repositories\Task;

use App\Repositories\Task\Interfaces\TaskInterface;
use App\Repositories\Base\BaseRepository;
use App\Models\Task\Task;

class TaskRepository extends BaseRepository implements TaskInterface
{

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

}