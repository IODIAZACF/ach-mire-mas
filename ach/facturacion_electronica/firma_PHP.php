<?php
	$queryu = new sql();
	$queryu->DBHost     = "127.0.0.1";
	$queryu->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$queryu->DBUser     = "SYSDBA";
	$queryu->DBPassword = "masterkey";
	$queryu->Initialize();

	
	
	echo "a<<<<<<<<<";
	
	
	require_once(__DIR__ . '/FirmaXades/sriT.php');
	echo "bbbb<<<<<<<<<";
	$echo = true; // solo para ver los procesos

	// NOTA: las funciones estan seccionadas por procesos, osea se debe llevar control del estado del documento,
	// Ejemplo: si se envio pero quedo pendiente la autorizacion solo se tendria que volver a autorizar, etc.

	$xFECHA_ENVIO = date("m-d-Y");


	if($echo) echo 'SRI, Iniciando...' . "\n";

	
	echo "8888888888888888888";
	$signer=new sriT($config);
	echo "99999999999999";
	
	
	//die('--------------firma---------------');
	// Arg1 true=Links Online, false= Links Offline
	// Arg2 true=Produccion, false=development
	$signer->setAmbiente(false,sri_modo); // IMPORTANTE: setear el metodo a usar y el ambiente, por default viene ONLINE y PRODUCTION
	
	/* VALIDACION XML CON XSD */
	//NOTA: Funciona con cualquier documento electronico mientras el XSD exista en la ruta, con el nombre de acuerdo al formato

	//die('************************no enviar***************************');
	$sri_send = $signer->sendToSri($config['file_signed']);
	if($sri_send['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET COMENTARIOS ='". 'No se pudo Enviar al SRI. '. $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		die('No se pudo Enviar al SRI. '. $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'')); // error envio SRI
	}
	if($echo) echo 'SRI Sending ----> Correctamente!' ."\n\n";

	/* AUTORIZACION DEL SRI */
	$sri_aut = $signer->autorizarSri(); // no lleva argumentos, xq ya los cogio del config
	if($sri_aut['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  COMENTARIOS ='". 'No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		die('No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'')); // error autorizacion SRI
	}
	$queryu->sql = "UPDATE SRI_DOCUMENTOS SET COMENTARIOS =NULL, FECHA_ENVIO='". $xFECHA_ENVIO ."', SRI_AUTORIZACION='". $sri_aut['numeroAutorizacion'] ."', FECHA_APROBACION='". date("m/d/Y H:i:s", strtotime($sri_aut['fechaAutorizacion'])) ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
	$queryu->ejecuta_query();

    //print_r($queryu);



	echo "OK: -> NA: " . $sri_aut['numeroAutorizacion'] . "\n";
	if($echo) echo "\n\n" . 'EN Systems SRI, termino correctamente!.';

?>