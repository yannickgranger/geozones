<?php

namespace MyPrm\GeoZones\Domain\Builder\ZonesBuilder;

use MyPrm\GeoZones\Domain\Factory\UnM49ZoneFactoryInterface;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Domain\Service\Data\Parser\DataParserInterface;
use MyPrm\GeoZones\Domain\Service\Data\Validator\DataValidatorInterface;
use MyPrm\GeoZones\Domain\Service\Http\HttpGeoClientInterface;
use MyPrm\GeoZones\Infrastructure\Service\Data\UnM49Validator;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class UnM49Builder implements ZonesBuilderInterface
{
    private HttpGeoClientInterface $client;
    private DataParserInterface $parser;
    private UnM49ZoneFactoryInterface $zoneFactory;
    private UnM49Validator $dataValidator;
    private string $url;

    public function __construct(
        HttpGeoClientInterface $client,
        DataParserInterface $parser,
        UnM49Validator $dataValidator,
        UnM49ZoneFactoryInterface $zoneFactory,
        string $unsdUrl
    ) {
        $this->client = $client;
        $this->parser = $parser;
        $this->zoneFactory = $zoneFactory;
        $this->dataValidator = $dataValidator;
        $this->url = $unsdUrl;
    }

    public function build(array $parameters): World|error
    {
        $data = $this->getData();
        $data = $this->parseData($data, $parameters);

        $content['fieldNames'] = array_shift($data);
        $content['data'] = $data;

        $error = $this->dataValidator->validateData($content);

        if($error instanceof Error){
            return $error;
        }

        $data = $this->zoneFactory->instanciate($content['data']);
        return $this->zoneFactory->createTable($data);
    }

    public function getData()
    {
        $html = $this->client->doRequest($this->url);
        return $html[0];
    }

    public function parseData($data, array $parameters = [])
    {
        return $this->parser->parse($data, $parameters);
    }

    public function supports(string $provider): bool
    {
        return strtolower($provider) === 'unm49';
    }
}