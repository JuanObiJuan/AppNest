<?php

namespace App\Console\Commands;

use App\Providers\OpenAIServiceProvider;
use App\Services\OpenAIService;
use Illuminate\Console\Command;

class TestOpenAIService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:openai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the test method for open ai service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $openaiService = app()->make(OpenAIService::class);

        $messages = [];
        $messages = [
            ['role'=>'system','content'=>'You are a helpful assistant.'],
            ['role'=>'user','content'=>'Hi! How are you doing?'],
            ];

        // Call the function
        $body_data = $openaiService->ChatCompletions($messages);

        print_r($body_data);

    }
}
