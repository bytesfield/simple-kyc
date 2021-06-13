
<?php

use Bytesfield\SimpleKyc\SimpleKyc;


if (!function_exists('simple_kyc')) {
    function simple_kyc()
    {
        return new SimpleKyc();
    }
}

if (!function_exists('filterCountry')) {
    function filterCountry($country = null)
    {
        if ($country == null) {
            return 'NG';
        }
        $country = strtoupper($country);

        if ($country == 'NIGERIA') {
            return 'NG';
        }

        if ($country == 'GHANA') {
            return 'GH';
        }

        if ($country == 'KENYA') {
            return 'KE';
        }

        if ($country == 'UGANDA') {
            return 'UG';
        }

        return $country;
    }
}
