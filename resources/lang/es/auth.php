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

    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'throttle' => 'Demasiados intentos de inicio de sesión. Por favor intente nuevamente en :seconds segundos.',
    'login' => [
        'toast' => [
            'success' => '¡Has iniciado sesión correctamente!',
            'error' => 'La autenticación ha fallado. Inténtalo de nuevo.'
        ]
    ],

    'password_complexity' => [
        'weak' => 'Esta contraseña es débil y fácil de adivinar. Considere usar una contraseña más segura.'
    ],

    'invalid_credentials' => 'Las credenciales dadas no son válidas.',

    'unauthorized' => 'No tiene autorización para ver este recurso.',

    'logout' => [
        'toast' => [
            'success' => '¡Saliste exitosamente!',
            'error' => 'Cerrar sesión ha fallado. Inténtalo de nuevo.'
        ]
    ]
];
