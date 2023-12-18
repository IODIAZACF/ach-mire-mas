<?
define('path_Utilidades',   '/opt/lampp/utilidades/');
include_once (path_Utilidades 	. 'php/clases/class_sql.php');


if(isset($_REQUEST['img'])){
	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/avante.gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$data = base64_decode($_REQUEST['img']);
	
	$im = imagecreatefromstring($data);
	
	if ($im !== false) {
		imagejpeg($im, '../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg');	
		imagedestroy($im);
		
		convert('../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg', '../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg', 800, 800, 100);
		
		echo '{"ESTATUS":"OK", "MENSAJE":"SE SUBIO LA IMAGEN ' . $_REQUEST['id_m_guias'].  '"}';
		
		$query->sql = "UPDATE M_GUIAS SET FOTO_ENTREGA='". date('Y-m-d H:i:s') ."' WHERE ID_M_GUIAS ='". $_REQUEST['id_m_guias']  ."'";
		$query->ejecuta_query();
		//$query->next_record();
		
		
	}
	else {
		echo '{"ESTATUS":"ERROR", "MENSAJE":"Ocurrio un error."}';
	}
}else{
	
	$img = '../../../imagenes/pruebas_entrega/'. $_REQUEST['id_m_guias'] .'.jpg';
	
	
	if( !file_exists($img) ) 	$img = '../../../imagenes/pruebas_entrega/0.jpg';
	header('Content-Type: image/jpeg');
	readfile($img);


}

function convert($sourceImage, $des, $maxWidth, $maxHeight, $quality=80){
    // Obtain image from given source file.
    if (!$image = @imagecreatefromjpeg($sourceImage))
    {
        return false;
    }

    // Get dimensions of source image.
    list($origWidth, $origHeight) = getimagesize($sourceImage);

    if ($maxWidth == 0)
    {
        $maxWidth  = $origWidth;
    }

    if ($maxHeight == 0)
    {
        $maxHeight = $origHeight;
    }

    // Calculate ratio of desired maximum sizes and original sizes.
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;

    // Ratio used for calculating new image dimensions.
    $ratio = min($widthRatio, $heightRatio);

    // Calculate new image dimensions.
    $newWidth  = (int)$origWidth  * $ratio;
    $newHeight = (int)$origHeight * $ratio;

    // Create final image with new dimensions.
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
	
	//header("Content-Type: image/jpeg");
	
    imagejpeg($newImage, $des, $quality);

    // Free up the memory.
    imagedestroy($image);
    imagedestroy($newImage);
	
} 
?> 