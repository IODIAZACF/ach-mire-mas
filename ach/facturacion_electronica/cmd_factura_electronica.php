#!/usr/bin/php

<?php

$entorno = php_sapi_name();
/************************** GENERACION DE XML **************************************/
	include_once ( __DIR__ . '/config.php');	
	define('TABLA_DOC'			,'M_DOCUMENTOS');

	if($_REQUEST){
		$xDOC = $_REQUEST['IDX'];	
	}else
	{
		$xDOC = $argv[1];
	}

	@mkdir(Path_Facturas . $xDOC . '/', 0777);
	@chmod(Path_Facturas . $xDOC . '/', 0777);

	@unlink(Path_Facturas . $xDOC . '/factura.xml');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();

	eFACT('<?xml version="1.0" encoding="UTF-8"?>');
	eFACT('<factura id="comprobante" version="2.1.0">');
	eFACT('  <infoTributaria>');
	eFACT('    <ambiente>'. $query->Record['AMBIENTE'] .'</ambiente>');
	eFACT('    <tipoEmision>'. $query->Record['TIPOEMISION'] .'</tipoEmision>');
	eFACT('    <razonSocial>'. htmlspecialchars ($query->Record['RAZONSOCIAL']) .'</razonSocial>');
	eFACT('    <nombreComercial>'. htmlspecialchars ($query->Record['NOMBRECOMERCIAL']) .'</nombreComercial>');
	eFACT('    <ruc>'. $query->Record['RUC'] .'</ruc>');
	eFACT('    <claveAcceso>'. $query->Record['CLAVEACCESO'] .'</claveAcceso>');
	eFACT('    <codDoc>'. $query->Record['CODDOC'] .'</codDoc>');
	eFACT('    <estab>'. $query->Record['ESTAB'] .'</estab>');
	eFACT('    <ptoEmi>'. $query->Record['PTOEMI'] .'</ptoEmi>');
	eFACT('    <secuencial>'. $query->Record['SECUENCIAL'] .'</secuencial>');
	eFACT('    <dirMatriz>'. $query->Record['DIRMATRIZ'] .'</dirMatriz>');
	eFACT('  </infoTributaria>');

	eFACT('  <infoFactura>');
	eFACT('    <fechaEmision>'. MDY($query->Record['FECHAEMISION']) .'</fechaEmision>');
	eFACT('    <dirEstablecimiento>'. $query->Record['DIRESTABLECIMIENTO'] .'</dirEstablecimiento>');
	if( $query->Record['CONTRIBUYENTEESPECIAL']!=''){
		 eFACT('    <contribuyenteEspecial>'. $query->Record['CONTRIBUYENTEESPECIAL'] .'</contribuyenteEspecial>');	
	}
	eFACT('    <obligadoContabilidad>'. $query->Record['OBLIGADOCONTABILIDAD'] .'</obligadoContabilidad>');
	eFACT('    <tipoIdentificacionComprador>'. $query->Record['TIPOIDENTIFICACIONCOMPRADOR'] .'</tipoIdentificacionComprador>');
	eFACT('    <razonSocialComprador>'. htmlspecialchars ($query->Record['RAZONSOCIALCOMPRADOR']) .'</razonSocialComprador>');
	eFACT('    <identificacionComprador>'. $query->Record['IDENTIFICACIONCOMPRADOR'] .'</identificacionComprador>');
	eFACT('    <direccionComprador>'. str_replace("\n", " ", $query->Record['DIRECCIONCOMPRADOR']) .' </direccionComprador>');
	eFACT('    <totalSinImpuestos>'. $query->Record['TOTALSINIMPUESTOS'] .'</totalSinImpuestos>');
	eFACT('    <totalDescuento>'. $query->Record['TOTALDESCUENTO'] .'</totalDescuento>');
	eFACT('    <totalConImpuestos>');

	$iva = new sql();
	$iva->DBHost     = "127.0.0.1";
	$iva->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$iva->DBUser     = "SYSDBA";
	$iva->DBPassword = "masterkey";
	$iva->Initialize();

	$iva->sql = "SELECT * FROM SRI_IMPUESTOS_DOC('". $xDOC ."') WHERE BASEIMPONIBLE>0";
	$iva->ejecuta_query();
	while($iva->next_record())
	{
		eFACT('      <totalImpuesto>');
		eFACT('        <codigo>'. $iva->Record['CODIGO'] .'</codigo>');
		eFACT('        <codigoPorcentaje>'. $iva->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
		eFACT('        <baseImponible>'. $iva->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eFACT('        <valor>'. $iva->Record['VALOR'] .'</valor>');
		eFACT('      </totalImpuesto>');
	}

	eFACT('    </totalConImpuestos>');
	eFACT('    <propina>'. $query->Record['PROPINA'] .'</propina>');
	eFACT('    <importeTotal>'. $query->Record['IMPORTETOTAL'] .'</importeTotal>');
	eFACT('    <moneda>'. $query->Record['MONEDA'] .'</moneda>');

	eFACT('    <pagos>');


	$pagos = new sql();
	$pagos->DBHost     = "127.0.0.1";
	$pagos->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$pagos->DBUser     = "SYSDBA";
	$pagos->DBPassword = "masterkey";
	$pagos->Initialize();


	$pagos->sql = "SELECT * FROM V_D_PAGOS_FACT_ELEC WHERE TABLA='M_DOCUMENTOS' AND IDX ='". $xDOC ."'";
	$pagos->ejecuta_query();
	while($pagos->next_record())
	{
		eFACT('      <pago>');
		eFACT('        <formaPago>'. $pagos->Record['FORMAPAGO'] .'</formaPago>');
		eFACT('        <total>'. $pagos->Record['TOTAL'] .'</total>');
		eFACT('      </pago>');	
	}	
	eFACT('    </pagos>');
	eFACT('  </infoFactura>');
	eFACT('  <detalles>');

	$detalles = new sql();
	$detalles->DBHost     = "127.0.0.1";
	$detalles->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$detalles->DBUser     = "SYSDBA";
	$detalles->DBPassword = "masterkey";
	$detalles->Initialize();

	$detalles->sql = "SELECT * FROM V_D_DOCUMENTOS_FACT_ELEC WHERE  ID_M_DOCUMENTOS ='". $xDOC ."'";
	$detalles->ejecuta_query();
	while($detalles->next_record())
	{
		eFACT('    <detalle>');
		eFACT('      <codigoPrincipal>'. $detalles->Record['CODIGOPRINCIPAL'] .'</codigoPrincipal>');
		eFACT('      <codigoAuxiliar>'. $detalles->Record['CODIGOAUXILIAR'] .'</codigoAuxiliar>');
		eFACT('      <descripcion>'. $detalles->Record['DESCRIPCION'] .'</descripcion>');
		eFACT('      <cantidad>'. $detalles->Record['CANTIDAD'] .'</cantidad>');
		eFACT('      <precioUnitario>'. $detalles->Record['PRECIOUNITARIO'] .'</precioUnitario>');
		eFACT('      <descuento>'. $detalles->Record['DESCUENTO'] .'</descuento>');
		eFACT('      <precioTotalSinImpuesto>'. $detalles->Record['PRECIOTOTALSINIMPUESTO'] .'</precioTotalSinImpuesto>');
		eFACT('      <detallesAdicionales>');
		eFACT('        <detAdicional nombre="Serial" valor="'. $detalles->Record['SERIAL'] .' "></detAdicional>');
		eFACT('      </detallesAdicionales>');
		eFACT('      <impuestos>');
		eFACT('        <impuesto>');
		eFACT('          <codigo>'. $detalles->Record['CODIGO'] .'</codigo>');
		eFACT('          <codigoPorcentaje>'. $detalles->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
		eFACT('          <tarifa>'. $detalles->Record['TARIFA'] .'</tarifa>');
		eFACT('          <baseImponible>'. $detalles->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eFACT('          <valor>'. $detalles->Record['VALOR'] .'</valor>');
		eFACT('        </impuesto>');
		eFACT('      </impuestos>');
		eFACT('    </detalle>');
		
	}
	eFACT('  </detalles>');
	eFACT('  <infoAdicional>');
	eFACT('    <campoAdicional nombre="Email">'. $query->Record['CORREO'] .' </campoAdicional>');
	eFACT('    <campoAdicional nombre="Telefono">'. $query->Record['TELEFONO1'] .' </campoAdicional>');
	eFACT('    <campoAdicional nombre="Direccion">'. str_replace("\n", " ", $query->Record['DIRECCIONCOMPRADOR']) .' </campoAdicional>');
	eFACT('    <campoAdicional nombre="Comentarios">'. str_replace("\n", " ", $query->Record['COMENTARIOS']) .' </campoAdicional>');
	eFACT('  </infoAdicional>');
	eFACT('</factura>');

	chmod(Path_Facturas . $xDOC . '/factura.xml', 0777);
	
	
	
	
	

/************************************************** FIN ***********************************************************/

/************************* FIRMADO DE XML ****************************************************************/
	$config=array(
		'access_key'	=> $query->Record['CLAVEACCESO'],
		'pass_p12'		=> clave_certificado,
		'file_p12'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',		
		'file_to_sign'	=> Path_Facturas . $xDOC .'/factura.xml',
		'file_signed'	=> Path_Facturas . $xDOC .'/factura_firmada.xml',
		'file_autorized'=> Path_Facturas . $xDOC .'/factura_autorizada.xml',
		'wordwrap', 76
	);					
	
	$comando = "java -Dfile.encoding=UTF-8 -jar ";
	$comando.= "/opt/lampp/utilidades/QuijoteLuiFirmador-1.4.jar ";
	$comando.= $config['file_to_sign'] ." ";
	$comando.= dirname($config['file_to_sign']) ." ";
	$comando.= basename($config['file_signed']) ." ";
	$comando.= $config['file_p12'] ." ";
	$comando.= $config['pass_p12'];
	
	exec($comando, $result); 
	if($result[1]!='OK'){
		die('Error al Firmar');
	}
	
	
	$queryu = new sql();
	$queryu->DBHost     = "127.0.0.1";
	$queryu->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$queryu->DBUser     = "SYSDBA";
	$queryu->DBPassword = "masterkey";
	$queryu->Initialize();


// INICIO DE AUTORIZACION DEL SRI
	
	require_once(__DIR__ . '/FirmaXades/sriT.php');
	$echo = $entorno=='cli' ? true : false;
	$xFECHA_ENVIO = date("m-d-Y");
	if($echo) echo 'SRI, Iniciando...' . "\n";	
	$signer=new sriT($config);
		
	// Arg1 true=Links Online, false= Links Offline
	// Arg2 true=Produccion, false=development
	$signer->setAmbiente(false,sri_modo); // IMPORTANTE: setear el metodo a usar y el ambiente, por default viene ONLINE y PRODUCTION
	
	$xsd_validation = $signer->validaXml(); // no lleva argumentos, xq ya los cogio del config
	if($xsd_validation['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  FECHA_ENVIO='". $xFECHA_ENVIO ."', COMENTARIOS ='Error Validando XML: ". $xsd_validation['message'] ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		print_r($xsd_validation);
		die('Error Validando XML ' . $xsd_validation['message']);
		
	}
	if($echo) echo 'XSD Validation-> <b>Correctamente</b>!</br>';
	

	$sri_send = $signer->sendToSri($config['file_signed']);
	if($sri_send['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET COMENTARIOS ='". 'No se pudo Enviar al SRI. '. $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		print_r($sri_send);
		die('No se pudo Enviar al SRI. '. $sri_send['message'].(isset($sri_send['informacionAdicional'])?' - '.$sri_send['informacionAdicional']:'')); // error envio SRI
	}
	if($echo) echo 'SRI Sending ----> Correctamente!' ."\n\n";

	$sri_aut = $signer->autorizarSri(); // no lleva argumentos, xq ya los cogio del config
	if($sri_aut['success']==false){
		$queryu->sql = "UPDATE SRI_DOCUMENTOS SET  COMENTARIOS ='". 'No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'') ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
		$queryu->ejecuta_query();
		print_r($sri_aut);
		die('No se pudo Autorizar en SRI. '. $sri_aut['message'].(isset($sri_aut['informacionAdicional'])?' - '.$sri_aut['informacionAdicional']:'')); // error autorizacion SRI
	}
	$queryu->sql = "UPDATE SRI_DOCUMENTOS SET COMENTARIOS =NULL, FECHA_ENVIO='". $xFECHA_ENVIO ."', SRI_AUTORIZACION='". $sri_aut['numeroAutorizacion'] ."', FECHA_APROBACION='". date("m/d/Y H:i:s", strtotime($sri_aut['fechaAutorizacion'])) ."' WHERE IDX='". $xDOC ."' AND TABLA='". TABLA_DOC ."'";
	$queryu->ejecuta_query();

	echo "OK: -> NA: " . $sri_aut['numeroAutorizacion'] . "\n";
	if($echo) echo "\n\n" . 'Sistemas24 enviado correctamente!.';

    //FIN DE AUTORIZACION DEL SRI	

//  ************************************************** FIN ***********************************************************/
//  ***************** FACTURA HTML Y PDF ************************************/
	

	$Plantilla    = Server_Path . "facturacion_electronica/Recursos/factura.html";	
	$Tpl = new Template($Plantilla);
	
	

	@unlink(Path_Facturas . $xDOC . '/factura.html');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		if($query->Record['CONTRIBUYENTEESPECIAL']!=''){
			$query->Record['CONTRIBUYENTEESPECIAL'] ='<tr><td>CONTRIBUYENTE ESPECIAL:</td><td>'. $query->Record['CONTRIBUYENTEESPECIAL'].'</td></tr>';
		}  
		$Record= xFormat($query);	
		foreach($Record as $campo => $valor)
		{
			$Tpl->__set($campo, htmlspecialchars($valor));	
		}
	}
	
	//$Tpl->SetVar($campo, htmlspecialchars($valor));	
	$const = get_defined_constants(true);
	foreach($const['user'] as $campo => $valor)
	{
		$Tpl->__set($campo, htmlspecialchars($valor));	
	}	
	if(file_exists(Server_Path . 'facturacion_electronica/Recursos/logo_empresa.jpg')){
		$Tpl->__set('logo_empresa', 'logo_empresa');	
	}else{
		$Tpl->__set('logo_empresa', 'empresa_sin_logo');	
	}

	$query->sql = "SELECT * FROM V_D_DOCUMENTOS_FACT_ELEC WHERE  ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);	
		foreach($Record as $campo => $valor){
			$Tpl->__set($campo, htmlspecialchars($valor));	
		}
		$Tpl->block("DETALLES");		
	}
	

	$query->sql = "SELECT * FROM V_D_PAGOS_FACT_ELEC WHERE TABLA='M_DOCUMENTOS' AND IDX ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);	
		foreach($Record as $campo => $valor)
		{
			$Tpl->__set($campo, htmlspecialchars($valor));	
		}
		$Tpl->block("PAGOS");
	}	

	$query->sql = "SELECT * FROM V_RESUMEN_TOTAL_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);
		foreach($Record as $campo => $valor)
		{
			$Tpl->__set($campo, htmlspecialchars($valor));	
		}
	}

	
	$html = $Tpl->parse();
	file_put_contents(Path_Facturas . $xDOC . '/factura.html', $html);
	//echo 'http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/facturas/'. $xDOC .'/factura.html' . "\n";
	$cmd_pdf = 'wkhtmltopdf http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/facturas/'. $xDOC .'/factura.html ' . Path_Facturas . $xDOC . '/factura.pdf';
	system($cmd_pdf);


/****************** FIN ********************************/

/***************** EMAIL PHPMailer ************************************/	

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();
  
	$para = $query->Record['CORREO'];
	if(!sri_modo) $para = 'luisman01@gmail.com';

	
	$ID_D_CORREO = '001'. $query->Next_ID('D_CORREO');
	
	$sql  = "INSERT INTO D_CORREO(";
	$sql .= "ID_D_CORREO";
	$sql .= ",TIPO";
	$sql .= ",INTENTO";
	$sql .= ",REMITENTE";
	$sql .= ",DESTINATARIO";
	$sql .= ",ASUNTO";
	$sql .= ",COMENTARIOS";
	$sql .= ",IDX";
	$sql .= ",TABLA";
	$sql .= ",ORIGEN";
	$sql .= ",ESTATUS";
	$sql .= ") values (";
	$sql .= "'". $ID_D_CORREO ."'";
	$sql .= ",'OUT'";
	$sql .= ",0";
	$sql .= ",'". MAIL_USER ."'";
	$sql .= ",'". $para ."'";
	$sql .= ", '".  $asunto."'";
	$sql .= ",'SRI_DOCUMENTOS'";
	$sql .= ", '". $xDOC ."'";
	$sql .= ", 'M_DOCUMENTOS'";
	$sql .= ", 'factura_electronica'";
	$sql .= ", 'CONFIRMADO'";
	$sql .= ")";

	$query->sql = $sql;
	$query->ejecuta_query();
	
	if($query->erro_msg!=''){
		print_r($query);
		die();
	}
	$xfile = Path_Facturas  . $xDOC .'/factura_autorizada.xml';

	
	
	$sql  = "INSERT INTO D_ARCHIVOS(";
	$sql .= "IDX";
	$sql .= ",TABLA";
	$sql .= ",RUTA";
	$sql .= ") VALUES (";
	$sql .= "'". $ID_D_CORREO ."'";
	$sql .= ",'D_CORREO'";
	$sql .= ",'". $xfile ."'";
	$sql .= ")";
	
	$query->sql = $sql;
	$query->ejecuta_query();	
	
	
	$xfile = Path_Facturas . $xDOC .'/factura.pdf';
	$sql  = "INSERT INTO D_ARCHIVOS(";
	$sql .= "IDX";
	$sql .= ",TABLA";
	$sql .= ",RUTA";
	$sql .= ") VALUES (";
	$sql .= "'". $ID_D_CORREO ."'";
	$sql .= ",'D_CORREO'";
	$sql .= ",'". $xfile ."'";
	$sql .= ")";
	
	$query->sql = $sql;
	$query->ejecuta_query();
	
	
	
	//print_r($query);

/****************** FIN ********************************/

function xD($d,$l){	
	file_put_contents(__DIR__  . "/aaadump.txt",  print_r($d, true));
	echo "\n\n" . $l . "\n\n";	
	die('---FIn----');
}
?>