<?php
$archivo_original = '../' .$_GET['imagen'];
$ancho      	  = $_REQUEST['ancho'] ?  $_REQUEST['ancho'] 	: 400;
$alto      		  = $_REQUEST['alto'] ?  $_REQUEST['alto'] 	: 400;


$im = new imagick($archivo_original);
$ancho_imagen = $im->getImageWidth();
$alto_imagen = $im->getImageHeight();
$im->setImageFormat('jpeg');

$rel_ancho = ($ancho_imagen / $ancho);
$rel_alto  = ($alto_imagen / $alto);

if ($rel_ancho > $rel_alto)
{
  $alto  = intval($alto_imagen * $ancho / $ancho_imagen);
}
else
{
  $ancho = intval($ancho_imagen * $alto / $alto_imagen);
}
if($alto>$alto_imagen) $alto = $alto_imagen;



$im->scaleImage($ancho, $alto, true);
header("Content-Type: image/jpg");
echo $im->getImageBlob();


?>