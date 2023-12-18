<?php
/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	
*/

include('../config.php');
define('Server_xPath','/opt/lampp/htdocs/makrocel/');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

if($_REQUEST['comprobante']!=''){
	$tipo= substr($_REQUEST['comprobante'], 8, 2);
	if($tipo!='01'){
		echo "Tipo de Comprobante Invalido";
		die();
	}
	setlocale(LC_CTYPE, 'en_AU.utf8');
	 
	$xml_post_string='<s11:Envelope xmlns:s11="http://schemas.xmlsoap.org/soap/envelope/">
		<s11:Body>
			<ns1:autorizacionComprobante xmlns:ns1="http://ec.gob.sri.ws.autorizacion">
				<claveAccesoComprobante>'. $_REQUEST['comprobante'] .'</claveAccesoComprobante>
			</ns1:autorizacionComprobante>
		</s11:Body>
	</s11:Envelope>';

   $headers = array(
				"Content-type: text/xml;charset=\"utf-8\"",
				"Expect: 100-continue",
				"Host: cel.sri.gob.ec", 
				"Content-length: ".strlen($xml_post_string),
			); //SOAPAction: your op URL

	$url = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
			
	// PHP cURL  for https connection with auth
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_VERBOSE, true);

	// converting
	$tmp_response = curl_exec($ch); 
	curl_close($ch);

	//file_put_contents(__DIR__ .'/aa.xml', $tmp_response);

	$response = html_entity_decode($tmp_response);
	$response = iconv('UTF-8', 'ASCII//TRANSLIT', $response);
	
	$response1 = str_replace("<soap:Body>","",$response);
	$response2 = str_replace("</soap:Body>","",$response1);

	$comprobante = gText($response2, '<comprobante>', '</comprobante>');
	
	$xml_recibido =  sys_get_temp_dir() . "/". $_REQUEST['comprobante'] .".xml";
	//$xml_recibido = __DIR__  . "/". $_REQUEST['comprobante'] .".xml";
	file_put_contents($xml_recibido, $comprobante);
/***************************************/

	$fechaAutorizacion = gText($response2, '<fechaAutorizacion>', '</fechaAutorizacion>');
	//die($fechaAutorizacion );

/***************************************/
	
}else{
	$xml_recibido = $_FILES["archivo"]["tmp_name"];
	$pdf_recibido = $_FILES["archivo_pdf"]["tmp_name"];	
	$ext	  = strtoupper(substr($_FILES["archivo"]["name"], -3));
	
	if(strtoupper(substr($_FILES["archivo"]["name"], -3)) !='XML') die('Archivo XML No Valido <b>' . $_FILES["archivo"]["name"] . '</b>');
	if(strtoupper(substr($_FILES["archivo_pdf"]["name"], -3)) !='PDF') die('Archivo PDF No Valido <b>' . $_FILES["archivo_pdf"]["name"] . '</b>');
}


$query = new sql(0);

include_once(Server_Path . 'facturacion_electronica/RideSRI/RideSRI.php');
$ride = new RideSRI();
$doc=$ride->getXmlArray($xml_recibido, true);
echo "<pre>";

if($doc['comprobante']['infoTributaria']['claveAcceso']==''){
	//forma 2
	$xXML = implode('', $doc['comprobante']['comprobante']);	
	$doc['comprobante'] = json_decode(json_encode(simplexml_load_string($xXML)), true);
	//echo $doc['comprobante']['infoTributaria']['claveAcceso'];
	//print_r($doc);	
	//die();
}
else{
	//echo $doc['comprobante']['infoTributaria']['claveAcceso'];
	//die('-----------------------');
}

//print_r($doc);
$sql="SELECT * FROM M_DOCUMENTOS WHERE DOC_FISCAL='". $doc['comprobante']['infoTributaria']['claveAcceso'] ."'";
$query->sql = $sql;
$query->ejecuta_query();
if($query->next_record()){
	echo "Factura Nro.         " . $query->Record['ID_M_DOC_FINAL']. "\n";
	echo "Nro de Autorizacion  " . $query->Record['DOC_FISCAL']. "\n";
	echo "Fecha Emision        " . $query->Record['FECHA_DOCUMENTO']. "\n";
	echo "Proveedor            " . $query->Record['NOMBRES']. "\n";
	echo "<b>Error-> Ya se encuentra registrada en el sistema</b>\n";
	die();
	
}



