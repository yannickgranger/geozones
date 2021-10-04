<?php

namespace  GeoZones\Domain\Factory;

use GeoZones\Domain\Builder\CountryBuilder\CountryDataBuilderInterface;
use GeoZones\Domain\Service\Data\File\Cache\CacheAdapterInterface;
use GeoZones\Domain\Service\FieldsMapper\FieldsMapperInterface;

class UnM49ZoneFactory extends AbstractZoneFactory implements UnM49ZoneFactoryInterface
{
    public const PROVIDER = 'unM49';
    private CountryDataBuilderInterface $countryDataBuilder;
    private CacheAdapterInterface $cacheAdapter;
    private string $cacheKey = 'countriesData.json';
    private int $cacheTtl = 24*3600*366;

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
        $cacheData = $this->cacheAdapter->get($this->cacheKey);
        if ($cacheData === null) {
            $this->cacheAdapter->delete($this->cacheKey);
            $data = $this->countryDataBuilder->build();
            $this->cacheAdapter->save($this->cacheKey, $data, $this->cacheTtl);
            $cacheData = $this->cacheAdapter->get($this->cacheKey);
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
