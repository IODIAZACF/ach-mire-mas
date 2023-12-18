<?php
set_time_limit(0);
include_once('../config.php');
	
$excepciones[]		= $_SERVER['SCRIPT_NAME'];
$db_server[0]['DB'] = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";

$xpath 		= '/opt/lampp/';

include_once ($xpath . 'utilidades/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/seguro.gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();


$Url_Modulo = '';
$Url_Modulo = isset( $_REQUEST['url_modulo'] ) ? dirname( $_REQUEST['url_modulo'] , 2 ) : '';


$excel_file = $_REQUEST['file'];

require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load( $excel_file );
$sheetNames = $spreadsheet->getSheetNames();

$spreadsheet->setActiveSheetIndex(0); 

	
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

$col = array_flip( $sheetData[1]) ;
array_shift( $sheetData );

$iCol = $col['ID_M_SEGUROS_TASAS'];
$ID_M_SEGUROS_TASAS = $sheetData[0][ $iCol ];

$sql = "SELECT ACTUALIZA_PLANTILLA FROM M_SEGUROS_TASAS WHERE ID_M_SEGUROS_TASAS='" . $ID_M_SEGUROS_TASAS ."'";
$query->sql = $sql;
$query->ejecuta_query();
$query->next_record();

if( $query->Record['ACTUALIZA_PLANTILLA'] == '' ){	
	file_put_contents (__DIR__ . '/__data_erro.txt', print_r( $sheetData, true));
	@unlink ( $excel_file );
	die();
}

$sql = "UPDATE TMP_EXCEPCIONES SET UNICO = 'DEL' WHERE ID_M_SEGUROS_TASAS='" . $ID_M_SEGUROS_TASAS ."'";
$query->sql = $sql;
$query->ejecuta_query();

file_put_contents (__DIR__ . '/__data.txt', print_r( $sheetData, true));

array_pop( $col );

foreach( $sheetData as $Row ){
	foreach( $col as $campo => $iCol ){
		$xCAMPO[] = $campo;
		$valor = "'" . utf8_decode ( $Row[ $iCol ] ). "'";
		$xVALOR[] = $valor;
	}	
	
	$sql = "INSERT INTO TMP_EXCEPCIONES (" . join (',', $xCAMPO ) . ") VALUES (" . join (',', $xVALOR ) . ")";
	$query->sql = $sql;
	$query->ejecuta_query();
	file_put_contents ( __DIR__ . '/__data.txt', $sql . PHP_EOL , FILE_APPEND );
	
	if( $query->erro !='' ){
		file_put_contents ( __DIR__ . '/__err.txt', $sql, FILE_APPEND );
		
		$sql = "DELETE FROM TMP_EXCEPCIONES WHERE ID_M_SEGUROS_TASAS='" . $ID_M_SEGUROS_TASAS ."' AND UNICO = NULL";
		$query->sql = $sql;
		$query->ejecuta_query();
		
		$sql = "UPDATE TMP_EXCEPCIONES SET UNICO = NULL WHERE ID_M_SEGUROS_TASAS='" . $ID_M_SEGUROS_TASAS ."' AND UNICO = 'DEL'";
		$query->sql = $sql;
		$query->ejecuta_query();		
		echo "Error";
		die();
	}	

	unset($xCAMPO);
	unset($xVALOR);
}

$sql = "DELETE FROM TMP_EXCEPCIONES WHERE ID_M_SEGUROS_TASAS='" . $ID_M_SEGUROS_TASAS ."' AND UNICO = 'DEL'";
$query->sql = $sql;
$query->ejecuta_query();

$sql = "UPDATE M_SEGUROS_TASAS SET ACTUALIZA_PLANTILLA='TEST' WHERE ID_M_SEGUROS_TASAS = '" . $ID_M_SEGUROS_TASAS ."'";
$query->sql = $sql;
$query->ejecuta_query();		

@unlink ( $excel_file );

?>
