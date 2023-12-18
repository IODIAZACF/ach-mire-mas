<?
$info['fecha'] 		= date('d/m/Y');
$info['fecha_ymd'] 	= date('Y/m/d');
$info['fecha_mdy'] 	= date('m/d/Y');
$info['hora'] 		= date('H:i:s');
$info['hora_h'] 	= date('H');
$info['hora_m'] 	= date('m');
$info['hora_s'] 	= date('i');

foreach(get_defined_constants(true)['user'] as $varible=>$valor){
	$info[$varible] 	= utf8_encode ( $valor );
}
echo json_encode ( $info );
?>