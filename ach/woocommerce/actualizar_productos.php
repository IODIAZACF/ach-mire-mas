<?php
header('Content-Type: text/event-stream; charset=utf-8');
header('Cache-Control: no-cache');

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
		'verify_ssl' => false
    ]
);

$query->sql = "SELECT * FROM V_CONSULTA_PRECIO WHERE ECOMMERCE_WEB = 'SI'";
$query->ejecuta_query();

while( $query->next_record() ){

	$ID_M_PRODUCTOS = $query->Record['ID_M_PRODUCTOS'];
	$xText =  'Producto ' . $ID_M_PRODUCTOS . " ===> " . utf8_encode($query->Record['ECOMMERCE_DESCRIPCION']);
	//sendMsg($serverTime, $xText);
	
	$data = [
		'name' => utf8_encode($query->Record['ECOMMERCE_DESCRIPCION']),
		'type' => 'simple',
		'regular_price' =>     $query->Record['PRECIO1'],
		'description' =>       utf8_encode($query->Record['PREPARACION']),
		'short_description' => utf8_encode($query->Record['ECOMMERCE_DESCRIPCION']),
		'categories' => [
			[
				'id' => 1
			],
		],
		'images' => [
			[
				'src' => "http://192.168.1.150/demo24/imagenes/productos/" . $query->Record['ID_M_PRODUCTOS'] . ".jpg" 
			]
		]
	];

	//print_r($data);
	if( $query->Record['ECOMMERCE_ACCION'] == 'UPDATE') {
		
		if( !$query->Record['ECOMMERCE_ID']  ){
			
			$xText .=  '==> Insertando ..!';
			sendMsg($serverTime, $xText);
			
			$result = $woocommerce->post('products', $data);
			if( $result->id ) {
				$query2->sql = "UPDATE M_PRODUCTOS SET ECOMMERCE_ACCION = 'NONE', ECOMMERCE_ID='" . $result->id . "' WHERE ID_M_PRODUCTOS = '" . $ID_M_PRODUCTOS ."'" ;
				$query2->ejecuta_query();
			}
		} else {
			
			$xText .= ' ==> Actualizando ..! ';
			sendMsg($serverTime, $xText);

			$result = $woocommerce->post('products/' . $query->Record['ECOMMERCE_ID'] , $data);

			$xText = $result->id;
			sendMsg($serverTime, $xText);

			if( $result->id ) {
				$query2->sql = "UPDATE M_PRODUCTOS SET ECOMMERCE_ACCION = 'NONE', ECOMMERCE_ID='" . $result->id . "' WHERE ID_M_PRODUCTOS = '" . $ID_M_PRODUCTOS ."'" ;
				$query2->ejecuta_query();
			}
			
			
		}
	}

}

$xText = '--fin--';

sendMsg($serverTime, $xText);

function sendMsg($id, $msg) {
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}

function envio_mail($para, $asunto ,$texto){

    $mail  = new PHPMailer();

    $body="";

	$mail->IsSMTP();                            // telling the class to use SMTP
	$mail->Host       =  "smtp.1and1.com";  	// SMTP server
	$mail->SMTPDebug  = 0;                      // enables SMTP debug information (for testing)
	                                            // 1 = errors and messages
	                                            // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
	$mail->Username   = "info@milarooftop.com";  // GMAIL username
	$mail->Password   = "424639Mail*";          // GMAIL password

	$mail->SetFrom('info@milarooftop.com', 'Mila Rooftop');
	//$mail->AddReplyTo("no-responder@sistemas24.com","First");
    $mail->Subject    = $asunto;
	$mail->AltBody    = ''; // optional, comment out and test
	$mail->MsgHTML($texto);

	$mail->AddAddress($para);
	//if(strlen($argv[3]))  $mail->AddAttachment($argv[3]);      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

    if(!$mail->Send())
    {
	  xlog("Email Error: " . $mail->ErrorInfo);
	}
    else
    {
	  xlog("Email enviado a :" . $para);
	}
}


?>

