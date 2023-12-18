<?php
//header('content-type: text/javascript'."\n\n");
define("Server_Path","../../");

include("comun.php");

$modulo = $_GET['modulo'];
$modulo = explode(',',$modulo);
$Estilo = Estilo;
$Path 	= path(1);

echo <<<EOT

var server_path   	= '{$Path}';
var estilo_actual 	= '{$Estilo}';
var t_msg_error 	= new Array();
var t_msg_unico 	= new Array();
var aventanas 		= new Array();
var abmaestros		= new Array();


EOT;



$contador = -1;
while ($modulo [++$contador])
{
	//$ruta = path(_folder)."herramientas/".$modulo[$contador]."/javascript/".$modulo[$contador].".js";
    /*modificado por luis problemas con la authentificacion....*/
    $ruta = Server_Path ."herramientas/".$modulo[$contador]."/javascript/".$modulo[$contador].".js";
    readfile($ruta);
}



?>