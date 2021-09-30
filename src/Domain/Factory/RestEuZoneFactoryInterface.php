<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

interface RestEuZoneFactoryInterface
{
    public function build(array $data): World|Error;
}
