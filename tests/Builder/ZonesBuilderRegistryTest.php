<?php

namespace MyPrm\GeoZones\Tests\Builder;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\RestCountriesEuBuilder;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\UnM49Builder;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistry;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistryInterface;
use MyPrm\GeoZones\Domain\Exception\MissingBuilderException;
use PHPUnit\Framework\TestCase;

class ZonesBuilderRegistryTest extends TestCase
{
    private ZonesBuilderRegistryInterface $registry;

    protected function setUp(): void
    {
        $one = $this->createConfiguredMock(UnM49Builder::class, [
            'supports' => true
        ]);
        $two = $this->createConfiguredMock(RestCountriesEuBuilder::class, [
            'supports' => true
        ]);

        $providers = new \ArrayIterator([$one, $two]);

        $this->registry = new ZonesBuilderRegistry($providers, ['unm49', 'resteucountries']);
    }

    public function testItReturnsByOrder()
    {
        $expected = $this->registry->getBuilderByOrder(0);
        $this->assertInstanceOf(ZonesBuilderInterface::class, $expected);

        $expected = $this->registry->getBuilderByOrder(1);
        $this->assertInstanceOf(ZonesBuilderInterface::class, $expected);

        $this->expectException(MissingBuilderException::class);
        $this->registry->getBuilderByOrder(2);
    }
}