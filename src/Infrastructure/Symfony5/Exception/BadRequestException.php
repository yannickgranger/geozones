<?php

namespace Symfony5\Exception;

use GeoZones\Domain\Exception\BadRequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException implements BadRequestExceptionInterface
{
}
