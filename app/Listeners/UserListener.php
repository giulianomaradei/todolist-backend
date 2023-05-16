<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Repositories\User\Interfaces\UserInterface as UserRepository;
use App\Services\User\Interfaces\UserInterface as UserService;

class UserListener
{
    private $user;

    public function __construct(
        UserRepository     $user,
        UserService        $userService
    ){
        $this->user  = $user;
        $this->userService = $userService;
    }

    public function subscribe($events)
    {
        $events->listen(
           'App\Events\UserCreatedEvent',
           'App\Listeners\UserListener@onCreated'
        );
    }

    public function onCreated(UserCreatedEvent $event){
        $this->userService->welcomeMail($event->user);
    }
}
