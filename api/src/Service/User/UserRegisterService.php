<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\EncoderService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;
use App\Messenger\Message\UserRegisteredMessage;
use App\Exception\User\UserAlreadyExistException;
use App\Messenger\RoutingKey;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegisterService
{
    private UserRepository $userRepository;
    private EncoderService $encoderService;
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
        $this->messageBus = $messageBus;
    }

    public function create(Request $request): User
    {
        $name = RequestService::getField($request, 'name');
        $email = RequestService::getField($request, 'email');
        $password = RequestService::getField($request, 'password');
        $surname = RequestService::getField($request, 'surname');
        $username = RequestService::getField($request, 'username');

        $user = new User($email, $password, $username, $name, $surname);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));

        try {
            $this->userRepository->saveEntity($user);
        } catch (\Exception $e) {
            throw UserAlreadyExistException::fromSQL($e->getMessage());
        }

        // Send message to queue
        $this->messageBus->dispatch(
            new UserRegisteredMessage(
                $user->getId(),
                $user->getName(),
                $user->getEmail(),
                $user->getToken()
            ),
            [new AmqpStamp(RoutingKey::USER_QUEUE)] // "sello tipico de carta"
        );

        return $user;
    }
}
