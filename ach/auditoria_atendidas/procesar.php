<?php

include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

require_once __DIR__ . '/vendor/autoload.php';

use Aspera\Spreadsheet\XLSX\Reader;
use Aspera\Spreadsheet\XLSX\Worksheet;


//error_reporting(E_ALL ^ E_NOTICE);

$nombre_archivo          = $_FILES["archivo"]["name"];
$recibido                = $_FILES["archivo"]["tmp_name"];
@mkdir(Server_Path . "../tmp/", 0777);
@chmod(Server_Path . "../tmp/", 0777);
$archivo_destino         = Server_Path . ".." . $recibido;

$query = new sql();

$nombre = str_replace('.xls','',$nombre_archivo);

echo '<table border="1" width="100%">' . "\n";
$xtipo = '1';
$archivo_destino = $archivo_destino . ".xls";
move_uploaded_file( $recibido , $archivo_destino);
chmod($archivo_destino, 0777);

$reader = new Reader();
$reader->open($archivo_destino);
$sheets = $reader->getSheets();
$reader->changeSheet($index);


$xfil = $data->rowcount($sheet_index=0);
die('38');
/*
$xcol = $data->colcount($sheet_index=0);

echo "FILAS DE ARCHIVO --> " . $xfil . "<br/>\n";
echo "COLUMNAS DE ARCHIVO --> " . $xcol . "<br/>\n";
*/

echo '<tr><td colspan="10">Importando Archivo de personas atendidas</td></tr>' . "\n";

echo '<tr>' ."\n";
echo '<td>Registro</td>' . "\n";
echo '<td>Codigo</td>'."\n";
echo '<td align="left">lpa_id</td>'."\n";
echo '<td align="left">nombre</td>'."\n";
echo '<td align="left">apellido1</td>'."\n";
echo '<td align="left">apellido2</td>'."\n";
echo '</tr>'."\n";


for($j=2; $j<= $xfil; $j++)
{
	
   $LPA_ID = $data->val($j, 'A');
   $NOMBRE = mayusculas($data->val($j, 'B'));
   $APELLIDO1 = mayusculas($data->val($j, 'C'));
   $APELLIDO2 = mayusculas($data->val($j, 'D'));
   /* if($LPA_ID=='') continue; */

   echo '<tr>' . "\n";
   echo '<td align="left">' . $j . '&nbsp;</td>' . "\n";
   echo '<td align="left">' . $LPA_ID . '&nbsp;</td>' . "\n";
   echo '<td align="left">' . $NOMBRE . '&nbsp;</td>' . "\n";
   echo '<td align="left">' . $APELLIDO1 . '&nbsp;</td>' . "\n";
   echo '<td align="left">' . $APELLIDO2 . '&nbsp;</td>' . "\n";
   echo '</tr>' . "\n";

   
   $sql  = "INSERT INTO M_LPA  (";
   $sql .= "LPA_ID,";
   $sql .= "NOMBRE,";
   $sql .= "APELLIDO1,";
   $sql .= "APELLIDO2";
   $sql .= ") VALUES (";
   
   $sql .= "'" . $LPA_ID . "'," ;
   $sql .= "'" . $NOMBRE . "'," ;
   $sql .= "'" . $APELLIDO1 . "'," ;
   $sql .= "'" . $APELLIDO2 . "'" ;
   $sql .= ")";

   $query->sql = $sql;
   $query->ejecuta_query();

	if(strlen($query->regi['ERROR']))
	{
		echo $query->regi['ERROR']."<br/>\n";
		limpiar();
		die("HA OCURRIDO UN ERROR AL INTENTAR REGISTRAR EL CODIGO ->".$lpa_id."<br/>\n");
	}

}

echo '</table>' . "<br/>\n";
echo "<b>SE HA IMPORTADO EL ARCHIVO DE FORMA EXITOSA</b><br/>\n";

/*
if(file_exists($archivo_destino)){
	if(unlink($archivo_destino)) echo "ARCHIVO TEMPORAL LIMPIADO->".$archivo_destino. "<br/>\n";
}
else echo "EL ARCHIVO TEMPORAL <b>".$archivo_destino."<b/> NO EXISTE<br/>\n";
*/

die();


function mayusculas($texto){

	$texto = str_replace('á', 'a', $texto);
	$texto = str_replace('é', 'e', $texto);
	$texto = str_replace('í', 'i', $texto);
	$texto = str_replace('ó', 'o', $texto);
	$texto = str_replace('ú', 'u', $texto);
	$texto = strtoupper($texto);
	$texto = trim($texto);
	return $texto;
}

function MDY($fecha){
	$tmp = split('/', $fecha);
	$fecha = $tmp[1] . '/' . $tmp[0] . '/' .$tmp[2];
	return $fecha;
}

?>
