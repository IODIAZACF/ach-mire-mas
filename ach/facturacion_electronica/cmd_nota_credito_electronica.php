#!/usr/bin/php

<?php
/************************** GENERACION DE XML **************************************/

	include_once ( __DIR__ . '/config.php');
	define('TABLA_DOC'			,'M_DOCUMENTOS');

	define('sri_modo', false);
	define('nombre_db'			,'desarrollo');



	if($_REQUEST){
		$xDOC = $_REQUEST['IDX'];
	}else
	{
		$xDOC = $argv[1];
	}

	@mkdir(Path_NotasCreditos . $xDOC . '/', 0777);
	@chmod(Path_NotasCreditos . $xDOC . '/', 0777);

	@unlink(Path_NotasCreditos . $xDOC . '/nota_credito.xml');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$queryu = new sql();
	$queryu->DBHost     = "127.0.0.1";
	$queryu->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$queryu->DBUser     = "SYSDBA";
	$queryu->DBPassword = "masterkey";
	$queryu->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_DEV_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();
  /*
    echo "<pre>";
	$Record= xFormat($query);
    print_r($Record);
    die();
       */
	eNCC('<?xml version="1.0" encoding="UTF-8" standalone="no"?>');
	eNCC('<notaCredito id="comprobante" version="1.1.0">');
	eNCC('  <infoTributaria>');
	eNCC('    <ambiente>'. $query->Record['AMBIENTE'] .'</ambiente>');
	eNCC('    <tipoEmision>'. $query->Record['TIPOEMISION'] .'</tipoEmision>');
	eNCC('    <razonSocial>'. htmlspecialchars ($query->Record['RAZONSOCIAL']) .'</razonSocial>');
	eNCC('    <nombreComercial>'. htmlspecialchars ($query->Record['NOMBRECOMERCIAL']) .'</nombreComercial>');
	eNCC('    <ruc>'. $query->Record['RUC'] .'</ruc>');
	eNCC('    <claveAcceso>'. $query->Record['CLAVEACCESO'] .'</claveAcceso>');
	eNCC('    <codDoc>'. $query->Record['CODDOC'] .'</codDoc>');
	eNCC('    <estab>'. $query->Record['ESTAB'] .'</estab>');
	eNCC('    <ptoEmi>'. $query->Record['PTOEMI'] .'</ptoEmi>');
	eNCC('    <secuencial>'. $query->Record['SECUENCIAL'] .'</secuencial>');
	eNCC('    <dirMatriz>'. $query->Record['DIRMATRIZ'] .'</dirMatriz>');
	eNCC('  </infoTributaria>');

	eNCC('  <infoNotaCredito>');
	eNCC('    <fechaEmision>'. MDY($query->Record['FECHAEMISION']) .'</fechaEmision>');
	if( $query->Record['CONTRIBUYENTEESPECIAL']!=''){
			 eNCC('    <contribuyenteEspecial>'. $query->Record['CONTRIBUYENTEESPECIAL'] .'</contribuyenteEspecial>');
	}
	eNCC('    <tipoIdentificacionComprador>'. $query->Record['TIPOIDENTIFICACIONCOMPRADOR'] .'</tipoIdentificacionComprador>');
	eNCC('    <razonSocialComprador>'. htmlspecialchars ($query->Record['RAZONSOCIALCOMPRADOR']) .'</razonSocialComprador>');
	eNCC('    <identificacionComprador>'. $query->Record['IDENTIFICACIONCOMPRADOR'] .'</identificacionComprador>');
	eNCC('    <obligadoContabilidad>'. $query->Record['OBLIGADOCONTABILIDAD'] .'</obligadoContabilidad>');
	eNCC('    <codDocModificado>'. $query->Record['CODDOCMODIFICADO'] .'</codDocModificado>');
	eNCC('    <numDocModificado>'. $query->Record['NUMDOCMODIFICADO'] .'</numDocModificado>');
	eNCC('    <fechaEmisionDocSustento>'. MDY($query->Record['FECHAEMISIONDOCSUSTENTO']) .'</fechaEmisionDocSustento>');
	eNCC('    <totalSinImpuestos>'. $query->Record['TOTALSINIMPUESTOS'] .'</totalSinImpuestos>');
	eNCC('    <valorModificacion>'. $query->Record['VALORMODIFICACION'] .'</valorModificacion>');
	eNCC('    <moneda>'. $query->Record['MONEDA'] .'</moneda>');
	eNCC('    <totalConImpuestos>');
	eNCC('      <totalImpuesto>');
	eNCC('        <codigo>'. $query->Record['CODIGO'] .'</codigo>');
	eNCC('        <codigoPorcentaje>'. $query->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
	eNCC('        <baseImponible>'. $query->Record['BASEIMPONIBLE'] .'</baseImponible>');
	eNCC('        <valor>'. $query->Record['VALOR'] .'</valor>');
	eNCC('      </totalImpuesto>');
	eNCC('    </totalConImpuestos>');
	eNCC('    <motivo>'. $query->Record['MOTIVO'] .'</motivo>');
	eNCC('  </infoNotaCredito>');
	eNCC('  <detalles>');

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
		eNCC('    <detalle>');
		eNCC('      <codigoInterno>'. $detalles->Record['CODIGOPRINCIPAL'] .'</codigoInterno>');
		eNCC('      <codigoAdicional>'. $detalles->Record['CODIGOAUXILIAR'] .'</codigoAdicional>');
		eNCC('      <descripcion>'. $detalles->Record['DESCRIPCION'] .'</descripcion>');
		eNCC('      <cantidad>'. $detalles->Record['CANTIDAD'] .'</cantidad>');
		eNCC('      <precioUnitario>'. $detalles->Record['PRECIOUNITARIO'] .'</precioUnitario>');
		eNCC('      <descuento>'. $detalles->Record['DESCUENTO'] .'</descuento>');
		eNCC('      <precioTotalSinImpuesto>'. $detalles->Record['PRECIOTOTALSINIMPUESTO'] .'</precioTotalSinImpuesto>');
		eNCC('      <detallesAdicionales>');
		eNCC('        <detAdicional nombre="Serial" valor="'. $detalles->Record['SERIAL'] .' "/>');
		eNCC('      </detallesAdicionales>');
		eNCC('      <impuestos>');
		eNCC('        <impuesto>');
		eNCC('          <codigo>'. $detalles->Record['CODIGO'] .'</codigo>');
		eNCC('          <codigoPorcentaje>'. $detalles->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
		eNCC('          <tarifa>'. $detalles->Record['TARIFA'] .'</tarifa>');
		eNCC('          <baseImponible>'. $detalles->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eNCC('          <valor>'. $detalles->Record['VALOR'] .'</valor>');
		eNCC('        </impuesto>');
		eNCC('      </impuestos>');
		eNCC('    </detalle>');

	}
	eNCC('  </detalles>');
	eNCC('  <infoAdicional>');
	eNCC('    <campoAdicional nombre="Email"> '. $query->Record['CORREO'] .' </campoAdicional>');
	eNCC('    <campoAdicional nombre="Telefono">'. $query->Record['TELEFONO1'] .' </campoAdicional>');
	eNCC('    <campoAdicional nombre="Direccion">'. str_replace("\n", " ", $query->Record['DIRECCIONCOMPRADOR']) .' </campoAdicional>');
	eNCC('  </infoAdicional>');
	eNCC('</notaCredito>');

	chmod(Path_NotasCreditos . $xDOC . '/nota_credito.xml', 0777);

/************************************************** FIN ***********************************************************/
/************************* FIRMADO DE XML ****************************************************************/
	$config=array(
		'access_key'	=> $query->Record['CLAVEACCESO'], // la clave de acceso es necesaria para la autorizacion
		'pass_p12'		=> clave_certificado,
		'file_p12'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',
		'file_to_sign'	=> Path_NotasCreditos . $xDOC .'/nota_credito.xml',
		'file_signed'	=> Path_NotasCreditos . $xDOC .'/nota_credito_firmada.xml',
		'file_autorized'=> Path_NotasCreditos . $xDOC .'/nota_credito_autorizada.xml'
	);

	if(prg_firma=='PHP'){
		include_once (Server_Path . 'facturacion_electronica/firma_PHP.php');
	}else{
		include_once (Server_Path . 'facturacion_electronica/firma_WS.php');
	}

/************************************************** FIN ***********************************************************/
/***************** FACTURA HTML Y PDF ************************************/
	$Plantilla    = Server_Path . "facturacion_electronica/Recursos/nota_credito.html";
	$BlockToParse  = "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");

	@unlink(Path_NotasCreditos . $xDOC . '/nota_credito.html');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_DEV_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);
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
		$Record= xFormat($query);
		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));
		}
		$Tpl->Parse("DETALLES", true);
	}

	$query->sql = "SELECT * FROM V_RESUMEN_TOTAL_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		$Record= xFormat($query);
		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));
		}
	}

	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	file_put_contents(Path_NotasCreditos . $xDOC . '/nota_credito.html', $html);
	//echo 'http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/facturas/'. $xDOC .'/factura.html' . "\n";
	$cmd_pdf = 'wkhtmltopdf http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/notas_credito/'. $xDOC .'/nota_credito.html ' . Path_NotasCreditos . $xDOC . '/nota_credito.pdf';
	system($cmd_pdf);

