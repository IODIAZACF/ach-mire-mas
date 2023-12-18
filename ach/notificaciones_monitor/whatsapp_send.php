#!/usr/bin/php

<?php

include __DIR__  . '/config.php';

require_once __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;

$twilio = new Client(WHATSAPP_TWILIO_SID, WHATSAPP_TWILIO_TOKEN);

$IDENTIFICADOR = $argv[1];
if(isset($argv[2])){
	$xsms['To'] 	= $argv[1];
	$xsms['Body'] 	= $argv[2];
	$xsms['Tipo'] 	= 'MENSAJE';
	$sms = json_encode($xsms);
}else{
	rename(__DIR__ . '/mensajes/whatsapp/pendientes/' . $IDENTIFICADOR, __DIR__ . '/mensajes/whatsapp/error/' . $IDENTIFICADOR);
	$sms = json_decode(file_get_contents(__DIR__ . '/mensajes/whatsapp/error/' . $IDENTIFICADOR));	
}

if($sms->Tipo=='MENSAJE'){
	$message = $twilio->messages
				  ->create("whatsapp:+" . $sms->To,
						   [
							   "from" => "whatsapp:+" . WHATSAPP_TWILIO_ORIGEN,
							   "body" => $sms->Body 
						   ]
				  );
}else{
	$message = $twilio->messages
				  ->create("whatsapp:+" . $sms->To,
						   [
								"mediaUrl" => [$sms->Url],
							   "from" => "whatsapp:+" . WHATSAPP_TWILIO_ORIGEN,
							   "body" => $sms->Body 
						   ]
				  );
	
}
unlink(__DIR__ . '/mensajes/whatsapp/error/' . $IDENTIFICADOR);	
file_put_contents(__DIR__ . '/mensajes/whatsapp/enviados/' . $IDENTIFICADOR , print_r( $message, true));
$resp['estatus'] ='OK';
$resp['id'] =$message->sid;
echo json_encode($resp);
exit(1);

?>