<?php

namespace MyPrm\GeoZones\Domain\Builder\ZonesBuilder;

use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class RestCountriesEuBuilder implements ZonesBuilderInterface
{
    public function getData()
    {
        // TODO: Implement getData() method.
    }

    public function supports(string $provider): bool
    {
        return strtolower($provider) === 'restcountrieseu';
    }

    public function parseData($data, array $parameters)
    {
        // TODO: Implement parseData() method.
    }

    public function build(array $parameters): World|Error
    {
        return new Error('', '', []);
    }
}
