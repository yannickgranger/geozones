<?php

namespace  GeoZones\Domain\Factory;

use GeoZones\Domain\Model\AbstractZone;
use GeoZones\Domain\Model\World;
use GeoZones\SharedKernel\Error\Error;

interface AbstractZoneFactoryInterface
{
    public function build(array $data): World|Error;
    public function instanciate(array $data): \ArrayIterator;
    public function createTable(\ArrayIterator $iterator, string $level): World|Error;
    public function createRegions(\ArrayIterator $iterator): \Iterator|Error;
    public function createSubRegions(\ArrayIterator $iterator): \Iterator|Error;
    public function mapCountries(\ArrayIterator $iterator, AbstractZone $zone): World|Error;
}
