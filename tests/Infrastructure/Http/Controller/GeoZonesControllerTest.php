<?php

namespace GeoZonesTests\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeoZonesControllerTest extends WebTestCase
{
    private string $baseUrl = '';

    protected function setUp(): void
    {
        $this->baseUrl = getenv('BASE_URL');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testitPingsRegionsRoute()
    {
        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/regions/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testitPingsSubRegionsRoute()
    {
        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/sub-regions/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertSame(200, $response->getStatusCode());
    }
}