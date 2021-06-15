<?php

namespace Bytesfield\SimpleKyc\Tests;

use PHPUnit\Framework\TestCase;
use Bytesfield\SimpleKyc\SimpleKyc;
use Bytesfield\SimpleKyc\Exceptions\IsNullException;
use Http\Mock\Client;

class SmileTest extends TestCase
{
    public function testShouldVerifyIDWithSmile()
    {
        $payload = [
            "id" => "00000000000",
            "id_type" => "NIN",
            "country" => "NG",
            "first_name" => "CHUKWUEMEKA",
            "last_name" => "Nyawera",
            "middle_name" => "Clement",
            "date_of_birth" => "24-11-1975",
            "user_id" => "123"
        ];
        $simpleKyc = new SimpleKyc();

        $response = $simpleKyc->verifyId($payload);

        $this->assertEquals($response['data']['Actions']['Verify_ID_Number'], 'Verified');
        $this->assertEquals(strtoupper($response['handler']), 'SMILE');
    }
}
