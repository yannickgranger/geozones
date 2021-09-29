<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Controller;

use MyPrm\GeoZones\Domain\Service\Data\File\Cache\CacheAdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    private CacheAdapterInterface $adapter;

    public function __construct(CacheAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __invoke(Request $request)
    {
        $this->adapter->save('prout.txt','prout', 180);
        return new JsonResponse('prout', Response::HTTP_OK);
    }
}
