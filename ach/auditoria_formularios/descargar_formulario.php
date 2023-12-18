<?php
ob_clean();
ob_start();

include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$query = new sql();

$conta = 0;

$query->sql ="SELECT * FROM V_M_KOBO_RESPUESTAS WHERE ROTULO IS NOT NULL AND ID_M_KOBO_FORMULARIOS='" . $_REQUEST['ID_M_KOBO_FORMULARIOS'] . "' ORDER BY POSICION_GRUPO,POSICION,XROTULO,XROTULO_PLANTILLA";
$query->ejecuta_query();

unset($C);

$C[] = 'CODIGO_ALERTA';														//Codigo
$C[] = 'FECHA_FORMULARIO';													//Fecha
$C[] = 'FORMULARIO';														//Nombre del Formulario
$C[] = 'CODIGO';															//Rotulo
$C[] = 'ROTULO';															//Rotulo
$C[] = 'VALOR';																//VAlor
$registro = join(';', $C);
echo $registro . "\n";


while( $query->next_record() ) {

	unset($C);
    $conta++;
    $C[] = $query->Record['XCODIGO_ALERTA'] ;									//Codigo Alerta Concatenado
    $C[] = substr($query->Record['FECHA_FORMULARIO'] ,0 , 10);					//Fecha
    $C[] = $query->Record['NOMBRE_FORMULARIO'];									//Nombre del Formulario
    $C[] = $query->Record['ID_P_FORMULARIOS'];									//Codigo de la pregunta
    $C[] = $query->Record['ROTULO'];											//Rotulo de la pregunta
    $C[] = str_replace( ';', ' ', str_replace( PHP_EOL, " ", $query->Record['VALOR'] ) ) ;					//Valor

	$registro = join(';', $C);
	echo $registro . "\n";
}




$salida = ob_get_contents();
$nombre = 'formulario_' . $_REQUEST['ID_M_KOBO_FORMULARIOS']  . '.csv' ;

ob_end_clean();

header( "Content-type: application/octet-stream" );
header( "Content-Disposition: attachment; filename={$nombre}" );
header( "Pragma: no-cache" );
header( "Expires: 0" );

echo $salida;
die();


?>