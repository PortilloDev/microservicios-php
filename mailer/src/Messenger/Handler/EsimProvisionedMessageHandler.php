<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\EsimProvisionedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EsimProvisionedMessageHandler implements MessageHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(EsimProvisionedMessage $message): void
    {
        $this->logger->info('Sending eSIM activation email', [
            'email' => $message->userEmail,
            'orderId' => $message->orderId,
            'iccid' => $message->iccid,
            'profile' => $message->profileName,
            'activationCode' => $message->activationCode,
            'qrCodeUrl' => $message->qrCodeUrl,
        ]);
    }
}
