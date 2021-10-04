<?php

namespace GeoZones\Presentation\GetZones;

use GeoZones\Domain\UseCase\GetZones\GetZonesResponse;
use GeoZones\Presentation\GetZones\Model\ZonesViewModel;

class GetZonesPresenter implements GetZonesPresenterInterface
{
    private GetZonesViewModel $viewModel;

    public function __construct()
    {
        $this->viewModel = new GetZonesViewModel();
    }

    public function present(GetZonesResponse $response): void
    {
        $zonesViewModel = new ZonesViewModel();
        $zonesViewModel->world = $response->getWorld();
        $this->viewModel->viewModel = $zonesViewModel;
        $this->viewModel->errors = $response->getErrors();
    }

    public function viewModel(): GetZonesViewModel
    {
        return $this->viewModel;
    }
}
