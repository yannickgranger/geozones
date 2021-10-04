<?php

namespace  GeoZones\Domain\UseCase\GetZones;

use Assert\Assert;
use Assert\LazyAssertionException;
use GeoZones\Domain\Builder\ZonesBuilderRegistryInterface;
use GeoZones\Domain\Model\World;
use GeoZones\Domain\Service\Data\Translation\DomainTranslatorInterface;
use GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
use GeoZones\SharedKernel\Error\Error;

class GetZones
{
    private ZonesBuilderRegistryInterface $builderRegistry;
    private DomainTranslatorInterface $domainTranslator;

    public function __construct(
        DomainTranslatorInterface $domainTranslator,
        ZonesBuilderRegistryInterface $builderRegistry
    ) {
        $this->builderRegistry = $builderRegistry;
        $this->domainTranslator = $domainTranslator;
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

//        $result = $this->translator->translate($request->getParams());

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
