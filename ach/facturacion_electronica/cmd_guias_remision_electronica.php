#!/usr/bin/php

<?php
/************************** GENERACION DE XML **************************************/
	include_once ( __DIR__ . '/config.php');	
	define('TABLA_DOC'			,'M_DOCUMENTOS');

	if($_REQUEST){
		$xDOC = $_REQUEST['IDX'];	
	}else
	{
		$xDOC = $argv[1];
	}

	@mkdir(Path_Guias_Remision . $xDOC . '/', 0777);
	@chmod(Path_Guias_Remision . $xDOC . '/', 0777);

	@unlink(Path_Guias_Remision . $xDOC . '/guia_remision.xml');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	//die($query->sql);
	$query->ejecuta_query();
	$query->next_record();

	eGUIA('<?xml version="1.0" encoding="UTF-8"?>');
	eGUIA('<guiaRemision id="comprobante" version="1.1.0">');
	eGUIA('  <infoTributaria>');
	eGUIA('    <ambiente>'. $query->Record['AMBIENTE'] .'</ambiente>');
	eGUIA('    <tipoEmision>'. $query->Record['TIPOEMISION'] .'</tipoEmision>');
	eGUIA('    <razonSocial>'. htmlspecialchars ($query->Record['RAZONSOCIAL']) .'</razonSocial>');
	eGUIA('    <nombreComercial>'. htmlspecialchars ($query->Record['NOMBRECOMERCIAL']) .'</nombreComercial>');
	eGUIA('    <ruc>'. $query->Record['RUC'] .'</ruc>');
	eGUIA('    <claveAcceso>'. $query->Record['CLAVEACCESO'] .'</claveAcceso>');
	eGUIA('    <codDoc>'. $query->Record['CODDOC'] .'</codDoc>');
	eGUIA('    <estab>'. $query->Record['ESTAB'] .'</estab>');
	eGUIA('    <ptoEmi>'. $query->Record['PTOEMI'] .'</ptoEmi>');
	eGUIA('    <secuencial>'. $query->Record['SECUENCIAL'] .'</secuencial>');
	eGUIA('    <dirMatriz>'. $query->Record['DIRMATRIZ'] .'</dirMatriz>');
	eGUIA('  </infoTributaria>');

	eGUIA('  <infoGuiaRemision>');
	//eGUIA('    <fechaEmision>'. MDY($query->Record['FECHAEMISION']) .'</fechaEmision>');
	eGUIA('    <dirEstablecimiento>'. $query->Record['DIRESTABLECIMIENTO'] .'</dirEstablecimiento>');
	if( $query->Record['CONTRIBUYENTEESPECIAL']!=''){
		 eGUIA('    <contribuyenteEspecial>'. $query->Record['CONTRIBUYENTEESPECIAL'] .'</contribuyenteEspecial>');	
	}
	eGUIA('    <dirPartida>'. $query->Record['DIRPARTIDA'] .'</dirPartida>');
	eGUIA('    <razonSocialTransportista>'. htmlspecialchars ($query->Record['RAZONSOCIALTRANSPORTISTA']) .'</razonSocialTransportista>');	
	eGUIA('    <tipoIdentificacionTransportista>'. $query->Record['TIPOIDENTIFICACIONTRANSPORTISTA'] .'</tipoIdentificacionTransportista>');
	eGUIA('    <rucTransportista>'. $query->Record['RUCTRANSPORTISTA'] .'</rucTransportista>');
	eGUIA('    <obligadoContabilidad>'. $query->Record['OBLIGADOCONTABILIDAD'] .'</obligadoContabilidad>');
	eGUIA('    <fechaIniTransporte>'. MDY($query->Record['FECHAINITRANSPORTE']) .'</fechaIniTransporte>');
	eGUIA('    <fechaFinTransporte>'. MDY($query->Record['FECHAFINTRANSPORTE']) .'</fechaFinTransporte>');
	eGUIA('    <placa>'. $query->Record['PLACA'] .'</placa>');
	eGUIA('  </infoGuiaRemision>');
	eGUIA('  <destinatarios>');
	eGUIA('  	<destinatario>');
	eGUIA('    		<identificacionDestinatario>'. $query->Record['IDENTIFICACIONCOMPRADOR'] .' </identificacionDestinatario>');
	eGUIA('    		<razonSocialDestinatario>'. htmlspecialchars ($query->Record['RAZONSOCIALCOMPRADOR']) .'</razonSocialDestinatario>');	
	eGUIA('    		<dirDestinatario>'. htmlspecialchars ($query->Record['DIRECCIONCOMPRADOR']) .'</dirDestinatario>');	
	eGUIA('    		<motivoTraslado>'. $query->Record['MOTIVOTRASLADO'] .'</motivoTraslado>');
	eGUIA('    		<detalles>');

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
		eGUIA('    			<detalle>');
		eGUIA('      			<codigoInterno>'. $detalles->Record['CODIGOPRINCIPAL'] .'</codigoInterno>');
		eGUIA('      			<descripcion>'. $detalles->Record['DESCRIPCION'] .'</descripcion>');
		eGUIA('      			<cantidad>'. $detalles->Record['CANTIDAD'] .'</cantidad>');
		eGUIA('    			</detalle>');		
	}
	eGUIA('    		</detalles>');
	eGUIA('  	</destinatario>');
	eGUIA('  </destinatarios>');
	eGUIA('  <infoAdicional>');
	eGUIA('    <campoAdicional nombre="Email">'. $query->Record['CORREO'] .' </campoAdicional>');
	eGUIA('    <campoAdicional nombre="Telefono">'. $query->Record['TELEFONO1'] .' </campoAdicional>');
	eGUIA('    <campoAdicional nombre="Direccion">'. str_replace("\n", " ", $query->Record['DIRECCIONCOMPRADOR']) .' </campoAdicional>');
	eGUIA('  </infoAdicional>');
	eGUIA('</guiaRemision>');

	chmod(Path_Guias_Remision . $xDOC . '/guia_remision.xml', 0777);

	
	
	
