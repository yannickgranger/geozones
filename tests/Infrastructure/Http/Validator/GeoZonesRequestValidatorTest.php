<?php

namespace MyPrm\GeoZones\Tests\Infrastructure\Http\Validator;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\HttpClient;

class GeoZonesRequestValidatorTest extends KernelTestCase
{
    private array $acceptedContentType;
    private array $acceptedLocales;
    private array $validLevels;
    private string $baseUrl;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->acceptedContentType = $container->getParameter('app_content_types');
        $this->acceptedLocales = $container->getParameter('app_valid_locales');
        $this->validLevels = $container->getParameter('app_classif_levels');
        $this->baseUrl = getenv('BASE_URL');
    }

    public function testParameters()
    {
        $this->assertNotEmpty($this->acceptedContentType);
        $this->assertNotEmpty($this->acceptedLocales);
        $this->assertNotEmpty($this->validLevels);
    }

    public function testContentType()
    {
        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/regions/en', [
            'headers' => [
                'Content-type' => 'application/xml'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/regions/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());


        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/regions/en', [
            'headers' => [
                'Content-type' => 'application/zip'
            ]
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testLocale()
    {
        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/sub-regions/ko', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(400, $response->getStatusCode());


        $response = $client->request('GET', '/api/geozones/sub-regions', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/de', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/es', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/fr', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/it', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/nl', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/pl', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $client->request('GET', '/api/geozones/sub-regions/pt', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLevel()
    {
        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/sub-regions/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/regions/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $client = HttpClient::createForBaseUri($this->baseUrl);
        $response = $client->request('GET', '/api/geozones/toto/en', [
            'headers' => [
                'Content-type' => 'application/json'
            ]
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }
}