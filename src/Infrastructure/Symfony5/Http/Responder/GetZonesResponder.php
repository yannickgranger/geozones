<?php

namespace Symfony5\Http\Responder;

use GeoZones\Presentation\GetZones\GetZonesPresenterInterface;
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

        $normalizedData = $this->serializer->normalize($data, 'json', $params);

        if ($contentType === '' || strtolower($contentType)  === 'application/json') {
            $response = new JsonResponse(
                $normalizedData,
                $statusCode
            );
        }

        if (strtolower($contentType) === 'application/xml') {
            $xml = $this->serializer->serialize(
                $normalizedData,
                'xml'
            );
            $response = new Response($xml, $statusCode);
            $response->headers->set('Content-Type', 'text/xml');
        }

        return $response;
    }
}
