<?php

namespace App\Http\Controllers\Comment;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
#use App\Http\Requests\Comment\PasswordUpdateRequest;
#use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Requests\Comment\CommentCreateRequest;
use App\Services\Comment\Interfaces\CommentInterface as CommentService;


class CommentController extends Controller
{
    use ApiResponser;

    public function get( CommentService $Comment, Request $request )
    {
        return $this->success( $request->user() );
    }

    public function update( $id, CommentService $Comment, CommentUpdateRequest $request )
    {
        return $this->success( $Comment->save($request, $id), __('user.successfully_updated'));
    }

    public function create( CommentService $Comment, CommentCreateRequest $request )
    {
        return $this->success( $Comment->create( $request ) );
    }

    public function list( CommentService $Comment, ListRequest $request, $skip=0, $take=10 )
    {
        return $this->success( $Comment->list( $skip, $take, $request ) );
    }

    public function delete( $id, CommentService $Comment )
    {
        return $this->success( $Comment->delete($id) );
    }

    public function getById( $id, CommentService $Comment )
    {
        return $this->success( $Comment->getById( $id ) );
    }
    
    public function getByUserId(Request $request ,CommentService $Comment )
    {   
        return $this->success( $Comment->getByUser($request));
    }

}