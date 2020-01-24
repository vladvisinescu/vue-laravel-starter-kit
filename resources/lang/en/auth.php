<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'login' => [
        'toast' => [
            'success' => 'You have logged in successfully!',
            'error' => 'Authentication has failed. Please try again.'
        ]
    ],

    'password_complexity' => [
        'weak' => 'This password is weak and easily guessable. Please consider using a stronger password.'
    ],

    'invalid_credentials' => 'The given credentials are invalid.',

    'unauthorized' => 'You are not authorized to view this resource.',

    'forgot_password_text' => 'Let us know of the email address you used to signup for our service and we will send you a message containing instructions to reset your password.',
    'forgot_password_success' => 'If you have an account with us, an email should arrive shortly at the email provided.',

    'logout' => [
        'toast' => [
            'success' => 'You logged out successfully!',
            'error' => 'Log out has failed. Please try again.'
        ]
    ]
];
