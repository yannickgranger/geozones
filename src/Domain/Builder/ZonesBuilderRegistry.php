<?php

namespace MyPrm\GeoZones\Domain\Builder;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;
use MyPrm\GeoZones\Domain\Exception\MissingBuilderException;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class ZonesBuilderRegistry implements ZonesBuilderRegistryInterface, ZonesBuilderInterface
{
    private \Traversable $providers;
    private array $providerNames;

    public function __construct(\Traversable $providers, array $providerNames)
    {
        $this->providers = $providers;
        $this->providerNames = $providerNames;
    }

    public function getBuilderByOrder(int $order): ZonesBuilderInterface
    {
        if (array_key_exists($order, $this->providerNames)) {
            return $this->getBuilderFor($this->providerNames[$order]);
        }

        throw new MissingBuilderException('Unknown zone_builder : '.$order, 500, null);
    }

    public function getBuilderFor(string $provider): ZonesBuilderInterface
    {
        foreach ($this->providers as $builder) {
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

    public function parseData($data, array $parameters): array
    {
        return [];
    }


    public function build(array $parameters): World|error
    {
        return new Error(__METHOD__, 'Do not use registry method as implementation method', []);
    }
}
