<?php

namespace App\Service;

use App\Infraestructure\Http\Client\HttpClientInterface;
use App\Infraestructure\Http\Exception\HttpClientException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterActionService
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $applicationServiceUserEndpoint
    ) {
    }

    public function __invoke(string $name, string $email): void
    {
        try{
            $this->httpClient->get(sprintf('%s?email=%s',$this->applicationServiceUserEndpoint, $email));
        }catch (HttpClientException $exception){
            if (JsonResponse::HTTP_NOT_FOUND === $exception->getStatusCode()){
                $this->messageBus->dispatch(
                    new UserRegisteredMessage($name, $email),
                    [new AmqpStamp(RoutingKey::REGISTER_APPLICATION_QUEUE)]
                );
            }
            $this->logger->error($exception->getMessage());
            return;

        }
        throw new ConflictHttpException(sprintf('User with email %s already exists', $email));

    }
}