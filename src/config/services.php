<?php

return [
    "smile" => [
        'handler' => 'Smile',
        'api_url' => 'https://testapi.smileidentity.com',
        'api_key' => getenv('SMILE_API_KEY'),
        'partner_id' => getenv('SMILE_PARTNER_ID')
    ],
    "appruve" => [
        'handler' => 'Appruve',
        'api_url' => 'https://api.appruve.co',
        'api_key' => getenv('APPRUVE_API_KEY')
    ],


    "credequity" => [
        'handler' => 'Credequity',
        'api_url' => 'http://102.164.38.38',
        'api_key' => getenv('CREDEQUITY_API_KEY')
    ]

];
