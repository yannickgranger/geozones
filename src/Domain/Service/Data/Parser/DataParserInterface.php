<?php

namespace MyPrm\GeoZones\Domain\Service\Data\Parser;

interface DataParserInterface
{
    public function parse(string|array $data, array $parameters = []): array;
}
