<?php

namespace MyPrm\GeoZones\Domain\Model;

class World extends AbstractZone
{
    private array $regions = [];

    public function getRegions(): array
    {
        return $this->regions;
    }

    public function addRegion(Region $region)
    {
        if (!array_key_exists($region->getName(), $this->regions)) {
            $this->regions[$region->getName()] = $region;
        }
    }

    public function setRegions(array $regions): World
    {
        $this->regions = $regions;
        return $this;
    }
}
