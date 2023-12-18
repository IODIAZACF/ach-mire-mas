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
		if($query->next_record()){
			echo "  YA EXISTE \n";
			continue;
		}	
		echo "  INSERTANTO \n";
	
		$ID_M_PEDIDOS = '001'. $query->Next_ID('M_PEDIDOS');
		$RUC = '';
		$TELEFONO = '';
		if(isset($reg->meta_data)){
			foreach($reg->meta_data as $im => $meta){
				if($meta->key=='_billing_ruc')			$RUC = $meta->value;
				if($meta->key=='_billing_cellphone')	$TELEFONO = $meta->value;
			}
		}
		 
		
		$sql = 	"INSERT INTO M_PEDIDOS (";
		$sql.= 	"ID_M_PEDIDOS";
		$sql.= 	",REFERENCIA";
		$sql.= 	",ID_M_CAJAS";
		$sql.= 	",FECHA_PEDIDO";
		$sql.=	",CODIGO1";
		$sql.=	",NOMBRES";
		$sql.=	",DIRECCION";
		$sql.=	",TELEFONO";
		$sql.=	",CORREO";
		$sql.=	",WOOCOMMERCE_ESTATUS";
		$sql.=	",ESTATUS";
		$sql.=	",ORIGEN"; 
		$sql.=	",MONTO_PEDIDO";
		$sql.=	") VALUES (";
		$sql.=	"'".  $ID_M_PEDIDOS ."'";
		$sql.=	",'". $reg->id ."'";	
		$sql.=	",'". '0011' ."'";
		$sql.=	",'". lT($reg->date_created) ."'";	
		$sql.=	",'". $RUC."'";	
		
		$sql.=	",'". qT($reg->billing->first_name) . ' '. qT($reg->billing->last_name) ."'";
		$sql.=	",'". qT($reg->billing->address_1) . ' '. qT($reg->billing->address_2) ."'";
		$sql.=	",'". $TELEFONO ."'";
		$sql.=	",'".  $reg->billing->email ."'";
		$sql.=	",'".  qT($reg->status) ."'";
		$sql.=	",'".  'CERRADO' ."'";
		$sql.=	",'".  'WWW' ."'";
		$sql.=	",'". $reg->total ."'";	
		$sql.=	")";
		
		$query->sql = $sql;
		$query->ejecuta_query();
		
		if($query->erro_msg!=''){
			print_r($query);
			die();
		}
		
	
		foreach($reg->line_items as $it => $item){
			
			$sql = 	"INSERT INTO D_PEDIDOS (";
			$sql.= 	"ID_M_PEDIDOS";
			$sql.= 	",TIPO_PRODUCTO";
			$sql.= 	",ID_M_PRODUCTOS";
			$sql.=	",DESCRIPCION";
			$sql.=	",CANTIDAD";
			$sql.=	",PRECIO";
			$sql.=	",ID_M_IMPUESTOS";
			//$sql.=	",MONTO_IMPUESTO";
			$sql.=	",MONTO_DESCUENTO";
			$sql.=	",COMENTARIOS";
			$sql.=	") VALUES (";
			$sql.=	"'".  $ID_M_PEDIDOS ."'";
			$sql.=	",'". 'S' ."'";
			$sql.=	",'". 'XXXX' ."'";
			$sql.=	",'". qT($item->name)  ."'";
			$sql.=	",'". $item->quantity ."'";
			$sql.=	",'". $item->price ."'";
			$sql.=	",'". '0012' ."'";
			//$sql.=	",'".  $item->total_tax ."'";
			
			$comentarios ='';
			$MONTO_DESCUENTO = 0;
			if(isset($item->meta_data)){
				foreach($item->meta_data as $im => $meta){
					if($meta->key =='_advanced_woo_discount_item_total_discount'){
						$desc = current($meta->value->cart_discount_details);
						//print_r($meta->value);
						//echo "Pedido: " . $reg->id . "\n";
						//print_r($desc);
						//die('---------------descuento--------------------');
						$MONTO_DESCUENTO = $desc->cart_discount_price;						
					}else{
						if(!is_object( $meta->value )) $comentarios.= qT($meta->key) . ':' . qT($meta->value) . "\n";						
					}
				}
			}
			$sql.=	",'".  $MONTO_DESCUENTO ."'";
			$sql.=	",'".  $comentarios ."'";
			$sql.=	")";
			
			$query->sql = $sql;
			$query->ejecuta_query();
			if($query->erro_msg!=''){
				print_r($query);
				die();
			}
		}

		foreach($reg->fee_lines as $it => $item){
			if($item->total<=0) continue;

			$sql = 	"INSERT INTO D_PEDIDOS (";
			$sql.= 	"ID_M_PEDIDOS";
			$sql.= 	",TIPO_PRODUCTO";
			$sql.= 	",ID_M_PRODUCTOS";
			$sql.=	",DESCRIPCION";
			$sql.=	",CANTIDAD";
			$sql.=	",PRECIO";
			$sql.=	",ID_M_IMPUESTOS";
			$sql.=	",MONTO_IMPUESTO";
			$sql.=	",COMENTARIOS";
			$sql.=	") VALUES (";
			$sql.=	"'".  $ID_M_PEDIDOS ."'";
			$sql.=	",'". 'S' ."'";
			$sql.=	",'". 'XXXX' ."'";
			$sql.=	",'". 'TRANSPORTE'  ."'";
			$sql.=	",'". '1'  ."'";
			$sql.=	",'". $item->total ."'";
			$sql.=	",'". '0014' ."'";
			$sql.=	",'". '0' ."'";
			
			$comentarios ='';
			if(isset($item->meta_data)){
				foreach($item->meta_data as $im => $meta){
					if(!is_object( $meta->value )) $comentarios.= qT($meta->key) . ':' . qT($meta->value) . "\n";
				}
			}
			$sql.=	",'".  $comentarios ."'";						
			$sql.=	")";
			
			$query->sql = $sql;
			$query->ejecuta_query();
			if($query->erro_msg!=''){
				print_r($query);
				die();
			}
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
    return utf8_encode($cadena);
}

?>

