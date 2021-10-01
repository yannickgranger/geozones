<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Builder\CountryBuilder\CountryDataBuilderInterface;
use MyPrm\GeoZones\Domain\Service\Data\File\Cache\CacheAdapterInterface;
use MyPrm\GeoZones\Domain\Service\FieldsMapper\FieldsMapperInterface;

class UnM49ZoneFactory extends AbstractZoneFactory implements UnM49ZoneFactoryInterface
{
    public const PROVIDER = 'unM49';
    private CountryDataBuilderInterface $countryDataBuilder;
    private CacheAdapterInterface $cacheAdapter;

    public function __construct(
        CacheAdapterInterface $cacheAdapter,
        CountryFactory $countryFactory,
        CountryDataBuilderInterface $countryDataBuilder,
        FieldsMapperInterface $fieldsMapper
    ) {
        $this->cacheAdapter = $cacheAdapter;
        $this->countryFactory = $countryFactory;
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

            $countryCode = strtolower($current[$this->getAlpha2()]);
            $countryName = $current[$this->getCountryName()];
            $regionName = $current[$this->getRegionName()];
            $subRegionName = $current[$this->getSubRegionName()];
            $countryDataFilter = array_filter(
                $this->countriesData,
                function ($row) use ($countryCode) {
                    return strtolower($row[0]) === $countryCode;
                }
            );
            $countryData = array_pop($countryDataFilter);
            if (is_array($countryData)) {
                $countries[$countryName] = CountryFactory::buildCountry(
                    $countryCode,
                    $countryName,
                    $regionName,
                    $countryData,
                    $subRegionName
                );
            }
        }
        ksort($countries);
        return $countries;
    }
}
