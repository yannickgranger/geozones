<?php

namespace MyPrm\GeoZones\Domain\Service\FieldsMapper;

use MyPrm\GeoZones\Domain\Exception\MissingConfigurationException;

class FieldsMapper implements FieldsMapperInterface
{
    private array $zonesParameters;

    public function __construct(array $zonesParameters)
    {
        $this->zonesParameters = $zonesParameters;
    }

    public function setup(string $provider)
    {
        $filter =array_filter($this->zonesParameters, function ($row) use ($provider) {
            return strtolower($row['provider']) === strtolower($provider);
        });

        if (!empty($filter)) {
            return array_shift($filter);
        }

        throw new MissingConfigurationException('Zones parameters missing for provider '.$provider, 500);
    }
}
