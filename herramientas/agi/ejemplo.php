<?php
include_once("util.php");
echo "<pre>";
$x = enviar('http://10.3.150.1/clinica/herramientas/agi/agi.php?tabla=M_USUARIOS&campos=*');
$registro = xml2array($x,'tabla/registro');

echo $registro[0][NOMBRES] . "\n";
?>