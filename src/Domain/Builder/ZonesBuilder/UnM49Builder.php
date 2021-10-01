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
     * @param array $params
     * @return World|Error
     * @throws \Exception
     */
    public function build(array $params): World|error
    {
        if (array_key_exists('locale', $params) && !in_array($params
            ['locale'], $this->acceptedLocales)) {
            $params['locale'] = 'en';
        }

        $data = $this->getData();
        $data = $this->parseData($data, $params);
        $error = $this->dataValidator->validateData($data);

        if ($error instanceof Error) {
            return $error;
        }

        $data = $this->zoneFactory->instanciate($data['data']);
        return $this->zoneFactory->createTable($data, $params['level']);
    }

    public function getData()
    {
        $html = $this->client->doRequest($this->url);
        return $html[0];
    }

    public function parseData($data, array $parameters = []): array
    {
        $data = $this->parser->parse($data, $parameters);
        $content['fieldNames'] = array_shift($data);
        $content['data'] = $data;
        return $content;
    }

    public function supports(string $provider): bool
    {
        return strtolower($provider) === 'unm49';
    }
}
