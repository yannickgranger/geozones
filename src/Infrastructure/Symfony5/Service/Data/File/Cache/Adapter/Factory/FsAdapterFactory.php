<?php

namespace Symfony5\Service\Data\File\Cache\Adapter\Factory;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FsAdapterFactory
{
    private string $cacheDirectory;
    private string $cacheTtl;
    private ?string $nameSpace;

    public function __construct(
        string $cacheDirectory,
        string $cacheTtl,
        ?string $nameSpace = ""
    ) {
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheTtl = $cacheTtl;
        $this->nameSpace = $nameSpace;
    }

    public function getAdapter(): FilesystemAdapter
    {
        return new FilesystemAdapter($this->nameSpace, (int) $this->cacheTtl, $this->cacheDirectory);
    }
}
