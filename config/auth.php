<?php

return [
    'defaults' => [
        'guard' => 'api',
        'password' => 'users'
    ],
    'guards' =>[
        'api' => [
            'driver' => 'passport',
            'provider' => 'users'
        ]
        ],
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => \App\Models\User::class
            ]
        ]    
];
// return [
//     'providers' => [
//         'users' => [
//             'driver' => 'eloquent',
//             'model' => \App\Models\User::class
//         ]
//     ]
// ];