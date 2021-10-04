<?php

namespace  GeoZones\Domain\Builder\LanguageBuilder;

use GeoZones\Domain\Exception\ExternalSourceException;
use GeoZones\Domain\Factory\LanguageFactoryInterface;
use GeoZones\Domain\Service\Data\Validator\DataValidatorInterface;
use GeoZones\Domain\Service\Data\Validator\LanguageDataValidator;
use GeoZones\Domain\Service\Http\HttpGeoClientInterface;

class LanguageBuilder implements LanguageBuilderInterface
{
    private string $languagesCodesUrl;
    private HttpGeoClientInterface $client;
    private LanguageFactoryInterface $languageFactory;
    private DataValidatorInterface $languageDataValidator;

    public function __construct(
        HttpGeoClientInterface $client,
        LanguageDataValidator $languageDataValidator,
        LanguageFactoryInterface $languageFactory,
        string $languagesCodesUrl
    ) {
        $this->client = $client;
        $this->languageDataValidator = $languageDataValidator;
        $this->languagesCodesUrl = $languagesCodesUrl;
        $this->languageFactory = $languageFactory;
    }

    public function build(): \ArrayIterator
    {
        $rawContent = $this->getData();
        $arrayData = $this->parseData($rawContent);
        $this->languageDataValidator->validateData($arrayData);
        $languagesIterator = new \ArrayIterator($arrayData);
        return $this->languageFactory->createListFromIterator($languagesIterator, []);
    }

    public function getData()
    {
        return $this->client->doRequest($this->languagesCodesUrl);
    }

    public function parseData($data): array
    {
        try {
            return json_decode($data[0], true);
        } catch (\Exception $exception) {
            throw new ExternalSourceException(__METHOD__.'. '.$exception->getMessage(), 500);
        }
    }
}
