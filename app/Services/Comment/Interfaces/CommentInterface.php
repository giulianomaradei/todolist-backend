<?php

namespace App\Services\Comment\Interfaces;

use App\Services\Base\Interfaces\BaseInterface;
use Illuminate\Http\Request;

interface CommentInterface extends BaseInterface
{
    public function save( Request $request, $id );
    public function create( Request $request );
    public function delete( $id );
    public function getById( $id );
    public function list( $skip, $take, $request );
}