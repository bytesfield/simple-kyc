<?php

namespace Bytesfield\SimpleKyc\Tests;

use PHPUnit\Framework\TestCase;
use Bytesfield\SimpleKyc\SimpleKyc;

class SimpleKycTest extends TestCase
{

    public function testShouldNotVerifyIdIfPayloadIsEmpty()
    {
        $payload = [];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals($e->getMessage(), "Payload must not be empty.");
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyIdIfInvalidPayloadKeyIsSpecified()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "my_country" => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Nyawera",
            "middle_name" => "Clement",
            "date_of_birth" => "24-11-1975",
            "user_id" => "123"
        ];

        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals($e->getMessage(), "my_country is not a supported form field or array key.");
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyIdIfInvalidPayloadIDTypeIsNotSpecified()
    {
        $payload = [
            "id" => "00000000000",
            "country" => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Nyawera",
            "middle_name" => "Clement",
            "date_of_birth" => "24-11-1975",
            "user_id" => "123"
        ];

        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("id_type key and value must be specified.", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyIdWithUnSupportedIDTypeValue()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "GOLD",
            "country" => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Nyawera",
            "middle_name" => "Clement",
            "date_of_birth" => "24-11-1975",
            "user_id" => "123"
        ];

        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("GOLD is not supported or not a valid ID TYPE.", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyIdWithUnSupportedCountry()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "country" => "EGYPT",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Nyawera",
            "middle_name" => "Clement",
            "date_of_birth" => "24-11-1975",
            "user_id" => "123"
        ];

        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("EGYPT is not a valid country or not supported.", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }
}
