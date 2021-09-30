<?php

namespace MyPrm\GeoZones\Infrastructure\Service\Http\Serializer\Normalizer;

use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Domain\Service\Http\GeoNormalizer;
use MyPrm\GeoZones\Domain\Service\Http\GeoNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonGeoNormalizer implements GeoNormalizerInterface, NormalizerInterface
{
    private GeoNormalizer $geoNormalizer;

    public function __construct(GeoNormalizer $geoNormalizer)
    {
        $this->geoNormalizer = $geoNormalizer;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return $this->geoNormalizer->normalize($object);
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof World;
    }
}
