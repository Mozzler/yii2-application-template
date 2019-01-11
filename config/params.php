<?php

return [
    'adminEmail' => 'chris@mozzler.com.au',

    // -- These are used by the ApiExceptions
    'messages' => [

        // -- Defaults
        'error-default-api-exception' => [
            'message' => 'Unknown system error', // The default ApiException
            'code' => 500, // 500 System Error
        ],
        'error-default-viterra-api-exception' => [
            'message' => 'System error', // The default ApiException
            'code' => 502, // 502 Bad Gateway
        ],

        // -- Specific to UMS
        'error-no-response-from-viterra-ums' => [
            'message' => 'The authentication system is currently unavailable', // E.g During /v1/device/userLogin doing a call to /token could be an empty / timed out response
            'code' => 503 // 503  Service (temporarily) Unavailable
        ],
        'error-invalid-ums-credentials' => [
            'message' => 'Invalid Username or Password', // E.g During /v1/device/userLogin doing a call to /token if the user or password provided was invalid
            'code' => 401 // 401 Unauthorized
        ],
    ]

];
