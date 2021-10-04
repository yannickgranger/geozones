<?php

namespace GeoZonesTests\Service\Http;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\HttpClient;

class HttpGeoClientTest extends KernelTestCase
{
    private string $unsdUrl;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->unsdUrl = $container->getParameter('unsd_url');
    }

    public function testUnsdUrl()
    {
        $client = HttpClient::createForBaseUri($this->unsdUrl);
        $response = $client->request('GET', $this->unsdUrl);
        $this->assertEquals(200, $response->getStatusCode());
    }
}