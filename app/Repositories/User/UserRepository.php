<?php

namespace App\Repositories\User;

use App\Exceptions\CustomException;
use App\Repositories\User\Interfaces\UserInterface;
use App\Repositories\Base\BaseRepository;
use App\Models\User\User;

class UserRepository extends BaseRepository implements UserInterface
{

    public function __construct(User $model)
    {
        $this->model = $model;
    }

}