/************************************************** FIN ***********************************************************/

/************************* FIRMADO DE XML ****************************************************************/
	$config=array(
		'access_key'	=> $query->Record['CLAVEACCESO'], // la clave de acceso es necesaria para la autorizacion
		'pass_p12'		=> clave_certificado,
		'file_p12'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',
		'file_to_sign'	=> Path_Guias_Remision . $xDOC .'/guia_remision.xml',
		'file_signed'	=> Path_Guias_Remision . $xDOC .'/guia_remision_firmada.xml',
		'file_autorized'=> Path_Guias_Remision . $xDOC .'/guia_remision_autorizada.xml'
	);

	if(prg_firma=='PHP'){
		include_once (Server_Path . 'facturacion_electronica/firma_PHP.php');		
	}else{
		include_once (Server_Path . 'facturacion_electronica/firma_WS.php');		
	}		

	
/************************************************** FIN ***********************************************************/
	
/***************** FACTURA HTML Y PDF ************************************/	
	$Plantilla    = Server_Path . "facturacion_electronica/Recursos/guia_remision.html";
	$BlockToParse  = "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");	

	@unlink(Path_Guias_Remision . $xDOC . '/guia_remision.html');

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

		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));	
		}
	}
	//$Tpl->SetVar($campo, htmlspecialchars($valor));	
	$const = get_defined_constants(true);
	foreach($const['user'] as $campo => $valor)
	{
		$Tpl->SetVar($campo, htmlspecialchars($valor));	
	}	
	if(file_exists(Server_Path . 'facturacion_electronica/Recursos/logo_empresa.jpg')){
		$Tpl->SetVar('logo_empresa', 'logo_empresa');	
	}else{
		$Tpl->SetVar('logo_empresa', 'empresa_sin_logo');	
	}

	$query->sql = "SELECT * FROM V_D_DOCUMENTOS_FACT_ELEC WHERE  ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));	
		}
		$Tpl->Parse("DETALLES", true);
	}



	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	file_put_contents(Path_Guias_Remision . $xDOC . '/guia_remision.html', $html);
	//echo 'http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/facturas/'. $xDOC .'/factura.html' . "\n";
	$cmd_pdf = 'wkhtmltopdf http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/guias_remision/'. $xDOC .'/guia_remision.html ' . Path_Guias_Remision . $xDOC . '/guia_remision.pdf';
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
	
	$Record= xFormat($query);	
		
	$Plantilla    	= Server_Path . 'facturacion_electronica/Recursos/template_mail_guia_remision.html';
	$BlockToParse  	= "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");	
	
	while (list($clave, $valor) = each($Record))
	{	
		$Tpl->SetVar($clave, htmlspecialchars($valor));
	}

	$xfile = Path_Guias_Remision  . $xDOC .'/guia_remision_autorizada.xml';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$xfile = Path_Guias_Remision . $xDOC .'/guia_remision.pdf';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$asunto  = 'Comprobante Electronico ';
	$asunto .= $Record['ESTAB'] .'-';
	$asunto .= $Record['PTOEMI'] .'-';
	$asunto .= $Record['SECUENCIAL'];
	
	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	
	file_put_contents(Path_Guias_Remision . $xDOC . '/guia_remision.email', $html);
	
	$xfiles = '';
	if(is_array($files))
	{
		for($i=0;$i<sizeof($files);$i++)
		{
			if($xfiles!='') $xfiles.=' ';
			$xfiles .= $files[$i]['file'];
		}		
	}

  
  
	$para = $query->Record['CORREO'];
	//if(!sri_modo) 
		$para = 'luisman01@gmail.com';

	$comando = Path_Guias_Remision . $xDOC . '/guia_remision.email ' . $xfiles;

	$query->sql = "INSERT INTO D_CORREO(tipo,intento,destinatario,asunto,mensaje,comando,comentarios,idx,tabla) values ('OUT',0,'". $para ."', '".  $asunto."', '','". $comando ."','SRI_DOCUMENTOS', '". $xDOC ."', 'M_DOCUMENTOS')";
	$query->ejecuta_query();
	
	//print_r($query);

/****************** FIN ********************************/

?>