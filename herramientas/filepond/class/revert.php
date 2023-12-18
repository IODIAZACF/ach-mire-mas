<?php
define("Verifica_session",false);

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";
include_once(RUTA_HERRAMIENTAS  . "/herramientas/utiles/comun.php");
include_once(RUTA_HERRAMIENTAS  . "/herramientas/sql/class/class_sql.php");
include_once (RUTA_HERRAMIENTAS . "/herramientas/ini/class/class_ini.php"); 
  

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uniqueFileID = $_REQUEST["key"];
    
	function revertImagesFromUploadsLocation () {
		global $uniqueFileID;
		$imgName = null;

		$query = new sql();
		$sql ="SELECT * FROM D_ARCHIVOS WHERE ID_D_ARCHIVOS='". $uniqueFileID ."'";
		$query->sql = $sql;
		$query->ejecuta_query();
		
	
		if ( $query->next_record() ){
			$imgFilePointer = $query->Record['RUTA'];

			$sql ="DELETE FROM D_ARCHIVOS WHERE ID_D_ARCHIVOS='". $uniqueFileID ."'";
			$query->sql = $sql;
			$query->ejecuta_query();


			if (file_exists($imgFilePointer)) {
				$filedeleted = unlink($imgFilePointer);
				return $filedeleted;
			} 
			else {
				return true;
			}
		}  
	}


	$response = [];

	if (revertImagesFromUploadsLocation()) {
		$response["status"] = "success";
		$response["key"] = $uniqueFileID;
		http_response_code(200);
	} 
	else {
		$response["status"] = "error";
		$response["msg"] = "File could not be deleted";
		http_response_code(400);
	}

	header('Content-Type: application/json');
	echo json_encode($response);

	exit();
} 
else {
	exit();
}

?>