<?php

namespace MyPrm\GeoZones\Domain\Builder\ZonesBuilder;

use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

interface ZonesBuilderInterface
{
    public function getData();
    public function parseData($data, array $parameters): array;
    public function build(array $parameters): World|error;
    public function supports(string $provider): bool;
}
