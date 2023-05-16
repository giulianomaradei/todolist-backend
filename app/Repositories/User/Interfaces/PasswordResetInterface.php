<?php

namespace App\Repositories\User\Interfaces;

use App\Repositories\Base\Interfaces\BaseInterface;

interface PasswordResetInterface extends BaseInterface
{
    public function getToken($email);
    public function getEmailByToken($token);
}