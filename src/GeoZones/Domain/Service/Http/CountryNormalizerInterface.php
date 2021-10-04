<?php

namespace  GeoZones\Domain\Service\Http;

interface CountryNormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array;
}
