<?php

namespace MyPrm\GeoZones\Domain\Model;

class Country extends AbstractZone
{
    private ?string $isoAlpha2 = null;
    private ?string $isoAlpha3= null;
    private array $languages = [];


    public function addLanguage(Language $language): void
    {
        if (!in_array($language->getName(), $this->languages, true)) {
            $this->languages[$language->getIso6391()] = $language;
        }
    }

    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIsoAlpha2(): ?string
    {
        return $this->isoAlpha2;
    }

    public function setIsoAlpha2(?string $isoAlpha2): void
    {
        $this->isoAlpha2 = $isoAlpha2;
    }

    public function getIsoAlpha3(): ?string
    {
        return $this->isoAlpha3;
    }

    public function setIsoAlpha3(?string $isoAlpha3): void
    {
        $this->isoAlpha3 = $isoAlpha3;
    }

    public function toArray(): array
    {
        return [
            'name' => ucfirst($this->getName()),
            'iso-2' => $this->getIsoAlpha2(),
            'iso-3' => $this->getIsoAlpha3(),
            'languages' => array_map(static function (Language $languages) {
                return $languages->toArray();
            }, $this->getLanguages())
        ];
    }
}
