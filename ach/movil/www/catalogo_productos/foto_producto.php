<?
if(isset($_REQUEST['img'])){
	$data = base64_decode($_REQUEST['img']);
	
	$im = imagecreatefromstring($data);
	
	if ($im !== false) {
		imagejpeg($im, '../../../imagenes/productos/'. $_REQUEST['id_m_productos'] .'.jpg');	
		imagedestroy($im);
		
		echo '{"ESTATUS":"OK", "MENSAJE":"SE SUBIO LA IMAGEN ' . $_REQUEST['id_m_productos'].  '"}';
		
	}
	else {
		echo '{"ESTATUS":"ERROR", "MENSAJE":"Ocurrio un error."}';
	}
}else{
	$img = '../../../imagenes/productos/'. $_REQUEST['id_m_productos'] .'.jpg';
	if(!file_exists($img)) 	$img = '../../../imagenes/productos/0.jpg';
	$im = imagecreatefromjpeg($img);	
	header('Content-Type: image/jpeg');	
	imagejpeg($im);
	imagedestroy($im);	   			
}
?> 