<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Controller;

use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistry;
use MyPrm\GeoZones\Infrastructure\Http\Validator\GeoZonesRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    private ZonesBuilderRegistry $registry;

    public function __construct(ZonesBuilderRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(Request $request)
    {
        $parameters = GeoZonesRequestValidator::requestParams($request);
        $builder = $this->registry->getBuilderFor('unm49');
        $response = $builder->build($parameters);
        return new Response(json_encode($response), Response::HTTP_OK);
    }
}