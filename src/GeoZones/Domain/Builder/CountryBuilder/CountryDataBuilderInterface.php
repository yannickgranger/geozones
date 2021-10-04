<?php

namespace GeoZones\Domain\Builder\CountryBuilder;

interface CountryDataBuilderInterface
{
    public function build(): string;
    public function getData();
    public function cacheData(?string $data): void;
}
