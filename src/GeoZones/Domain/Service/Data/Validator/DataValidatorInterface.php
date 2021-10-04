<?php

namespace  GeoZones\Domain\Service\Data\Validator;

use GeoZones\SharedKernel\Error\Error;

interface DataValidatorInterface
{
    public function validateData(array $data): ?Error;
}
