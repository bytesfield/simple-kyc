<?php

namespace Bytesfield\SimpleKyc\Pipes;

use Bytesfield\SimpleKyc\Exceptions\DataMismatchException;
use Bytesfield\SimpleKyc\HttpProcessor;
use Noodlehaus\Config;

use Closure;

class Smile
{
    public function __construct()
    {
        $config = new Config(__DIR__ . '/../config');

        $this->handler = $config->get('smile.handler');
        $this->apiKey = $config->get('smile.api_key');
        $this->baseUrl = $config->get('smile.api_url');
        $this->partnerId = $config->get('smile.partner_id');
    }


    /**
     * Process http calls
     * 
     * @param string $method The call method get|post|put|delete|patch
     * @param string $url The url to call

     * @param array payload The payload data to send with the call
     */
    private function process($method, $url, $payload)
    {
        //HttpProcessor class to handle axios calls
        $processor = new HttpProcessor($this->baseUrl, $this->apiKey, $this->handler);

        return $processor->process($method, $url, $payload);
    }
    /**
     * filter id requests
     *
     * @param Closure $next
     * @return void
     */
    public function handle($IdFilter, Closure $next)
    {

        if (!$IdFilter->isSuccessful()) {

            $secKey = $this->generateKey($this->partnerId, $this->apiKey);
            $jobId = md5(time() . rand());

            $body = [
                'partner_id' => $this->partnerId,
                'sec_key' => $secKey[0],
                'timestamp' => $secKey[1],
                'country' => strtoupper($IdFilter->getCountry()),
                'id_type' => $IdFilter->getIdType(),
                'id_number' => $IdFilter->getIdNumber(),
                'first_name' => $IdFilter->getFirstName(),
                'last_name' => $IdFilter->getLastName(),
                'dob' => $IdFilter->getDOB(),
                'phone_number' => $IdFilter->getPhoneNumber(),
                'partner_params' => [
                    'job_type' => 5,
                    'job_id' => $jobId,
                    'user_id' => $IdFilter->getUserId()
                ]
            ];

            try {
                $response = $this->process('POST', '/v1/id_verification', array_filter($body));

                $result = $response->getResponse();

                if ($this->verifyResponse($result, $IdFilter)) {

                    $IdFilter->confirmSuccess();

                    $IdFilter->setHandler('Smile');

                    $IdFilter->setData([
                        'handler' => $IdFilter->getHandler(),
                        'country' => $IdFilter->getCountry(),
                        'message' => $IdFilter->getIDType() . ' Verified' . ' Successfully',
                        'data' => $result
                    ]);

                    return $IdFilter->getData();
                } else {
                    //Verification Failed
                    $IdFilter->setError(['error' => $result['ResultText']]);

                    return json_encode(['error' => $IdFilter->getError()]);
                }
            } catch (\Exception $e) {
                return $IdFilter->setError(['error' => $e->getMessage()]);

                return json_encode(['error' => $IdFilter->getError()]);
            }
        }
        return $next($IdFilter);
    }

    /**
     * Generate Secret Key
     *
     * @param string $partnerId
     * @param string $apiKey
     * @return array
     */
    private function generateKey(string $partnerId, string $apiKey): array
    {
        $timestamp = time();
        $plaintext = intval($partnerId) . ":" . $timestamp;
        $hashSignature = hash('sha256', $plaintext);

        openssl_public_encrypt($hashSignature, $secKey, base64_decode($apiKey), OPENSSL_PKCS1_PADDING);

        $secKey = base64_encode($secKey);
        $secKey = $secKey . "|" . $hashSignature;

        return array($secKey, $timestamp);
    }

    private function verifyResponse($body, $IdFilter): bool
    {
        if (!($body['ResultCode'] == 1012)) {
            return false;
        }
        if (!($body['Actions']['Verify_ID_Number'] === 'Verified')) {
            return false;
        }

        return true;
    }
}
