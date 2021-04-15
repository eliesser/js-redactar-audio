<?php
if (isset($_FILES["file"]))
{
    $file = $_FILES["file"];
    $name = $file["name"];
	$type = $file["type"];
    $tmp_n = $file["tmp_name"];
    $size = $file["size"];
	//$name = str_replace(' ','_',$name);
	//$nombre = explode(".", $name);
	//$nombre = $nombre[0].'.wav';
    $folder = "";
    $src = $folder.$name;
	
	function renombrar_archivo($nom){
		$extension = substr($nom, -3);
		$fecha = date("His");
		$clave=md5($fecha);
		$nom = 'audio'.$fecha.$clave;
		$nom = substr($nom, 0, 20);
		return $nom.'.'.$extension;
	}

    $name = renombrar_archivo($name);
    
	//Moviendo el archivo al servidor
	//$resultado= @move_uploaded_file($tmp_n, $src);
	$resultado= move_uploaded_file($tmp_n, $name);
	//$resultado = file_put_contents($name, $file);	
	//$resultado = file_put_contents($name, $tmp_n);
	
	if(!empty($resultado)){
		echo $name;  // Enviamos el nuevo nombre al cliente
	}else{
		echo "2-Error al subir archivo de audio";   
	}
}else
{
	echo "3-Error al Cargar audio";
}
?>