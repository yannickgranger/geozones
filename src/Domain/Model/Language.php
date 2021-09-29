<?php

namespace MyPrm\GeoZones\Domain\Model;

class Language
{
    private string $name;
    private string $nativeName;
    private ?string $iso639_1;
    private ?string $iso639_2;
    private array $translations;

    public function __construct(string $name, string $nativeName, ?string $iso639_1, ?string $iso639_2, ?array $translations = [])
    {
        $this->name = $name;
        $this->nativeName = $nativeName;
        $this->iso639_1 = $iso639_1;
        $this->iso639_2 = $iso639_2;
        $this->translations = $translations;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNativeName(): string
    {
        return $this->nativeName;
    }

    public function getIso6391(): ?string
    {
        return $this->iso639_1;
    }

    public function getIso6392(): ?string
    {
        return $this->iso639_2;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }
}
