<?php

use Google\Cloud\TextToSpeech\V1\AudioEncoding;

return [
    //Where is your Google credentials json file (full file path)
    'credentials_filepath' => env('GOOGLE_TTS_CREDENTIALS_FILEPATH',''),
    // audio encoding options are LINEAR16, MP3, ALAW, MULAW, OGG_OPUS;
   'audio_encoding'=>'LINEAR16',
    'price' => [
        'currency'=>'dollar',
        'ratio'=>1000000,
        'Neural2' => [
            'price' => 16,
            'unit' => 'characters',
        ],
        'Polyglot' => [
            'price' => 16,
            'unit' => 'characters',
        ],
        'Studio' => [
            'price' => 160,
            'unit' => 'characters',
        ],
        'Standard' => [
            'price' => 4,
            'unit' => 'characters',
        ],
        'WaveNet' => [
            'price' => 16,
            'unit' => 'characters',
        ],
    ],
];
