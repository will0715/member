<?php

return [

    'provider' => 'twilio',
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'sender_number' => env('TWILIO_NUMBER'),
        'messaging_service_sid' => env('TWILIO_SERIVCE_SID')
    ]

];
