<?php

return [
    'HOSPITAL_NAME' => env('HOSPITAL_NAME'),
    'HOSPITAL_TEL' => env('HOSPITAL_TEL'),
    'HOSPITAL_URL' => env('HOSPITAL_URL'),
    'OP_TIME' => [
        [
            'START' => env('OP_TIME1_START'),
            'END' => env('OP_TIME1_END'),
        ],
        [
            'START' => env('OP_TIME2_START'),
            'END' => env('OP_TIME2_END'),
        ]
    ],
];
