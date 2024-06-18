<?php
return [
    'user' => [
        'register' => 'User registered successfully. Please verify your email.',
        'login' => 'Login successful',
        'logout' => 'Logout successful',
    ],

    'error' => [
        'default' => 'An error occurred!',

        'login' => [
            'invalid_credentials' => 'Invalid credentials',
            'user_not_active' => 'User is not active',
            'email_not_verified' => 'Email not verified'
        ],

        'logout' => [
            'unauthenticated' => 'unauthenticated',
        ],
    ],


    'validation' => [
        'failed' => 'Validation failed.',
    ],

    'post_messages' => [
        'success' => 'Post Created Successfully.',
        'error' => 'Post not Created, something went wrong.',
        'not_found' => 'Posts not available.',
        'post_not_found' => 'Post not found.',
        'fetched' => 'Posts feteched succefully.',
        'update' => 'Post updated successfully.',
        'user_not_owns' => 'User does not owns the post.',

        'post_validation' => [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'array' => 'The :attribute must be an array.',
            'file' => 'The :attribute must be a file.',
            'image' => 'The :attribute must be an image.',
            'in' => 'The selected :attribute is invalid.',
        ],
    ],

    'forgot_password' => [
        'user_not_found' => 'User not found!',
        'email_not_verified' => 'Email not verified !',
        'reset_link_sent' => 'Reset link sent to your email!',
    ],

    'reset_password' => [
        'invalid_token' => 'Invalid Token',
        'expired_token' => 'Token Expired Request Again link.',
        'password_same_as_old' => 'New password cannot be the same as the old password',
        'password_reset_success' => 'Password reset successfully!',
    ],

];
