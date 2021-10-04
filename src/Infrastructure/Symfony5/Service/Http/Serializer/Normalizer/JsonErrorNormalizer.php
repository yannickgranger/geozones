<?php

namespace Symfony5\Service\Http\Serializer\Normalizer;

use GeoZones\Domain\Service\Http\ErrorNormalizer;
use GeoZones\Domain\Service\Http\GeoNormalizerInterface;
use GeoZones\SharedKernel\Error\Error;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonErrorNormalizer implements GeoNormalizerInterface, NormalizerInterface
{
    private ErrorNormalizer $normalizer;

    public function __construct(ErrorNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return  $this->normalizer->normalize($object);
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Error;
    }
}
