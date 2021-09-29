<?php

namespace MyPrm\GeoZones\Infrastructure\Service\Data;

use Assert\Assert;
use Assert\LazyAssertionException;
use MyPrm\GeoZones\Domain\Exception\ExternalSourceException;
use MyPrm\GeoZones\Domain\Service\Data\Validator\DataValidatorInterface;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class LanguageDataValidator implements DataValidatorInterface
{
    public function validateData(array $data): ?Error
    {
        try {
            Assert::lazy()
                ->that($data)->notEmpty()->minCount(400)
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            $exceptions = $exception->getErrorExceptions();
            $first = $exceptions[0];
            throw new ExternalSourceException($first->getMessage(), 500, $exception);
        }

        return null;
    }
}
