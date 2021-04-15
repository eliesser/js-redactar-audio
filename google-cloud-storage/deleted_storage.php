<?php
echo $_POST["nombre"];
if (isset($_POST["nombre"]))
{
	
require __DIR__ . '/vendor/autoload.php';

define('GAPI_CLIENT_ID', '794679642923-ori9kjv06qm6esqugd98ep9onqie78rv.apps.googleusercontent.com');
define('GAPI_EMAIL_ADDRESS', 'voice-956@voice-268715.iam.gserviceaccount.com');
define('GAPI_API_KEY', 'b6cde6c83a12cfd733bb996a0c36012463fe8f81');
define('GAPI_PROJECT_ID', 'voice-268715');
define('BUCKET_NAME', 'voice190220');

//$file_name		= "audio5403.wav";
//$file_name		= "test2.txt";
//$file_content		= "01101010";

$file_name	 = $_POST["nombre"];
$file_content = file_get_contents($file_name);


//notasecret
$google_cert = new Google_Auth_AssertionCredentials(
  GAPI_EMAIL_ADDRESS,
  [Google_Service_Storage::DEVSTORAGE_FULL_CONTROL],
  file_get_contents(__DIR__ . '/voice-268715-f0ac1f48e3e0.p12')
);
 
$client = new Google_Client();
$client->setAssertionCredentials($google_cert);
$client->setDeveloperKey(GAPI_API_KEY);
$storageService = new Google_Service_Storage($client);

/***
 * Subiendo archivos desde Google Storage (INSERT)
 */

function upload_archivo($storageService,$file_name, $file_content){
	
	try 
	{
		$postbody = array( 
				'name' => $file_name, 
				'data' => $file_content,
				'uploadType' => "media"
				);
		$gsso = new Google_Service_Storage_StorageObject();
		$gsso->setName($file_name);
		$result = $storageService->objects->insert(BUCKET_NAME, $gsso, $postbody);
		
		echo "<pre>";
		print_r($result);
		//foreach ($result as $object)
			//printf("%s\n<br>", $object["mediaLink"]);
		echo "</pre>";
		
		
	}      
	catch (Exception $e)
	{
		print $e->getMessage();
	}
	
}


/***
 * Listando archivos desde Google Storage (GET)
 */

function get_listado($storageService){
$request = $storageService->objects->listObjects(BUCKET_NAME);
echo "Archivos en Google Cloud:<br><br>";
foreach ($request["items"] as $object)
    printf("%s\n<br>", $object["name"].' <a href="'.$object["mediaLink"].'">descargar</a>');
}
	
	
/***
 * Eliminando archivos desde Google Storage (DELETE)
 */	

function delete_archivo($storageService,$file_name){
	try
	{
		$result = $storageService->objects->delete(BUCKET_NAME, $file_name);	
		echo "archivo $file_name eliminado ";
	}
	catch (Google_Service_Exception $e)
	{
		//syslog(LOG_ERR, $e);
		print $e->getMessage();
	}
}

/***
 * Llamando a las funciones
 */	
 
//upload_archivo($storageService, $file_name, $file_content);
//get_listado($storageService);
delete_archivo($storageService,$file_name);

}else
{
	echo  "Error al subir archivo a GoogleCloudStorage";
}