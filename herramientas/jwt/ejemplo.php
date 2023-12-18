<?php
define("Server_Path", "../../");
include_once (Server_Path . "herramientas/jwt/class/class_jwt.php");

$jwt = new jwt();


$xREG['tabla']['db']            = 'sek';
$xREG['tabla']['id_m_usuarios'] = '0013';

echo "<pre>";
echo "Arreglo:\n";
print_r($xREG);
echo "Arreglo Codificado:\n";
echo $jwt->encode($xREG) . "\n";


$cadena ='zGhUde11Isi6r5XgeGZIhJeKd5Tf8fvMt0cqFCXpN28bxiN62xmhaEeYOS8MSrJ2FMaLId1ocAxJe7yaIng@JA==';
echo "Cadena decodificado:\n";
print_r($jwt->decode($cadena)) . "\n";

?>
