<?php

namespace Bytesfield\SimpleKyc\Classes;


class IdFilter
{
    public const COUNTRIES = [
        'NIGERIA' => 'NG',
        'GHANA' => 'GH',
        'KENYA' => 'KE',
        'UGANDA' => 'UG',
        'SOUTH_AFRICA' => 'ZA'
    ];

    public const REQUEST_FORMAT = [
        'ID' => 'id',
        'COUNTRY' => 'country',
        'ID_TYPE' => 'id_type',
        'FIRST_NAME' => 'first_name',
        'LAST_NAME' => 'last_name',
        'MIDDLE_NAME' => 'middle_name',
        'DOB' => 'date_of_birth',
        'PHONE' => 'phone_number',
        'PIN' => 'pin',
        'TIN' => 'tin',
        'GENDER' => 'gender',
        'USER_ID' => 'user_id',
        'COMPANY' => 'company',
        'REGISTRATION_NUMBER' => 'registration_number'
    ];

    public const IDVALUES = [
        'TYPE_PASSPORT' => 'PASSPORT',
        'TYPE_NATIONAL_ID'  => 'NATIONAL_ID',
        'TYPE_SSNIT'  => 'SSNIT',
        'TYPE_VOTER_CARD' => 'VOTER_CARD',
        'TYPE_VOTER_ID' => 'VOTER_ID',
        'TYPE_ALIEN_CARD' => 'ALIEN_CARD',
        'TYPE_BVN' => 'BVN',
        'TYPE_NIN' => 'NIN',
        'TYPE_NIN_SLIP' => 'NIN_SLIP',
        'TYPE_DRIVERS_LICENSE' => 'DRIVERS_LICENSE',
        'TYPE_TIN' => 'TIN',
        'TYPE_CAC' => 'CAC',
        'NATIONAL_ID_NO_PHOTO' => 'NATIONAL_ID_NO_PHOTO',
        'TELCO_SUBSCRIBER' =>  'TELCO_SUBSCRIBER',
        'TYPE_CUSTOMER_PROFILE' => 'CUSTOMER_PROFILE',
        'TYPE_KRA' => 'KRA',

    ];

    private ?string $country;
    private string $idType;
    private ?string $idNumber;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $dob;
    private ?string $phoneNumber;
    private ?string $middleName;
    private ?string $userId;
    private ?string $gender;
    private ?string $expiry;
    private ?string $address;
    private ?string $IdentificationProof;
    private ?string $faceProof;
    private array $data = [];
    private bool $success = false;
    private array $error = [];
    private bool $withImage = false;

    private string $handler = '';
    private array $credequityProfile = [];

    public function __construct(
        string $country,
        ?string $idType,
        ?string $idNumber = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $middleName = null,
        ?string $dob = null,
        ?string $phoneNumber = null,
        ?string $pin = null,
        ?string $tin = null,
        ?string $gender = null,
        ?string $full_name = null,
        ?string $userId = null,
        ?string $company = null,
        ?string $expiry = null,
        ?string $address = null,
        ?string $registration_number = null,
        ?string $IdentificationProof = null,
        ?string $faceProof = null
    ) {
        $this->country = $country;
        $this->idType = $idType;
        $this->idNumber = $idNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->dob = $dob;
        $this->phoneNumber = $phoneNumber;
        $this->pin = $pin;
        $this->tin = $tin;
        $this->gender = $gender;
        $this->full_name = $full_name;
        $this->userId = $userId;
        $this->company = $company;
        $this->expiry = $expiry;
        $this->address = $address;
        $this->registration_number = $registration_number;
        $this->IdentificationProof = $IdentificationProof;
        $this->faceProof = $faceProof;
    }


    public function getIDNumber()
    {
        return $this->idNumber;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getRegistrationNumber()
    {
        return $this->registration_number;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function getIDType()
    {
        return $this->idType;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function getTin()
    {
        return $this->tin;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function getDOB()
    {
        return $this->dob;
    }

    public function getIdentificationProof()
    {
        return $this->identificationProof;
    }

    public function getFaceProof()
    {
        return $this->faceProof;
    }

    public function setWithImage()
    {
        $this->withImage = true;
    }

    public function isWithImage()
    {
        return $this->withImage;
    }

    public function setCredequityProfile($nin, $frscno, $bvn)
    {
        $this->credequityProfile = ['nin' => $nin, 'frscno' => $frscno, 'bvn' => $bvn];
    }

    public function getCredequityProfile()
    {
        return $this->credequityProfile;
    }

    /**
     * Returns user phone
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Returns the user id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }


    /**
     * Sets success to true
     *
     * @return void
     */
    public function confirmSuccess(): void
    {
        $this->success = true;
    }

    /**
     * Sets the pipe that handled the request
     *
     * @param string $handler
     * @return void
     */
    public function setHandler($handler): void
    {
        $this->handler = $handler;
    }

    /**
     * Gets the pipe that handled the request
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Sets data returned from the request
     *
     * @param array $data
     * @return void
     */
    public function setData($data = []): void
    {
        $this->data = $data;
    }

    /**
     * Sets the error associated with request
     *
     * @param array $error
     * @return void
     */
    public function setError($error = []): void
    {
        $this->error = $error;
    }

    /**
     * Return error associated with request
     *
     * @return string
     */
    public function getError()
    {
        return $this->error['error'];
    }

    /**
     * Returns the data gotten from the request
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Checks if the request is successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->success;
    }
}
