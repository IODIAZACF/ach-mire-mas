<?php

$host = "192.168.9.160";

$mensaje = $_REQUEST['mensaje'];
$numero  = $_REQUEST['numero'];

$operador = substr($numero, 0,4);

switch($operador)
{
	case '0412':
    	$puerto = 4;
        break;
    case '0426':
    case '0416':
    	$puerto = 3;
    	break;
    case '0424':
    case '0414':
    	$puerto = 4;
    	break;
	default:
    	$puerto = 3;

}

/* Se condiciona  segun el destino 0412 = 3 , 0414, 2 etcccc ... */

$url = 'http://'. $host . '/enviar.php?puerto=' . $puerto . '&numero=' . $numero . '&mensaje=' . $mensaje;

header('content-type: text/xml');
header('Expires: Fri, 1 Ene 1980 00:00:00 GMT'); //la pagina expira en fecha pasada
header('Last-Modified: ' . gmdate("D, d M Y H:i:s"));// . ' GMT
header('Cache-Control: no-cache, must-revalidate');//
header('Pragma: no-cache');
echo '<?xml version="1.0"  encoding="iso-8859-1"'.'?'.'>';

echo '<tabla>';
  echo '<registro>'."\n";
  echo '<RESULTADO>';
  readfile($url);
  echo '</RESULTADO>'."\n";
  echo '</registro>'."\n";
echo '</tabla>';

?>