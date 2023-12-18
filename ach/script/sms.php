<?php
$host = "192.168.9.160";

$mensaje = getvar('c_MENSAJE_CSS');
$numero  = getvar('c_NUMERO_CSS');

$operador = substr($numero, 0,4);

switch($operador)
{
	case '0412':
    	$puerto = 5;
        break;
    case '0426':
    case '0416':
    	$puerto = 5;
    	break;
    case '0424':
    case '0414':
    	$puerto = 5;
    	break;
	default:
    	$puerto = 5;
}

$mensaje .=  ' SMS:  "' . getsession('CONFIGURACION_NOMBRES') .'"';


$url = 'http://'. $host . '/enviar.php?puerto=' . $puerto . '&numero=' . $numero . '&mensaje=' . $mensaje;
$respuesta = @file_get_contents($url);
if($respuesta=='') $respuesta = '404';

$_POST['c_RESPUESTA_CSS']= $respuesta;
$_GET['c_RESPUESTA_CSS']= $respuesta;
?>