<?php

namespace App\Service;

use App\Infraestructure\Http\Client\HttpClientInterface;
use App\Infraestructure\Http\Exception\HttpClientException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FindProductInServiceAction
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $productServiceEndpoint
    ) {
    }
    public function __invoke(string $id): ?bool
    {
        try{
           $response = $this->httpClient->get(sprintf('%s/%s',$this->productServiceEndpoint, $id));
        }catch (HttpClientException $exception){
            $this->logger->error($exception->getMessage());
            return null;

        }
        if ($response->getStatusCode() === JsonResponse::HTTP_OK){
            return true;
        };

        return false;

    }
}