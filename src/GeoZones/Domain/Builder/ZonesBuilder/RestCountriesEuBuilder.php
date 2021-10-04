<?php

namespace  GeoZones\Domain\Builder\ZonesBuilder;

use GeoZones\Domain\Factory\RestEuZoneFactoryInterface;
use GeoZones\Domain\Model\World;
use GeoZones\Domain\Service\Data\Parser\DataParserInterface;
use GeoZones\Domain\Service\Data\Parser\RestEuParser;
use GeoZones\Domain\Service\Data\Validator\RestEuValidator;
use GeoZones\Domain\Service\Http\HttpGeoClientInterface;
use GeoZones\SharedKernel\Error\Error;

/**
 * RestEu api will not return sub-regions and translations in v2
 *
 * @param array $parameters
 * @return World|Error
 */
class RestCountriesEuBuilder implements ZonesBuilderInterface
{
    private HttpGeoClientInterface $client;
    private DataParserInterface $parser;
    private RestEuValidator $dataValidator;
    private RestEuZoneFactoryInterface $zoneFactory;
    private string $url;

    public function __construct(
        HttpGeoClientInterface $client,
        RestEuParser $parser,
        RestEuValidator $dataValidator,
        RestEuZoneFactoryInterface $zoneFactory,
        string $restEuUrl
    ) {
        $this->client = $client;
        $this->parser = $parser;
        $this->dataValidator = $dataValidator;
        $this->zoneFactory = $zoneFactory;
        $this->url = $restEuUrl;
    }

    public function build(array $params): World|Error
    {
        if (array_key_exists('level', $params) && $params['level'] === "sub-regions") {
            return new Error(
                __METHOD__,
                "sub-regions classification level is not available on restcountries.eu",
                []
            );
        }

        $data = $this->getData();
        $data = $this->parseData($data[0], $params);
        $error = $this->dataValidator->validateData($data);
        if ($error instanceof Error) {
            return $error;
        }
        $data = $this->zoneFactory->instanciate($data);
        return $this->zoneFactory->createTable($data, $params['level']);
    }

    public function getData()
    {
        return $this->client->doRequest($this->url);
    }

    public function parseData($data, array $parameters): array
    {
        return $this->parser->parse($data, $parameters);
    }

    public function supports(string $provider): bool
    {
        return strtolower($provider) === 'restcountrieseu';
    }
}
