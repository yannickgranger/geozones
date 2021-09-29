<?php

namespace MyPrm\GeoZones\Domain\Builder;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;

interface ZonesBuilderRegistryInterface
{
    public function getBuilderByOrder(int $order): ?ZonesBuilderInterface;
    public function getBuilderFor(string $provider): ?ZonesBuilderInterface;
}
