<?php

namespace MyPrm\GeoZones\Domain\Builder\CountryBuilder;

use MyPrm\GeoZones\Domain\Builder\LanguageBuilder\CountryLanguageBuilderInterface;
use MyPrm\GeoZones\Domain\Builder\LanguageBuilder\LanguageBuilderInterface;
use MyPrm\GeoZones\Domain\Service\Data\File\Cache\CacheAdapterInterface;

class CountryDataBuilder implements CountryDataBuilderInterface
{
    public const LANGUAGES = 15;

    private LanguageBuilderInterface $languageBuilder;
    private CountryLanguageBuilderInterface $countryLanguageBuilder;
    private CacheAdapterInterface $cacheAdapter;
    private string $cacheKey = 'countriesData.json';
    private string $cacheTtl;

    public function __construct(
        LanguageBuilderInterface $languageBuilder,
        CountryLanguageBuilderInterface $countryLanguageBuilder,
        CacheAdapterInterface $cacheAdapter,
        string $cacheTtl
    ) {
        $this->languageBuilder = $languageBuilder;
        $this->countryLanguageBuilder = $countryLanguageBuilder;
        $this->cacheAdapter = $cacheAdapter;
        $this->cacheTtl = $cacheTtl;
    }

    public function build(): void
    {
        $data = $this->getData();
        $iterator = $data[0];
        $languagesIterator = $data[1];
        $countries = [];

        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            $countryData = $iterator->current();
            $codeList = array_key_exists(self::LANGUAGES, $countryData) ? strtolower($countryData[self::LANGUAGES]) : null;
            $countryData[self::LANGUAGES] = [];
            $codes = explode(",", $codeList);

            $countryLanguages = [];

            foreach ($codes as $code) {
                if (str_contains($code, "-")) {
                    preg_match('/.+?(?=\-)/', $code, $match);
                    $code = $match[0];
                }

                $languageData = iterator_to_array($this->findLanguage($languagesIterator, $code));

                $countryLanguages[$code] = empty($languageData) ? null : $languageData[0];
                ksort($countryLanguages);
            }
            $countryData[self::LANGUAGES] = $countryLanguages;
            $countries[] = $countryData;
        }
        $data =json_encode($countries);
        $this->cacheData($data, "CountryWithData.json");
    }

    public function findLanguage(\ArrayIterator $iterator, string $code): \Traversable
    {
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()){
            $current = $iterator->current();
            if($current['iso639_1'] === $code){
                yield $current;
            } elseif($current['iso639_2'] === $code) {
                yield $current;
            }
        }
    }

    public function getData()
    {
        $languages = $this->languageBuilder->build();
        $countryLanguages = $this->countryLanguageBuilder->build();
        array_shift($countryLanguages);
        $countryLanguages = new \ArrayIterator($countryLanguages);
        return [$countryLanguages, $languages];
    }

    public function cacheData(?string $data, string $fileName): void
    {
        $this->cacheAdapter->save($this->cacheKey, $data, $this->cacheTtl);
    }
}
