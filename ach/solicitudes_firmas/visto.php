<?php
if($_REQUEST['firma']=='') die();
$xpath 		= '/opt/lampp/';
include_once ($xpath . 'utilidades/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/ach.gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize(); 


$query->sql ="UPDATE M_SOLICITUDES_FIRMANTES SET VISTO_FECHA ='". date('Y-m-d') ."', VISTO_HORA='". date("H:i:s") ."', ESTATUS='VISTO' WHERE LLAVE ='". $_REQUEST['firma'] ."' AND VISTO_FECHA IS NULL";
$query->ejecuta_query();

file_put_contents('./__data.txt', print_r($_REQUEST, true));

function CargarPNG($imagen)
{
    /* Intentar abrir */
    $im = @imagecreatefrompng($imagen);

    /* Ver si falló */
    if(!$im)
    {
        /* Crear una imagen en blanco */
        $im  = imagecreatetruecolor(150, 30);
        $fondo = imagecolorallocate($im, 255, 255, 255);
        $ct  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $fondo);

        /* Imprimir un mensaje de error */
        imagestring($im, 1, 5, 5, 'Error cargando ' . $imagen, $ct);
    }

    return $im;
}

header('Content-Type: image/png');

$img = CargarPNG('firma.png');

imagepng($img);
imagedestroy($img);

?>