$query->sql ="SELECT * FROM M_PROVEEDORES WHERE CODIGO1='". $doc['comprobante']['infoTributaria']['ruc'] ."'";
$query->ejecuta_query();
if(!$query->next_record()){
	
	$nombre_comercial = $doc['comprobante']['infoTributaria']['nombreComercial'];
	if(!isset($doc['comprobante']['infoTributaria']['nombreComercial'])){
		$nombre_comercial  = $doc['comprobante']['infoTributaria']['razonSocial'];
	}
	$sql="INSERT INTO M_PROVEEDORES(";
	$sql.= "CODIGO1,";
	$sql.= "RAZON,";
	$sql.= "NOMBRES,";
	$sql.= "DIRECCION,";
	$sql.= "REEMBOLSABLE,";
	$sql.= "SRI_TIPO,";
	$sql.= "SRI_CONTABILIDAD) ";
	$sql.= "VALUES(";
	$sql.= "'". $doc['comprobante']['infoTributaria']['ruc'] ."'";
	$sql.=",'". $doc['comprobante']['infoTributaria']['razonSocial'] ."'";
	$sql.=",'". $nombre_comercial ."'";
	$sql.=",'". $doc['comprobante']['infoTributaria']['dirMatriz'] ."'";
	$sql.=",'". '*' ."'"; 
	$sql.=",'". '04' ."'"; 
	$sql.=",'". 'NO' ."'";
	$sql.=")";
	$query->sql = $sql;
	$query->ejecuta_query();
	if($query->erro_msg!=''){
		die('<B>' . $query->regi['ERROR'] .'</B>');
	}	
	
	$query->sql ="SELECT * FROM M_PROVEEDORES WHERE CODIGO1='". $doc['comprobante']['infoTributaria']['ruc'] ."'";
	$query->ejecuta_query();
	$query->next_record();		
	echo "Proveedor Nuevo OK\n";
}else{
	
	echo "Proveedor Existente OK\n";
}
	$proveedor = $query->Record;
	//print_r($proveedor);
	
	$sql="DELETE FROM X_M_DOCUMENTOS WHERE DOC_FISCAL='". $doc['comprobante']['infoTributaria']['claveAcceso'] ."'";
	$query->sql = $sql;
	$query->ejecuta_query();
	if($query->erro_msg!=''){
		die('<B>' . $query->regi['ERROR'] .'</B>');
	}	 	
	
	$DOCUMENTO = $doc['comprobante']['infoTributaria']['estab'];
	$DOCUMENTO.= $doc['comprobante']['infoTributaria']['ptoEmi'];
	$DOCUMENTO.= $doc['comprobante']['infoTributaria']['secuencial'];

	
	$sql ='INSERT INTO X_M_DOCUMENTOS(
	ID_M_DOC_FINAL,
	DOC_FISCAL,
	FECHA_DOCUMENTO,
	FECHA_RECEPCION,
	CREDITO,
	ID_M_TIPO_COMPROBANTES,
	ID_M_ALMACENES,
	TIPO,
	ID_M_PROVEEDORES,
	REEMBOLSABLE,
	CONDICION_PAGO,
	NOMBRES,
	CODIGO1,
	DIRECCION,
	ESTATUS
	) VALUES (';	
	
	$sql.= "'". $DOCUMENTO ."'";
	$sql.=",'". $doc['comprobante']['infoTributaria']['claveAcceso'] ."'";
	$sql.=",'". toMDY($doc['comprobante']['infoFactura']['fechaEmision']) ."'";
	$sql.=",'". MDY($fechaAutorizacion) ."'";
	$sql.=",'". $proveedor['DIAS_CREDITO'] ."'"; 
	$sql.=",'". '0011' ."'";
	$sql.=",'". '0011' ."'";
	$sql.=",'". 'COM' ."'";
	$sql.=",'". $proveedor['ID_M_PROVEEDORES'] ."'";
	$sql.=",'". $proveedor['ID_M_PROVEEDORES'] ."'";
	$sql.=",'". 'CRE' ."'";
	$sql.=",'". $proveedor['NOMBRES'] ."'";
	$sql.=",'". $proveedor['CODIGO1'] ."'";
	$sql.=",'". $proveedor['DIRECCION'] ."'";
	$sql.=",'". 'P' ."'";
	$sql.=")";
	
	$query->sql = $sql;
	$query->ejecuta_query();
	if($query->erro_msg!=''){
		die('<B>' . $query->regi['ERROR'] .'</B>');
	}		
	
	$query->sql ="SELECT * FROM X_M_DOCUMENTOS WHERE DOC_FISCAL='". $doc['comprobante']['infoTributaria']['claveAcceso'] ."'";
	$query->ejecuta_query();
	$query->next_record();	
	echo "Documento Creado OK\n";
	
	$documento = $query->Record;
	//print_r($documento);
	
	 $detalles = $doc['comprobante']['detalles'];
	 if(!isset($detalles['detalle'][0])){
		unset($detalles);
		$detalles['detalle'][0] = $doc['comprobante']['detalles']['detalle'];
	 }
	 $deta = $detalles['detalle'];

	//print_r($detalles);
	for($i=0;$i<sizeof($deta);$i++){
		//print_r($deta[$i]);
		$ximp = $deta[$i]['impuestos']['impuesto'];
	 	if(isset($ximp[0]))
		{
			for($g=0;$g<sizeof($ximp);$g++){
				if($ximp[$g]['codigo']==3){
					$iva_ice = $ximp[$g];
					$deta[$i]['precioUnitario'] = $deta[$i]['precioUnitario'] + ($ximp[$g]['valor']/$deta[$i]['cantidad']);
				}
				if($ximp[$g]['codigo']==2){
					$iva = $ximp[$g];
				}
			}
			unset($deta[$i]['impuestos']['impuesto']);
			$deta[$i]['impuestos']['impuesto'] = $iva;
		}
		else{
			//print_r($deta[$i]['impuestos']);
			//die('ok impu');
		}
		$DESCUENTO = 0;
		if(trim($deta[$i]['descuento'])>0){
			$XMONTO1 = $deta[$i]['precioUnitario'] *  $deta[$i]['cantidad'];
			$DESCUENTO = ($deta[$i]['descuento']*100)/$XMONTO1;
		}
		
		$sql='INSERT INTO X_DOCUMENTOS(
		SRI_CODIGOPRINCIPAL,
		ID_M_IMPUESTOS,
		DESCRIPCION,
		CANTIDAD,
		PRECIO,
		DESCUENTO,
		ID_X_M_DOCUMENTOS,
		TIPO,
		ID_M_ALMACENES,
		SRI_CODIGO,
		SRI_CODIGOPORCENTAJE,
		SRI_TARIFA,
		SRI_BASEIMPONIBLE,
		SRI_VALOR,
		ID_M_PROVEEDORES
		) VALUES (';
		$sql.="'". trim($deta[$i]['codigoPrincipal']) ."'";
		$sql.=",'". '' ."'";
		$sql.=",'". trim($deta[$i]['descripcion']) ."'";
		$sql.=",'". trim($deta[$i]['cantidad']) ."'";
		$sql.=",'". trim($deta[$i]['precioUnitario']) ."'";
		$sql.=",'". $DESCUENTO ."'";
		$sql.=",'". $documento['ID_X_M_DOCUMENTOS'] ."'";
		$sql.=",'". 'COM' ."'"; 
		$sql.=",'". '0011' ."'"; 
		$sql.=",'". trim($deta[$i]['impuestos']['impuesto']['codigo'])."'";
		$sql.=",'". trim($deta[$i]['impuestos']['impuesto']['codigoPorcentaje'])."'";
		$sql.=",'". trim($deta[$i]['impuestos']['impuesto']['tarifa'])."'";
		$sql.=",'". trim($deta[$i]['impuestos']['impuesto']['baseImponible'])."'";
		$sql.=",'". trim($deta[$i]['impuestos']['impuesto']['valor'])."'";
		$sql.=",'". $proveedor['ID_M_PROVEEDORES'] ."'";
		$sql.=")";
		
		$query->sql = $sql;
		$query->ejecuta_query();
		if($query->erro_msg!=''){
			die('<B>' . $query->regi['ERROR'] .'</B>');
		}	
	}
	echo "Detalles insertados OK\n";
	echo "<b>OK-> Documento cargado con exito....</b>\n";

$Doc_Path = Server_Path . 'comprobantes_electronicos/compras/' . $doc['comprobante']['infoTributaria']['claveAcceso'] . '/';

@mkdir(Server_Path . 'comprobantes_electronicos/compras/', 0777);
@chmod(Server_Path . 'comprobantes_electronicos/compras/',0777);

@mkdir($Doc_Path, 0777);
@chmod($Doc_Path ,0777);


@move_uploaded_file($_FILES["archivo"]["tmp_name"], 	$Doc_Path . 'factura.xml');
@move_uploaded_file($_FILES["archivo_pdf"]["tmp_name"], $Doc_Path . 'factura.pdf');

$comando = 'convert -density 150 -antialias "'.  $Doc_Path . 'factura.pdf' .'" -resize 1024x -quality 92 -background white -alpha remove "'.  $Doc_Path . 'factura.jpg"';
$resp = system($comando);

	
	
/*	

*/
//echo "<hr>";
//print_r($doc);


function gText($text, $d1, $d2){
	
	$cadena1 = str_replace($d1, '',  strstr($text, $d1));
	return strstr($cadena1, $d2, true);
	
}
//$result = $ride->createRide($file_autorized, $file_pdf, $logo_path); // Instancio la clase, y muestro el ride
function toMDY($fecha)
{
	$xf =  explode("/", substr($fecha,0,10));	
	return $xf[1] . "-" . $xf[0] .'-' . $xf[2];

}

function MDY($fecha)
{
	return date("m-d-Y", strtotime($fecha));
}
?>