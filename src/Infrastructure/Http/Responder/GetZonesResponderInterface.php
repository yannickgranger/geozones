<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Responder;

use MyPrm\GeoZones\Presentation\GetZones\GetZonesPresenterInterface;

interface GetZonesResponderInterface
{
    public function respond(GetZonesPresenterInterface $presenter, array $params);
}
