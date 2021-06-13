<?php

namespace Bytesfield\SimpleKyc;

use Bytesfield\SimpleKyc\Classes\IdFilter;
use Bytesfield\SimpleKyc\Exceptions\NotFoundException;
use Bytesfield\SimpleKyc\Services\IdVerification;
use Noodlehaus\Config;

class SimpleKyc
{
    public function __construct()
    {
        $this->config = new Config(__DIR__ . '/config');

        $this->smileHandler = $this->config->get('smile.handler');
        $this->appruveHandler = $this->config->get('appruve.handler');
        $this->credequityHandler = $this->config->get('credequity.handler');
    }

    public function verifyId($payload, ?string $handler = null)
    {
        $requestData = (object) $payload;

        //Checks whether Handler is valid
        if ($handler != null) {
            if (!is_string($handler)) {
                return json_encode(['error' => $handler . 'is not a valid string']);
            }

            $pipes = [
                strtoupper($this->smileHandler),
                strtoupper($this->appruveHandler),
                strtoupper($this->credequityHandler),
            ];

            if (!in_array(strtoupper($handler), $pipes)) {
                throw new NotFoundException("{$handler} is not a valid handler.");
            }

            if (empty((array) $requestData)) {
                throw new NotFoundException('Payload must not be empty.');
            }

            $idValue = array_values(IdFilter::IDVALUES);
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

            return $idVerification->verify($handler);
        }
    }
}
