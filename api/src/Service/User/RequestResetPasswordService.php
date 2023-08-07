<?php

namespace App\Service\User;

use App\Messenger\RoutingKey;
use Doctrine\ORM\ORMException;
use App\Repository\UserRepository;

use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Message\RequestResetPasswordMessage;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

class RequestResetPasswordService
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
    public function send(string $email): void
    {
        $user = $this->userRepository->findOneByEmailOrFail($email);

        $user->setResetPasswordToken(\sha1(uniqid()));

        $this->userRepository->saveEntity($user);

        $this->messageBus->dispatch(
            new RequestResetPasswordMessage($user->getId(), $user->getEmail(), $user->getResetPasswordToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
    }
}
