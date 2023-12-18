<?php
header('Content-Type: text/event-stream; charset=utf-8');
header('Cache-Control: no-cache');

system('clear');
system("printf '\033[3J'");

$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

$db = 'sabu';

include_once ($xpath . '/php/clases/class_sql.php');
include_once ($xpath . '/php/clases/class_ini.php');
include_once ($xpath . '/php/clases/class.phpmailer.php');

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


$query2 = new sql();
$query2->DBHost     = "127.0.0.1";
$query2->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query2->DBUser     = "SYSDBA";
$query2->DBPassword = "masterkey";
$query2->Initialize();

$query->sql = "SELECT * FROM CONFIGURACION_WEB ROWS 1 TO 1";
$query->ejecuta_query();
if( $query->next_record() ) {
	$URL = trim($query->Record['URL']);
	$CK  = trim($query->Record['WOOCOMMERCE_VALOR1']);
	$CS  = trim($query->Record['WOOCOMMERCE_VALOR2']);
	
} else {
	echo 'Faltan los datos de configuracion en tabla CONFIGURACION_WEB';
	die();
}


$serverTime = time();

require __DIR__ . '/vendor/autoload.php'; 

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    $URL, 
    $CK, 
    $CS,
    [
        'version' => 'wc/v2',
		'verify_ssl' => false,
		 'timeout' => 400 // curl timeout
    ]
);


$np = 1;
$data = [
	'page' => $np,
    'per_page' => '100',
	'order' => 'asc'
];
$result = $woocommerce->get('orders', $data);
while(count($result)){
	
	echo "   Pagina: " . $np ." Resultado: " . count($result) . "\n";
	file_put_contents(__DIR__ . '/pedidos_pag_'. $np .'.txt', print_r($result, true));	
	
	foreach($result as $id => $reg){
		echo "		PEDIDO: " . $reg->id;
		$sql = "SELECT ID_M_PEDIDOS FROM M_PEDIDOS WHERE REFERENCIA='". $reg->id  ."'";
		$query->sql = $sql;
		$query->ejecuta_query();
		if(!$query->next_record()){
			echo "  NUEVO o NO EXISTE \n";
			continue;
		}	
	
		$ID_M_PEDIDOS = $query->Record['ID_M_PEDIDOS'];	
		$sql = 	"UPDATE M_PEDIDOS SET ";
		$sql.= 	"RECIBE_NOMBRE='". qT($reg->shipping->first_name) . ' ' . qT($reg->shipping->last_name) . "'";
		$sql.= 	",RECIBE_DIRECCION='". qT($reg->shipping->address_1) . ' ' . qT($reg->shipping->address_2) . ' ' . qT($reg->shipping->city) . "'";		
		$sql.= 	",COMENTARIO_PAGO='". qT($reg->payment_method_title) . "'";
		$sql.= 	" WHERE ID_M_PEDIDOS='". $ID_M_PEDIDOS ."'";
		
		$query->sql = $sql;
		$query->ejecuta_query();
		
		if($query->erro_msg!=''){
			print_r($query);
			die();
		}
	}	
	$np++;
	$data = [
		'page' => $np,
		'per_page' => '100',
		'order' => 'asc'
	];
	$result = $woocommerce->get('orders', $data);
}


//file_put_contents(__DIR__ . '/pedidos.txt', print_r($result, true));




function sendMsg($id, $msg) {
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}


function lT($xTime){
	$startTime = $xTime;
	$timeI = strtotime($startTime);
	$LocalDate = date("Y-m-d H:i:s", $timeI);
	return $LocalDate;	
}

function dT($startTime, $endTime){
	$date1 = new DateTime($startTime);
	$date2 = new DateTime($endTime);
	$diff = $date1->diff($date2);

	$duracion = $diff->h < 10 ? '0' . $diff->h : $diff->h;
	$duracion.= $diff->i < 10 ? ':0' . $diff->i : ":".$diff->i;
	$duracion.= $diff->s < 10 ? ':0' . $diff->s : ":".$diff->s;
	$tmp['h'] = $duracion;
	$tmp['o'] = $diff;
	return $tmp;
}


function qT($cadena, $upper = true){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena,  utf8_decode($originales), $modificadas);
    if($upper) $cadena = strtoupper($cadena);
	$cadena = str_replace("'", "''", $cadena);
    return utf8_encode($cadena);
}

?>

