<?php


namespace App\Services;


use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\ListVoicesRequest;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

class GoogleTTSService
{
    protected $credentials_file_path;
    protected $client;
    protected $price;
    protected $voicesByModelName;
    protected $priceByModelType;
    protected $audioEncoding;

    public function __construct()
    {
        $this->credentials_file_path = config('googletts.credentials_filepath');

        try {
            $this->client = new TextToSpeechClient(['credentials' => json_decode(file_get_contents($this->credentials_file_path), true)]);
            //brings to the service all available voices
            $this->set_voices();
            //brings to the services all prices
            $this->set_prices();
            //config audio encoding
            $this->set_audio_encoding();

        } catch (ValidationException $e) {
            printf($e->getMessage());
        }



    }

    function set_audio_encoding():void{
        //By default, audio encoding will be LINEAR16
        $this->audioEncoding = AudioEncoding::LINEAR16;
        //consider other options from the config
        $audioEncodingConfig = config('googletts.audio_encoding');
        if ($audioEncodingConfig==='MP3') $this->audioEncoding = AudioEncoding::MP3;
        if ($audioEncodingConfig==='ALAW') $this->audioEncoding = AudioEncoding::ALAW;
        if ($audioEncodingConfig==='MULAW') $this->audioEncoding = AudioEncoding::MULAW;
        if ($audioEncodingConfig==='OGG_OPUS') $this->audioEncoding = AudioEncoding::OGG_OPUS;
    }

    function set_voices(): void
    {
        $request = new ListVoicesRequest();

        // Call the API and handle any network failures.
        try {
            /** @var ListVoicesResponse $response */
            $response = $this->client->listVoices($request);
            $voices_collection = json_decode($response->serializeToJsonString(),true)['voices'];

            $this->voicesByModelName = [];
            foreach ($voices_collection as $voice) {
                $this->voicesByModelName[$voice['name']] = $voice;
            }

            //printf('Response data: %s' . PHP_EOL, json_encode( $voices,JSON_PRETTY_PRINT));
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
        }
    }

    public function synthesizeSpeech($text,$modelName,$voicePitch,$speakingRate){

        $input = new SynthesisInput();
        $input->setText($text);
        $voice = (new VoiceSelectionParams())
            ->setName($modelName)
            ->setLanguageCode($this->voicesByModelName[$modelName]['languageCodes'][0]);
            //->setSsmlGender($this->voicesByName[$modelName]['ssmlGender']);

        $audioConfig = (new AudioConfig())
            ->setAudioEncoding($this->audioEncoding)
            ->setPitch($voicePitch)
            ->setSpeakingRate($speakingRate);
        $request = (new SynthesizeSpeechRequest())
            ->setInput($input)
            ->setVoice($voice)
            ->setAudioConfig($audioConfig);

        try {
            $google_tts_response = $this->client->synthesizeSpeech($request);
            file_put_contents('test.mp3', $google_tts_response->getAudioContent());
            printf('Check the file test.mp3 on the root of this project');
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
        }

    }

    public function calculateCost(int $charactersAmount, string $modelName):float{
        $ratio = $this->priceByModelType['ratio'];
        $price = $this->priceByModelType[$this->getModelType($modelName)];
        return $charactersAmount*$price/$ratio;
    }

    public function getModelType(string $modelName):string{
        if (str_contains($modelName,'Standard')) return 'Standard';
        if (str_contains($modelName,'Neural2')) return 'Neural2';
        if (str_contains($modelName,'Studio')) return 'Studio';
        if (str_contains($modelName,'WaveNet')) return 'WaveNet';
        if (str_contains($modelName,'Polyglot')) return 'Polyglot';
        return 'Standard';
    }

    public function TestVoiceAndCost(){
        $text = 'This is a test checking the voice and cost of an Standard model';
        $model_name = 'en-US-Standard-D';
        $this->synthesizeSpeech($text,$model_name,0,1);
        $cost = $this->calculateCost(strlen($text),$model_name);
        printf(PHP_EOL);
        printf('character amount: '.strlen($text).PHP_EOL);
        printf('cost: '.$cost.PHP_EOL);
        printf('currency: '.config ('googletts.price.currency').PHP_EOL);

    }

    public function TestService()
    {
        $input = new SynthesisInput();
        $input->setText('This is a speech generated in google cloud text to speech platform!');
        $voice = (new VoiceSelectionParams())
            ->setLanguageCode('en-US');
        $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3);
        $request = (new SynthesizeSpeechRequest())
            ->setInput($input)
            ->setVoice($voice)
            ->setAudioConfig($audioConfig);

        // Call the API and handle any network failures.
        try {
            $response = $this->client->synthesizeSpeech($request);
            file_put_contents('test.mp3', $response->getAudioContent());
            printf('Check the file test.mp3 on the root of this project');
        } catch (ApiException $ex) {
            printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
        }
    }

    private function set_prices()
    {
        $this->priceByModelType['ratio']=(float)config ('googletts.price.ratio');
        $this->priceByModelType['currency']=config ('googletts.price.currency');
        $this->priceByModelType['Neural2']=config ('googletts.price.Neural2.price');
        $this->priceByModelType['Polyglot']=config ('googletts.price.Polyglot.price');
        $this->priceByModelType['Studio']=config ('googletts.price.Studio.price');
        $this->priceByModelType['Standard']=config ('googletts.price.Standard.price');
        $this->priceByModelType['WaveNet']=config ('googletts.price.WaveNet.price');
    }
}
