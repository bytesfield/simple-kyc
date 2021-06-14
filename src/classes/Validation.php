<?php

namespace Bytesfield\SimpleKyc\Classes;

use Bytesfield\SimpleKyc\Classes\IdFilter;
use Bytesfield\SimpleKyc\Exceptions\NotValidException;
use Bytesfield\SimpleKyc\Exceptions\DataMismatchException;
use Bytesfield\SimpleKyc\Exceptions\NotFoundException;

class Validation
{
    /**
     * Validate Appruve information
     *
     * @param array $response
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter
     * 
     * @return array
     */
    public function validateAppruve(array $response, IdFilter $IdFilter): array
    {
        $idType = $IdFilter->getIDType();

        if ($IdFilter->getCountry() == IdFilter::COUNTRIES['GHANA']) {

            if ($idType == IdFilter::IDVALUES['TYPE_VOTER_CARD']) {
                return $response;
            } //Exclude Appruve Field Verification for this

            if ($idType == IdFilter::IDVALUES['TYPE_TIN']) {
                if (!$response['data']['is_valid']) {
                    throw new NotValidException('Tin is not valid');
                }

                return $response;
            } //Exclude Appruve Field Verification for this

            if ($idType == IdFilter::IDVALUES['TYPE_DRIVERS_LICENSE']) {
                if (!$response['data']['is_full_name_match']) {
                    throw new DataMismatchException('Fullname does not match');
                }

                if (!$response['data']['is_date_of_birth_match']) {
                    throw new DataMismatchException('Date of birth does not match');
                }
                return $response;
            } //Exclude Appruve Field Verification for this

            if ($idType == IdFilter::IDVALUES['TYPE_SSNIT']) {
                if (!$response['data']['is_full_name_match']) {
                    throw new DataMismatchException('Fullname does not match');
                }

                return $response;
            } //Exclude Appruve Field Verification for this

        }

        if (
            $idType == IdFilter::IDVALUES['TYPE_TIN']
            || $idType == IdFilter::IDVALUES['TYPE_KRA']
            || $idType == IdFilter::IDVALUES['TELCO_SUBSCRIBER']
        ) {
            return $response;
        } //Exclude Appruve Field Verification for this

        $isVerified = $this->verifyAppruve($response);

        if ($isVerified == true) {
            return $response;
        }
    }

    /**
     * Verify Appruve information
     *
     * @param array result
     * 
     * @throws \Bytesfield\SimpleKyc\Exceptions\DataMismatchException
     * 
     * @return bool
     */
    private function verifyAppruve($result): bool
    {
        $data = $result['data'];

        if (!$data['is_first_name_match']) {
            throw new DataMismatchException('Firstname does not match');
        }

        if (!$data['is_last_name_match']) {
            throw new DataMismatchException('Lastname does not match');
        }

        if (!$data['is_date_of_birth_match']) {
            throw new DataMismatchException('Date of birth does not match');
        }

        return true;
    }

    /**
     * Validate Credequity information
     *
     * @param array $response
     * @param \Bytesfield\SimpleKyc\Classes\IdFilter
     * 
     * @return array
     */
    public function validateCredequity($response, IdFilter $IdFilter): array
    {
        $isVerified = $this->verifyCredequity($response, $IdFilter);

        if ($isVerified == true) {
            return $response;
        }
    }

    /**
     * Verify Credequity information
     *
     * @param array result
     * @param Bytesfield\SimpleKyc\Classes\IdFilter
     * 
     * @throws \Bytesfield\SimpleKyc\Exceptions\DataMismatchException
     * 
     * @return bool
     */
    private function verifyCredequity($result, $IdFilter): bool
    {
        $data = $result['data'];
        $idType = $IdFilter->getIDType();

        if ($idType == IdFilter::IDVALUES['TYPE_BVN']) {

            if (strtoupper($data['firstName']) !== strtoupper($IdFilter->getFirstName())) {
                throw new DataMismatchException('Firstname does not match');
            }

            if (strtoupper($data['lastName']) !== strtoupper($IdFilter->getLastName())) {
                throw new DataMismatchException('Lastname does not match');
            }

            if ($data['dateOfBirth'] !== $IdFilter->getDOB()) {
                throw new DataMismatchException('Date of birth does not match');
            }
        }

        if ($idType == IdFilter::IDVALUES['TYPE_DRIVERS_LICENSE']) {
            if ($data['Birthdate'] !== $IdFilter->getDOB()) {
                throw new DataMismatchException('Date of birth does not match');
            }
        }

        if ($idType == IdFilter::IDVALUES['TYPE_NIN']) {
            if (strtoupper($data['firstname']) !== strtoupper($IdFilter->getFirstName())) {
                throw new DataMismatchException('Firstname does not match');
            }

            if ($data['birthdate'] !== $IdFilter->getDOB()) {
                throw new DataMismatchException('Date of birth does not match');
            }
        }

        return true;
    }

    /**
     * Validate Smile response
     *
     * @param array $response
     * 
     * @throws \Bytesfield\SimpleKyc\Exceptions\NotFoundException
     * 
     * @return array
     */
    public function validateSmile(array $response): array
    {
        $isVerified = $this->verifySmile($response);

        if ($isVerified === false) {
            throw new NotFoundException("{$response['data']['ResultText']}");
        }
        return $response;
    }

    /**
     * Verify Smile response
     *
     * @param array $response
     * 
     * @return bool
     */
    private function verifySmile($response): bool
    {
        if (($response['data']['Actions']['Verify_ID_Number'] !== 'Verified')) {
            return false;
        }

        return true;
    }
}
