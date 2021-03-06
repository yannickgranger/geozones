<?php

namespace GeoZonesTests\Model;

use Monolog\Test\TestCase;
use GeoZones\Domain\Model\Translation;
use GeoZones\Domain\Model\Translations;

class TranslationsTest extends TestCase
{
    private Translations $translations;

    protected function setUp(): void
    {
       $this->translations = new Translations('toto', 'test');
    }

    public function testAddTranslation()
    {
        $translation = new Translation('titi', '', '');
        $this->translations->addTranslation($translation);
        $this->assertEquals(count($this->translations->getValues()), 0);

        $translation = new Translation('test', '', '');
        $this->translations->addTranslation($translation);
        $this->assertEquals(count($this->translations->getValues()), 1);

        $translation = new Translation('test', '', '');
        $this->translations->addTranslation($translation);
        $this->assertEquals(count($this->translations->getValues()), 1);

        $translation = new Translation('test', 'test', '');
        $this->translations->addTranslation($translation);
        $this->assertEquals(count($this->translations->getValues()), 2);
    }
}
