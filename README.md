<p align="center"><img src="/images/simple-kyc.jpg" alt="Simple-Kyc Preview"></p>

# Simple KYC

[![GitHub license](https://img.shields.io/github/license/bytesfield/simple-kyc)](https://github.com/bytesfield/simple-kyc/blob/main/LICENSE.md)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bytesfield/simple-kyc/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/bytesfield/simple-kyc/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/bytesfield/simple-kyc/badges/build.png?b=main)](https://scrutinizer-ci.com/g/bytesfield/simple-kyc/build-status/main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/bytesfield/simple-kyc/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![GitHub issues](https://img.shields.io/github/issues/bytesfield/simple-kyc)](https://github.com/bytesfield/simple-kyc/issues)

## Description

A Know Your Customer (KYC) PHP Package to verify business's customer identity using [SMILE IDENTITY](https://docs.smileidentity.com/), [APPRUVE](https://www.appruve.co/) and [CREDEQUITY](https://credequity.com/) KYC services. This service currently support countries like Nigeria(NG), Ghana(GH), Kenya(KE), Uganda(UG).

## Installation

[PHP](https://php.net) 7.4+ and [Composer](https://getcomposer.org) are required.

To get the latest version of Simple-Kyc, simply require it

```bash
composer require bytesfield/simple-kyc
```

Or add the following line to the require block of your `composer.json` file.

```
"bytesfield/simple-kyc": "1.0.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

## Configuration

Once `simple-kyc` is installed, you will need to add the following credentials gotten from the different KYC service providers to your `.env`. Click on their names it will redirect you to their websites where you can sign up and get there API KEYs.

_If you are using a hosting service like heroku, ensure to add the above details to your configuration variables._

- [SMILE IDENTITY](https://docs.smileidentity.com/) <br/>

```javascript
SMILE_API_KEY = xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx;
SMILE_PARTNER_ID = xxxx;
```

- [APPRUVE](https://www.appruve.co/) <br/>

```javascript
APPRUVE_API_KEY = xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx;
```

- [CREDEQUITY](https://credequity.com/) <br/>

```javascript
CREDEQUITY_API_URL = xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx;
```

## Usage

```php
use Bytesfield\SimpleKyc\SimpleKyc;

$simpleKyc = new SimpleKyc;

$response = $simpleKyc->verifyId($payload = []);
```

###### Supported Handlers:

```javascript
SMILE, APPRUVE, CREDEQUITY;
```

_The request will pass through simple-kyc's pipeline and return the result and the pipe that handled the verification request_

_Note all data used here are dummy data, to get test data you can visit the individual KYC service provider's websites and get data to test_

### For Nigeria (NG)

#### APPRUVE SERVICE

##### ID Verification

###### Supported ID Types Values:

```php
NIN, BVN, DRIVERS_LICENSE, PASSPORT, TIN, VOTER_CARD;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'NG',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'middle_name' => 'Peter',
    'date_of_birth' => '1982-05-20',
];

$response = $simpleKyc->verifyId($payload);
```

#### SMILE IDENTITY SERVICE

##### ID Verification

Supported ID Types Values:

```php
NIN_SLIP, BVN, DRIVERS_LICENSE, CAC, TIN, VOTER_ID;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'NG',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'user_id': 'USER_UNIQUE_ID',
    'company': 'COMPANY_NAME', //Include this for CAC
];

$response = $simpleKyc->verifyId($payload);
```

#### CREDEQUITY SERVICE

##### ID Verification

###### Supported ID Types Values:

```javascript
NIN, BVN, DRIVERS_LICENSE;
```

```php
$payload = [
    'id' => '00000000000',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'NG',
    'first_name' => 'KAYODE',
    'last_name' => 'BABATUNDE',
    'date_of_birth' => '24-11-1975',
    'phone_number' => '1234567890',
];

$response = $simpleKyc->verifyId($payload);
```

### For Ghana (GH)

#### SMILE IDENTITY SERVICE

##### ID Verification

Supported ID Types Values:

```php
SSNIT, VOTER_ID, DRIVERS_LICENSE;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'GH',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'user_id' => 'USER_UNIQUE_ID',
];

$response = $simpleKyc->verifyId($payload);
```

#### APPRUVE SERVICE

##### ID Verification

Supported ID Types Values:

```php
SSNIT, TIN, DRIVERS_LICENSE, PASSPORT, VOTER_CARD;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'GH',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'date_of_birth': '24-11-1975',
];

$response = $simpleKyc->verifyId($payload);
```

### For Kenya (KE)

#### SMILE IDENTITY SERVICE

##### ID Verification

Supported ID Types Values:

```php
ALIEN_CARD, NATIONAL_ID, PASSPORT;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'KE',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'user_id' => 'USER_UNIQUE_ID',
];

$response = $simpleKyc->verifyId($payload);
```

#### APPRUVE SERVICE

##### ID Verification

Supported ID Types Values:

```php
NATIONAL_ID, KRA, PASSPORT;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'KE',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'date_of_birth' => "24-11-1975",
];

$response = $simpleKyc->verifyId($payload);
```

_Credequity not supported for Kenya_

### For South Africa (ZA)

#### SMILE IDENTITY SERVICE

##### ID Verification

Supported ID Types Values:

```php
NATIONAL_ID, NATIONAL_ID_NO_PHOTO;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'ZA',
    'first_name' => 'Michael',
    'last_name' => 'Olugbenga',
    'user_id' => "USER_UNIQUE_ID",
];

$response = $simpleKyc->verifyId($payload);
```

_Credequity and Appruve not supported for South Africa_

### For Uganda (UG)

#### APPRUVE SERVICE

##### ID Verification

Supported ID Types Values:

```php
TELCO_SUBSCRIBER;
```

```php
$payload = [
    'id' => '48126406145',
    'id_type' => 'ID_TYPE_VALUE',
    'country' => 'UG',
    'phone_number' => '+256000000003',

];

$response = $simpleKyc->verifyId($payload);
```

_Credequity and Smile not supported for Uganda_

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email abrahamudele@gmail instead of using the issue tracker.

## Credits

- [Abraham Udele](https://github.com/bytesfield) <br/>
  Find me on
  <a href="https://twitter.com/SaintAbrahams/">Twitter.</a>
  <a href="https://www.linkedin.com/in/abraham-udele-246003130/">Linkedin.</a>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
