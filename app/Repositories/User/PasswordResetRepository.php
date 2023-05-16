<?php

namespace App\Repositories\User;

use App\Repositories\User\Interfaces\PasswordResetInterface;
use App\Repositories\Base\BaseRepository;
use App\Models\User\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetRepository extends BaseRepository implements PasswordResetInterface
{

    public function __construct( PasswordReset $model )
    {
        $this->model = $model;
    }

    public function getToken($email)
    {
        return $this->model->updateOrCreate(
            [ 'email' => $email ],
            [ 'email' => $email, 'token' => (string) Str::uuid() ]
        )->token;
    }

    public function getEmailByToken($token)
    {
        $model = $this->model->whereToken($token)->first();
        $email = $model->email;

        $model->delete();

        return $email;
    }
}
