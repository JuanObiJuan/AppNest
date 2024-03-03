<?php

namespace App\Services;

use GuzzleHttp\Client;
use http\Env;

class OpenAIService
{

    protected $client;
    protected $apikey;

    protected $model;
    protected $price;
    protected $temperature;

    public function __construct()
    {
        $this->client = new Client();
        $this->model = config('openai.model');
        $this->apikey = config('openai.apikey');
        $this->temperature = config('openai.temperature');
        $this->price = config('openai.price');
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->apikey,
            ],
        ]);
    }

    public function ChatCompletions($messagesArray)
    {
        $data_request = [];
        $data_request['model'] = $this->model;
        //$data['temperature'] = $this->temperature;
        $data_request['messages'] = $messagesArray;

        $responseTime = 0;
        $response = $this->client->request(
            'POST',
            'chat/completions',
            [
                'json' => $data_request,
                'on_stats' => function (\GuzzleHttp\TransferStats $stats) use(&$responseTime){
                    $responseTime = $stats->getTransferTime();
                }
            ]
        );

        $statusCode = $response->getStatusCode();

        $body_json = $response->getBody();
        $response_array = json_decode($body_json, true);

        //prepare data to respond

        $return_data['message'] = $response_array['choices'][0]['message'];
        $return_data['usage'] = $response_array['usage'];

        //calculate cost
        $currency = config ('openai.price.currency');
        $ratio = config ('openai.price.ratio');
        $prompt_cost = config('openai.price.'.$this->model.'.input',0)*$response_array['usage']['prompt_tokens']/$ratio;
        $completion_cost = config('openai.price.'.$this->model.'.output',0)*$response_array['usage']['completion_tokens']/$ratio;
        $total_cost = $prompt_cost + $completion_cost;

        //return data
        $return_data['cost']['prompt_cost']= $prompt_cost;
        $return_data['cost']['completion_cost']= $completion_cost;
        $return_data['cost']['total_cost']= $total_cost;
        $return_data['cost']['currency']= $currency;
        $return_data['status']= $statusCode;

        return $return_data;
    }
}
