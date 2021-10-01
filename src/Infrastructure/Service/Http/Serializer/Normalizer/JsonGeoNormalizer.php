<?php

namespace MyPrm\GeoZones\Infrastructure\Service\Http\Serializer\Normalizer;

use MyPrm\GeoZones\Domain\Model\AbstractZone;
use MyPrm\GeoZones\Domain\Model\Region;
use MyPrm\GeoZones\Domain\Model\SubRegion;
use MyPrm\GeoZones\Domain\Service\Http\GeoNormalizerInterface;
use MyPrm\GeoZones\Domain\Service\Http\ZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonGeoNormalizer implements GeoNormalizerInterface, NormalizerInterface
{
    private ZoneNormalizer $zoneNormalizer;

    public function __construct(
        ZoneNormalizer $zoneNormalizer
    ) {
        $this->zoneNormalizer = $zoneNormalizer;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $level = $context['level'];
        $world = $this->zoneNormalizer->normalize($object);

        switch ($level) {
            case 'world':
                break;
            case 'regions':
                $regions = $object->getRegions();
                $regions = array_map(
                    function (Region $region) {
                        return $this->zoneNormalizer->normalize($region);
                    },
                    $regions
                );
                $world['regions'] = $regions;
                break;
            case 'sub-regions':
                $regions = $object->getRegions();
                $normalizedRegions = [];
                foreach ($regions as $region) {
                    $subRegions = $region->getSubRegions();
                    $normalizedRegion = $this->zoneNormalizer->normalize($region);
                    $subRegions = array_map(
                        function (SubRegion $subRegion) {
                            return $this->zoneNormalizer->normalize($subRegion);
                        },
                        $subRegions
                    );

                    $normalizedRegion['subRegions'] = $subRegions;
                    $normalizedRegions[$region->getName()] = $normalizedRegion;
                }

                $world['regions'] = $normalizedRegions;
                break;
        }
        return $world;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof AbstractZone;
    }
}
