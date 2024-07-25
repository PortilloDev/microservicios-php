<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\UserSuccessfullyStoredMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserSuccessfullyStoredMessageHandler implements MessageHandlerInterface
{
     public function __construct(private LoggerInterface $logger)
     {
     }

    public function __invoke(UserSuccessfullyStoredMessage $message): void
    {
        $this->logger->info('User successfully stored', [
            'name' => $message->getName(),
            'email' => $message->getEmail(),
            'code' => $message->getCode(),
        ]);
    }
}