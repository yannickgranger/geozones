<?php

namespace MyPrm\GeoZones\Tests\Builder;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilder\ZonesBuilderInterface;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistry;
use MyPrm\GeoZones\Domain\Exception\MissingBuilderException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ZonesBuilderRegistryTest extends KernelTestCase
{
    private ZonesBuilderRegistry $registry;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->registry = $container->get('MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistry');
    }

    public function testItGetsAbuilder()
    {
        $builder =$this->registry->getBuilderFor('unm49');
        $this->assertInstanceOf( ZonesBuilderInterface::class, $builder);

        $builder =$this->registry->getBuilderFor('restcountrieseu');
        $this->assertInstanceOf( ZonesBuilderInterface::class, $builder);

        $this->expectException(MissingBuilderException::class);
        $this->registry->getBuilderFor('toto');
    }
}