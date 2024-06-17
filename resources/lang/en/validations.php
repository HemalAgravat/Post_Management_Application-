<?php

return [
    'name' => [
        'required' => 'The name field is required for registration.',
        'string' => 'The name must be a string for registration.',
        'max' => [
            'string' => 'The name may not be greater than :max characters for registration.',
        ],
    ],
    'email' => [
        'required' => 'The email field is required for registration.',
        'email' => 'The email must be a valid email address for registration.',
        'max' => [
            'string' => 'The email may not be greater than :max characters for registration.',
        ],
        'unique' => 'The email has already been taken for registration.',
    ],
    'phone_no' => [
        'required' => 'The phone number field is required for registration.',
        'string' => 'The phone number must be a string for registration.',
        'max' => 'The phone number may not be greater than :max characters for registration.',
    ],
    'password' => [
        'required' => 'The password field is required for registration.',
        'string' => 'The password must be a string for registration.',
        'min' => 'The password must be at least :min characters for registration.',
        'confirmed' => 'The password confirmation does not match for registration.',
    ],
];
