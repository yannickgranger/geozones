<?php

namespace MyPrm\GeoZones\Domain\Builder\ZonesBuilder;

use MyPrm\GeoZones\Domain\Factory\UnM49ZoneFactoryInterface;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Domain\Service\Data\Parser\DataParserInterface;
use MyPrm\GeoZones\Domain\Service\Data\Parser\UnM49Parser;
use MyPrm\GeoZones\Domain\Service\Data\Validator\UnM49Validator;
use MyPrm\GeoZones\Domain\Service\Http\HttpGeoClientInterface;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class UnM49Builder implements ZonesBuilderInterface
{
    private HttpGeoClientInterface $client;
    private DataParserInterface $parser;
    private UnM49ZoneFactoryInterface $zoneFactory;
    private UnM49Validator $dataValidator;
    private string $url;
    private array $acceptedLocales;

    public function __construct(
        HttpGeoClientInterface $client,
        UnM49Parser $parser,
        UnM49Validator $dataValidator,
        UnM49ZoneFactoryInterface $zoneFactory,
        string $unsdUrl,
        array $unM49acceptedLocales
    ) {
        $this->client = $client;
        $this->parser = $parser;
        $this->zoneFactory = $zoneFactory;
        $this->dataValidator = $dataValidator;
        $this->url = $unsdUrl;
        $this->acceptedLocales = $unM49acceptedLocales;
    }

    /**
     * UN M49 methodology has a specific set of locales, others are substituted by 'en' and may be translated
     *
     * @param array $parameters
     * @return World|Error
     */
    public function build(array $parameters): World|error
    {
        if (array_key_exists('locale', $parameters) && !in_array($parameters
            ['locale'], $this->acceptedLocales)) {
            $parameters['locale'] = 'en';
        }

        $data = $this->getData();
        $data = $this->parseData($data, $parameters);
        $content['fieldNames'] = array_shift($data);
        $content['data'] = $data;

        $error = $this->dataValidator->validateData($content);

        if ($error instanceof Error) {
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
