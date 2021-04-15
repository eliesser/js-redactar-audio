<?php
//$url = 'https://comunitouch.es/pruebas/libro3.mp3';
//$url = 'https://comunitouch.es/pruebas/conversacion1.mp3';
$url = 'http://localhost/speechtotext/speech/test/data/libro3.mp3';
$data = json_decode(file_get_contents('http://api.rest7.com/v1/sound_convert.php?url=' . $url . '&format=wav'));
$nombrear='audio'.date("is").'.wav';

if (@$data->success !== 1)
{
    die('Failed');
}
$wave = file_get_contents($data->file);
file_put_contents($nombrear, $wave);
echo 'Archivo '.$nombrear.' convertido con éxito!';