<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
define('sri_modo', true); /* false = pruebas ; true = produccion*/

define('local_ip'			, '127.0.0.1');
define('nombre_db'			,'sabu');
define('nombre_programa'	,'sabu');

define('nombre_certificado'	,'linda_simara_beltran_valdiviezo');
define('clave_certificado'	,'Likewei1');
define('prg_firma'			,'PHP'); /* PHP certificado banco cental ; WS para certificado DataSecurity*/

define('MAIL_USER', 		'sabu@notificaciones24.com');
define('MAIL_PASS', 		'424639mail');
define('MAIL_REPLI', 		'sabu@notificaciones24.com');

define('Server_Path',		'/opt/lampp/htdocs/' . nombre_programa . '/');
define('path_Utilidades',   '/opt/lampp/utilidades/');

include_once (path_Utilidades 	. 'php/clases/class_sql.php');
include_once (Server_Path 		. 'facturacion_electronica/Template.php');

define('Path_Facturas', 		Server_Path . 'comprobantes_electronicos/facturas/');
define('Path_Compras',  		Server_Path . 'comprobantes_electronicos/compras/');
define('Path_Retenciones', 		Server_Path . 'comprobantes_electronicos/retenciones/');
define('Path_NotasCreditos', 	Server_Path . 'comprobantes_electronicos/notas_credito/');

@mkdir('/qtmp' , 0700,true);
@chmod('/qtmp' , 0700);

@mkdir(Server_Path , 0777,true);
@chmod(Server_Path , 0777);

@mkdir(Path_Facturas , 0777,true);
@chmod(Path_Facturas , 0777);

@mkdir(Path_Compras  , 0777, true);
@chmod(Path_Compras  , 0777);

@mkdir(Path_Retenciones , 0777,true);
@chmod(Path_Retenciones , 0777);

@mkdir(Path_NotasCreditos , 0777,true);
@chmod(Path_NotasCreditos , 0777);

function eFACT($cont){
	global $xDOC;
	
	$fp = fopen(Path_Facturas . $xDOC . '/factura.xml', 'a');
	fwrite($fp, $cont. "\n");
	fclose($fp);
}

function eRET($cont){
	global $xDOC;
	
	$fp = fopen(Path_Retenciones . $xDOC . '/retencion.xml', 'a');
	fwrite($fp, $cont. "\n");
	fclose($fp);
}

function eNCC($cont){
	global $xDOC;
	
	$fp = fopen(Path_NotasCreditos . $xDOC . '/nota_credito.xml', 'a');
	fwrite($fp, $cont. "\n");
	fclose($fp);
}
function MDY($fecha){
	$t =  explode('-',$fecha);
	$xfecha = $t[2] .'/' . $t[1] . '/' . $t[0];
	return $xfecha;
}

function xFormat($xQuery){
	for($i=0;$i<sizeof($xQuery->arreglo_atributos);$i++)
	{
		$campo    	= $xQuery->arreglo_atributos[$i]["NOMBRE"];
		$valor  	= $xQuery->Record[$campo];
		
		switch ($xQuery->arreglo_atributos[$i]["TIPO"])
		{
			case 'C':
				 break;
			case 'N':
				 $valor = number_format($valor, 2, '.', '');
				 break;
			case 'I':
				 $valor = number_format($valor, 0, '.', '');
				 break;
			case 'D':
				$valor = date("d/m/Y", strtotime($valor));
				break;
			case 'T':
				$valor = substr($valor,8,2).'/'.substr($valor,5,2).'/'. substr($valor,0,4) . substr($valor,10);
				 break;
		}
		$xQuery->Record[$campo] = $valor;
	}
	return  $xQuery->Record;
}


?>