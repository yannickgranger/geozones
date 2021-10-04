<?php

namespace GeoZones\Domain\Service\Data\File\Cache;

interface CacheAdapterInterface
{
    public function get(string $fileName);

    public function save(string $fileName, mixed $content, int $cacheTtl);

    public function delete(string $fileName);
}
