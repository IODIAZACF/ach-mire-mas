<?php

$tipo = isset( $_REQUEST['tipo'] ) ? $_REQUEST['tipo'] : ''; 

include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");

$query 	    = new sql();
$query->sql = "SELECT * FROM D_ARCHIVOS WHERE ID_D_ARCHIVOS='" . $_REQUEST['ID_D_ARCHIVOS'] . "'";
$query->ejecuta_query();
if( $query->next_record() ){
	
	$ancho	 =  isset( $_GET['ancho'] )   ? $_GET['ancho']   : 150 ;
	$alto 	 =  isset( $_GET['alto'] ) 	  ? $_GET['alto'] 	 : 150 ;
	$calidad =  isset( $_GET['calidad'] ) ? $_GET['calidad'] : 80 ;

	convert( $query->Record['RUTA'], $ancho, $alto, $calidad, $tipo ); 
	
}


function convert($sourceImage, $maxWidth, $maxHeight, $quality=80, $tipo){
	
	$im = new imagick( $sourceImage );
	$imageprops = $im->getImageGeometry(); 
	$width = $imageprops['width'];
	$height = $imageprops['height'];
	
	$newWidth	= $width;
	$newHeight 	= $height;
	
	if(isset( $_REQUEST['debug_php'] )){
		echo 'newWidth: ' . $newWidth . PHP_EOL;
		echo 'newHeight:' . $newHeight . PHP_EOL;
		echo '----------------------------' . PHP_EOL;
	}
	
	//verifico el tamaño del contenedor a ver quien tiene el mayor valorel ancho o el alto.
	if( $maxWidth > $maxHeight ){
		//el contenedor es mas ancho que alto
		//verifico si el ancho de la imagen cabe el el contenedor.
		if($newWidth > $maxWidth){
			//no cabe.....ajusto el ancho de la imagen al ancho del contenedor y verifico si cabe el alto.
			$pa = $maxWidth/$newWidth;
			$newWidth  = $maxWidth;			
			$newHeight = $newHeight * $pa;
		}	
		
		if($newHeight > $maxHeight){
			$pa = $maxHeight/$newHeight;
			$newHeight 	= $maxHeight;
			$newWidth 	= $newWidth * $pa;
		}		
		
	}
	else{
		if($newHeight > $maxHeight){
			$pa = $maxHeight/$newHeight;
			$newHeight 	= $maxHeight;
			$newWidth 	= $newWidth * $pa;
		}			
		
		if($newWidth > $maxWidth){
			$pa = $maxWidth/$newWidth;
			$newWidth  = $maxWidth;			
			$newHeight = $newHeight * $pa;
		}	
		
	}		
	
	if(isset( $_REQUEST['debug_php'] )){
		echo 'newWidth: ' . $newWidth . PHP_EOL;
		echo 'newHeight:' . $newHeight . PHP_EOL;
		echo '----------------------------' . PHP_EOL;
	}

	if(isset( $_REQUEST['debug_php'] )){
		echo 'newWidth: ' . $newWidth . PHP_EOL;
		echo 'newHeight:' . $newHeight . PHP_EOL;
	}
	$quality = $quality/100;
	$im->resizeImage( $newWidth, $newHeight, imagick::FILTER_LANCZOS, $quality,  true );

	if( $tipo == 'base64'){
		$file = base64_encode( $im->getImageBlob() );
		header("Content-Type: text/plain; charset=utf8");
		header("Content-Length: " . strlen( $file ) );
		header("Content-Encoding: none");
		header('Accept-Ranges: bytes'."\r\n");
		echo $file;
		
	} else {
		header("Content-Type: image/jpg");
		echo $im->getImageBlob();
	}
	
	
	
	
}

?>