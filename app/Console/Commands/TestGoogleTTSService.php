<?php

namespace App\Console\Commands;

use App\Services\GoogleTTSService;
use Illuminate\Console\Command;

class TestGoogleTTSService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:googletts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the test method for google tts service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gooolettsService = app()->make(GoogleTTSService::class);

        // Call the function
        $response = $gooolettsService->TestVoiceAndCost();

        print_r($response);

    }
}
