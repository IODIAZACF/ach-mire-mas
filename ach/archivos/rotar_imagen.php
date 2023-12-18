<?php
$imagen = '../' .$_GET['imagen'];
$grados = 90;
$imagick = new imagick($imagen);
$imagick->setImageFormat('jpeg');
$imagick->rotateimage(new ImagickPixel('#00000000'), $grados);
$imagick->writeImage($imagen);



?>