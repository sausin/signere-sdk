<?php

return [
    'id'            => env('SIGNERE_API_ID', 'id'),
    'primary_key'   => env('SIGNERE_KEY_PRIMARY', 'primary_key'),
    'secondary_key' => env('SIGNERE_KEY_SECONDARY', 'secondary_key'),
    'ping_token'    => env('PINGTOKEN', 'ping_token')
];
