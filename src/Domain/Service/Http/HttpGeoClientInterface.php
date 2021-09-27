<?php

namespace MyPrm\GeoZones\Domain\Service\Http;

interface HttpGeoClientInterface
{
    public function doRequest(string $url);
}