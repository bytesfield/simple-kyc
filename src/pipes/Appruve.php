<?php

namespace Bytesfield\SimpleKyc\Pipes;

use Bytesfield\SimpleKyc\Classes\IdFilter;
use Closure;
use Noodlehaus\Config;
use Bytesfield\SimpleKyc\HttpProcessor;

class Appruve
{
    public function __construct()
    {
        $config = new Config(__DIR__ . '/../config');

        $this->handler = $config->get('appruve.handler');
        $this->apiKey = $config->get('appruve.api_key');
        $this->baseUrl = $config->get('appruve.api_url');
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
     * 
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter
     * @param Closure $next
     * 
     * @return mixed
     */
    public function handle(IdFilter $IdFilter, Closure $next)
    {
        if (!$IdFilter->isSuccessful()) {

            $idType =  strtoupper($IdFilter->getIDType());
            $country = strtolower($IdFilter->getCountry());
            $type = $this->getType($idType);
            $url = '/v1/verifications/' . $country . '/' . $type;

            $body = [
                'id' => $IdFilter->getIdNumber(),
                'first_name' => $IdFilter->getFirstName(),
                'last_name' => $IdFilter->getLastName(),
                'middle_name' => $IdFilter->getMiddleName(),
                'date_of_birth' => $IdFilter->getDOB(),
                'phone_number' => $IdFilter->getPhoneNumber(),
                'expiry_date' => $IdFilter->getExpiry(),
                'gender' => $IdFilter->getGender(),
                'address' => $IdFilter->getAddress(),
                'pin' => $IdFilter->getPin(),
                'tin' => $IdFilter->getTin(),
                'full_name' => $IdFilter->getFullName(),
                'company_name' => $IdFilter->getCompany(),
                'registration_number' => $IdFilter->getRegistrationNumber()
            ];

            try {
                $response = $this->process('POST', $url, array_filter($body))->getResponse();

                $IdFilter->confirmSuccess();

                $IdFilter->setHandler($this->handler);

                $IdFilter->setData([
                    'handler' => $IdFilter->getHandler(),
                    'country' => $IdFilter->getCountry(),
                    'message' => $IdFilter->getIDType() . ' Verified' . ' Successfully',
                    'data' => $response
                ]);

                return $IdFilter->getData();
            } catch (\Exception $e) {

                $IdFilter->setError(['error' => $e->getMessage()]);

                return $next($IdFilter);
            }
        }
        return $next($IdFilter);
    }

    /**
     * Transform the ID
     *
     * @param string $type
     * 
     * @return string
     */
    private function getType(string $type): string
    {
        if ($type === IdFilter::IDVALUES['TYPE_NATIONAL_ID']  || $type === IdFilter::IDVALUES['TYPE_NIN']) {
            return 'national_id';
        }
        if ($type === IdFilter::IDVALUES['TYPE_DRIVERS_LICENSE']) {
            return 'driver_license';
        }
        if ($type === IdFilter::IDVALUES['TYPE_VOTER_CARD']) {
            return 'voter';
        }
        if ($type === IdFilter::IDVALUES['TYPE_BVN']) {
            return 'bvn';
        }
        if ($type === IdFilter::IDVALUES['TELCO_SUBSCRIBER']) {
            return 'telco_subscriber';
        }
        return strtolower($type);
    }
}
