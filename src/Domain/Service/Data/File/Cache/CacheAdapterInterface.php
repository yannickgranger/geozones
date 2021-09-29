<?php

namespace MyPrm\GeoZones\Domain\Service\Data\File\Cache;

interface CacheAdapterInterface
{
    public function save(string $fileName, mixed $content, int $cacheTtl);

    public function delete(string $fileName);
}
