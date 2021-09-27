<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Service\FieldsMapper\FieldsMapperInterface;

class UnM49ZoneFactory extends AbstractZoneFactory implements UnM49ZoneFactoryInterface
{
    public const PROVIDER = 'unM49';

    public function __construct(
        FieldsMapperInterface $fieldsMapper
    ) {
        $this->parameters = $fieldsMapper->setup(self::PROVIDER);
    }
}
