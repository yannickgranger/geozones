<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Builder\CountryBuilder\CountryDataBuilderInterface;
use MyPrm\GeoZones\Domain\Model\AbstractZone;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Domain\Service\Data\File\Cache\CacheAdapterInterface;
use MyPrm\GeoZones\Domain\Service\FieldsMapper\FieldsMapperInterface;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class RestEuZoneFactory extends AbstractZoneFactory implements RestEuZoneFactoryInterface
{
    public const PROVIDER = 'restCountriesEu';
    private CountryDataBuilderInterface $countryDataBuilder;
    private CacheAdapterInterface $cacheAdapter;

    public function __construct(
        CacheAdapterInterface $cacheAdapter,
        CountryDataBuilderInterface $countryDataBuilder,
        FieldsMapperInterface $fieldsMapper
    ) {
        $this->cacheAdapter = $cacheAdapter;
        $this->countryDataBuilder = $countryDataBuilder;
        $this->parameters = $fieldsMapper->setup(self::PROVIDER);
    }

    public function getCountries(\ArrayIterator $iterator): array
    {
        $cacheData = $this->cacheAdapter->get('countriesData.json');
        if (!$cacheData) {
            $this->countryDataBuilder->build();
            $cacheData = $this->cacheAdapter->get('countriesData.json');
        }
        $this->countriesData = json_decode($cacheData, true);

        $countries = [];
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $current = $iterator->current();
            $countryName = $current[$this->getCountryName()];
            $countryCode = strtolower($current[$this->getAlpha2()]);
            $countryDataFilter = array_filter(
                $this->countriesData,
                function ($row) use ($countryCode) {
                    return strtolower($row[0]) === $countryCode;
                }
            );
            $countryData = array_pop($countryDataFilter);
            if (is_array($countryData)) {
                $countries[$countryName] = CountryFactory::buildCountry($countryName, $countryCode, $countryData);
            }
        }
        ksort($countries);
        return $countries;
    }
}
