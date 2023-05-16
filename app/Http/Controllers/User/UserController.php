<?php

namespace App\Http\Controllers\User;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Services\User\Interfaces\UserInterface as UserService;
use App\Repositories\User\Interfaces\UserInterface as UserRepository;

class UserController extends Controller
{
    use ApiResponser;

    public function get( UserService $user, Request $request )
    {
        return $this->success( $request->user() );
    }

    public function updateProfile( UserService $user, UserUpdateRequest $request )
    {
        return $this->success( $user->updateProfile($request), __('user.successfully_updated'));
    }

    public function updatePassword( UserService $user, PasswordUpdateRequest $request )
    {
        return $this->success( $user->updatePassword($request->user(), $request), __('user.successfully_updated'));
    }

    public function update( $id, UserService $user, UserUpdateRequest $request )
    {
        return $this->success( $user->save($request, $id), __('user.successfully_updated'));
    }

    public function create( UserService $user, UserCreateRequest $request )
    {
        return $this->success( $user->create( $request ) );
    }

    public function list( UserService $user, ListRequest $request, $skip=0, $take=10 )
    {
        return $this->success( $user->list( $skip, $take, $request ) );
    }

    public function delete( $id, UserService $user )
    {
        return $this->success( $user->delete($id) );
    }

    public function getById( $id, UserService $user )
    {
        return $this->success( $user->getById( $id ) );
    }

    public function resetPassword( $id, UserService $user )
    {
        return $this->success( $user->resetPassword( $id ) );
    }

}