<?php

namespace App\Service\User;

use App\Messenger\RoutingKey;
use Doctrine\ORM\ORMException;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use App\Exception\User\UserIsActiveException;
use Symfony\Component\HttpFoundation\Request;
use App\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

class ResendActivationEmailService
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;


    public function __construct(UserRepository $userRepository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function resend(string $email): void
    {

        $user = $this->userRepository->findOneByEmailOrFail($email);

        //echo 'User found: ' . $user->getName() . PHP_EOL;
        if ($user->isActive()) {
            throw UserIsActiveException::fromEmail($email);
        }

        //echo 'User is not active' . PHP_EOL;
        $user->setToken(\sha1(\uniqid()));
        //echo 'User token: ' . $user->getToken() . PHP_EOL;
        $this->userRepository->saveEntity($user);
        //echo 'User saved' . PHP_EOL;

        $this->messageBus->dispatch(
            new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
        //echo 'Message dispatched' . PHP_EOL;
    }
}
