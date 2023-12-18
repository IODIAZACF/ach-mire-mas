<?	
	header('Content-Type: application/json; charset=utf-8');
	$Path = $_SERVER['DOCUMENT_ROOT'] . explode('/', $_SERVER['SCRIPT_NAME'])[1];
	$debug = 1;
	$json = file_get_contents("php://input");
	if($json=='' && $debug==1){
		//activar debug = 1 y utilizar los parametros que se reciben para validar por consola.
		$xjson['url'] 	= 'https://192.168.1.150/onlyoffice/cache/files/ZG9jdW1lbnRvcy9wb2xpemFzLzAwMTEvZGlnaXRhbGl6YWNpb24vMDAxOS54bHN4P1J3N0JUYTAycXZDNmNyMHY=_2504/output.xlsx/output.xlsx?md5=H5OdD-4d6k9Zscj0EGddfA&expires=1644081602&filename=output.xlsx';
		$xjson['key'] 	= 'ZG9jdW1lbnRvcy9wb2xpemFzLzAwMTEvZGlnaXRhbGl6YWNpb24vMDAxOS54bHN4P1J3N0JUYTAycXZDNmNyMHY=';
		$xjson['status'] ='2';	
		$json = json_encode( $xjson );
	}
	if($json==''){
		$response['error'] = 1;
		echo json_encode ($response);		
	}
	
	$archivo = json_decode( $json );	
	if($debug==1) file_put_contents( __DIR__ . "/info.txt", $json . "\n", FILE_APPEND  );	
	$estatus = $archivo->status;
	
	file_put_contents( __DIR__ . "/info.txt", "estatus-->" .  $estatus . "\n"  , FILE_APPEND  );
	if( $estatus == "2" || $estatus == "6" ) {
		if($debug==1) file_put_contents( __DIR__ . "/info.txt", print_r(json_decode ($json) , true) , FILE_APPEND  );		
		$xKey 			= $archivo->key;
		$xUrl_Archivo 	= $archivo->url;		
		//$Nombre_Archivo = trim(explode("?",base64_decode ($xKey))[0]);
		$Nombre_Archivo = base64_decode ($xKey);		
		$destino = RUTA_SISTEMA . $Nombre_Archivo;
		if($debug==1) file_put_contents( __DIR__ . "/info.txt", "url archivo-->" . $xUrl_Archivo . "\n" , FILE_APPEND  );	
		if($debug==1) file_put_contents( __DIR__ . "/info.txt", 'Nombre_Archivo-->' . $Nombre_Archivo  . "\n" , FILE_APPEND  );			
		if($debug==1) file_put_contents( __DIR__ . "/info.txt", "Name-->" . $destino . "\n" , FILE_APPEND  );			
		
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		
		$fp = fopen($destino, 'wb');		
		$ch = curl_init($xUrl_Archivo);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$resp = curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	$response['error'] = 0;
	echo json_encode ($response);
?>