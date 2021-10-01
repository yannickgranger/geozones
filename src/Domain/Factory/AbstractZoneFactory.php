<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Model\Region;
use MyPrm\GeoZones\Domain\Model\SubRegion;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

abstract class AbstractZoneFactory
{
    protected World $world;
    protected array $parameters = [];
    protected CountryFactory $countryFactory;
    protected array $countriesData;

    public function instanciate(array $data): \ArrayIterator
    {
        $this->world = new World($this->getGlobalCode(), 'World', null);
        return new \ArrayIterator($data);
    }

    /**
     * @throws \Exception
     */
    public function createTable(\ArrayIterator $iterator, string $level): World|Error
    {
        $world = $this->world;
        switch ($level) {
            case 'regions':
                $world = $this->createRegions($iterator, $world);
                break;
            case 'sub-regions':
                $world = $this->createRegions($iterator, $world);
                $world = $this->createSubRegions($iterator, $world);
                break;

        };

        $countries = $this->getCountries($iterator);
        return $this->mapCountries($world, $countries, $level);
    }

    public function createRegions(\ArrayIterator $iterator, World $world): World|Error
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
        $world->setRegions($regions);
        return $world;
    }

    public function createSubRegions(\ArrayIterator $iterator, World $world): World|Error
    {
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $regionName = $iterator[$iterator->key()][$this->getRegionName()];
            $regions = $world->getRegions();
            $region = $regions[$regionName];
            $subRegions = $region->getSubRegions();
            $subRegionName = $iterator[$iterator->key()][$this->getSubRegionName()];
            if (!array_key_exists($subRegionName, $subRegions)) {
                $subRegion = new SubRegion('', $subRegionName, $region);
                $subRegions[$subRegionName] = $subRegion;
            }
            ksort($subRegions);
            $region->setSubRegions($subRegions);
            $regions[$regionName] = $region;
            ksort($regions);
            $world->setRegions($regions);
        }
        return $world;
    }

    /**
     * This method belongs to implementation, because of fields mapping details
     */
    public function getCountries(\ArrayIterator $iterator): array
    {
        throw new \Exception('TBD:// Implement method '.__METHOD__);
    }

    public function mapCountries(World $world, array $countries, string $level): World|Error
    {
        $iterator = new \ArrayIterator($countries);
        switch ($level) {
            case 'world':
                for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
                    $current = $iterator->current();
                    $current->setParent($world);
                }
                $world->setCountries(iterator_to_array($iterator));
                break;
            case 'regions':
                $regions = $world->getRegions();
                for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
                    $current = $iterator->current();
                    $region = $regions[$current->getRegionName()];
                    $region->addCountry($current);
                    $current->setParent($region);
                }
                break;
            case 'sub-regions':
                $regions = $world->getRegions();
                for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
                    $current = $iterator->current();
                    $region = $regions[$current->getRegionName()];
                    $subRegions = $region->getSubRegions();
                    $subRegion = $subRegions[$current->getSubRegionName()];
                    $subRegion->addCountry($current);
                    $current->setParent($subRegion);
                }
        }

        return $world;
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
