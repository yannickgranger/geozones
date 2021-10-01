<?php

namespace MyPrm\GeoZones\Domain\Factory;

use MyPrm\GeoZones\Domain\Model\Country;
use MyPrm\GeoZones\Domain\Model\Language;

class CountryFactory
{
    public static function buildCountry(string $countryCode, string $name, string $regionName, array $countryData, ?string $subRegionName): Country
    {
        $country = new Country('', $name, null);
        $country->setIsoAlpha2($countryCode);
        $country->setRegionName($regionName);
        if ($subRegionName !== null) {
            $country->setSubRegionName($subRegionName);
        }


        if ($countryData && array_key_exists(15, $countryData)) {
            foreach ($countryData[15] as $languageData) {
                if ($languageData) {
                    $name = array_key_exists('name', $languageData) ? $languageData['name'] : '';
                    $iso639_1 = array_key_exists('iso639_1', $languageData) ? $languageData['iso639_1'] : '';
                    $iso639_2 = array_key_exists('iso639_2', $languageData) ? $languageData['iso639_2'] : '';
                    $country->addLanguage(
                        new Language(
                            $name,
                            '',
                            strtolower($iso639_1),
                            strtolower($iso639_2),
                            []
                        )
                    );
                }
            }
        }

        return $country;
    }
}
