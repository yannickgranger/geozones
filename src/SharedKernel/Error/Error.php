<?php

declare(strict_types=1);

namespace MyPrm\GeoZones\SharedKernel\Error;

class Error
{
    private string $message;
    private string $method;
    private array $context;

    public function __construct(string $method, string $message, array $context = [])
    {
        $this->method = $method;
        $this->message = $message;
        $this->context = $context;
    }

    public function __toString()
    {
        return $this->method.':'.$this->message.'. '.json_encode($this->context);
    }

    public function message(): string
    {
        return $this->message;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
