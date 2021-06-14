<?php

namespace Bytesfield\SimpleKyc\Pipes;

use Bytesfield\SimpleKyc\Classes\IdFilter;
use Closure;
use Noodlehaus\Config;
use Bytesfield\SimpleKyc\HttpProcessor;

class Credequity
{
    public function __construct()
    {
        $config = new Config(__DIR__ . '/../config');

        $this->handler = $config->get('credequity.handler');
        $this->apiKey = $config->get('credequity.api_key');
        $this->baseUrl = $config->get('credequity.api_url');
    }

    /**
     * Process http calls
     * 
     * @param string $method The call method get|post|put|delete|patch
     * @param string $url The url to call
     * @param array $payload

     * @return \Bytesfield\SimpleKyc\HttpProcessor
     */
    private function process($method, $url, $payload): HttpProcessor
    {
        //HttpProcessor class to handle http request
        $processor = new HttpProcessor($this->baseUrl, $this->apiKey, $this->handler);

        return $processor->process($method, $url, $payload);
    }

    /**
     * Handles the ID request
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter
     * @param Closure $next
     * 
     * @return array
     */
    public function handle(IdFilter $IdFilter, Closure $next)
    {

        if (!$IdFilter->isSuccessful()) {

            if ($IdFilter->getCountry() == IdFilter::COUNTRIES['NIGERIA']) {

                if ($IdFilter->isWithImage()) {
                    return $this->getWithImage($IdFilter);
                }

                $idNumber =  $IdFilter->getIDNumber();
                $firstName =  $IdFilter->getFirstName();
                $lastName =  $IdFilter->getLastName();
                $phone =  $IdFilter->getPhoneNumber();

                if ($IdFilter->getIDType() === IdFilter::IDVALUES['TYPE_BVN']) {
                    $url = '/CredBvn/api/v1/Bvn/GetCustomerBvn';

                    $body = [
                        'bvn' => $idNumber,
                        'PhoneNumber' => $phone
                    ];

                    return $this->postData($IdFilter, $body, $url);
                }
                if ($IdFilter->getIDType() === IdFilter::IDVALUES['TYPE_NIN']) {
                    if (!$IdFilter->isSuccessful()) {
                        $url = '/CredNin/api/v1/Identity?phoneNo=' . $phone;
                        $body = [];

                        return $this->postData($IdFilter, $body, $url);
                    }

                    //If request is not successful makes post with IdNumber
                    if (!$IdFilter->isSuccessful()) {
                        $url = '/CredNin/api/v1/IdentityByNin?nin=' . $idNumber;
                        $body = [];

                        return $this->postData($IdFilter, $body, $url);
                    }
                }

                if ($IdFilter->getIDType() === IdFilter::IDVALUES['TYPE_DRIVERS_LICENSE']) {
                    $url = '/Verify/api/v1/FrscInfo';

                    $body = [
                        'firstname' => $firstName,
                        'lastname' => $lastName,
                        'phoneNo' => $phone,
                        'frscidentityNo' => $idNumber
                    ];

                    return $this->postData($IdFilter, $body, $url);
                }

                if ($IdFilter->getIDType() === IdFilter::IDVALUES['TYPE_CUSTOMER_PROFILE']) {
                    $url = '/CredBvn/api/v1/CustomerProfile';
                    //$IdFilter->setCredequityProfile();
                    $profile = $IdFilter->getCredequityProfile();

                    $body = [
                        "Nin" => $profile['nin'],
                        "FrscNo" => $profile['frscno'],
                        "phoneNo" => $phone,
                        "Bvn" => $profile['bvn']
                    ];

                    return $this->postData($IdFilter, $body, $url);
                }
            }
        }
        return $next($IdFilter);
    }

    /**
     * Get ID information via images
     *
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter

     * @return array
     */
    private function getWithImage(IdFilter $IdFilter): array
    {
        $relative = ($IdFilter->getIDType() === 'NIN') ? 'VerifyNinWithFace' : 'VerifyFrscWithFace';

        $url = '/CredOcr/api/v1/' . $relative;

        $body = [
            'IdentificationProof' => $IdFilter->getIdentificationProof(),
            'FaceProof' => $IdFilter->getFaceProof()
        ];

        return $this->postData($IdFilter, $body, $url);
    }

    /**
     * Make API call and get the required data from Credequity
     *
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter
     * @param array $body
     * @param string url
     * 
     * @return array
     */
    private function postData(IdFilter $IdFilter, $body, $url)
    {
        try {
            $result =  $this->process('POST', $url, array_filter($body));

            $response = (object) $result->getResponse();

            if ($response->message === 'Successful') {

                $IdFilter->confirmSuccess();

                $IdFilter->setHandler($this->handler);

                $IdFilter->setData([
                    'handler' => $IdFilter->getHandler(),
                    'message' => $IdFilter->getIDType() . ' Verified' . ' Successfully',
                    'data' => $response->data
                ]);

                return $IdFilter->getData();
            }
        } catch (\Exception $e) {
            $IdFilter->setError(['error' => $e->getMessage()]);

            return json_encode(['error' => $IdFilter->getError()]);
        }
    }
}
