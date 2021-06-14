<?php

namespace Bytesfield\SimpleKyc\Services;

use Noodlehaus\Config;
use Mpociot\Pipeline\Pipeline;
use Bytesfield\SimpleKyc\Pipes\Smile;
use Bytesfield\SimpleKyc\Pipes\Appruve;
use Bytesfield\SimpleKyc\Classes\IdFilter;
use Bytesfield\SimpleKyc\Classes\Validation;
use Bytesfield\SimpleKyc\Pipes\Credequity;

class IdVerification
{

    public function __construct($data)
    {
        $this->country = filterCountry($data->country);
        $this->id_type = strtoupper($data->id_type) ?? null;
        $this->id_number = $data->id ?? null;
        $this->first_name = $data->first_name ?? null;
        $this->last_name = $data->last_name ?? null;
        $this->middle_name = $data->middle_name ?? null;
        $this->date_of_birth = $data->date_of_birth ?? null;
        $this->phone = $data->phone_number ?? null;
        $this->pin = $data->pin ?? null;
        $this->tin = $data->tin ?? null;
        $this->gender = $data->gender ?? null;
        $this->full_name = $this->first_name . ' ' . $this->last_name;
        $this->user_id = $data->user_id ?? null;
        $this->company = $data->company ?? null;
        $this->registration_number = $data->registration_number ?? null;

        $this->config = new Config(__DIR__ . '/../config');

        $this->appruveHandler = $this->config->get('appruve.handler');
        $this->smileHandler = $this->config->get('smile.handler');
        $this->credequityHandler = $this->config->get('credequity.handler');
    }

    public function verify()
    {
        $IdFilter = new IdFilter(
            $this->country,
            $this->id_type,
            $this->id_number,
            $this->first_name,
            $this->last_name,
            $this->middle_name,
            $this->date_of_birth,
            $this->phone,
            $this->pin,
            $this->tin,
            $this->gender,
            $this->full_name,
            $this->user_id,
            $this->company,
            $this->registration_number

        );

        $response = null;
        $pipes = [Smile::class, Appruve::class, Credequity::class,];

        $response = (new Pipeline)->send($IdFilter)
            ->through($pipes)
            ->then(function ($result) {
                return $result;
            });

        $executedHandler = strtoupper($IdFilter->getHandler());

        $validation = new Validation();

        //Validate Appruve Handler result
        if ($executedHandler == strtoupper($this->appruveHandler)) {

            return $validation->validateAppruve($response, $IdFilter);
        }

        //Validate Smile Handler result
        if ($executedHandler == strtoupper($this->smileHandler)) {
            return $validation->validateSmile($response, $IdFilter);
        }

        //Validate Credequity Handler result
        if ($executedHandler == strtoupper($this->credequityHandler)) {
            return $validation->validateCredequity($response, $IdFilter);
        }

        return $response;
    }
}
