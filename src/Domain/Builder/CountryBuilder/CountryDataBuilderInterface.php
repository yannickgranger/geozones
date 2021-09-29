<?php

namespace MyPrm\GeoZones\Domain\Builder\CountryBuilder;

interface CountryDataBuilderInterface
{
//    public function getCountriesData(): array;
    public function build(): void;
    public function getData();
    public function cacheData(?string $data, string $fileName): void;
}
