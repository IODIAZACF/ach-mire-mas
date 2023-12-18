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

	@mkdir(Path_Liquidacion_Compra . $xDOC . '/', 0777);
	@chmod(Path_Liquidacion_Compra . $xDOC . '/', 0777);

	@unlink(Path_Liquidacion_Compra . $xDOC . '/liquidacion.xml');

	$query = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$query->sql = "SELECT * FROM V_M_DOCUMENTOS_LIQ_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	$query->next_record();

	eLIQ('<?xml version="1.0" encoding="UTF-8"?>');
	eLIQ('<liquidacionCompra id="comprobante" version="1.0.0">');
	eLIQ('  <infoTributaria>');
	eLIQ('    <ambiente>'. $query->Record['AMBIENTE'] .'</ambiente>');
	//eLIQ('    <ambiente>1</ambiente>');
	eLIQ('    <tipoEmision>'. $query->Record['TIPOEMISION'] .'</tipoEmision>');
	eLIQ('    <razonSocial>'. htmlspecialchars ($query->Record['RAZONSOCIAL']) .'</razonSocial>');
	eLIQ('    <nombreComercial>'. htmlspecialchars ($query->Record['NOMBRECOMERCIAL']) .'</nombreComercial>');
	eLIQ('    <ruc>'. $query->Record['RUC'] .'</ruc>');
	eLIQ('    <claveAcceso>'. $query->Record['CLAVEACCESO'] .'</claveAcceso>');
	eLIQ('    <codDoc>'. $query->Record['CODDOC'] .'</codDoc>');
	eLIQ('    <estab>'. $query->Record['ESTAB'] .'</estab>');
	eLIQ('    <ptoEmi>'. $query->Record['PTOEMI'] .'</ptoEmi>');
	eLIQ('    <secuencial>'. $query->Record['SECUENCIAL'] .'</secuencial>');
	eLIQ('    <dirMatriz>'. $query->Record['DIRMATRIZ'] .'</dirMatriz>');
	eLIQ('  </infoTributaria>');

	eLIQ('  <infoLiquidacionCompra>');
	eLIQ('    <fechaEmision>'. MDY($query->Record['FECHAEMISION']) .'</fechaEmision>');
	//eLIQ('    <fechaEmision>10/02/2020</fechaEmision>');
	eLIQ('    <dirEstablecimiento> '. $query->Record['DIRESTABLECIMIENTO'] .'</dirEstablecimiento>');
	if( $query->Record['CONTRIBUYENTEESPECIAL']!=''){
		 eLIQ('    <contribuyenteEspecial>'. $query->Record['CONTRIBUYENTEESPECIAL'] .'</contribuyenteEspecial>');	
	}
	eLIQ('    <obligadoContabilidad>'. $query->Record['OBLIGADOCONTABILIDAD'] .'</obligadoContabilidad>');
	eLIQ('    <tipoIdentificacionProveedor>'. $query->Record['TIPOIDENTIFICACIONPROVEEDOR'] .'</tipoIdentificacionProveedor>');
	eLIQ('    <razonSocialProveedor>'. htmlspecialchars ($query->Record['RAZONSOCIALPROVEEDOR']) .'</razonSocialProveedor>');
	eLIQ('    <identificacionProveedor>'. $query->Record['IDENTIFICACIONPROVEEDOR'] .'</identificacionProveedor>');
	eLIQ('    <direccionProveedor>'. str_replace("\n", " ", $query->Record['DIRECCIONPROVEEDOR']) .' </direccionProveedor>');
	eLIQ('    <totalSinImpuestos>'. $query->Record['TOTALSINIMPUESTOS'] .'</totalSinImpuestos>');
	eLIQ('    <totalDescuento>'. $query->Record['TOTALDESCUENTO'] .'</totalDescuento>');
	eLIQ('    <codDocReembolso>'. $query->Record['CODDOCREEMBOLSO'] .'</codDocReembolso>');	
	eLIQ('    <totalComprobantesReembolso>'. $query->Record['TOTALCOMPROBANTESREEMBOLSO'] .'</totalComprobantesReembolso>');
	eLIQ('    <totalBaseImponibleReembolso>'. $query->Record['TOTALBASEIMPONIBLEREEMBOLSO'] .'</totalBaseImponibleReembolso>');
	eLIQ('    <totalImpuestoReembolso>'. $query->Record['TOTALIMPUESTOREEMBOLSO'] .'</totalImpuestoReembolso>');	
	eLIQ('    <totalConImpuestos>');

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
		eLIQ('      <totalImpuesto>');
		eLIQ('        <codigo>'. $iva->Record['CODIGO'] .'</codigo>');
		eLIQ('        <codigoPorcentaje>'. $iva->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
		eLIQ('			<descuentoAdicional>0.00</descuentoAdicional>');
		eLIQ('        <baseImponible>'. $iva->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eLIQ('        <tarifa>'. $iva->Record['TARIFA'] .'</tarifa>');
		eLIQ('        <valor>'. $iva->Record['VALOR'] .'</valor>');
		eLIQ('      </totalImpuesto>');
	}

	eLIQ('    </totalConImpuestos>');
	//eLIQ('    <propina>'. $query->Record['PROPINA'] .'</propina>');
	eLIQ('    <importeTotal>'. $query->Record['IMPORTETOTAL'] .'</importeTotal>');
	eLIQ('    <moneda>'. $query->Record['MONEDA'] .'</moneda>');

	eLIQ('    <pagos>');
	eLIQ('      <pago>');
	eLIQ('        <formaPago>01</formaPago>');
	eLIQ('        <total>'. $query->Record['TOTALCOMPROBANTESREEMBOLSO'] .'</total>');
	eLIQ('        <plazo>0</plazo>');
	eLIQ('        <unidadTiempo>Dias</unidadTiempo>');
	eLIQ('      </pago>');		
	eLIQ('    </pagos>');


	/*
	$pagos = new sql();
	$pagos->DBHost     = "127.0.0.1";
	$pagos->DBDatabase = "/opt/lampp/firebird/db/". nombre_db .".gdb";
	$pagos->DBUser     = "SYSDBA";
	$pagos->DBPassword = "masterkey";
	$pagos->Initialize();

	

	/*
	$pagos->sql = "SELECT * FROM V_D_PAGOS_FACT_ELEC WHERE TABLA='M_DOCUMENTOS' AND IDX ='". $xDOC ."'";
	$pagos->ejecuta_query();
	while($pagos->next_record())
	{
		eLIQ('      <pago>');
		eLIQ('        <formaPago>'. $pagos->Record['FORMAPAGO'] .'</formaPago>');
		eLIQ('        <total>'. $pagos->Record['TOTAL'] .'</total>');
		eLIQ('      </pago>');	
	}	
	eLIQ('    </pagos>');
	*/
	eLIQ('  </infoLiquidacionCompra>');
	eLIQ('  <detalles>');

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
		eLIQ('    <detalle>');
		eLIQ('      <codigoPrincipal>'. $detalles->Record['CODIGOPRINCIPAL'] .'</codigoPrincipal>');
		eLIQ('      <codigoAuxiliar>'. $detalles->Record['CODIGOAUXILIAR'] .'</codigoAuxiliar>');
		eLIQ('      <descripcion>'. $detalles->Record['DESCRIPCION'] .'</descripcion>');
		eLIQ('      <cantidad>'. $detalles->Record['CANTIDAD'] .'</cantidad>');
		eLIQ('      <precioUnitario>'. $detalles->Record['PRECIOUNITARIO'] .'</precioUnitario>');
		eLIQ('      <descuento>'. $detalles->Record['DESCUENTO'] .'</descuento>');
		eLIQ('      <precioTotalSinImpuesto>'. $detalles->Record['PRECIOTOTALSINIMPUESTO'] .'</precioTotalSinImpuesto>');
		eLIQ('      <detallesAdicionales>');
		eLIQ('        <detAdicional nombre="Serial" valor="'. $detalles->Record['SERIAL'] .' "/>');
		eLIQ('      </detallesAdicionales>');
		eLIQ('      <impuestos>');
		eLIQ('        <impuesto>');
		eLIQ('          <codigo>'. $detalles->Record['CODIGO'] .'</codigo>');
		eLIQ('          <codigoPorcentaje>'. $detalles->Record['CODIGOPORCENTAJE'] .'</codigoPorcentaje>');
		eLIQ('          <tarifa>'. $detalles->Record['TARIFA'] .'</tarifa>');
		eLIQ('          <baseImponible>'. $detalles->Record['BASEIMPONIBLE'] .'</baseImponible>');
		eLIQ('          <valor>'. $detalles->Record['VALOR'] .'</valor>');
		eLIQ('        </impuesto>');
		eLIQ('      </impuestos>');
		eLIQ('    </detalle>');
		
	}
	eLIQ('  </detalles>');
	eLIQ('  <infoAdicional>');
	eLIQ('    <campoAdicional nombre="Email">'. $query->Record['CORREO'] .' </campoAdicional>');
	eLIQ('    <campoAdicional nombre="Telefono">'. $query->Record['TELEFONO1'] .' </campoAdicional>');
	eLIQ('    <campoAdicional nombre="Direccion">'. str_replace("\n", " ", $query->Record['DIRECCIONCOMPRADOR']) .' </campoAdicional>');
	eLIQ('  </infoAdicional>');
	eLIQ('</liquidacionCompra>');

	chmod(Path_Liquidacion_Compra . $xDOC . '/liquidacion.xml', 0777);

