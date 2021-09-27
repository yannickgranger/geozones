<?php

namespace MyPrm\GeoZones\Domain\Builder;

interface ZonesBuilderRegistryInterface
{
    public function getBuilderFor(string $provider);
}
