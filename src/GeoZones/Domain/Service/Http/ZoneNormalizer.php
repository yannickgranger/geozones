<?php

namespace  GeoZones\Domain\Service\Http;

class ZoneNormalizer
{
    private LeafNormalizer $leafNormalizer;

    public function __construct(LeafNormalizer $leafNormalizer)
    {
        $this->leafNormalizer = $leafNormalizer;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        if (!empty($object->getCountries())) {
            return  $this->leafNormalizer->normalize($object);
        }

        $data = $object->getParent()
            ? ['parent' => $object->getParent()->getName()]
            : [];

        $data['name'] = $object->getName() ?? "missing_name";
        return $data;
    }
}
