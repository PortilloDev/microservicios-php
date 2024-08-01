<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\NotificationStockOutMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationStockOutMessageHandler implements MessageHandlerInterface
{
     public function __construct(private LoggerInterface $logger)
     {
     }

    public function __invoke(NotificationStockOutMessage $message): void
    {
        $this->logger->info('Notified user stock out', [
            'productId' => $message->productId,
            'quantity' => $message->quantity,
        ]);
    }
}