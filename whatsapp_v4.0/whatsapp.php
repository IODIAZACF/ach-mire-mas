<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require __DIR__ . '/vendor/autoload.php';
$puerto_inicio 	= 4000;
$config 		= array();

if( file_exists( __DIR__ . '/config.json' ) ) {
	$config =  json_decode( file_get_contents( __DIR__ . '/config.json' ), true );
}

$puerto 		= $_REQUEST['puerto'];
$accion 		= $_REQUEST['accion'];
$identificador 	= $_REQUEST['identificador'];
$recibe_param 	= file_get_contents("php://input");
define('DIR_DATA_DOWNLOAD', dirname(__DIR__,1) . '/whatsapp_download');
//$recibe_param = file_get_contents(__DIR__ .'/recibe.json');

if($recibe_param!=''){
	$param = json_decode($recibe_param, true);
	
	$identificador = $param['identificador'];
	$accion 		= $param['accion'];
	
	if($param['proceso']=='session_open' && $param['estatus']=='Ok'){
		$resp = estatus_dispositivo($identificador, 'VINCULADO', 'SI');
		echo json_encode($resp);
		die();
	}
	if($param['proceso']=='session_close' && $param['estatus']=='Ok'){
		$resp = estatus_dispositivo($identificador,  'DESVINCULADO', 'NO');
		if($resp['estatus']){
			$puerto_asignado = $config['dispositivos'][$identificador]['puerto'];
			$command = 'rm -rf  '. __DIR__ .'/sessions/' . $puerto_asignado;
			exec($command);	
		}		
		echo json_encode($resp);
		die();
	}

	if($param['proceso']=='session_open' && $param['estatus']=='Error'){
		if($param['identificador']!= $param['numero']){
			$resp = estatus_dispositivo($identificador, 'ERROR', 'NO');
			if($resp['estatus']){
				$puerto_asignado = $config['dispositivos'][$identificador]['puerto'];
				$command = 'rm -rf  '. __DIR__ .'/sessions/' . $puerto_asignado;
				exec($command);	
			}
			echo json_encode($resp);
			die();			
		}
		
	}	
}


switch($accion){
	case 'vincular':
		$resp = vincular_dispositivo($identificador);
		echo json_encode($resp);
	break;
	case 'desvincular':
		$resp = desvincular_dispositivo($identificador);
		echo json_encode($resp);
	break;
	case 'qr':
		$resp = send_qr($identificador);
		echo json_encode($resp);
	break;	
	case 'revincular':
		$resp = revincular($identificador);
		echo json_encode($resp);
	break;
	case 'estatus':
		echo json_encode($config);
	break;
	case 'enviar':
		enviar_mensaje ($param);
	break;	
	case 'descargar': 
		descargar_archivo ($_REQUEST);
	break;		
	case 'detener':
		$resp = estatus_servicio($identificador, 'DETENIDO');
		echo json_encode($resp);
	break;
	case 'iniciar':
		$resp = estatus_servicio($identificador, 'CORRIENDO');
		echo json_encode($resp);
	break;	
}

function vincular_dispositivo($identificador){
	global $config, $puerto_inicio;
	$puerto_asignado = $puerto_inicio;
	$existe = false;
	$port = array();
	foreach($config['dispositivos'] as $numero => $dispositivo){
		if($numero == $identificador){
			$existe = true;
			break;
		}else{
			$num = $dispositivo['puerto'];
			$port[$num] = 'usado';
		}		
	}
	if($existe){
		$resultado['estatus']= false;
		$resultado['dispositivo'] = $dispositivo;
		return $resultado;
	}else{
		ksort($port);
		foreach($port as $num=>$estatus){
			if($num == $puerto_asignado){
				$puerto_asignado++;
			}else{
				break;
			}
		}	
		
		mkdir(__DIR__ .'/sessions/' . $puerto_asignado, 0777, true );
		$dispositivo['identificador'] = $identificador;
		$dispositivo['puerto'] = $puerto_asignado;
		$dispositivo['estatus_dispositivo'] = 'CREADO';
		$dispositivo['vinculado'] 			= 'NO';
		$dispositivo['estatus_servicio'] 	= 'CORRIENDO';

		$config['dispositivos'][$identificador] = $dispositivo;
		file_put_contents( __DIR__ . '/config.json', json_encode($config));
		$resultado['estatus']= true;
		$resultado['dispositivo'] = $dispositivo;
		
		start_whatsapp ($identificador, $puerto_asignado);
	}
		
	return $resultado;	
}

