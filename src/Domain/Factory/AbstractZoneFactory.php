<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Model\Region;
use MyPrm\GeoZones\Domain\Model\SubRegion;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

abstract class AbstractZoneFactory implements AbstractZoneFactoryInterface
{
    protected World $world;
    protected array $parameters = [];
    protected array $regions = [];

    public function build(array $data): World|Error
    {
        $iterator = $this->instanciate($data);
        return $this->createTable($iterator);
    }

    public function instanciate(array $data): \ArrayIterator
    {
        $this->world = new World($this->getGlobalCode(), 'World', null);
        return new \ArrayIterator($data);
    }

    public function createTable(\ArrayIterator $iterator): World|Error
    {
        $iterator = $this->createRegions($iterator);
        if (!$iterator instanceof \ArrayIterator) {
            return $iterator;
        }

        $iterator = $this->createSubregions($iterator);
        if (!$iterator instanceof \ArrayIterator) {
            return $iterator;
        }

        $iterator = $this->mapCountries($iterator);
        if (!$iterator instanceof \ArrayIterator) {
            return $iterator;
        }

        return $this->mapCountries($iterator);
    }

    public function createRegions(\ArrayIterator $iterator): \Iterator|Error
    {
        $regions = [];
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $regionName = $iterator[$iterator->key()][$this->getRegionName()];
            if (!array_key_exists($regionName, $regions)) {
                $region = new Region('', $regionName, $this->world);
                $regions[$regionName] = $region;
            }
        }
        ksort($regions);
        $this->regions = $regions;
        return $iterator;
    }

    public function createSubRegions(\ArrayIterator $iterator): \Iterator|Error
    {
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $regionName = $iterator[$iterator->key()][$this->getRegionName()];
            $region = $this->regions[$regionName];
            $subRegions = $region->getSubRegions();
            $subRegionName = $iterator[$iterator->key()][$this->getSubRegionName()];
            if (!array_key_exists($subRegionName, $subRegions)) {
                $subRegion = new SubRegion('', $subRegionName, $region);
                $subRegions[$subRegionName] = $subRegion;
            }
            ksort($subRegions);
            $region->setSubRegions($subRegions);
            $this->regions[$regionName] = $region;
        }
        return $iterator;
    }

    public function mapCountries(\ArrayIterator $iterator): World|Error
    {
    }


    protected function getGlobalCode(): ?string
    {
        return $this->parameters['globalCode'];
    }

    protected function getCountryName(): ?string
    {
        return $this->parameters['countryName'];
    }

    protected function getProvider(): ?string
    {
        return $this->parameters['provider'];
    }

    protected function getRegionName(): ?string
    {
        return $this->parameters['regionName'];
    }

    protected function getSubRegionName(): ?string
    {
        return $this->parameters['subRegionName'];
    }

    protected function getAlpha2(): ?string
    {
        return $this->parameters['alpha2'];
    }

    protected function getAlpha3(): ?string
    {
        return $this->parameters['alpha3'];
    }
}
