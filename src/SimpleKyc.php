<?php

namespace Bytesfield\SimpleKyc;

use Noodlehaus\Config;
use Bytesfield\SimpleKyc\Classes\IdFilter;
use Bytesfield\SimpleKyc\Services\IdVerification;
use Bytesfield\SimpleKyc\Exceptions\IsNullException;
use Bytesfield\SimpleKyc\Exceptions\NotFoundException;

class SimpleKyc
{
    public function __construct()
    {
        $this->config = new Config(__DIR__ . '/config');

        $this->smileHandler = $this->config->get('smile.handler');
        $this->appruveHandler = $this->config->get('appruve.handler');
        $this->credequityHandler = $this->config->get('credequity.handler');
    }

    /**
     * Verify ID
     *
     * @param array $payload
     * 
     * @throws IsNullException|NotFoundException
     * @return array
     */
    public function verifyId(array $payload)
    {
        $requestData = (object) $payload;

        if (empty((array) $requestData)) {
            throw new IsNullException('Payload must not be empty.');
        }

        $arrayKeyFormats = array_values(IdFilter::REQUEST_FORMAT);

        foreach ($requestData as $key => $data) {
            if (!in_array($key, $arrayKeyFormats)) {
                throw new NotFoundException("{$key} is not a supported form field or array key.");
            }
        }

        $idValue = array_values(IdFilter::IDVALUES);

        if (!array_key_exists('id_type', (array) $requestData)) {
            throw new IsNullException('id_type key and value must be specified.');
        }

        $idType = $requestData->id_type;

        //Checks for valid ID Types
        if (!in_array($idType, $idValue)) {
            throw new NotFoundException("{$idType} is not supported or not a valid ID TYPE.");
        }

        $supportedCountries = array_values(IdFilter::COUNTRIES);

        //Checks for supported country
        if (!in_array($requestData->country, $supportedCountries)) {
            throw new NotFoundException("{$requestData->country} is not a valid country or not supported.");
        }

        $idVerification = new IdVerification($requestData);

        return $idVerification->verify();
    }
}
