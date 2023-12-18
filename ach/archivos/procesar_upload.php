<?php
error_reporting(E_ALL);

$db = $_REQUEST['DB'];

define('path_Utilidades',   '/opt/lampp/utilidades/');
include_once (path_Utilidades 	. 'php/clases/class_sql.php');


$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". $db .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();


$ID_M_ALUMNOS	 	= $_REQUEST['IDX'];
$NOMBRES	 		= $_REQUEST['NOMBRES'];
$CARPETA	 		= $_REQUEST['CARPETA'];



$recibido = $_FILES["archivo"]["tmp_name"];
$nombre   = $_FILES["archivo"]["name"];
$tam   	  = ceil($_FILES["archivo"]["size"]/1024);
$xruta =  '/opt/lampp/htdocs/ach/'. $CARPETA ;

@mkdir($xruta ,0777,true);
@chmod($xruta ,0777);
$ext	  = strtoupper(substr($_FILES["archivo"]["name"], -3));


if($ext=='PDF')
{
	
	$nombre_archivo = $NOMBRES;
    
	
	$pdf_file = $xruta . '/original_'. $nombre_archivo . '.pdf';
	$jpg_file = $xruta . '/'. $nombre_archivo ;

	move_uploaded_file( $recibido , $pdf_file);	
	//*/etc/ImageMagick-7/policy.xml
    //<policy domain="coder" rights="read | write" pattern="PDF" />  
	$comando = 'convert -density 150 -antialias "'. $pdf_file .'" -resize 1024x -quality 92 -background white -alpha remove "'. $jpg_file .'-%03d.jpg"';
	$resp = system($comando);
	//unlink($pdf_file);	
	//die($ext);

	$imagenes=@glob($jpg_file .'*.jpg');
	if(is_array($imagenes))
	{
		$cantidad = sizeof($imagenes);
	}else
	{
		$cantidad = 0;
	}
	$cantidad++;
	
	$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD=" . $cantidad . " , FECHA_DIGITAL='" . date("Y-m-d") . "' WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
	$query->ejecuta_query();

	if($query->Error)
	{
		echo "ERROR [" . $query->regi['ERROR'] . "]";
		die();
	}
	
}
else
{
	$imagenes=@glob($xruta .'/'. $NOMBRES .'*.jpg');
	if(is_array($imagenes))
	{
		$cantidad = sizeof($imagenes);
	}else
	{
		$cantidad = 0;
	}
	$cantidad++;

	$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD=" . $cantidad . " , FECHA_DIGITAL='" . date("Y-m-d") . "' WHERE ID_D_ARCHIVOS='". $_REQUEST['ID_D_ARCHIVOS'] ."'";
	$query->ejecuta_query();

	if($query->Error)
	{
		echo "ERROR [" . $query->regi['ERROR'] . "]";
		die();
	}

	$xdate = time();
	$secuencia = md5($xdate . $cantidad);
	$nombre_archivo = $NOMBRES  . '_'. $secuencia;

	$archivo_original = $recibido;
	$img_origen     = imagecreatefromjpeg($archivo_original);
    $imginfo        = getimagesize($archivo_original);

    $alto  = $imginfo[1];
    $ancho = $imginfo[0];
	
    $im2 = imagecreatetruecolor($ancho,$alto);
    imagecopyresampled ($im2,   $img_origen, 0, 0, 0, 0, $ancho,   $alto,   $imginfo[0], $imginfo[1]);

    imagejpeg($im2,$xruta . '/'. $nombre_archivo . ".jpg");
    imagedestroy($im2);	
	
}

echo "OK";

?>
