<?php

namespace App\Service;

use App\Infraestructure\Http\Client\HttpClientInterface;
use App\Infraestructure\Http\Exception\HttpClientException;
use App\Messenger\Message\UpdateInventoryMessage;
use App\Messenger\RoutingKey;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class FindUserServiceAction
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $applicationServiceUserEndpoint
    ) {
    }

    public function __invoke(string $email): void
    {
        try{
          $this->httpClient->get(sprintf('%s?email=%s',$this->applicationServiceUserEndpoint, $email));
        }catch (HttpClientException $exception){
            $this->logger->error($exception->getMessage());
            throw new HttpException(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'Error trying to connect to user service');
        }
    }
}