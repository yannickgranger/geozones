<?php

namespace MyPrm\GeoZones\Domain\UseCase\GetZones;

use Assert\Assert;
use Assert\LazyAssertionException;
use MyPrm\GeoZones\Domain\Builder\ZonesBuilderRegistryInterface;
use MyPrm\GeoZones\Domain\Model\World;
use MyPrm\GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class GetZones
{
    private ZonesBuilderRegistryInterface $builderRegistry;

    public function __construct(ZonesBuilderRegistryInterface $builderRegistry)
    {
        $this->builderRegistry = $builderRegistry;
    }

    public function execute(GetZonesRequest $request, GetZonesPresenterInterface $presenter)
    {
        $result = null;
        $response = new GetZonesResponse();
        $isValid = $this->validateRequest($request, $response);


        if ($isValid) {
            try {
                $builder = $this->builderRegistry->getBuilderByOrder(0);
                $result = $builder->build($request->getParams());
            } catch (\Exception $exception) {
                $builder = $this->builderRegistry->getBuilderByOrder(1);
                $result = $builder->build($request->getParams());
            }
        }

        if ($result instanceof World) {
            $response->setWorld($result);
        }

        if ($result instanceof Error) {
            $response->addError($result);
        }

        $presenter->present($response);
    }

    public function validateRequest(GetZonesRequest $request, GetZonesResponse $response): bool
    {
        try {
            Assert::lazy()
                ->that($request->getParams())->keyExists('locale')
                ->that($request->getParams())->keyExists('level')
                ->that($request->getParams())->keyExists('content-type')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $exception) {
            foreach ($exception->getErrorExceptions() as $error) {
                $response->addError(
                    new Error(
                        __METHOD__,
                        $error->getPropertyPath(),
                        $error->getMessage()
                    )
                );
            }

            return false;
        }
    }
}
