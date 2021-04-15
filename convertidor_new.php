<?php
//exec("ffmpeg -i inputfile.mp3 -b:a 64k outputfile.mp3");
//exec ("ffmpeg -i reglasdeldinero.mp3 -ab 32k -ac 1 reglasdeldinero_mono.mp3");
//exec ("ffmpeg -i reglasdeldinero.mp3 -ab 64k -ac 1 reglasdeldinero_mono.mp3");
//exec ("ffmpeg -i reglasdeldinero.mp3 -ab 128k -ac 1 reglasdeldinero.mp3");
//exec("ffmpeg -i reglasdeldinero.mp3 -b:a 64k 1 reglasdeldinero2.mp3");

$name=isset($_POST['nombre']) ? $_POST['nombre'] : $_GET['nombre'];
//$name=$_GET['nombre'];
$nombre = explode(".", $name);
$nombre = $nombre[0].'.wav';
// Convertir Mp3 a Wav - Mono 1 channel 16000 rate 
exec("ffmpeg -i $name -acodec pcm_s16le -ac 1 -ar 16000 $nombre");
echo "1-Archivo convertido con éxito";
?>