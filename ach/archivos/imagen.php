<?php
$archivo_original = '../' .$_GET['imagen'];
$ancho      	  = $_REQUEST['ancho'] ?  $_REQUEST['ancho'] 	: 400;
$alto      		  = $_REQUEST['alto'] ?  $_REQUEST['alto'] 	: 400;

/*
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

*/
    $xdir           = dirname($archivo_original);
    $img_origen     = imagecreatefromjpeg($archivo_original);
    $imginfo        = getimagesize($archivo_original);

    $alto_imagen  = $imginfo[1];
    $ancho_imagen = $imginfo[0];

    if($alto_imagen <= $alto && $ancho_imagen<=$ancho)
    {
        $alto  = $imginfo[1];
        $ancho = $imginfo[0];
    }
    else
    {
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
    }
    $ppp = 72;

    $im2 = imagecreatetruecolor($ancho,$alto);
    imagecopyresampled ($im2,   $img_origen, 0, 0, 0, 0, $ancho,   $alto,   $imginfo[0], $imginfo[1]);

    imagejpeg($im2);
    imagedestroy($im2);


?>