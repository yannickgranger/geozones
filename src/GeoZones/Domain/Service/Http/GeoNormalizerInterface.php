<?php

namespace  GeoZones\Domain\Service\Http;

interface GeoNormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array;
}
