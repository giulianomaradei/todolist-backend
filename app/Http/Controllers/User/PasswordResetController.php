<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccessNotification;
use App\Traits\ApiResponser;
use App\Repositories\User\Interfaces\UserInterface as UserRepository;
use App\Repositories\User\Interfaces\PasswordResetInterface as PasswordResetRepository;
use App\Services\User\Interfaces\UserInterface as UserService;

class PasswordResetController extends Controller
{
    use ApiResponser;

    public function forgot(ForgotPasswordRequest $request, UserRepository $repo, PasswordResetRepository $passRepo )
    {
        $user  = $repo->findOrFailByEmail( $request->email );
        $token = $passRepo->getToken( $user->email );

        if ($user && $token)
            $user->notify(new PasswordResetNotification( $token, currentDomain() ));

        return $this->success(null, __('user.reset-sent'));
    }

    public function reset(PasswordResetRequest $request, UserRepository $repo, PasswordResetRepository $passRepo, UserService $service )
    {
        $userEmail = $passRepo->getEmailByToken( $request->token );
        $user = $repo->findOrFailByEmail( $userEmail );

        $service->savePassword($user, $request);

        $user->notify(new PasswordResetSuccessNotification());

        return $this->success(null, __('user.password-updated'));
    }
}
