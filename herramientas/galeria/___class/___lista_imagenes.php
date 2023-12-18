<?php
include('../config.php');

$ruta = $_REQUEST['ruta'];
$xruta = '/opt/lampp/htdocs/' . $ruta ;


$archivo = @glob( $xruta .'/*.jpg');
if(is_array($archivo))
{
    $sec=0;
    for($i=0; $i < sizeof( $archivo ); $i++)
    {
       $image_size = getimagesize( $archivo[$i] );

	   $nombre = basename($archivo[$i]);
       $tmp['location']  = $archivo[$i];
       $tmp['src'] 		 = $ruta . '/' . basename( $archivo[$i]) ;
       $tmp['nombre']	 = $nombre;
       $tmp['id']		 = $sec;
	   $tmp['width']	 = $image_size[0];
	   $tmp['height']	 = $image_size[1];
	   $tmp['thumbnail'] = '/megatech/maestro_requerimientos/gallery/php/get_thumbnail.php?src=' . $ruta . '/' . basename( $archivo[$i]) ;
	   
	   
	   //$tmp['alt']= $sec;
	   
	   $archivos['archivos'][]=$tmp;
	   unset($tmp);
       $sec++;
	}
    
	$archivos['total'] = $sec;
}
else
{
    die();
    unset($tmp);
}
echo  json_encode($archivos);

?>
