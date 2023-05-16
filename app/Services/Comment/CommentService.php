<?php
namespace App\Services\Comment;

use App\Services\Base\BaseService;
use App\Services\Comment\Interfaces\CommentInterface;
use App\Repositories\Comment\Interfaces\CommentInterface as CommentRepository;

use Illuminate\Http\Request;

class CommentService extends BaseService implements CommentInterface
{
    private $CommentRepository;

    public function __construct (
        CommentRepository $CommentRepository
    )
    {
        $this->CommentRepository = $CommentRepository;
    }

    public function save( Request $request, $id )
    {
        $Comment = $this->CommentRepository->update( $request->validated(), $id );

        return $Comment->fresh();
    }

    public function getByUser(Request $request){
        $Comment = $this->CommentRepository->newInstance();
        return $Comment->byUserId($request->user()->id)->get();
    }

    public function create( Request $request )
    {
        $Comment = $this->CommentRepository->create($request->validated());
        return $Comment->fresh();
    }

    public function delete( $id )
    {
        $Comment = $this->CommentRepository->getById($id);
        $Comment->delete();
    }

    public function getById( $id )
    {
        return $this->CommentRepository->getById( $id );
    }

    public function list( $skip, $take, $request )
    {
        return $this->CommentRepository->list($skip, $take, $request, [], ['byComment']);
    }

}
