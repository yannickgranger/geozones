<?php

namespace MyPrm\GeoZones\Domain\Model;

class SubRegion extends AbstractZone
{
    public function getCountries(): array
    {
        return $this->countries;
    }

    public function addCountry(Country $country): void
    {
        if (!array_key_exists($country->getName(), $this->countries)) {
            $this->countries[$country->getName()] = $country;
        }
    }
}