/************************************************** FIN ***********************************************************/

/************************* FIRMADO DE XML ****************************************************************/


	$config=array(
		'access_key'	=> $query->Record['CLAVEACCESO'], // la clave de acceso es necesaria para la autorizacion
		'pass_p12'		=> clave_certificado,
		'file_p12'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',
		'file_to_sign'	=> Path_Liquidacion_Compra . $xDOC .'/liquidacion.xml',
		'file_signed'	=> Path_Liquidacion_Compra . $xDOC .'/liquidacion_firmada.xml',
		'file_autorized'=> Path_Liquidacion_Compra . $xDOC .'/liquidacion_autorizada.xml'
	);

	if(prg_firma=='PHP'){
		//include_once (Server_Path . 'facturacion_electronica/firma_PHP.php');
		
		require __DIR__.'/vendor2/autoload.php';

			$config=array(
				'access_key'	=> $query->Record['CLAVEACCESO'],
				'pass'		=> clave_certificado,
				'file'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.p12',
				'fkey'		=> Server_Path . 'facturacion_electronica/Recursos/'. nombre_certificado .'.key',
				'file_to_sign'	=> Path_Liquidacion_Compra . $xDOC .'/liquidacion.xml',
				'file_signed'	=> Path_Liquidacion_Compra . $xDOC .'/liquidacion_firmada.xml',
				'file_autorized'=> Path_Liquidacion_Compra . $xDOC .'/liquidacion_autorizada.xml',
				'wordwrap', 76
			);

			$FirmaElectronica = new \sasco\LibreDTE\FirmaElectronica($config);
			$res = $FirmaElectronica->signXML(file_get_contents($config['file_to_sign']));
			file_put_contents($config['file_signed'], $res);
			
			die('************************');
			include_once (Server_Path . 'facturacion_electronica/firma_PHP.php');		

		
		die('dddddddddddd');
	}else{
		include_once (Server_Path . 'facturacion_electronica/firma_WS.php');		
	}		

