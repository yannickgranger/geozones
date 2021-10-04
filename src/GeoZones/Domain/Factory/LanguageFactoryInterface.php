<?php

namespace  GeoZones\Domain\Factory;

use GeoZones\Domain\Model\Language;

interface LanguageFactoryInterface
{
    public function createListFromIterator(\Iterator $iterator, array $params): \ArrayIterator;

    public function instanciateLanguage(string $name, string $nativeName, ?string $iso639_1, ?string $iso639_2): Language;
}
