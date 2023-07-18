<?php

namespace App\Service\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterService
{
    $private UserRepositoy $userRepository;
    $private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');
        $surname = RequestService::getField($request, 'surname');
        $username = RequestService::getField($request, 'username');

        $user = new User( $email, $password, $username, $name, $surname);
        $user->setPassword($this->encoderService->encode($user, $password));
        
    }
}