#!/usr/bin/php

<?php
/************************** GENERACION DE XML **************************************/
	include_once ( __DIR__ . '/config.php');
    define('TABLA_DOC'			,'D_CXCCXP');

	if($_REQUEST){
		$xDOC = $_REQUEST['IDX'];
	}else
	{
		$xDOC = $argv[1];
	}

	@mkdir(Path_Retenciones . $xDOC . '/', 0777);
	@chmod(Path_Retenciones . $xDOC . '/', 0777);

	@unlink(Path_Retenciones . $xDOC . '/retencion.xml');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_D_RETENCIONES_MULTIPLES_ELECT WHERE ID_PADRE ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();
	$Record = $query->Record;

	eRET('<?xml version="1.0" encoding="UTF-8" standalone="no"?>');
	eRET('<comprobanteRetencion id="comprobante" version="1.0.0">');
	eRET('  <infoTributaria>');
	eRET('    <ambiente>'. $query->Record['AMBIENTE'] .'</ambiente>');
	eRET('    <tipoEmision>'. $query->Record['TIPOEMISION'] .'</tipoEmision>');
	eRET('    <razonSocial>'. htmlspecialchars ($query->Record['RAZONSOCIAL']) .'</razonSocial>');
	eRET('    <nombreComercial>'. htmlspecialchars ($query->Record['NOMBRECOMERCIAL']) .'</nombreComercial>');
	eRET('    <ruc>'. $query->Record['RUC'] .'</ruc>');
	eRET('    <claveAcceso>'. $query->Record['CLAVEACCESO'] .'</claveAcceso>');
	eRET('    <codDoc>'. $query->Record['CODDOC'] .'</codDoc>');
	eRET('    <estab>'. $query->Record['ESTAB'] .'</estab>');
	eRET('    <ptoEmi>'. $query->Record['PTOEMI'] .'</ptoEmi>');
	eRET('    <secuencial>'. $query->Record['SECUENCIAL'] .'</secuencial>');
	eRET('    <dirMatriz>'. $query->Record['DIRMATRIZ'] .'</dirMatriz>');
	eRET('  </infoTributaria>');
	eRET('	 <infoCompRetencion>');
	eRET('		<fechaEmision>'.MDY($query->Record['FECHAEMISION']) .'</fechaEmision>');
	eRET('		<dirEstablecimiento>'. $query->Record['DIRESTABLECIMIENTO'] .'</dirEstablecimiento>');
	if( $query->Record['CONTRIBUYENTEESPECIAL']!=''){
		eRET('     <contribuyenteEspecial>'. $query->Record['CONTRIBUYENTEESPECIAL'] .'</contribuyenteEspecial>');
	}
	eRET('		<obligadoContabilidad>'. $query->Record['OBLIGADOCONTABILIDAD'] .'</obligadoContabilidad>');
	eRET('		<tipoIdentificacionSujetoRetenido>'. $query->Record['TIPOIDSUJETORETENIDO'] .'</tipoIdentificacionSujetoRetenido>');
	eRET('		<razonSocialSujetoRetenido>'. htmlspecialchars($query->Record['RAZONSOCIALSUJETORETENIDO']) .'</razonSocialSujetoRetenido>');
	eRET('		<identificacionSujetoRetenido>'. $query->Record['IDENTIFICACIONSUJETORETENIDO'] .'</identificacionSujetoRetenido>');
	eRET('		<periodoFiscal>'. $query->Record['PERIODOFISCAL'] .'</periodoFiscal>');
	eRET('	 </infoCompRetencion>');
	eRET('	 <impuestos>');

	$query->sql = "SELECT * FROM V_D_RETENCIONES_MULTIPLES_ELECT WHERE ID_PADRE ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record()){
		eRET('		<impuesto>');
		eRET('			<codigo>'. $query->Record['CODIGO'] .'</codigo>');
		eRET('			<codigoRetencion>'. $query->Record['CODIGORETENCION'] .'</codigoRetencion>');
		eRET('			<baseImponible>'. $query->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eRET('			<porcentajeRetener>'. $query->Record['PORCENTAJERETENER'] .'</porcentajeRetener>');
		eRET('			<valorRetenido>'. $query->Record['VALORRETENIDO'] .'</valorRetenido>');
		eRET('			<codDocSustento>'. $query->Record['CODDOCSUSTENTO'] .'</codDocSustento>');
		eRET('			<numDocSustento>'. $query->Record['NUMDOCSUSTENTO'] .'</numDocSustento>');
		eRET('			<fechaEmisionDocSustento>'. MDY($query->Record['FECHAEMISIONDOCSUSTENTO']) .'</fechaEmisionDocSustento>');
		eRET('		</impuesto>');
	}
	eRET('	 </impuestos>');
	eRET('  <infoAdicional>');
	eRET('    <campoAdicional nombre="Email"> '. $Record['CORREO'] .'</campoAdicional>');
	eRET('    <campoAdicional nombre="Telefono"> '. $Record['TELEFONO1'] .'</campoAdicional>');
	eRET('    <campoAdicional nombre="Direccion"> '.htmlspecialchars($Record['DIRECCIONSUJETORETENIDO']) .'</campoAdicional>');
	eRET('  </infoAdicional>');
	eRET('</comprobanteRetencion>');

	@chmod(Path_Retenciones . $xDOC . '/retencion.xml', 0777);

