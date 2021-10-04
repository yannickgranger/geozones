<?php

namespace MyPrm\GeoZones\Tests\Model;

use Monolog\Test\TestCase;
use MyPrm\GeoZones\Domain\Model\Translation;
use MyPrm\GeoZones\Domain\Model\Translations;

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
