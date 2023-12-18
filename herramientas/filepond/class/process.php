<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, GET, DELETE, POST, HEAD, PATCH');
header('Access-Control-Allow-Headers: content-type, upload-length, upload-offset, upload-name');
header('Access-Control-Expose-Headers: upload-offset');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

define("Verifica_session",false);
define("Usa_Log",false);

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
include_once(RUTA_HERRAMIENTAS  . "/herramientas/utiles/comun.php");
include_once(RUTA_HERRAMIENTAS  . "/herramientas/sql/class/class_sql.php");
include_once (RUTA_HERRAMIENTAS . "/herramientas/ini/class/class_ini.php");
  
$Url_Modulo = '';
$Url_Modulo = isset( $_REQUEST['url_modulo'] ) ? dirname( $_REQUEST['url_modulo'] , 2 ) : '';

if( getvar('origen') ) {
	if($Url_Modulo!=''){
		$origen = Server_Path . $Url_Modulo  . '/' .  getvar('origen');
		if (str_contains( $Url_Modulo, 'herramientas') ) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
		if (str_contains(getvar('origen'), 'maestros/')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
	}else{
		$origen = RUTA_SISTEMA . getvar('origen');
	}
}
  
$ini = new ini( $origen );

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$files = $_FILES["filepond"];
	$imageName = null;
	$id = null;
	
	
	file_put_contents ( __DIR__ . '/log.txt', print_r($_REQUEST, true ) );

	function saveImagesToTempLocation ($uploadedFile, $id) {
		global $imageName;
		global $ini;
		global $structuredFiles;

		if (isset($uploadedFile) && $uploadedFile['error'] === UPLOAD_ERR_OK) {
			$query = new sql();
			$tabla = $ini->variable('TABLA', 'TABLA');
			
			$UPLOAD_DIR  = parseVar ( $ini->variable('SERVIDOR', 'RUTA') );
			
			$unico 	= strtoupper ( md5 ( uniqid() . time() ) ) ;
			
			$TIPO = strtolower ( pathinfo( $_FILES['filepond']['name'][0] , PATHINFO_EXTENSION) );
			$destino = $UPLOAD_DIR . '/' . $unico . '.' . $TIPO;

			$imageName = uploadFile( $uploadedFile, $destino);

			if ($imageName) {
				$campos = $ini->secciones("CAMPO","","N");
				$xcampos = array();
				$xvalues = array();
				foreach( $campos as $campo){
					$xcampos[] = $campo['CAMPO'];
					$xvalues[] = "'" . parseVar ( $campo['VALOR'] ) . "'";
				}

				$xcampos[] = 'NOMBRES';
				$xvalues[] = "'" .  $_FILES['filepond']['name'][0]  . "'";

				$xcampos[] = 'TIPO';
				$xvalues[] = "'" .  $TIPO  . "'";

				$xcampos[] = 'CONTENT';
				$xvalues[] = "'" .  $_FILES['filepond']['type'][0]  . "'";

				$xcampos[] = 'RUTA';
				$xvalues[] = "'" .  $destino  . "'";

				$xcampos[] = 'FECHA_DIGITAL';
				$xvalues[] = "'" .  date('Y-m-d')  . "'";

				$xcampos[] = 'UNICO';
				$xvalues[] = "'" .  $unico  . "'";
				
				if(!isset( $_REQUEST['ID_D_ARCHIVOS']  )){
					$_REQUEST['ID_D_ARCHIVOS']  ='-1';
				}
				
				if( $_REQUEST['ID_D_ARCHIVOS'] != '-1' ){
					$nf = 0;
					$sql = '';
					
					$xcampos[] = 'CANTIDAD';
					$xvalues[] = "CANTIDAD+1";
					
					foreach( $xcampos as $campo){
						if ( $sql !='' ) $sql.=' , ';
						$sql .= $campo . '=' . $xvalues[ $nf ];
						$nf++;
					}
					$sql = " UPDATE D_ARCHIVOS SET ".  $sql . " WHERE ID_D_ARCHIVOS ='". $_REQUEST['ID_D_ARCHIVOS']  . "'";					
				}
				else{
					$xcampos[] = 'CANTIDAD';
					$xvalues[] = "'1'";
					$sql = " INSERT INTO " . $tabla . " (". join( ',', $xcampos )  .") VALUES (". join( ',', $xvalues ) .")";
				}
				
				if ( $tabla!='' ){
					$query->sql = $sql;
					$query->ejecuta_query();
					
					file_put_contents (__DIR__ . '/log.txt', print_r($query, true ), FILE_APPEND );
					
					if ( $query->erro_msg == ''){
						
						$query->sql = "SELECT ID_D_ARCHIVOS FROM D_ARCHIVOS WHERE UNICO='". $unico  ."'";
						$query->ejecuta_query();
						$query->next_record();
						
						$ID_D_ARCHIVOS =  $query->Record['ID_D_ARCHIVOS'];
						
						rename ( $destino,  $UPLOAD_DIR . '/' . $ID_D_ARCHIVOS . '.' . $TIPO );
						
						$query->sql = "UPDATE D_ARCHIVOS SET RUTA = '". $UPLOAD_DIR . '/' . $ID_D_ARCHIVOS . '.' . $TIPO ."' WHERE ID_D_ARCHIVOS='". $ID_D_ARCHIVOS  ."'";
						$query->ejecuta_query();					
					}
				}else{
					$ID_D_ARCHIVOS = 1;
					$structuredFiles[ $id ]['destino'] = $destino;
				}
			}
		}
		
		return $ID_D_ARCHIVOS;

	}

	$structuredFiles = [];
	if (isset($files)) {
		foreach($files["name"] as $filename) {
			$structuredFiles[] = [
				"name" => $filename
			];
		}

		foreach($files["type"] as $index => $filetype) {
			$structuredFiles[$index]["type"] = $filetype;
		}

		foreach($files["tmp_name"] as $index => $file_tmp_name) {
			$structuredFiles[$index]["tmp_name"] = $file_tmp_name;
		}

		foreach($files["error"] as $index => $file_error) {
			$structuredFiles[$index]["error"] = $file_error;
		}

		foreach($files["size"] as $index => $file_size) {
			$structuredFiles[$index]["size"] = $file_size;
		}
	}

	$uniqueImgID = null;
	if (count($structuredFiles)) {
		foreach ($structuredFiles as $id => $structuredFile) {
			$uniqueImgID = saveImagesToTempLocation($structuredFile, $id );
		}
	}

	$response = [];
	if ($uniqueImgID) {
		$response["status"] = "success";
		$response["key"] = $uniqueImgID;
		$response["msg"] = null;
		$response["files"] = json_encode($structuredFiles);

		http_response_code(200);

	} 
	else {
		$response["status"] = "error";
		$response["key"] = null;
		$response["msg"] = "An error occured while uploading file";
		$response["files"] = json_encode($structuredFiles);

		http_response_code(400);

	}

	header('Content-Type: application/json');
	echo json_encode($response);

	exit();

} 
else {
	exit();
}