/****************** FIN ********************************/

/***************** EMAIL PHPMailer ************************************/

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_DEV_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();

	$Record= xFormat($query);

	$Plantilla    	= Server_Path . 'facturacion_electronica/Recursos/template_mail_nota_credito.html';
	$BlockToParse  	= "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");

	while (list($clave, $valor) = each($Record))
	{
		$Tpl->SetVar($clave, htmlspecialchars($valor));
	}

	$xfile = Path_NotasCreditos  . $xDOC .'/nota_credito_autorizada.xml';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$xfile = Path_NotasCreditos . $xDOC .'/nota_credito.pdf';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$asunto  = 'Comprobante Electronico ';
	$asunto .= $Record['ESTAB'] .'-';
	$asunto .= $Record['PTOEMI'] .'-';
	$asunto .= $Record['SECUENCIAL'];

	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");

	file_put_contents(Path_NotasCreditos . $xDOC . '/nota_credito.email', $html);

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
	if(!sri_modo) $para = 'luisman01@gmail.com';

    //$para = 'luisman01@gmail.com';

	$comando = Path_NotasCreditos . $xDOC . '/nota_credito.email ' . $xfiles;

	$query->sql = "INSERT INTO D_CORREO(tipo,intento,destinatario,asunto,mensaje,comando,comentarios,idx,tabla) values ('OUT',0,'". $para ."', '".  $asunto."', '','". $comando ."','SRI_DOCUMENTOS', '". $xDOC ."', 'M_DOCUMENTOS')";
	$query->ejecuta_query();

?>