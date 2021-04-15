<?php



// Include Google Cloud dependendencies using Composer
require_once __DIR__ . '/vendor/autoload.php';

//putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account-credentials.json');
//putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\key_private\voice-268715-b6cde6c83a12.json');
////putenv('GOOGLE_APPLICATION_CREDENTIALS=D:\www\enterdev\voice\google-api-speechtotext\voice-268715-b6cde6c83a12.json');
putenv('GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/voice/google-api-speechtotext/voice-268715-b6cde6c83a12.json');


# [START speech_transcribe_async_gcs]
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

if(isset($_POST["nombre"])){
$file_name	 = $_POST["nombre"];
//$file_name	 = "audio17264493fdf84fa.wav";
$guzzleClient = new \GuzzleHttp\Client(['verify' => false]);

/** Uncomment and populate these variables in your code */
// $uri = 'The Cloud Storage object to transcribe (gs://your-bucket-name/your-object-name)';
//$uri = 'gs://voice19022020/reglasdeldinero.wav';
$uri = 'gs://voice19022020/'.$file_name;

// change these variables if necessary
$encoding = AudioEncoding::LINEAR16;
//$sampleRateHertz = 32000;
//$languageCode = 'en-US';
$sampleRateHertz = 16000;
$languageCode = 'es-ES';

// set string as audio content
$audio = (new RecognitionAudio())
    ->setUri($uri);

// set config
$config = (new RecognitionConfig())
    ->setEncoding($encoding)
    ->setSampleRateHertz($sampleRateHertz)
    ->setLanguageCode($languageCode);

// create the speech client
$client = new SpeechClient();

// create the asyncronous recognize operation
$operation = $client->longRunningRecognize($config, $audio);
$operation->pollUntilComplete();

if ($operation->operationSucceeded()) {
    $response = $operation->getResult();

    // each result is for a consecutive portion of the audio. iterate
    // through them to get the transcripts for the entire audio file.
    foreach ($response->getResults() as $result) {
        $alternatives = $result->getAlternatives();
        $mostLikely = $alternatives[0];
        $transcript = $mostLikely->getTranscript();
        $confidence = $mostLikely->getConfidence();
        //printf('Transcript: %s' . PHP_EOL, $transcript);
        //printf('Confidence: %s' . PHP_EOL, $confidence);
		
		printf('%s' . PHP_EOL, $transcript);
    }
} else {
    print_r($operation->getError());
}

$client->close();
# [END speech_transcribe_async_gcs]


}else
{
	echo  "Error al trasncribir archivo API GoogleSpeechToText";
}


?>
