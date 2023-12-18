<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
require('excel_reader/XLSXReader.php');

$origen = $_REQUEST['origen'];


$ini = new ini();
$ini->origen = RUTA_SISTEMA . $origen ;
$ini->cargar_ini();



//print_r ( $ini );

$file_excel = $ini->seccion('ARCHIVO','ORIGEN'); 
//echo $file_excel;

if(!file_exists( $file_excel ) ){
	
	die('Archivo ' . $file_excel . ' no existe...!' );
	
}

//$data = new Spreadsheet_Excel_Reader($file_excel, false);
$xlsx = new XLSXReader( $file_excel );
$sheetNames = $xlsx->getSheetNames();
//print_r($sheetNames);


$hoja  = $ini->seccion('ARCHIVO','HOJA'); 

$existe = 0;
foreach ( $sheetNames as $sheetName ){

	if( $sheetName == $hoja ) $existe = 1;
	

}

if($existe == 0 ){
	die('No existe la Hoja [' . $hoja . '] en el archivo ..');
	
}

/*print_r(array_values($sheetNames));*/
/*$xhoja  = "a18072021";*/


$sheet = $xlsx->getSheet($hoja);
$xdata = $sheet->getData();
//print_r ( $sheet );

echo 'Hojas del archivo :' .  $xlsx->getSheetCount($hoja) . PHP_EOL;

$tabla = $ini->seccion('TABLA','TABLA');

$query = new sql();


$sql = 'DELETE FROM ' . $tabla;
//$sql .= '   /* Herramienta importar_archivos*/'; 

$query->sql =  $sql;
$query->ejecuta_query(); 


//echo $sheet->getColumnIndex() . PHP_EOL;

$acampos = $ini->secciones("CAMPO");


for( $fila = 6; $fila < sizeof($xdata); $fila++){
	
	//echo $xdata[$fila]["A"] . PHP_EOL;
	
	if( is_array($acampos) ){
		
		unset ( $xcampos );
		unset ( $xvalores );
		
		foreach ( $acampos as $campo ){
			
			$xcampos[] = $campo['CAMPO'];
			$xtipo	   = $campo['TIPO'];
			$letra_columna = $campo['COLUMNA'];
			
			$numero_columna = alphabet_to_number( $letra_columna );
			$numero_columna = $numero_columna -1;

			switch ( $xtipo ) {
				case "C": 
					$xvalores[] = "'" . utf8_decode($xdata[$fila][$numero_columna]) . "'";
				break;
				
				case "N": 
					$xvalores[] = $xdata[$fila][$numero_columna];
				break;
				
				case "D": 
					
					//= $xdata[$fila][$numero_columna] ;
					$unix_timestamp  = $xlsx->toUnixTimeStamp( $xdata[$fila][$numero_columna] ); 
					$xvalores[] = "'" . gmdate("m/d/Y",  (int) $unix_timestamp ) . "'";
					
					
				break;
				
			}
			//echo  $valor . PHP_EOL;
			
		}
		
		
		$sql = 'INSERT INTO ' . $tabla ;
		$sql .= '(' . join("," , $xcampos) .')'. ' VALUES ' . '(' . join("," , $xvalores) . ')';
		//$sql .= '/* Herramienta importar_archivos */';
		echo $sql . PHP_EOL;

		$query->sql =  $sql;
		$query->ejecuta_query(); 
		
	}
	
}

		

function number_to_alphabet($number) {
    $number = intval($number);
    if ($number <= 0) {
       return '';
    }
    $alphabet = '';
    while($number != 0) {
       $p = ($number - 1) % 26;
       $number = intval(($number - $p) / 26);
       $alphabet = chr(65 + $p) . $alphabet;
   }
   return $alphabet;
  }

 function alphabet_to_number($string) {
     $string = strtoupper($string);
     $length = strlen($string);
     $number = 0;
     $level = 1;
     while ($length >= $level ) {
         $char = $string[$length - $level];
         $c = ord($char) - 64;        
         $number += $c * (26 ** ($level-1));
        $level++;
     }
    return $number;
 }



function MDY($fecha){
	$tmp = explode('-', $fecha);
	$fecha = $tmp[2] . '-' . $tmp[1] . '/' .$tmp[0];
	return $fecha;
}



function mayusculas($texto){

	$texto = str_replace('á', 'a', $texto);
	$texto = str_replace('é', 'e', $texto);
	$texto = str_replace('í', 'i', $texto);
	$texto = str_replace('ó', 'o', $texto);
	$texto = str_replace('ú', 'u', $texto);
	$texto = strtoupper($texto);
	$texto = trim($texto);
	return $texto;
}


function limpiar()
{
    $query = new sql(0);
	$sql = "DELETE FROM X_GUIAS_GENERICAS";
	$query->sql = $sql;
	$query->ejecuta_query();

	if(strlen($query->regi['ERROR']))
	{
	    echo $query->regi['ERROR'];
	    die("OCURRIÓ UN ERROR AL INTENTAR BORRAR LOS REGISTROS<br/>\n");
	}

}


?>
