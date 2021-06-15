<?php

namespace Bytesfield\SimpleKyc\Tests;

use PHPUnit\Framework\TestCase;
use Bytesfield\SimpleKyc\SimpleKyc;

class AppruveTest extends TestCase
{
    public function testShouldVerifyNigerianNIN()
    {
        $payload = [
            "id" => "13478900911",
            "id_type" => "NIN",
            "country" => "NG",
            "first_name" => "Clement",
            "last_name" => "Nwoji",
            "middle_name" => "Okezie",
            "date_of_birth" => "1966-01-11",
            "phone_number" => "08000110004"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'NIN Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyNigerianPASSPORT()
    {
        $payload = [
            "id" => "A50013320",
            "id_type" => "PASSPORT",
            "country" => "NG",
            "first_name" => "Sunday",
            "last_name" => "Obafemi",
            "middle_name" => "Clement",
            "date_of_birth" => "1975-04-25",
            "phone_number" => "08000110004"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'PASSPORT Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyNigerianVOTER_CARD()
    {
        $payload = [
            "id" => "90F5B0407E2960502637",
            "id_type" => "VOTER_CARD",
            "country" => "NG",
            "first_name" => "Nwabia",
            "last_name" => "Chidozie",
            "middle_name" => "Stanley",
            "date_of_birth" => "1998-01-10"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'VOTER_CARD Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyNigerianDRIVERS_LICENSE()
    {
        $payload = [
            "id" => "ABC00578AA2",
            "id_type" => "DRIVERS_LICENSE",
            "country" => "NG",
            "first_name" => "Henry",
            "last_name" => "Nwandicne",
            "middle_name" => "Emeka",
            "date_of_birth" => "1976-04-15"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'DRIVERS_LICENSE Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyNigerianBVN()
    {
        $payload = [
            "id" => "22000000900",
            "id_type" => "BVN",
            "country" => "NG",
            "first_name" => "Ayodele",
            "last_name" => "Obasooto",
            "middle_name" => "Femi",
            "date_of_birth" => "1988-10-20"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'BVN Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyNigerianTIN()
    {
        $payload = [
            "id" => "00000000-0001",
            "id_type" => "TIN",
            "country" => "NG",
            "first_name" => "Ayodele",
            "last_name" => "Obasooto",
            "middle_name" => "Femi",
            "date_of_birth" => "1988-10-20"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'TIN Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyGhanaVOTER_CARD()
    {
        $payload = [
            "id" => "2000100000",
            "id_type" => "VOTER_CARD",
            "country" => "GH",
            "first_name" => "Elizabeth",
            "last_name" => "Adjei",
            "date_of_birth" => "1996-02-21"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'VOTER_CARD Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyGhanaPASSPORT()
    {
        $payload = [
            "id" => "G0000000",
            "id_type" => "PASSPORT",
            "country" => "GH",
            "first_name" => "Evans",
            "last_name" => "Amankwah",
            "date_of_birth" => "1986-04-05"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'PASSPORT Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldNotVerifyGhanaSSNITIfFullnameDoesNotMatch()
    {
        $payload = [
            "id" => "C000007000001",
            "id_type" => "SSNIT",
            "country" => "GH",
            "first_name" => "Mike",
            "last_name" => "John",
            "date_of_birth" => "1986-04-05"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Fullname does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldVerifyGhanaSSNIT()
    {
        $payload = [
            "id" => "C000007000001",
            "id_type" => "SSNIT",
            "country" => "GH",
            "first_name" => "Michael",
            "last_name" => "Essien",
            "date_of_birth" => "1986-04-05"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'SSNIT Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldNotVerifyGhanaDRIVERS_LICENSEIfFullnameDoesNotMatch()
    {
        $payload = [
            "id" => "G0000001",
            "id_type" => "DRIVERS_LICENSE",
            "country" => "GH",
            "first_name" => "Mike",
            "last_name" => "Davis",
            "date_of_birth" => "1992-04-05"
        ];
        $simpleKyc = new SimpleKyc();

        $errorOccurred = false;

        try {
            $simpleKyc->verifyId($payload);
        } catch (\Exception $e) {
            $errorOccurred = true;

            $this->assertEquals("Fullname does not match", $e->getMessage());
        }
        $this->assertTrue($errorOccurred);
    }

    public function testShouldNotVerifyGhanaDRIVERS_LICENSEIfDateOfBirthDoesNotMatch()
    {
        $payload = [
            "id" => "G0000001",
            "id_type" => "DRIVERS_LICENSE",
            "country" => "GH",
            "first_name" => "Kwabena",
            "last_name" => "Aikens",
            "date_of_birth" => "2000-04-05"
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

    public function testShouldVerifyGhanaDRIVERS_LICENSE()
    {
        $payload = [
            "id" => "G0000001",
            "id_type" => "DRIVERS_LICENSE",
            "country" => "GH",
            "first_name" => "Kwabena",
            "last_name" => "Aikens",
            "date_of_birth" => "1992-04-05"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'DRIVERS_LICENSE Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyKenyaNATIONAL_ID()
    {
        $payload = [
            "id" => "00000001",
            "id_type" => "NATIONAL_ID",
            "country" => "KE",
            "first_name" => "Fiona",
            "last_name" => "Nyawera",
            "date_of_birth" => "1986-05-13"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'NATIONAL_ID Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyKenyaPASSPORT()
    {
        $payload = [
            "id" => "A0000003",
            "id_type" => "PASSPORT",
            "country" => "KE",
            "first_name" => "Fiona",
            "last_name" => "Nyawera",
            "date_of_birth" => "1981-02-14"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'PASSPORT Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }

    public function testShouldVerifyKenyaKRA()
    {
        $payload = [
            "pin" => "A000000010",
            "id_type" => "KRA",
            "country" => "KE",
            "first_name" => "Fiona",
            "last_name" => "Nyawera",
            "date_of_birth" => "1981-02-14"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['message'], 'KRA Verified Successfully');
        $this->assertEquals(strtoupper($response['handler']), 'APPRUVE');
    }
}
