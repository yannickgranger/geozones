<?php

namespace MyPrm\GeoZones\Domain\Builder\CountryBuilder;

;

interface CountryBuilderInterface
{
    public function build(): array;
    public function getData();
    public function parseData($data): array;
}
