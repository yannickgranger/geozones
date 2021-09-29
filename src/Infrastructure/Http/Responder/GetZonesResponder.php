<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Responder;

use MyPrm\GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetZonesResponder implements GetZonesResponderInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function respond(GetZonesPresenterInterface $presenter, array $params): Response
    {
        $contentType = $params['content-type'];
        $viewModel = $presenter->viewModel();

        if (!empty($viewModel->errors)) {
            $data = $viewModel->errors;
            $statusCode = 400;
        } else {
            $data = $viewModel->viewModel->world;
            $statusCode = Response::HTTP_OK;
        }

        if ($contentType === '' || strtolower($contentType)  === 'application/json') {
            $response = new JsonResponse(
                $this->serializer->normalize($data, 'json', $params),
                $statusCode
            );
        }

        if (strtolower($contentType) === 'application/xml') {
            $xml = $this->serializer->serialize(
                $data,
                'xml'
            );
            $response = new Response($xml, $statusCode);
            $response->headers->set('Content-Type', 'text/xml');
            return $response;
        }

        return $response;
    }
}
