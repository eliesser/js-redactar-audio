<?php

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

//putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account-credentials.json');
//putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\key_private\voice-268715-b6cde6c83a12.json');
//putenv('GOOGLE_APPLICATION_CREDENTIALS=D:\www\enterdev\voice\google-api-speechtotext\voice-268715-b6cde6c83a12.json');
putenv('GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/voice/google-api-speechtotext/voice-268715-b6cde6c83a12.json');

# Imports the Google Cloud client library
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;


$guzzleClient = new \GuzzleHttp\Client(['verify' => false]);

# The name of the audio file to transcribe
//$audioFile = __DIR__ . '/test/data/audio32KHz.raw';
//$audioFile = __DIR__ . '/test/data/libro3.mp3';
//$audioFile = __DIR__ . '/test/data/sound.wav';
//$audioFile = __DIR__ . '/test/data/audio32KHz.flac';
//$audioFile = __DIR__ . '/test/data/libro6.flac';
//$audioFile = __DIR__ . '/test/data/conversacion1_corta.mp3';
//$audioFile = __DIR__ . '/test/data/commercial_mono.wav';
//$audioFile = __DIR__ . '/test/data/audio5403.wav'; // Duracion: 2min

//$audioFile = __DIR__ . '/test/data/conversacion1.wav'; // Duracion < 1min
$audioFile = __DIR__ . '/reglasdeldinero_corto.wav'; // Duracion < 1min



# get contents of a file into a string
$content = file_get_contents($audioFile);

# set string as audio content
$audio = (new RecognitionAudio())
    ->setContent($content);

# The audio file's encoding, sample rate and language
$config = new RecognitionConfig([
    'encoding' => AudioEncoding::LINEAR16,
	//'encoding' => AudioEncoding::FLAC,
    //'sample_rate_hertz' => 32000,
	//'sample_rate_hertz' => 48000,
    //'sample_rate_hertz' => 44100,
	'sample_rate_hertz' => 16000,
	//'sample_rate_hertz' => 8000,
	//'language_code' => 'en-US'
	'language_code' => 'es-ES'
]);

# Instantiates a client
$client = new SpeechClient();

# Detects speech in the audio file
$response = $client->recognize($config, $audio);

# Print most likely transcription
foreach ($response->getResults() as $result) {
    $alternatives = $result->getAlternatives();
    $mostLikely = $alternatives[0];
    $transcript = $mostLikely->getTranscript();
    printf('Transcript: %s' . PHP_EOL, $transcript);
}

$client->close();

?>