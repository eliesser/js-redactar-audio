<?php
require __DIR__ . '/vendor/autoload.php';

define('GAPI_CLIENT_ID', '794679642923-ori9kjv06qm6esqugd98ep9onqie78rv.apps.googleusercontent.com');
define('GAPI_EMAIL_ADDRESS', 'voice-956@voice-268715.iam.gserviceaccount.com');
define('GAPI_API_KEY', 'b6cde6c83a12cfd733bb996a0c36012463fe8f81');
define('GAPI_PROJECT_ID', 'voice-268715');
define('BUCKET_NAME', 'voice190220');

//notasecret
$google_cert = new Google_Auth_AssertionCredentials(
  GAPI_EMAIL_ADDRESS,
  [Google_Service_Storage::DEVSTORAGE_FULL_CONTROL],
  file_get_contents(__DIR__ . '/voice-268715-f0ac1f48e3e0.p12')
);
 
$google_client = new Google_Client();
$google_client->setAssertionCredentials($google_cert);
$google_client->setDeveloperKey(GAPI_API_KEY);
 
$gcs = new Google_Service_Storage($google_client);
 
$list = $gcs->buckets->listBuckets(GAPI_PROJECT_ID);
//print_r($list);

$request = $gcs->objects->listObjects(BUCKET_NAME);
/*
echo "<pre>";
print_r($request);
echo "</pre>";
*/

echo "Archivos en Google Cloud:<br><br>";
foreach ($request["items"] as $object)
    printf("%s\n<br>", $object["name"]);