function revincular($identificador){
	global $config;
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$puerto_asignado = $config['dispositivos'][$identificador]['puerto'];
		mkdir(__DIR__ .'/sessions/' . $puerto_asignado, 0777, true );
		
		@mkdir( DIR_DATA_DOWNLOAD .'/' . $identificador, 0777, true);
		
		$config['dispositivos'][$identificador]['estatus_dispositivo'] 	= 'READ';
		$config['dispositivos'][$identificador]['vinculado'] 			= 'READ';
		$config['dispositivos'][$identificador]['estatus_servicio'] 	= 'CORRIENDO';

		file_put_contents( __DIR__ . '/config.json', json_encode($config));
		$resultado['estatus']= true;
		$resultado['dispositivo'] = $dispositivo;
		
		start_whatsapp ($identificador, $puerto_asignado);
	}
	else{
		$resultado['estatus']= false;
		$resultado['mensaje']= 'identificador No Existe';
	
	}
		
	return $resultado;	
}

function send_qr($identificador){
	global $config;	
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$puerto_asignado = $config['dispositivos'][$identificador]['puerto'];
		
		$file = __DIR__ .'/sessions/' . $puerto_asignado . '/qr-code.svg';
		if(file_exists( $file) ){
			$resultado['estatus']= true;
			$resultado['estatus_dispositivo']= $config['dispositivos'][$identificador]['estatus_dispositivo'];
			$resultado['data']= base64_encode ( file_get_contents ( $file ) );
		}else{
			$resultado['estatus'] = false;
			$resultado['estatus_dispositivo']= $config['dispositivos'][$identificador]['estatus_dispositivo'];
			$resultado['mensaje'] = 'Generando_QR';
			$resultado['data'] = '';
		}
	}else{
		$resultado['estatus'] = false;
		$resultado['mensaje'] = 'identificador ' . $identificador . ' No Existe';
	}	
	return $resultado;
}


function desvincular_dispositivo($identificador){
	global $config;
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$puerto_asignado = $config['dispositivos'][$identificador]['puerto'];
		unset($config['dispositivos'][$identificador]);
		file_put_contents( __DIR__ . '/config.json', json_encode($config));
		$resultado['estatus']= true;
		$resultado['mensaje']= 'identificador desvinculado';
		$resultado['dispositivo'] = $config['dispositivos'][$identificador];
		
		$res = estatus($puerto_asignado);
		
		if($res['estatus']=='IS_RUN'){
			stop_whatsapp($res['pid'], $port);
		}
		
		$command = 'rm -rf  '. __DIR__ .'/sessions/' . $puerto_asignado;
		exec($command);
		
	}else{
		$resultado['estatus']= false;
		$resultado['mensaje']= 'identificador ' . $identificador . ' No Existe';
	}			
	return $resultado;	
}

function estatus_dispositivo($identificador, $estatus, $vinculado){
	global $config;
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$config['dispositivos'][$identificador]['estatus_dispositivo'] = $estatus;
		$config['dispositivos'][$identificador]['vinculado'] = $vinculado;
		file_put_contents( __DIR__ . '/config.json', json_encode($config));
		
		$resultado['estatus']= true;
		$resultado['mensaje']= 'identificador actualizado con exito';

	}else{
		
		$resultado['estatus']= false;
		$resultado['mensaje']= 'identificador ' . $identificador . ' No Existe';
	}			
	return $resultado;	
}

function estatus_servicio($identificador, $estatus){
	global $config;
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$config['dispositivos'][$identificador]['estatus_servicio'] = $estatus;
		file_put_contents( __DIR__ . '/config.json', json_encode($config));
		
		$resultado['estatus']= true;
		$resultado['mensaje']= 'identificador actualizado con exito';

	}else{
		
		$resultado['estatus']= false;
		$resultado['mensaje']= 'identificador ' . $identificador . ' No Existe';
	}			
	return $resultado;	
}

function estatus($port){	
	$respuesta = array();
	$respuesta['estatus']='NO_RUN';
	
	$command = 'ps -aux|grep "whatsapp" |grep -v grep | awk {\'print $16 "|" $15 "|" $2 "|" $9\'}';
	
	exec($command, $procesos);
	foreach($procesos as $proceso){
		$pID = explode('|',$proceso);
		if($pID[0]==$port && substr($pID[1],-3)=='.ts'){
			$respuesta['estatus']='IS_RUN';
			$respuesta['comando'] = $pID[1];
			$respuesta['pid'] = $pID[2];
			$respuesta['inicio'] = $pID[3];
		}
	}
	return $respuesta;
}

