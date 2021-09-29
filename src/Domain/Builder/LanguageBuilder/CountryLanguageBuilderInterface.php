<?php

namespace MyPrm\GeoZones\Domain\Builder\LanguageBuilder;

interface CountryLanguageBuilderInterface
{
    public function build(): array;
    public function getData();
    public function parseData($data): array;
}
