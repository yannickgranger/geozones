<?php

namespace  GeoZones\Domain\Builder\ZonesBuilder;

use GeoZones\Domain\Model\World;
use GeoZones\SharedKernel\Error\Error;

interface ZonesBuilderInterface
{
    public function getData();
    public function parseData($data, array $parameters): array;
    public function build(array $parameters): World|error;
    public function supports(string $provider): bool;
}
