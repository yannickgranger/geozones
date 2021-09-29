<?php

namespace MyPrm\GeoZones\Infrastructure\Exception;

use MyPrm\GeoZones\Domain\Exception\BadRequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException implements BadRequestExceptionInterface
{
}
