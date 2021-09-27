<?php

namespace MyPrm\GeoZones\Domain\Service\Data\Validator;

use MyPrm\GeoZones\SharedKernel\Error\Error;

interface DataValidatorInterface
{
    public function validateData(array $data): ?Error;
}