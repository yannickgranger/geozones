<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Controller;

use MyPrm\GeoZones\Infrastructure\Http\Validator\GeoZonesRequestValidator;
use MyPrm\GeoZones\Infrastructure\Http\Validator\GeoZonesRequestValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GeoZonesController
{
    private GeoZonesRequestValidatorInterface $requestValidator;

    public function __construct(GeoZonesRequestValidatorInterface $requestValidator)
    {
        $this->requestValidator = $requestValidator;
    }

    public function __invoke(Request $request): Response
    {
        $requestParams = GeoZonesRequestValidator::requestParams($request);
        $this->requestValidator->validate($requestParams);

        return new Response(null, Response::HTTP_OK);
    }
}