/************************************************** FIN ***********************************************************/
/************************* FIRMADO DE XML ****************************************************************/
	$config=array(
		'access_key'	=> $Record['CLAVEACCESO'], // la clave de acceso es necesaria para la autorizacion
		'pass_p12'		=> clave_certificado,
		'file_p12'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',
		'file_to_sign'	=> Path_Retenciones . $xDOC .'/retencion.xml',
		'file_signed'	=> Path_Retenciones . $xDOC .'/retencion_firmada.xml',
		'file_autorized'=> Path_Retenciones . $xDOC .'/retencion_autorizada.xml'
	);
	if(prg_firma=='PHP'){
		include_once (Server_Path . 'facturacion_electronica/firma_PHP.php');
	}else{
		include_once (Server_Path . 'facturacion_electronica/firma_WS.php');
	}

/************************************************** FIN ***********************************************************/
/***************** FACTURA HTML Y PDF ************************************/

	$Plantilla    = Server_Path . "facturacion_electronica/Recursos/retencion.html";
	$BlockToParse  = "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");

	@unlink(Path_Retenciones . $xDOC . '/retencion.html');

	$query->sql = "SELECT * FROM V_D_RETENCIONES_MULTIPLES_ELECT WHERE ID_PADRE ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);
		foreach($Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));
		}
		$Tpl->Parse("DETALLES", true);
	}
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

	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	file_put_contents(Path_Retenciones . $xDOC . '/retencion.html', $html);
	$cmd_pdf = 'wkhtmltopdf http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/retenciones/'. $xDOC .'/retencion.html ' . Path_Retenciones . $xDOC . '/retencion.pdf';
	system($cmd_pdf);

/****************** FIN ********************************/

/***************** EMAIL PHPMailer ************************************/

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_D_RETENCIONES_MULTIPLES_ELECT WHERE ID_PADRE ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();

	$Record= xFormat($query);

	$Plantilla    	= Server_Path . 'facturacion_electronica/Recursos/template_mail_retencion.html';
	$BlockToParse  	= "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");

	while (list($clave, $valor) = each($Record))
	{
		$Tpl->SetVar($clave, htmlspecialchars($valor));
	}

	$xfile = Path_Retenciones . $xDOC .'/retencion_autorizada.xml';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$xfile = Path_Retenciones . $xDOC .'/retencion.pdf';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$asunto  = 'Comprobante Electronico ';
	$asunto .= $Record['ESTAB'] .'-';
	$asunto .= $Record['PTOEMI'] .'-';
	$asunto .= $Record['SECUENCIAL'];

	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");

	file_put_contents(Path_Retenciones . $xDOC . '/retencion.email', $html);

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

	$comando = Path_Retenciones . $xDOC . '/retencion.email ' . $xfiles;

   	$query->sql = "INSERT INTO D_CORREO(tipo,intento,destinatario,asunto,mensaje,comando,comentarios,idx,tabla) values ('OUT',0,'". $para ."', '".  $asunto."', '','". $comando ."','SRI_DOCUMENTOS', '". $xDOC ."','". TABLA_DOC ."' )";
	$query->ejecuta_query();

	//print_r($query);

/****************** FIN ********************************/

?>