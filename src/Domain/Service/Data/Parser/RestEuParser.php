<?php

namespace MyPrm\GeoZones\Domain\Service\Data\Parser;

use MyPrm\GeoZones\Domain\Exception\ExternalSourceException;

class RestEuParser implements DataParserInterface
{
    public function parse(array|string $data, array $parameters = []): array
    {
        try {
            return json_decode($data, true);
        } catch (\Exception $exception) {
            throw new ExternalSourceException($exception->getMessage(), 500);
        }
    }
}
