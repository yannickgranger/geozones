<?php

namespace GeoZones\Presentation\GetZones;

use GeoZones\Domain\UseCase\GetZones\GetZonesResponse;

interface GetZonesPresenterInterface
{
    public function present(GetZonesResponse $response): void;

    public function viewModel(): GetZonesViewModel;
}
