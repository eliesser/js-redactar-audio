<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/speech/README.md
 */

// Include Google Cloud dependendencies using Composer
require_once __DIR__ . '/../vendor/autoload.php';

putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\key_private\voice-268715-b6cde6c83a12.json');

/*
if (count($argv) != 2) {
    return print("Usage: php transcribe_async.php AUDIO_FILE\n");
}
list($_, $audioFile) = $argv;

*/

# [START speech_transcribe_async]
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

$guzzleClient = new \GuzzleHttp\Client(['verify' => false]);

/** Uncomment and populate these variables in your code */
// $audioFile = 'path to an audio file';
//$audioFile = 'gs://voice19022020/conversacion1.mp3';
//$audioFile = __DIR__ . '..\test\data\audio5403.wav';
$audioFile = 'http://localhost/speechtotext/speech/test/data/audio5403.wav';

// change these variables if necessary
$encoding = AudioEncoding::LINEAR16;
//$sampleRateHertz = 32000;
//$languageCode = 'en-US';
$sampleRateHertz = 16000;
$languageCode = 'es-ES';


// get contents of a file into a string
$content = file_get_contents($audioFile);

// set string as audio content
$audio = (new RecognitionAudio())
    ->setContent($content);

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
        printf('Transcript: %s' . PHP_EOL, $transcript);
        printf('Confidence: %s' . PHP_EOL, $confidence);
    }
} else {
    print_r($operation->getError());
}

$client->close();
# [END speech_transcribe_async]
