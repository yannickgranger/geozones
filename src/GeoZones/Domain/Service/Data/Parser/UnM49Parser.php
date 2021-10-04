<?php

namespace  GeoZones\Domain\Service\Data\Parser;

use GeoZones\Domain\Exception\ExternalSourceException;
use Symfony\Component\DomCrawler\Crawler;

class UnM49Parser implements DataParserInterface
{
    public function parse(string|array $data, array $parameters = []): array
    {
        $lang = $parameters['locale'];

        $langTable = 'table'."[id='downloadTable".strtoupper($lang)."']";
        $crawler = new Crawler($data);

        try {
            $crawler = $crawler->filter($langTable);
        } catch (\Exception $exception) {
            throw new ExternalSourceException(400, __METHOD__.' : '.'Requested page html does not contains node downloadTable'.$lang, null);
        }

        return $crawler
                ->filter('tr')
                ->each(function ($tr, $i) {
                    return $tr
                        ->filter('td')->each(function ($td, $i) {
                            return trim($td->text());
                        });
                }) ?? [];
    }
}
