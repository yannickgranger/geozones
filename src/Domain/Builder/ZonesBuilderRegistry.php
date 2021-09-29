<?php

namespace MyPrm\GeoZones\Domain\Builder;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;
use MyPrm\GeoZones\Domain\Exception\MissingBuilderException;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class ZonesBuilderRegistry implements ZonesBuilderRegistryInterface, ZonesBuilderInterface
{
    private \Traversable $providers;

    public function __construct(\Traversable $providers)
    {
        $this->providers = $providers;
    }

    public function getBuilderFor(string $provider): ZonesBuilderInterface
    {
        $providers = $this->providers->getIterator();
        foreach ($providers as $builder) {
            if ($builder->supports($provider)) {
                return $builder;
            }
        }

        throw new MissingBuilderException('No zone_builder found for provider '.$provider, 500, null);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }


    public function supports(string $provider): bool
    {
        return false;
    }

    public function parseData($data, array $parameters)
    {
        return [];
    }


    public function build(array $parameters): World|error
    {
        return new Error(__METHOD__, 'Do not use registry method as implementation method', []);
    }
}