function uploadFile( $file, $fileDestination) {
	
    $fileName = $file['name'];
    $fileType = $file['type'];
    $fileTempName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileSize = $file['size'];
	
	$maxsize = isset( $_REQUEST['maxsize'] ) ? $_REQUEST['maxsize'] : 0;
	$maxsize = $maxsize * 1024 * 1024 ;

	@mkdir ( dirname ( $fileDestination ) , 0777, true );


	if ($fileError === 0) {
		if( ( $maxsize > 0 ) && ( $fileSize > $maxsize )){
			file_put_contents (__DIR__ . '/log.txt', print_r($file, true ), FILE_APPEND );
			return false; // error: file size too big			
		} 
		
		$fileNewName = basename( $fileDestination ) ;

		move_uploaded_file($fileTempName, $fileDestination);
		
		@chmod( $fileDestination, 0777);

		return $fileNewName;
	
	} else {
		return false; // error: error uploading file
	}	
}
    
function parseVar( $str ){
	
	preg_match_all('/{(.+?)}/', $str, $arr);
	
	foreach ( $arr[1] as $id => $var ){
		$valor = isset($_REQUEST[ $var ]) ? $_REQUEST[ $var ] : ''; 
		$str = preg_replace('/\{'.$var.'\}/', $valor , $str);
		
	}
	return $str;
	
}

?>