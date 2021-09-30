<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Builder\CountryBuilder\CountryDataBuilderInterface;
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
        CountryFactory $countryFactory,
        CountryDataBuilderInterface $countryDataBuilder,
        FieldsMapperInterface $fieldsMapper
    ) {
        $this->cacheAdapter = $cacheAdapter;
        $this->countryFactory = $countryFactory;
        $this->countryDataBuilder = $countryDataBuilder;
        $this->parameters = $fieldsMapper->setup(self::PROVIDER);
    }

    public function createTable(\ArrayIterator $iterator): World|Error
    {
        $iterator = $this->createRegions($iterator);
        if (!$iterator instanceof \ArrayIterator) {
            return $iterator;
        }

        $iterator = $this->mapCountries($iterator);
        if (!$iterator instanceof \ArrayIterator) {
            return $iterator;
        }

        return $this->world->setRegions($this->mapCountries($iterator));
    }

    public function mapCountries(\ArrayIterator $iterator): World|Error
    {
        $cacheData = $this->cacheAdapter->get('countriesData.json');
        if (!$cacheData) {
            $this->countryDataBuilder->build();
            $cacheData = $this->cacheAdapter->get('countriesData.json');
        }
        $this->countriesData = json_decode($cacheData, true);

        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $current = $iterator->current();
            $countryName = $current[$this->getCountryName()];
            $countryCode = strtolower($current[$this->getAlpha2()]);
            $regionName = $current[$this->getRegionName()];

            $region = $this->regions[$regionName];
            $parent = $region;

            $countryDataFilter = array_filter(
                $this->countriesData,
                function ($row) use ($countryCode) {
                    return strtolower($row[0]) === $countryCode;
                }
            );

            $countryData = array_pop($countryDataFilter);
            if (is_array($countryData)) {
                $country = $this->countryFactory->buildCountry($countryName, $countryCode, $countryData, $parent);
                $region->addCountry($country);
                $this->regions[$regionName] = $region;
            }
        }

        return $this->world->setRegions($this->regions);
    }
}
