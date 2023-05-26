<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reset Token Expiration
    |--------------------------------------------------------------------------
    | This option takes password reset expiration value from env file.
    |
    */

    'password_reset_token_expiration' => env('PASSWORD_RESET_TOKEN_EXPIRE', 60),
];
