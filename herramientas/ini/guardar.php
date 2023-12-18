<?php

include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");

$Url_Modulo = '';
$Url_Modulo = isset($_REQUEST['url_modulo']) ? dirname($_REQUEST['url_modulo'], 2) : '';

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

$my_ini = new ini($origen);
if($my_ini->error==''){
	if(isset( $_REQUEST['seccion'] )){
		$seccion = $_REQUEST['seccion'];
		$variables = leer_vars("v_");
		foreach($variables as $campo){
			$variable 	= $campo['nombre'];
			$valor 		= $campo['valor'];
			if(isset($my_ini->oconte[$seccion][$variable])){
				$my_ini->oconte[$seccion][$variable] = $valor;
			} 	
		}
		
		$my_ini->oconte[$seccion]['COMENTARIO']  = '"';
		$my_ini->oconte[$seccion]['COMENTARIO'] .= 'Modificado ' . date("d-m-Y H:i:s") . ' - Por: ' . $_SESSION['M_USUARIOS_ID_M_USUARIO'] . ' '.  $_SESSION['M_USUARIOS_NOMBRES'] . ' - Desde: ';
		$my_ini->oconte[$seccion]['COMENTARIO'] .= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];		
		$my_ini->oconte[$seccion]['COMENTARIO'] .= '"';
		
		$new_ini = '';
		foreach($my_ini->oconte as $s => $var){
			$new_ini .= '[' . $s . ']'. PHP_EOL;
			foreach($var as $c=>$v){
				if($c =='ONOMBRE' ) continue;
				switch (trim($c)){
					case 'ROTULO':
					case 'TITULO':
						$v = '"' . $v . '"';
					break;
					default:
						if( str_contains( $v, '__S24_EOL__') ){
							$v = '"' . $v . '"';
						}
				}				
				$new_ini .= $c . '=' . $v . PHP_EOL;
			}
			$new_ini .= PHP_EOL;
		}
		$new_ini 	= str_replace ('__S24_EOL__', "\n", $new_ini);
		$nfile = $origen . '_test';
		file_put_contents ( $nfile . '.ini', $new_ini );		
		$ini_test 	= new ini(  $nfile);
		if($ini_test->error==''){
			unlink ( $nfile . '.ini');
			file_put_contents ( $origen . '.ini', $new_ini );
		}else{
			echo "Error";
			echo $new_ini;
		}
	}
}
/*
if (!function_exists('str_contains')) {
    function str_contains (string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}
*/
?>
