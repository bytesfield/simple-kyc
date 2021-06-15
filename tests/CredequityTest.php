<?php

namespace Bytesfield\SimpleKyc\Tests;

use PHPUnit\Framework\TestCase;
use Bytesfield\SimpleKyc\SimpleKyc;

class CredequityTest extends TestCase
{
    public function testShouldVerifyNIN()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "country"  => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "BABATUNDE",
            "date_of_birth" => "24-11-1975",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'NIN Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'CREDEQUITY');
    }

    public function testShouldNotVerifyNINIfDateOfBirthDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "country"  => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "BABATUNDE",
            "date_of_birth" => "24-11-2020",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Date of birth does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyNINIfFullnameDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "country"  => "NG",
            "first_name" => "KAYODE",
            "last_name" => "BABATUNDE",
            "date_of_birth" => "24-11-1975",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Firstname does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyBVNIfFirstnameDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "BVN",
            "country"  => "NG",
            "first_name" => "James",
            "last_name" => "Doe",
            "date_of_birth" => "29-Aug-1988",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Firstname does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyBVNIfLastnameDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "BVN",
            "country"  => "NG",
            "first_name" => "John",
            "last_name" => "Dave",
            "date_of_birth" => "29-Aug-1988",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Lastname does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyBVNIfDateOfBirthDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "BVN",
            "country"  => "NG",
            "first_name" => "John",
            "last_name" => "Doe",
            "date_of_birth" => "29-Aug-1980",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Date of birth does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldVerifyBVN()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "BVN",
            "country"  => "NG",
            "first_name" => "John",
            "last_name" => "Doe",
            "date_of_birth" => "29-Aug-1988",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'BVN Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'CREDEQUITY');
    }

    public function testShouldNotVerifyDRIVERS_LICENSEIfDateOfBirthDoesNotMatch()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "DRIVERS_LICENSE",
            "country"  => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Doe",
            "date_of_birth" => "08-03-1993",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Date of birth does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldVerifyDRIVERS_LICENSE()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "DRIVERS_LICENSE",
            "country"  => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Doe",
            "date_of_birth" => "08-03-1992",
            "phone_number" => "1234567890"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'DRIVERS_LICENSE Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'CREDEQUITY');
    }
}
