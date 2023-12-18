<?php
$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

$db = 'demo24';

include_once ($xpath . '/php/clases/class_sql.php');
include_once ($xpath . '/php/clases/class_ini.php');
include_once ($xpath . '/php/clases/class.phpmailer.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$query->sql = "SELECT * FROM CONFIGURACION_WEB ROWS 1 TO 1";
$query->ejecuta_query();
if( $query->next_record() ) {
	$URL = trim($query->Record['URL']);
	$CK  = trim($query->Record['WOOCOMMERCE_VALOR1']);
	$CS  = trim($query->Record['WOOCOMMERCE_VALOR2']);
	
} else {
	echo 'Faltan los datos de configuracion en tabla CONFIGURACION_WEB' . "\n";
	die();
}


require __DIR__ . '/vendor/autoload.php'; 

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    $URL, 
    $CK, 
    $CS,
    [
        'version' => 'wc/v2',
		'verify_ssl' => false
    ]
);




$data = [
    'per_page' => '100'
];


$result = $woocommerce->get('products', $data);
file_put_contents(__DIR__ .'/products_list.json', json_encode($result));
foreach($result as $i => $list){
	echo $list->id . "  ===>  ";
	echo $list->name . "\n";
	echo "------------------------------------------------------------------------------\n";
	$woocommerce->delete('products/' . $list->id  , ['force' => true]);
}

$query->sql = "UPDATE M_PRODUCTOS SET ECOMMERCE_ID = NULL, ECOMMERCE_ACCION = 'INSERT' WHERE TIPO = 'P'";
$query->ejecuta_query();


?>