<?php
namespace App\Services\Task;

use App\Services\Base\BaseService;
use App\Services\Task\Interfaces\TaskInterface;
use App\Repositories\Task\Interfaces\TaskInterface as TaskRepository;

use Illuminate\Http\Request;

class TaskService extends BaseService implements TaskInterface
{
    private $taskRepository;

    public function __construct (
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    public function save( Request $request, $id )
    {
        $task = $this->taskRepository->update( $request->validated(), $id );

        return $task->fresh();
    }

    public function getByUser(Request $request){
        $task = $this->taskRepository->newInstance();
        return $task->byUserId($request->user()->id)->get();
    }

    public function create( Request $request )
    {
        $task = $this->taskRepository->create($request->validated());
        return $task->fresh();
    }

    public function delete( $id )
    {
        $task = $this->taskRepository->getById($id);
        $task->delete();
    }

    public function getById( $id )
    {
        return $this->taskRepository->getById( $id );
    }

    public function list( $skip, $take, $request )
    {
        return $this->taskRepository->list($skip, $take, $request, [], ['byTask']);
    }

}
