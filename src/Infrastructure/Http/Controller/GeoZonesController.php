<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Controller;

use MyPrm\GeoZones\Domain\UseCase\GetZones\GetZones;
use MyPrm\GeoZones\Domain\UseCase\GetZones\GetZonesRequest;
use MyPrm\GeoZones\Infrastructure\Http\Responder\GetZonesResponderInterface;
use MyPrm\GeoZones\Infrastructure\Http\Validator\GeoZonesRequestValidator;
use MyPrm\GeoZones\Infrastructure\Http\Validator\GeoZonesRequestValidatorInterface;
use MyPrm\GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GeoZonesController
{
    private GeoZonesRequestValidatorInterface $requestValidator;
    private GetZones $getZones;
    private GetZonesPresenterInterface $presenter;
    private GetZonesResponderInterface $responder;

    public function __construct(
        GeoZonesRequestValidatorInterface $requestValidator,
        GetZones $getZones,
        GetZonesPresenterInterface $presenter,
        GetZonesResponderInterface $responder
    ) {
        $this->requestValidator = $requestValidator;
        $this->getZones = $getZones;
        $this->presenter = $presenter;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        $requestParams = GeoZonesRequestValidator::requestParams($request);
        $this->requestValidator->validate($requestParams);

        $this->getZones->execute(
            new GetZonesRequest($requestParams),
            $this->presenter
        );

        return $this->responder->respond(
            $this->presenter,
            $requestParams
        );
    }
}
