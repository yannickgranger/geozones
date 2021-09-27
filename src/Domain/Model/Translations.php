<?php

namespace MyPrm\GeoZones\Domain\Model;

class Translations
{
    private string $domain;
    private string $locale;
    private array $values;

    public function __construct(string $domain, string $locale, ?array $values = [])
    {
        $this->domain = $domain;
        $this->locale = $locale;
        $this->values = $values;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function addTranslation(Translation $translation)
    {
        if($translation->getLocale() === $this->getLocale() && !array_key_exists($translation->getKey(), $this->getValues())){
            $this->values[$translation->getKey()] = $translation;
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }
}