function enviar_mensaje($m){
	global $config;
	
	//print_r ($m);
	
	$identificador = $m['identificador'];
	
	  		/*$resp['code'] = '00000';
			$resp['mensaje'] = $m;
			$resp['estatus'] = 'Error';
			echo json_encode ($resp);
			die();
*/
	
	file_put_contents (__DIR__ .'/recibe.txt', print_r ($m, true), FILE_APPEND);
	
	if( isset( $config['dispositivos'][$identificador] ) ) {
		$puerto = $config['dispositivos'][$identificador]['puerto'];
		//$puerto = $m['puerto'];
		$Url = 'http://127.0.0.1:'. $puerto .'/enviar'; 
		//echo $Url;
		require_once 'HTTP/Request2.php';
		$request = new HTTP_Request2();
		
		$request->setUrl($Url);
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->setHeader('Content-Type:application/json');

		$request->setConfig(array(
			'follow_redirects' => TRUE,
			'ssl_verify_peer'   => FALSE,
			'ssl_verify_host'   => FALSE
		));

		//{"identificador":"17869779348","numero":"593960163222", "mensaje":" { \"location\": { \"degreesLatitude\": \"-0.1904502\", \"degreesLongitude\": \"-78.5073708\" }}", "accion":"enviar"}
		
		$mensaje = '{"numero":"'. $m['numero'] .'","mensaje":'. json_encode ( $m['mensaje'] ) .'}';
		file_put_contents (__DIR__ .'/recibe.txt', PHP_EOL . $mensaje .PHP_EOL, FILE_APPEND);
		$request->setBody($mensaje);	

		try {
		  $response = $request->send();
		  if ($response->getStatus() == 200) {
			echo $response->getBody();
		  }  
		  else {
	  		$resp['code'] = $response->getStatus();
			$resp['mensaje'] = $response->getReasonPhrase();
			$resp['estatus'] = 'Error';
			echo json_encode ($resp);
		  }
		}
		catch(HTTP_Request2_Exception $e) {
			$resp['mensaje'] = $e->getMessage();
			$resp['estatus'] = 'Error';
			echo json_encode ($resp);		  
		}
	}else{
		$resp['mensaje'] = "Dispositivo " . $identificador . ' No existe ';
		$resp['estatus'] = 'Error';
		echo json_encode ($resp);		  
	}
		
}


function descargar_archivo ($m){
	$client = new \GuzzleHttp\Client();
	$request = new \GuzzleHttp\Psr7\Request('GET', $m['url']);
	$promise = $client->sendAsync($request)->then(function ($response) {
		$m = $_REQUEST;

		$tipo['imagen']  	= 'image';
		$tipo['video']  	= 'video';
		$tipo['audio']  	= 'audio';
		$tipo['documento']  = 'document';
		@mkdir( DIR_DATA_DOWNLOAD .'/' ,  0777, true);
		
		$tmpName = md5( $m['mediakey'] . $m['url'] . microtime() );	
		file_put_contents( DIR_DATA_DOWNLOAD .'/' . $tmpName .'.enc', $response->getBody() );
		
		$command = 'python3 ';
		$command.= __DIR__ . '/decrypt.py';	
		$command.= ' -m '. $tipo[ $m['tipo'] ];
		$command.= ' -o '. DIR_DATA_DOWNLOAD .'/' . $tmpName .'.dec';
		$command.= ' -b '. str_replace(' ', '+',  $m['mediakey']);
		$command.= ' '. DIR_DATA_DOWNLOAD .'/' . $tmpName .'.enc';	

		exec ($command, $result);
		
		if($result[0]=='Decrypted (hopefully)'){
			header("Content-Description: File Transfer"); 
			header("Content-Type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename=\"". basename( $m['nombre'] ) ."\""); 

			@readfile ( DIR_DATA_DOWNLOAD .'/' . $tmpName .'.dec');		
			@unlink ( DIR_DATA_DOWNLOAD .'/' . $tmpName .'.enc');
			@unlink ( DIR_DATA_DOWNLOAD .'/' . $tmpName .'.dec');
			die();
		}else{
			print_r ($result);
			echo "Descargado..." . date('H:i:s');
			die();
		}
		
	});

	$promise->wait();	
	
}

function stop_whatsapp($pID, $port){
	$command = 'kill '. $pID;
	exec($command);
	sleep(1);
	$res = estatus($port);
	if($res['estatus']=='NO_RUN'){
		return true;
	}
	return false;	
}

function start_whatsapp($identificador, $port){
	$command = 'nohup node -r ts-node/register '. __DIR__ .'/Example/whatsapp.ts '. $port .' ' . $identificador .' > /dev/null 2>&1 & echo $!';
	//$command = 'nohup node -r ts-node/register /opt/lampp/htdocs/whatsapp/Example/whatsapp.ts '. $port .' ' . $identificador .' > /dev/null 2>&1 & echo $!';
	exec($command);
	sleep(1);
}

?>