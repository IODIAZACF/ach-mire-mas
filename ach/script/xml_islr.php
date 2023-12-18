<?php

ob_clean();
ob_start();
$salida = "";

$query3 = new sql();
$xdesde  = getvar('FECHA_DESDE');
$xhasta  = getvar('FECHA_HASTA');

$f = explode("/", $xdesde);
$xdesde = $f[1]. '/' . $f[0]. '/'. $f[2];

$periodo = $f[2]. $f[1];
$xnombre='retenciones_' . $periodo;

$f = explode("/", $xhasta);
$xhasta = $f[1]. '/' . $f[0]. '/'. $f[2];

$query3->sql ="SELECT * FROM V_RETENCIONES WHERE CAST(FECHA_DOCUMENTO AS DATE) BETWEEN '" . $xdesde ."'  AND '" . $xhasta . "'   order by ID_M_DOC_FINAL  ";
$query3->ejecuta_query();
$rif     = getsession('CONFIGURACION_CODIGO1');

$query3->sql ="SELECT * FROM V_RETENCIONES WHERE CAST(FECHA_DOCUMENTO AS DATE) BETWEEN '" . $xdesde ."'  AND '" . $xhasta . "'   order by ID_M_DOC_FINAL  ";
$query3->ejecuta_query();

echo '<?xml version="1.0" encoding="utf-8" ?>' . "\n";
echo '<RelacionRetencionesISLR RifAgente="' . $rif . '" Periodo="' . $periodo . '">' . "\n";

while($query3->next_record())
{
    $rif        = $query3->Record['CODIGO1'];
    $factura    = $query3->Record['FACTURA'];
    $control    = $query3->Record['CONTROL'];
    $concepto   = '055';
    $subtotal   = $query3->Record['SUB_TOTAL'];
    $subtotal   = number_format($subtotal, 2, '.', '');
    $porcentaje = $query3->Record['MONTO_IMPUESTO'];
    $porcentaje = number_format($porcentaje, 2, '.', '');

    echo '	<DetalleRetencion>' . "\n";
    echo ' 		<RifRetenido>'         . $rif        . '</RifRetenido>'          . "\n";
    echo ' 		<NumeroFactura>'       . $factura    . '</NumeroFactura>'        . "\n";
    echo ' 		<NumeroControl>'       . $control    . '</NumeroControl>'        . "\n";
    echo ' 		<CodigoConcepto>'      . $concepto   . '</CodigoConcepto>'       . "\n";
    echo ' 		<MontoOperacion>'      . $subtotal   . '</MontoOperacion>'       . "\n";
    echo '		<PorcentajeRetencion>' . $porcentaje . '</PorcentajeRetencion>'  . "\n";
    echo '	</DetalleRetencion>' . "\n";
}

echo '</RelacionRetencionesISLR>' . "\n";

$salida = ob_get_contents();
//die('DEBUG');

ob_end_clean();


/*
$fichero = "../../../tmp/". $xnombre;
$fp = fopen($fichero, "w+");
fwrite($fp,$salida);
fclose($fp);

header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".basename($fichero)."\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($fichero));
readfile("$fichero");

exit();
*/

//echo "Archivo Generado con Exito.!";
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="' . $xnombre . '.xml"');
echo $salida;
exit();

?>