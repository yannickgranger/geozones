<?php

namespace  GeoZones\Domain\UseCase\GetZones;

use GeoZones\Domain\Model\World;
use GeoZones\SharedKernel\Error\Error;

class GetZonesResponse
{
    private ?World $world = null;
    private array $errors = [];

    public function setWorld(?World $world): void
    {
        $this->world = $world;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

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
