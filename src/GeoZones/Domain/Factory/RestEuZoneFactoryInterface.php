<?php

namespace  GeoZones\Domain\Factory;

use GeoZones\Domain\Model\World;
use GeoZones\SharedKernel\Error\Error;

interface RestEuZoneFactoryInterface
{
    public function instanciate(array $data): \ArrayIterator;
    public function createTable(\ArrayIterator $iterator, string $level): World|Error;
    public function createRegions(\ArrayIterator $iterator, World $world): World|Error;
    public function createSubRegions(\ArrayIterator $iterator, World $world): World|Error;
    public function getCountries(\ArrayIterator $iterator): array;
    public function mapCountries(World $world, array $countries, string $level): World|Error;
}
