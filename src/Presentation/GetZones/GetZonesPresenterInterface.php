<?php

namespace MyPrm\GeoZones\Presentation\GetZones;

use MyPrm\GeoZones\Domain\UseCase\GetZones\GetZonesResponse;

interface GetZonesPresenterInterface
{
    public function present(GetZonesResponse $response): void;

    public function viewModel(): GetZonesViewModel;
}
