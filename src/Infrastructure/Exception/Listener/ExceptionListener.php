<?php

namespace MyPrm\GeoZones\Infrastructure\Exception\Listener;

use MyPrm\GeoZones\Infrastructure\Exception\BadRequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $stackTrace = $exception->getTrace();

        if ($exception instanceof BadRequestException) {
            $this->logger->debug(
                $stackTrace[0]['class']."::".$stackTrace[0]['function'],
                [
                    'error' => $exception->getMessage(),
                    'type' => get_class($exception),
                ]
            );

            $response = new JsonResponse($exception->getMessage(), $exception->getCode());
            $response->headers->replace($exception->getHeaders());
            $event->setResponse($response);
        }

        if($exception instanceof Exception && $exception->getStatusCode() === 500)
        {
            $now = new \DateTime();
            $this->logger->debug(
                $stackTrace[0]['class']."::".$stackTrace[0]['function'],
                [
                    'error' => $exception->getMessage(),
                    'type' => get_class($exception),
                ]
            );
            $response = new JsonResponse('An unexpected error occured at '.$now->format('Y-m-d H:i:s').'. Contact customer support for help.', 500);
            $response->headers->replace($exception->getHeaders());
            $event->setResponse($response);
        }
    }
}