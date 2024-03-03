<?php

return [
    'apikey' => env('OPENAI_APIKEY',''),
    'model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4-0125-preview'),
    'temperature' => env('OPENAI_DEFAULT_TEMPERATURE', 0.7),
    'uri_base' => 'https://api.openai.com/v1/',

    'price' => [
        'currency'=>'dollar',
        'ratio'=>1000,
        'gpt-4-0125-preview' => [
            'input' => 0.01,
            'output' => 0.03,
        ],
        'gpt-4-1106-preview' => [
            'input' => 0.01,
            'output' => 0.03,
        ],
        'gpt-4' => [
            'input' => 0.03,
            'output' => 0.06,
        ],
        'gpt-4-32k' => [
            'input' => 0.06,
            'output' => 0.12,
        ],
    ],
];
