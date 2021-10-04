<?php

namespace  GeoZones\Domain\Model;

abstract class AbstractZone
{
    protected string $providerId;
    protected string $name;
    protected ?array $countries = [];
    protected ?AbstractZone $parent;
    protected ?Translations $translations;

    public function __construct(string $providerId, string $name, ?AbstractZone $parent)
    {
        $this->providerId = $providerId;
        $this->name = $name;
        $this->parent = $parent;
    }

    public function getTranslations(): ?Translations
    {
        return $this->translations;
    }

    public function setTranslations(?Translations $translations): void
    {
        $this->translations = $translations;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?AbstractZone
    {
        return $this->parent;
    }

    public function setParent(?AbstractZone $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): void
    {
        $this->countries = $countries;
    }
}
