<?php

return [
    'adminEmail' => 'chris@mozzler.com.au',
    'mozzler.base' => [
        'email' => [
            'from' => ['noreply@acme.com' => 'No reply'],
            'replyTo' => ['support@acme.com' => 'Support'],
        ]
    ],
    'mozzler.auth' => [
        'user' => [
            'passwordReset' => [
                "invalidToken" => "Your link to reset password has expired",
                "emailMismatch" => "Email address does not match token",
                "successMessage" => "Password successfully reset, please login",
                'redirectUrl' => '/user/login',
                'tokenExpiry' => 60*60,        // 1 hour
            ]
        ]
    ]
];