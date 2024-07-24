<?php

namespace App\Service;

use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterActionService
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(string $name, string $email): void
    {
        $this->messageBus->dispatch(
            new UserRegisteredMessage($name, $email),
            [new AmqpStamp(RoutingKey::REGISTER_APPLICATION_QUEUE)]
        );
    }
}