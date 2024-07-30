<?php

namespace App\Infraestructure\Http\Client;

use App\Infraestructure\Http\Exception\HttpClientException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $url, array $options = []): ResponseInterface
    {
        try{
            return $this->client->get($url, $options);
        }catch (\Exception $e){
            throw new HttpClientException($e->getMessage(), $e->getCode());
        }
    }
}