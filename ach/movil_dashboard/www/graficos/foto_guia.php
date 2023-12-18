<?

if(isset($_REQUEST['img'])){

	$data = base64_decode($_REQUEST['img']);
	
	$im = imagecreatefromstring($data);
	
	if ($im !== false) {
		imagejpeg($im, '../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg');	
		imagedestroy($im);
		
		echo '{"ESTATUS":"OK", "MENSAJE":"SE SUBIO LA IMAGEN ' . $_REQUEST['id_m_guias'].  '"}';
		
	}
	else {
		echo '{"ESTATUS":"ERROR", "MENSAJE":"Ocurrio un error."}';
	}
}else{
	
	$img = '../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg';
	
	
	if(!file_exists($img)) 	$img = '../../../imagenes/pruebas_entrega/0.jpg';
	$im = imagecreatefromjpeg($img);	
	header('Content-Type: image/jpeg');	
	imagejpeg($im);
	imagedestroy($im);	   			
}
?> 