<?php

namespace Mailer\Messenger\Handler;

use Mailer\Templating\TwigTemplate;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RequestResetPasswordMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host;

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(RequestResetPasswordMessage $message): void
    {
        $payload = [
            'url' => \sprintf(
                '%s%s?uid=%s&rpt=%s',
                $this->host,
                ClientRoute::RESET_PASSWORD,
                $message->getId(),
                $message->getResetPasswordToken()
            )
        ];

        $this->mailerService->send($message->getEmail(), TwigTemplate::USER_PASSWORD_RESET, $payload);
    }
}