/************************************************** FIN ***********************************************************/
	
/***************** FACTURA HTML Y PDF ************************************/	
	$Plantilla    = Server_Path . "facturacion_electronica/Recursos/factura.html";
	$BlockToParse  = "main"; 
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");	

	@unlink(Path_Liquidacion_Compra . $xDOC . '/liquidacion.html');

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

	$query->sql = "SELECT * FROM V_D_PAGOS_FACT_ELEC WHERE TABLA='M_DOCUMENTOS' AND IDX ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));	
		}
		$Tpl->Parse("PAGOS", true);
	}	

	$query->sql = "SELECT * FROM V_RESUMEN_TOTAL_FACT_ELEC WHERE ID_M_DOCUMENTOS ='". $xDOC ."'";
	$query->ejecuta_query();
	while($query->next_record())
	{
		foreach($query->Record as $campo => $valor)
		{
			$Tpl->SetVar($campo, htmlspecialchars($valor));	
		}
	}

	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	file_put_contents(Path_Liquidacion_Compra . $xDOC . '/liquidacion.html', $html);
	//echo 'http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/facturas/'. $xDOC .'/factura.html' . "\n";
	$cmd_pdf = 'wkhtmltopdf http://'. local_ip .'/'. nombre_programa .'/comprobantes_electronicos/liquidacion_compra/'. $xDOC .'/liquidacion.html ' . Path_Liquidacion_Compra . $xDOC . '/liquidacion.pdf';
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
		
	$Plantilla    	= Server_Path . 'facturacion_electronica/Recursos/template_mail_factura.html';
	$BlockToParse  	= "main";
	$Tpl = new clsmiTemplate();
	$Tpl->templates_path = '';
	$Tpl->LoadTemplate($Plantilla, "main");	
	
	while (list($clave, $valor) = each($Record))
	{	
		$Tpl->SetVar($clave, htmlspecialchars($valor));
	}

	$xfile = Path_Liquidacion_Compra  . $xDOC .'/liquidacion_autorizada.xml';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$xfile = Path_Liquidacion_Compra . $xDOC .'/liquidacion.pdf';
	$x['name'] = basename($xfile);
	$x['file'] = $xfile;
	$files[] = $x;

	$asunto  = 'Comprobante Electronico ';
	$asunto .= $Record['ESTAB'] .'-';
	$asunto .= $Record['PTOEMI'] .'-';
	$asunto .= $Record['SECUENCIAL'];
	
	$Tpl->Parse("main", false);
	$html = $Tpl->GetVar("main");
	
	file_put_contents(Path_Liquidacion_Compra . $xDOC . '/liquidacion.email', $html);
	
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

	$comando = Path_Liquidacion_Compra . $xDOC . '/factura.email ' . $xfiles;

	$query->sql = "INSERT INTO D_CORREO(tipo,intento,destinatario,asunto,mensaje,comando,comentarios,idx,tabla) values ('OUT',0,'". $para ."', '".  $asunto."', '','". $comando ."','SRI_DOCUMENTOS', '". $xDOC ."', 'M_DOCUMENTOS')";
	$query->ejecuta_query();
	
	//print_r($query);

/****************** FIN ********************************/

function xB($data, $pa){
	file_put_contents('/opt/dataraw.txt', print_r($data, true));
	die("\n" . $pa . "\n");
	
}


?>