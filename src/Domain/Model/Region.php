<?php

namespace MyPrm\GeoZones\Domain\Model;

class Region extends AbstractZone
{
    private array $subRegions = [];

    public function addSubRegion(SubRegion $subRegion): void
    {
        if (!array_key_exists($subRegion->getName(), $this->subRegions)) {
            $this->subRegions[$subRegion->getName()] = $subRegion;
        }
    }

    public function setSubRegion(SubRegion $subRegion)
    {
        if (array_key_exists($subRegion->getName(), $this->subRegions)) {
            $this->subRegions[$subRegion->getName()] = $subRegion;
        }
    }

    public function getSubRegions(): array
    {
        return $this->subRegions;
    }

    public function setSubRegions(array $subRegions): void
    {
        $this->subRegions = $subRegions;
    }

    public function findSubRegion(string $code)
    {
        return empty($this->subRegions) ? null :
            array_filter($this->subRegions, static function ($subRegion) use ($code) {
                return $subRegion->getProviderId() === $code;
            });
    }

    public function addCountry(AbstractZone $country): void
    {
        $this->countries[$country->getName()] = $country;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): void
    {
        $this->countries = $countries;
    }
}
