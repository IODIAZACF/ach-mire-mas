<?php
	$queryu = new sql();
	$queryu->DBHost     = "127.0.0.1";
	$queryu->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$queryu->DBUser     = "SYSDBA";
	$queryu->DBPassword = "masterkey";
	$queryu->Initialize();

	require_once(Server_Path . 'facturacion_electronica/FirmaXades/FirmaElectronica.php');
	$echo = false;

	// NOTA: las funciones estan seccionadas por procesos, osea se debe llevar control del estado del documento,
	// Ejemplo: si se envio pero quedo pendiente la autorizacion solo se tendria que volver a autorizar, etc.
	//if($echo) echo 'EJEMPLO CON PHP PURO [EN Systems]'. "\n" .'EN Systems SRI, Iniciando...'. "\n" ;
	$xFECHA_ENVIO = date("m-d-Y");

	
	if($echo) echo '<h2>EJEMPLO CON WEB SERVICE GRATUITO [EN Systems]</h2><br/><b>EN Systems SRI, Iniciando...</b><br/><br/>';
	
	$signer=new FirmaElectronica($config);
	// Arg1 true=Links Online, false= Links Offline 
	// Arg2 true=Produccion, false=development
	$signer->setAmbiente(false,sri_modo); // IMPORTANTE: setear el metodo a usar y el ambiente, por default viene ONLINE y PRODUCTION
	
	/* FIRMADO DEL XML */ 
	//NOTA: sendToSign(); usa el WS de firmado gratuito de EN Systems
	$signatured = $signer->sendToSign();
	if($signatured['success']==false)
	{
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  FECHA_ENVIO='". $xFECHA_ENVIO ."', COMENTARIOS ='Error al enviar a WS para firmar: ". $signatured['message'] ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();	
		die('Error al enviar a WS para firmar: ' . $signatured['message']);		
	}		
	if($echo) echo 'XML Signature-> <b>Correctamente</b>!</br>';
	
	/* VALIDACION XML CON XSD */ 
	//NOTA: En el caso de sendToSign(); este bloque no es necesario xq el ws ya pasa la validacion xsd, queda con fines educativos
	//NOTA: Funciona con cualquier documento electronico mientras el XSD exista en la ruta, con el nombre de acuerdo al formato
	$xsd_validation = $signer->validaXml(); // no lleva argumentos, xq ya los cogio del config
	if($xsd_validation['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  FECHA_ENVIO='". $xFECHA_ENVIO ."', COMENTARIOS ='Error Validando XML: ". $xsd_validation['message'] ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		die('Error Validando XML ' . $xsd_validation['message']);
		
	}
	if($echo) echo 'XSD Validation-> <b>Correctamente</b>!</br>';
		
	/* ENVIO AL SRI */
	$sri_send = $signer->sendToSri(); // no lleva argumentos, xq ya los cogio del config
	if($sri_send['success']==false){
		//echo "Error:\n\n";
		//print_r($sri_send);				
		/*
		if(curl_errno()){
			echo $sri_send['message'];
			die('error');
		}
		*/
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET INTENTO = INTENTO+1, FECHA_ENVIO='". $xFECHA_ENVIO ."', COMENTARIOS ='No se pudo Enviar al SRI: ". $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		die('No se pudo Enviar al SRI: '. $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'')); // error envio SRI
	}
	
	/* AUTORIZACION DEL SRI */
	$sri_aut = $signer->autorizarSri(); // no lleva argumentos, xq ya los cogio del config
	if($sri_aut['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  INTENTO = INTENTO+1,  FECHA_ENVIO='". $xFECHA_ENVIO ."', COMENTARIOS ='". 'No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();			
		die('No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'')); // error autorizacion SRI		
	}
	
	$queryu->sql = "UPDATE SRI_DOCUMENTOS SET COMENTARIOS =NULL, FECHA_ENVIO='". $xFECHA_ENVIO ."', SRI_AUTORIZACION='". $sri_aut['numeroAutorizacion'] ."', FECHA_APROBACION='". date("m/d/Y H:i:s", strtotime($sri_aut['fechaAutorizacion'])) ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
	$queryu->ejecuta_query();	
	
	//$queryu->sql = "UPDATE M_DOCUMENTOS SET SRI_AUTORIZACION='". $sri_aut['numeroAutorizacion'] ."', SRI_FECHAAUTORIZA='". date("d/m/Y H:i:s", strtotime($sri_aut['fechaAutorizacion'])) ."' WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	//$queryu->ejecuta_query();

	echo "OK: -> NA: " . $sri_aut['numeroAutorizacion'] . "\n";
	if($echo) echo "\n\n" . 'EN Systems SRI, termino correctamente!.';

?>