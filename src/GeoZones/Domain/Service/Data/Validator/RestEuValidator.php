<?php

namespace  GeoZones\Domain\Service\Data\Validator;

use Assert\Assert;
use Assert\LazyAssertionException;
use GeoZones\SharedKernel\Error\Error;

class RestEuValidator implements DataValidatorInterface
{
    public function validateData(array $data): ?Error
    {
        try {
            Assert::lazy()
                ->that($data)->notEmpty()->count(250)
                ->that($data[0])->keyExists('name')
                ->that($data[0])->keyExists('alpha2Code')
                ->that($data[0])->keyExists('alpha3Code')
                ->that($data[0])->keyExists('region')
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            return new Error(__METHOD__, $exception->getMessage());
        }

        return null;
    }
}
