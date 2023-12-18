<?php

$url = $_REQUEST['url'];
$url = urldecode($url);
$url = str_replace('&amp;','&',$url);

header('content-type: text/html');
header('Expires: Fri, 1 Ene 1980 00:00:00 GMT"); //la pagina expira en fecha pasada');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');


readfile($url);

?>