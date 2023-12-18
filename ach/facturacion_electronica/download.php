<?php

switch ($_REQUEST['TIPO_COMPROBANTE'])
{
	case 'NCC':
		$xCARPETA 	= 'notas_credito';
		$xFILE 		= 'nota_credito';
	break;
	case 'FAC':
		$xCARPETA 	= 'facturas';
		$xFILE 		= 'factura';
	break;
	case 'REM':
		$xCARPETA 	= 'retenciones';
		$xFILE 		= 'retencion';
	break;	
}
$documento = $xFILE . '.pdf';
if($_REQUEST['TIPO']=='XML'){
	$documento = $xFILE . '_autorizada.xml';	
}

$local_file = '../comprobantes_electronicos/' . $xCARPETA . '/' . $_REQUEST['IDX'] . '/' . $documento;
if(!file_exists($local_file)) return;

$mime =  getimagesize($local_file);
$size = filesize($local_file);
$fp   = fopen($local_file, "rb");
header("Content-type: " . $mime);
header("Content-Length: " . $size);
header("Content-Disposition: attachment; filename=" . $_REQUEST['ID_M_DOC_FINAL']. '.'. strtolower($_REQUEST['TIPO']));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
fpassthru($fp);

?>