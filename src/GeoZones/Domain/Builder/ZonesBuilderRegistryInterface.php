<?php

namespace  GeoZones\Domain\Builder;

use GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;

interface ZonesBuilderRegistryInterface
{
    public function getBuilderByOrder(int $order): ?ZonesBuilderInterface;
    public function getBuilderFor(string $provider): ?ZonesBuilderInterface;
}
