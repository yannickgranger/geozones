<?php

namespace Symfony5\Http\Client;

use GeoZones\Domain\Exception\ExternalSourceException;
use GeoZones\Domain\Service\Http\HttpGeoClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpGeoClient implements HttpGeoClientInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function doRequest(string $url)
    {
        try {
            $client = HttpClient::createForBaseUri($url);
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
            $headers = $response->getHeaders();
            return [$response->getContent(), $statusCode, $headers];
        } catch (\Exception $e) {
            throw new ExternalSourceException($e->getMessage(), $statusCode ?? 500, null);
        }
    }
}
