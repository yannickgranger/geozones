<?php

namespace MyPrm\GeoZones\Infrastructure\Service\Data;

use Assert\Assert;
use Assert\LazyAssertionException;
use MyPrm\GeoZones\Domain\Service\Data\Validator\DataValidatorInterface;
use MyPrm\GeoZones\SharedKernel\Error\Error;

class UnM49Validator implements DataValidatorInterface
{
    public function validateData(array $data): ?Error
    {
        if (!array_key_exists('fieldNames', $data)) {
            return new Error(__METHOD__, 'Missing key fieldsNames in data provided');
        }
        $result = $this->validateFieldsNames($data['fieldNames']);
        if ($result instanceof Error) {
            return $result;
        }

        if (!array_key_exists('data', $data)) {
            return new Error(__METHOD__, 'Missing key data in data provided');
        }
        $result = $this->validateDataTable($data['data']);
        if ($result instanceof Error) {
            return $result;
        }

        return null;
    }

    public function validateFieldsNames(array $fieldNames): ?Error
    {
        try {
            Assert::lazy()
                ->that($fieldNames)->notEmpty()->count(16)
                ->that($fieldNames[0])->notEmpty()->string()->eq('Global Code')
                ->that($fieldNames[1])->notEmpty()->string()->eq('Global Name')
                ->that($fieldNames[2])->notEmpty()->string()->eq('Region Code')
                ->that($fieldNames[3])->notEmpty()->string()->eq('Region Name')
                ->that($fieldNames[4])->notEmpty()->string()->eq('Sub-region Code')
                ->that($fieldNames[5])->notEmpty()->string()->eq('Sub-region Name')
                ->that($fieldNames[6])->notEmpty()->string()->eq('Intermediate Region Code')
                ->that($fieldNames[7])->notEmpty()->string()->eq('Intermediate Region Name')
                ->that($fieldNames[8])->notEmpty()->string()->eq('Country or Area')
                ->that($fieldNames[9])->notEmpty()->string()->eq('M49 Code')
                ->that($fieldNames[10])->notEmpty()->string()->eq('ISO-alpha2 Code')
                ->that($fieldNames[11])->notEmpty()->string()->eq('ISO-alpha3 Code')
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            return new Error(__METHOD__, $exception->getMessage());
        }

        return null;
    }

    public function validateDataTable(array $data): ?Error
    {
        try {
            Assert::lazy()
                ->that($data)->notEmpty()->minCount(200)
                ->verifyNow();
        } catch (LazyAssertionException $exception) {
            return new Error(__METHOD__, $exception->getMessage());
        }

        return null;
    }
}
