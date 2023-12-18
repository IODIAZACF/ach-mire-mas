<?php
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
$Url_Modulo = '';
$Url_Modulo = isset($_REQUEST['url_modulo']) ? dirname($_REQUEST['url_modulo'],2) : '';
if(getvar('origen')){
	if($Url_Modulo!=''){
		$origen = Server_Path . $Url_Modulo  . '/' .  getvar('origen');
		if (str_contains($Url_Modulo, 'herramientas')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
		if (str_contains(getvar('origen'), 'maestros/')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
	}else{
		$origen = RUTA_SISTEMA . getvar('origen');
	}
}

if (isset($_REQUEST['file']))
{  
  $reporte = file_get_contents ($origen . '.fr3');   
  $reporte = preg_replace("/DataSetName=\"(\w+)(\s+)\"/", 'DataSetName="$1"', $reporte);
  $reporte = preg_replace("/(\w+)(\s+)\.\&\#34\;/", "$1.&#34;", $reporte);
  file_put_contents ($origen .'.fr3', $reporte );

  echo $reporte;
  die();
}

define('IMPRESO_DEBUG', isset($_REQUEST['impresora_debug']) ? true : false );

if(IMPRESO_DEBUG) include (Server_Path . "herramientas/impresora/impresora_verifica.php");
$header  = getvar('header');
$letras  = getvar('letras');

echo "<pre>";
$my_ini = new ini($origen);
echo $my_ini->contenido;
/*echo "<hr>";
preg_match_all('/"([^"]*)"/', $my_ini->contenido, $Match);
foreach($Match[0] as $exp){
	$cadena = preg_replace("/[\n\r]/", " ", $exp);
	$my_ini->contenido = str_replace($exp,$cadena,$my_ini->contenido);
	
}

echo $my_ini->contenido;
*/
echo "<hr>";

print_r ($my_ini);

?>

