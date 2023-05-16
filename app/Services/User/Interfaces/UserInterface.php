<?php

namespace App\Services\User\Interfaces;

use App\Services\Base\Interfaces\BaseInterface;
use Illuminate\Http\Request;

interface UserInterface extends BaseInterface
{
    public function savePassword( $user, Request $request);
    public function updatePassword( $user, Request $request );
    public function save( Request $request, $id );
    public function create( Request $request );
    public function delete( $id );
    public function getById( $id );
    public function resetPassword( $id );
    public function list( $skip, $take, $request );
}