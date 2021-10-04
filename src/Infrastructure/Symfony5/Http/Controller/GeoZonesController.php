<?php

namespace Symfony5\Http\Controller;

use GeoZones\Domain\UseCase\GetZones\GetZones;
use GeoZones\Domain\UseCase\GetZones\GetZonesRequest;
use Symfony5\Http\Responder\GetZonesResponderInterface;
use Symfony5\Http\Validator\GeoZonesRequestValidator;
use Symfony5\Http\Validator\GeoZonesRequestValidatorInterface;
use GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GeoZonesController
{
    private CacheInterface $cache;
    private GeoZonesRequestValidatorInterface $requestValidator;
    private GetZones $getZones;
    private GetZonesPresenterInterface $presenter;
    private GetZonesResponderInterface $responder;
    private int $cacheTtl;

    public function __construct(
        CacheInterface $cache,
        GeoZonesRequestValidatorInterface $requestValidator,
        GetZones $getZones,
        GetZonesPresenterInterface $presenter,
        GetZonesResponderInterface $responder,
        int $cacheTtl
    ) {
        $this->cache = $cache;
        $this->requestValidator = $requestValidator;
        $this->getZones = $getZones;
        $this->presenter = $presenter;
        $this->responder = $responder;
        $this->cacheTtl = $cacheTtl;
    }

    public function __invoke(Request $request): Response
    {
        $requestParams = GeoZonesRequestValidator::requestParams($request);
        $requestParams['cacheTtl'] = $this->cacheTtl;
        $this->requestValidator->validate($requestParams);

        return $this->cache->get($requestParams['cacheKey'], function (ItemInterface $item) use ($requestParams) {
            $item->expiresAfter($requestParams['cacheTtl']);
            $this->getZones->execute(
                new GetZonesRequest($requestParams),
                $this->presenter
            );

            return $this->responder->respond(
                $this->presenter,
                $requestParams
            );
        });
    }
}
