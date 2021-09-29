<?php

namespace MyPrm\GeoZones\Infrastructure\Http\Validator;

use MyPrm\GeoZones\Infrastructure\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class GeoZonesRequestValidator implements GeoZonesRequestValidatorInterface
{
    private array $acceptedContentType;
    private array $acceptedLocales;
    private array $validLevels;

    public function __construct(array $acceptedContentType, array $acceptedLocales, array $validLevels)
    {
        $this->acceptedContentType = $acceptedContentType;
        $this->acceptedLocales = $acceptedLocales;
        $this->validLevels = $validLevels;
    }

    public function validateContentType(string $contentType): bool
    {
        if (in_array($contentType, $this->acceptedContentType)) {
            return true;
        }

        return false;
    }

    public function validateLevel(string $level): bool
    {
        if (!in_array($level, $this->validLevels)) {
            return false;
        }

        return true;
    }

    public function validateLocale(string $locale): bool
    {
        return in_array($locale, $this->acceptedLocales);
    }

    public function validate(array $parameters): void
    {
        if (!$this->validateContentType($parameters['content-type'])) {
            throw new BadRequestException('Invalid Content-type header : '.$parameters['content-type'], null, 400);
        };

        $locale = $parameters['locale'];
        if (!$this->validateLocale($locale)) {
            throw new BadRequestException("Locale : '.$locale.' is not accepted", null, 400);
        };

        $level =  $parameters['level'];
        if (!$level) {
            throw new BadRequestException("Invalid ressource requested", null, 400);
        }
        if (!$this->validateLevel($level)) {
            throw new BadRequestException("Invalid ressource requested", null, 400);
        }
    }

    public static function requestParams(Request $request): array
    {
        $headers = $request->headers->getIterator()->getArrayCopy();
        $headers = array_change_key_case($headers, CASE_LOWER);
        $contentType = !empty($headers['content-type'][0]) ? $headers['content-type'][0] : "no content-type";
        $locale = $request->getLocale();
        $uri = $request->getRequestUri();
        preg_match('/^\/api\/geozones\/\K\S+/', $uri, $matches);
        $elements = array_map(function ($match) {
            $level = explode('/', $match);
            return $level[0];
        }, $matches);

        $level = $elements[0] ?? null;

        return [
            'locale' => $locale,
            'content-type' => $contentType,
            'level' => $level
        ];
    }
}
