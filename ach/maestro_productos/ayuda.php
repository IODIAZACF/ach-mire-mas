<?php
header('Content-Type: application/json');
$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

$db     = $_REQUEST['db'];
$tipo   = $_REQUEST['tipo'];
$origen = $_REQUEST['origen'];
$objeto = $_REQUEST['objeto'];
$contenido   = $_REQUEST['contenido'];
$contenido_delta  = $_REQUEST['contenido_delta'];

file_put_contents(__DIR__ . '/data.txt', print_r($_REQUEST, true));

/*
$xREG['tabla']['registro'] = utf8_encode( 'respuesta del programa' );
die(  json_encode($xREG) );
*/

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$query2 = new sql();
$query2->DBHost     = "127.0.0.1";
$query2->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query2->DBUser     = "SYSDBA";
$query2->DBPassword = "masterkey";
$query2->Initialize();

$sql  = "SELECT * FROM M_AYUDA WHERE ";

$sql .= "TIPO ='" . $tipo . "' ";
$sql .= "AND ORIGEN='" . $origen . "' ";
$sql .= "AND OBJETO='" . $objeto . "' ";

$query->sql = $sql;

$query->ejecuta_query();

if( $query->next_record() ) {
	if($contenido){
		$sql  = "UPDATE M_AYUDA SET CONTENIDO = '". $contenido . "', CONTENIDO_DELTA = '". $contenido_delta . "' WHERE ID_M_AYUDA = '" . $query->Record['ID_M_AYUDA'] . "'";
		$query2->sql = $sql;
		$query2->ejecuta_query();
		
		file_put_contents(__DIR__ . '/query.txt', print_r($query2, true));
		
		$xREG['tabla']['registro']['XXXXX'] = utf8_encode( 'Guardadooo...!'  );
		$xREG['tabla']['registro']['QUERY'] = utf8_encode( $query2->sql   );
	} else {
		$xREG['tabla']['registro']['CONTENIDO'] = utf8_encode( $query->Record['CONTENIDO'] );
		$xREG['tabla']['registro']['CONTENIDO_DELTA'] = utf8_encode( $query->Record['CONTENIDO_DELTA'] );
		$xREG['tabla']['registro']['QUERY'] = utf8_encode( $sql );
	}
	
} else {

	if($contenido){
		$sql  = "INSERT INTO M_AYUDA (TIPO, ORIGEN, OBJETO, CONTENIDO) VALUES ('" . $tipo . "','" . $origen . "','" . $objeto . "','" . $contenido . "')";
		$query2->sql = $sql;
		$query2->ejecuta_query();
		
		file_put_contents(__DIR__ . '/query.txt', print_r($query2, true));

		$xREG['tabla']['registro']['XXXX'] = utf8_encode( 'Guardadooo...!'  );
		$xREG['tabla']['registro']['QUERY'] = utf8_encode( $query2->sql   );
	
	} else {
		$xREG['tabla']['registro']['CONTENIDO'] = utf8_encode( ''  );
		$xREG['tabla']['registro']['QUERY'] = utf8_encode( $query->sql   );
	}
}

echo  json_encode($xREG);

?>

