<?php

namespace App\Services\Task\Interfaces;

use App\Services\Base\Interfaces\BaseInterface;
use Illuminate\Http\Request;

interface TaskInterface extends BaseInterface
{
    public function save( Request $request, $id );
    public function create( Request $request );
    public function delete( $id );
    public function getById( $id );
    public function list( $skip, $take, $request );
}