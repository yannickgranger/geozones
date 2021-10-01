<?php

namespace MyPrm\GeoZones\Domain\Service\Http;

use MyPrm\GeoZones\Domain\Model\Country;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CountryNormalizer implements CountryNormalizerInterface, NormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        return $object->toArray();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Country;
    }
}
