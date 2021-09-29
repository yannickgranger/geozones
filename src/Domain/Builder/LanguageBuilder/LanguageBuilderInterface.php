<?php

namespace MyPrm\GeoZones\Domain\Builder\LanguageBuilder;

interface LanguageBuilderInterface
{
    public function build(): \ArrayIterator;
    public function getData();
    public function parseData($data): array;
}
