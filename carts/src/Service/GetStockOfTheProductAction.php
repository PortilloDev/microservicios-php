<?php

namespace App\Service;

use App\Infraestructure\Http\Client\HttpClientInterface;
use App\Infraestructure\Http\Exception\HttpClientException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class GetStockOfTheProductAction
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $inventoryServiceEndpoint
    ) {
    }
    public function __invoke(string $id): ?array
    {
        try{
            $response = $this->httpClient->get(sprintf('%s/%s',$this->inventoryServiceEndpoint, $id));
        }catch (HttpClientException $exception){
            $this->logger->error($exception->getMessage());
            return null;

        }
        if ($response->getStatusCode() === Response::HTTP_OK){
            return json_decode($response->getBody()->getContents(), true);
        };

        return null;

    }
}