<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Validator;

use Symfony\Component\HttpFoundation\Request;

interface GeoZonesRequestValidatorInterface
{
    public static function requestParams(Request $request): array;

    public function validate(array $parameters): void;

    public function validateContentType(string $contentType): bool;

    public function validateLevel(string $level): bool;

    public function validateLocale(string $locale): bool;
}
