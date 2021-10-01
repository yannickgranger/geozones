<?php

namespace MyPrm\GeoZones\Domain\Service\Data\Translation;

interface DomainTranslatorInterface
{
    public function regionName(string $name): ?string;
    public function subRegionName(string $name): ?string;
    public function countryName(string $name): ?string;
    public function languageName(string $name): ?string;
}
