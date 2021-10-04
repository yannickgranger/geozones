<?php

namespace  GeoZones\Domain\Model;

class Translation
{
    private string $locale;
    private string $key;
    private string $value;

    public function __construct(string $locale, string $key, string $value)
    {
        $this->locale = $locale;
        $this->key = $key;
        $this->value = $value;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
