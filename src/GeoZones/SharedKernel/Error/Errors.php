<?php

namespace GeoZones\SharedKernel\Error;

class Errors
{
    private array $errors = [];

    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
