<?php
set_time_limit(0);
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$sufijo = date('YmdHis');
if(!isset($argv)){
	$recibe = file_get_contents("php://input");	
}else{
	system('clear');
	system("printf '\033[3J'");	
	$recibe = file_get_contents( __DIR__ . '/tmp_data/' . $argv[1] . '.json');
	$sufijo = explode('_', $argv[1])[1];
}
if(isset($_REQUEST['debug'])){
	$recibe = file_get_contents(__DIR__ .'/tmp/'. $_REQUEST['debug'] .'.json');
	//die($recibe);
}
if($recibe=='') die('Sin Parametros');

file_put_contents(__DIR__ .'/___'. basename(__FILE__, '.php') .'.json', $recibe);
$param = json_decode($recibe);
file_put_contents(__DIR__ .'/___'. basename(__FILE__, '.php') .'.txt', print_r($param, true));
file_put_contents(__DIR__ .'/tmp/'. $param->_id .'_'. $sufijo .'.json', $recibe);
file_put_contents(__DIR__ .'/tmp/'. $param->_id . '_'. $sufijo .'.txt', print_r($param, true));

define('MAIL_USER', 'ach@notificaciones24.com');
define('DB', 'ach');

$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();

echo "$param->_xform_id_string\n";
exec(__DIR__ . '/formulario.php '. $param->_xform_id_string, $resp, $estatus);

$lastUID= '';
$tSQL = array();

print_r ($param);

foreach($param as $pregunta => $valor){
	if(is_array($valor) || is_object($valor)) continue;
	//echo $pregunta . " => " . utf8_decode(qT($valor, false)) ."\n";
	
	
	if($valor=='_criterio'){
		$grupo = explode('/', $pregunta )[0] . '/criterio';
		echo PHP_EOL . $pregunta . PHP_EOL;
		echo PHP_EOL . $grupo . PHP_EOL;		
		$valor = $param->$grupo ;
	}

	$valor = str_replace('á', 'a', $valor);	
	$valor = str_replace('é', 'e', $valor);	
	$valor = str_replace('í', 'i', $valor);	
	$valor = str_replace('ó', 'o', $valor);	
	$valor = str_replace('ú', 'u', $valor);	

	$valor = str_replace('Á', 'A', $valor);	
	$valor = str_replace('É', 'E', $valor);	
	$valor = str_replace('Í', 'I', $valor);	
	$valor = str_replace('Ó', 'O', $valor);	
	$valor = str_replace('Ú', 'U', $valor);
	
	
	$xpregunta = explode('/', $pregunta );
	//print_r ($xpregunta);
	$nref = array_pop( $xpregunta );
	
	
	
	$sql =   "SELECT * FROM TMP_RESPUESTAS  WHERE UID = '". $param->_id . "' AND REFERENCIA='".  $nref ."'";
	$query->sql = $sql;
	$query->ejecuta_query();
	if(!$query->next_record()){	
		$FUNICO = $param->_xform_id_string . $param->_id;
		if($lastUID=='') $lastUID = $FUNICO;
		$sql = 	"INSERT INTO TMP_RESPUESTAS (";
		$sql.= 	"FUID";
		$sql.= 	",REFERENCIA";
		$sql.= 	",UID";
		$sql.= 	",VALOR_C";
		$sql.=	",ESTATUS";  
		$sql.=	",FUNICO";
		$sql.=	") VALUES (";
		$sql.=	"'".  $param->_xform_id_string ."'";
		$sql.=	",'". $nref ."'";	
		$sql.=	",'".  $param->_id ."'";
		$sql.=	",'".  utf8_decode(qT($valor, false)) ."'";	
		$sql.=	",'". 'ACT' ."'";	
		$sql.=	",'". $FUNICO ."'";	
		$sql.=	")";
		
		$tSQL[] = $sql;
	}else{
		die($sql);
	}
}

	foreach($tSQL as $sql){
		exec_SQL($sql);
	}	

//$sql =   "SELECT FIRST 1 * FROM M_KOBO_FORMULARIOS WHERE FUID = '". $param->_xform_id_string . "' AND UID='". $lastUID ."'";
//$sql =   "SELECT * FROM M_KOBO_FORMULARIOS WHERE FUNICO = '". $lastUID ."'";
//$query->sql = $sql;
//$query->ejecuta_query();

//$sql = "UPDATE M_KOBO_FORMULARIOS SET CORREO='*' WHERE ID_M_KOBO_FORMULARIOS='". $query->Record['ID_M_KOBO_FORMULARIOS'] ."'";
$sql = "UPDATE M_KOBO_FORMULARIOS SET CORREO='*' WHERE FUNICO = '". $lastUID ."'";
exec_SQL($sql);
file_put_contents(__DIR__ . '/__querxxxx.txt', $sql);

/*
if($query->next_record()){	
	//file_put_contents(__DIR__ . '/querxxxx.txt', $sql);
}
*/

$sql = "UPDATE TMP_RESPUESTAS SET CAMPO1='X' WHERE FUID='". $param->_xform_id_string ."' AND ID_M_FORMULARIOS IS NULL";
exec_SQL($sql);

file_put_contents(__DIR__ .'/___'. basename(__FILE__, '.php') .'.txt',  "\n\n-----------fin---------", FILE_APPEND);


//echo "fin";

function exec_SQL($sql_query){
	$query2 = new sql();
	$query2->DBHost     = "127.0.0.1";
	$query2->DBDatabase = "/opt/lampp/firebird/db/". DB .".gdb";
	$query2->DBUser     = "SYSDBA";
	$query2->DBPassword = "masterkey";
	$query2->Initialize();
	$resp = 0;
	$ejecutar=1;
	$i=0;
	$query2->sql = $sql_query . ' /* '. date('Y-m-d H:i:s') .' */' ;	
	
	echo "\n". $query2->sql . "\n";	
	while($ejecutar)
	{
		$i++;
		$ejecutar=0;
		$query2->beginTransaction();
		$query2->ejecuta_query();
		echo "ejecutando...";
		if(strlen($query2->erro_msg)>0)
		{
			$ejecutar=1;
			echo "\nERROR:\n";
			echo $query2->sql . "\n";
			echo $query2->erro_msg . "\n";
			echo "\nEjecutando $i de 5 :\n";
			$query2->erro_msg = '';
			sleep(3);
		}
		if($i>=5) $ejecutar=0;
	}
	echo "Listo\n";	
	$query2->commit();
	$resp = $query2->Reg_Afect;
	$query2->close();
    unset($query2);
	return $resp;
}

function lT($xTime){
	$startTime = $xTime;
	$timeI = strtotime($startTime);
	$LocalDate = date("Y-m-d H:i:s", $timeI);
	return $LocalDate;	
}

function dT($startTime, $endTime){
	$date1 = new DateTime($startTime);
	$date2 = new DateTime($endTime);
	$diff = $date1->diff($date2);

	$duracion = $diff->h < 10 ? '0' . $diff->h : $diff->h;
	$duracion.= $diff->i < 10 ? ':0' . $diff->i : ":".$diff->i;
	$duracion.= $diff->s < 10 ? ':0' . $diff->s : ":".$diff->s;
	$tmp['h'] = $duracion;
	$tmp['o'] = $diff;
	return $tmp;
}


function qT($cadena, $upper = true){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena,  utf8_decode($originales), $modificadas);
    if($upper) $cadena = strtoupper($cadena);
    return utf8_encode($cadena);
}



?>