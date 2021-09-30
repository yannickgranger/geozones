<?php

namespace MyPrm\GeoZones\Domain\Service\Http;

use MyPrm\GeoZones\SharedKernel\Error\Error;

class ErrorNormalizer implements GeoNormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var Error $object */
        return [
            'method' => $object->getMethod(),
            'message' => $object->message()
        ];
    }
}
