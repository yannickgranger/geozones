<?php

namespace MyPrm\GeoZones\Domain\Builder\LanguageBuilder;

use MyPrm\GeoZones\Domain\Service\Http\HttpGeoClientInterface;

class CountryLanguageBuilder implements CountryLanguageBuilderInterface
{
    private string $countryLanguagesUrl;
    private HttpGeoClientInterface $client;

    public function __construct(
        HttpGeoClientInterface $client,
        string $countryLanguagesUrl
    ) {
        $this->client = $client;
        $this->countryLanguagesUrl = $countryLanguagesUrl;
    }

    public function build(): array
    {
        $rawData = $this->getData();
        return $this->parseData($rawData[0]);
    }

    public function getData()
    {
        return $this->client->doRequest($this->countryLanguagesUrl);
    }

    public function parseData($data): array
    {
        $table = [];
        $start = strpos($data, '#ISO');
        $strData = substr($data, $start+1);

        $lines = explode(PHP_EOL, $strData);

        foreach ($lines as $line) {
            $content = explode("\t", $line);
            if (count($content) > 1) {
                $table[] = explode("\t", $line);
            }
        }

        return $table;
    }
}
