<?php

namespace Symfony5\Http\Responder;

use GeoZones\Presentation\GetZones\GetZonesPresenterInterface;

interface GetZonesResponderInterface
{
    public function respond(GetZonesPresenterInterface $presenter, array $params);
}
