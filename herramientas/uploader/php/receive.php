<?php 


include_once (Server_Path.'herramientas/sql/class/class_sql.php');

// RESPONSE FUNCTION
function verbose($ok=1,$info=""){
	// THROW A 400 ERROR ON FAILURE
	if ($ok==0) { http_response_code(400); }
	die(json_encode(["ok"=>$ok, "info"=>$info]));
}

// INVALID UPLOAD
if (empty($_FILES) || $_FILES['file']['error']) {
	verbose(0, __LINE__ . ": Failed to move uploaded file.");
}

// THE UPLOAD DESITINATION - CHANGE THIS TO YOUR OWN
$filePath = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
if (!file_exists($filePath)) { 
	if (!mkdir($filePath, 0777, true)) {
	verbose(0, __LINE__ . ": Failed to create $filePath");
	}
}

$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

// DEAL WITH CHUNKS
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

if ($out) {
	$in = @fopen($_FILES['file']['tmp_name'], "rb");
	if ($in) {
		while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
	} else {
		verbose(0, __LINE__ . ": Failed to open input stream");
	}
	@fclose($in);
	@fclose($out);
	@unlink($_FILES['file']['tmp_name']);
} else {
	verbose(0, __LINE__ . ": Failed to open output stream");
}

// CHECK IF FILE HAS BEEN UPLOADED
if (!$chunks || $chunk == $chunks - 1) {
	
	//file_put_contents(__DIR__ . '/upload.txt', print_r($_SERVER, true), FILE_APPEND);
	
	/*
	file_put_contents (__DIR__ . '/upload.txt', "{$filePath}.part" ."\n", FILE_APPEND);
	file_put_contents (__DIR__ . '/upload.txt', $filePath ."\n", FILE_APPEND);
	*/	
	file_put_contents(__DIR__ . '/upload.txt', print_r($_REQUEST, true), FILE_APPEND);
	file_put_contents(__DIR__ . '/upload.txt', print_r($_FILES, true), FILE_APPEND);
	
	rename("{$filePath}.part", $filePath);

	if ($_REQUEST["name"] && isset($_REQUEST["folder"])) {
		$folder=$_REQUEST["folder"];
		$filename  = basename($filePath);
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		//$dir = '/' . __DIR__. "/../../../documentos/$folder";
		//@mkdir($dir, 0777, true);

		//--- nombre del archivo destino:
		$Directorio = RUTA_SISTEMA . $_REQUEST['folder'];
		$Nombre_Archivo = isset($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : $_REQUEST["name"];
		$Extension = strtolower(pathinfo($_REQUEST["name"], PATHINFO_EXTENSION));
		$Nombre_Archivo .= '.' . $Extension;
		$Archivo_Destino = $Directorio . '/' . $Nombre_Archivo;
		
		file_put_contents(__DIR__ . '/upload.txt', $Archivo_Destino .PHP_EOL , FILE_APPEND);
		
		if (!file_exists($Directorio)) { 
		  if (!mkdir($Directorio, 0777, true)) {
			verbose(0, __LINE__ . ": Failed to create $Directorio");
		  }
		}

		rename($filePath, $Archivo_Destino);
		chmod($Archivo_Destino,0777);
		if( $_REQUEST['id'] ){
			$query = new sql();
			$query->sql = "UPDATE D_ARCHIVOS SET CANTIDAD='1', FECHA_DIGITAL='". date('Y-m-d') ."', TIPO = '". $Extension ."' WHERE ID_D_ARCHIVOS='" . $_REQUEST['id'] . "'";
			  
			$query->ejecuta_query();
		}
	}
}

verbose(1, "Upload OK $dir/".$_REQUEST["name"].".".$extension.json_encode($_REQUEST));


?>