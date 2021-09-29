<?php

namespace MyPrm\GeoZones\Infrastructure\Service\Http\Serializer\Normalizer;

use MyPrm\GeoZones\Domain\Model\Country;
use MyPrm\GeoZones\Domain\Model\Region;
use MyPrm\GeoZones\Domain\Model\SubRegion;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Domain\Service\Http\GeoNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class GeoNormalizer implements GeoNormalizerInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    public function normalize($object, string $format = null, array $context = []): array
    {
        $level = array_key_exists('level', $context) ? $context['level'] : 'regions';
        if ($level === 'sub-regions') {
            return $this->getSubRegions($object);
        }
        return  $this->getRegions($object);
    }
    private function getRegions(World $world): array
    {
        $regions = [];
        foreach ($world->getRegions() as $region) {
            $countries = $this->getCountriesFromRegion($region);
            $region = [
                'name' => $regionName = $region->getName(),
                'countries' => $countries
            ];
            $regions[$regionName] = $region;
        }

        return $regions;
    }

    private function getSubRegions(World $world): array
    {
        foreach ($world->getRegions() as $region) {
            $subRegions = array_map(static function (SubRegion $subRegion) {
                $countries = [];

                $countryList = array_map(static function (Country $country) {
                    return $country->toArray();
                }, $subRegion->getCountries());

                foreach ($countryList as $country) {
                    $countries[] = $country;
                }

                ksort($countries);
                return $countries;
            }, $region->getSubRegions());

            $region = [
                'name' => $regionName = $region->getName(),
                'sub-regions' =>  $subRegions
            ];

            ksort($subRegions);
            ksort($region);

            $regions[$regionName] = $region;
        }

        return $regions;
    }

    private function getCountriesFromRegion(Region $region): array
    {
        $countries = [];

        foreach ($region->getSubRegions() as $subRegion) {
            $countryList = array_map(static function (Country $country) {
                return $country->toArray();
            }, $subRegion->getCountries());
            foreach ($countryList as $country) {
                $countries[] = $country;
            }
        }
        return $this->sortAlpha($countries);
    }

    private function sortAlpha(array $array): array
    {
        $iterator = new \RecursiveArrayIterator($array);
        iterator_apply($iterator, static function ($iterator) {
            $iterator->uasort(function ($a, $b) {
                return (strtolower($a['name']) < strtolower($b['name'])) ? -1 : 1;
            });
        }, array($iterator));

        return array_values(iterator_to_array($iterator));
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof World;
    }
}
