<?php

namespace MyPrm\GeoZones\Tests\Infrastructure\Http\Controller;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistryInterface;
use Symfony\Component\HttpFoundation\Request;

class TestController
{
    private ZonesBuilderRegistryInterface $zonesBuilder;

    public function __construct(
        ZonesBuilderRegistryInterface $zonesBuilder
    ) {
        $this->zonesBuilder = $zonesBuilder;
    }

    public function __invoke(Request $request)
    {
        $builder = $this->zonesBuilder->getBuilderFor('unm49');
        $builder->build();
    }
}