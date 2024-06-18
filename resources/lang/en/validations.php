<?php

return [
    'name' => [
        'required' => 'The name field is required.',
        'string' => 'The name must be a string.',
        'max' => [
            'string' => 'The name may not be greater than :max characters.',
        ],
    ],
    'email' => [
        'required' => 'The email field is required.',
        'email' => 'The email must be a valid email address.',
        'max' => [
            'string' => 'The email may not be greater than :max characters.',
        ],
        'unique' => 'The email has already been taken.',
    ],
    'phone_no' => [
        'required' => 'The phone number field is required.',
        'string' => 'The phone number must be a string.',
        'max' => 'The phone number may not be greater than :max characters.',
    ],
    'password' => [
        'required' => 'The password field is required.',
        'string' => 'The password must be a string.',
        'min' => 'The password must be at least :min characters.',
        'confirmed' => 'The password confirmation does not match.',
    ],
];
