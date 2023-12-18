<?php
$xpath 		= '/opt/lampp/';
include_once ($xpath . 'utilidades/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/ach.gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

$query->sql ="UPDATE M_SOLICITUDES_FIRMANTES SET FECHA_FIRMA ='". date('Y-m-d') ."', HORA_FIRMA='". date("H:i:s") ."', ESTATUS='FIRMADO' WHERE LLAVE ='". $_REQUEST['firma'] ."' AND FECHA_FIRMA IS NULL";
$query->ejecuta_query();

$query->sql ="SELECT * FROM V_M_SOLICITUDES_FIRMANTES WHERE LLAVE ='". $_REQUEST['firma'] ."'";
$query->ejecuta_query();
$query->next_record();

echo "<pre>";
echo "Muchas gracias " . $query->Record['NOMBRE_FIRMANTE'] . "\n";
echo "Su firma fue registrada con exito....!";
?>