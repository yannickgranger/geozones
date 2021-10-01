<?php

namespace MyPrm\GeoZones\Domain\Service\Http;

use MyPrm\GeoZones\Domain\Model\AbstractZone;
use MyPrm\GeoZones\Domain\Model\Country;

class LeafNormalizer implements GeoNormalizerInterface
{
    private CountryNormalizerInterface $countryNormalizer;

    public function __construct(CountryNormalizerInterface $countryNormalizer)
    {
        $this->countryNormalizer = $countryNormalizer;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var AbstractZone $object */
        $normalizedCountries = [];
        $countries = array_map(function (Country $country) {
            return $this->countryNormalizer->normalize($country);
        }, $object->getCountries());

        foreach ($countries as $country) {
            $normalizedCountries[$country['name']] = $country;
        }

        return [
            'parent' => $object->getParent() ? $object->getParent()->getName() : 'world',
            'name' => $object->getName(),
            'countries' => $normalizedCountries
        ];
    }
}
