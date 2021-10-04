<?php

namespace  GeoZones\Domain\UseCase\GetZones;

class GetZonesRequest
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
