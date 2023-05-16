<?php
namespace App\Services\User;

use App\Services\Base\BaseService;
use App\Services\User\Interfaces\UserInterface;
use App\Repositories\User\Interfaces\UserInterface as UserRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService implements UserInterface
{
    private $userRepository;

    public function __construct (
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function savePassword( $user, Request $request)
    {
        if( !empty($request->password) ){
            $user->senha = Hash::make($request->password);
            $user->save();
        }
    }

    public function updatePassword( $user, Request $request )
    {
        return $this->savePassword( $user, $request );
    }

    public function save( Request $request, $id )
    {
        $user = $this->userRepository->update( $request->validated(), $id );

        $this->savePassword($user, $request);

        return $user->fresh();
    }

    public function create( Request $request )
    {

        return DB::transaction( function() use ($request) {
            $validated = $request->validated();
            $validated['password'] = Hash::make( $validated['password'] );
            $user = $this->userRepository->create($validated);

            // event( new \App\Events\UserCreatedEvent( $user ) );

            return $user->fresh();
        });
    }

    public function delete( $id )
    {
        $user = $this->userRepository->getById($id);
        $user->delete();
    }

    public function getById( $id )
    {
        return $this->userRepository->getById( $id );
    }

    public function resetPassword( $id )
    {
        $user = $this->userRepository->getById( $id, ['person'] );
        $user->senha = Hash::make($user->person->cpf_cnpj);
        $user->save();
        return $user->fresh();
    }

    public function list( $skip, $take, $request )
    {
        return $this->userRepository->list($skip, $take, $request, ['person', 'profile'], ['byName', 'byEmail']);
    }

}
