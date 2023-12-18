<?php

$FUID = 'aN2rTeYLhRVH5UZj5UdBtB';

define('DB', 'desarrollo_ach');

$xpath = '/opt/lampp/utilidades';
$xtmp  = '/opt/tmp/';

include_once ($xpath . '/php/clases/class_sql.php');

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/" . DB . ".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();



require_once __DIR__ . '/vendor/autoload.php';


use Aspera\Spreadsheet\XLSX\Reader;
use Aspera\Spreadsheet\XLSX\Worksheet;

$reader = new Reader();
$reader->open($FUID . '.xlsx');
$sheets = $reader->getSheets();

/** @var Worksheet $sheet_data */
foreach ($sheets as $index => $sheet_data) {
    echo 'Sheet #' . $index . ': ' . $sheet_data->getName();

    $reader->changeSheet($index);

	$reg = 0;
	$fid= 0;
    foreach ($reader as $row) {
		$reg++;
		if($reg==1){
			$xpregunta = $row;
		}else{
			$fid++; 
			foreach($row as $id => $valor){
				$pregunta = $xpregunta[$id];
				$sql = 	"INSERT INTO TMP_RESPUESTAS (";
				$sql.= 	"FUID";
				$sql.= 	",REFERENCIA";
				$sql.= 	",UID";
				$sql.= 	",VALOR_C";
				$sql.=	",ESTATUS";   
				$sql.=	") VALUES (";
				$sql.=	"'".  $FUID ."'";
				$sql.=	",'/data/".  $pregunta ."'";	
				$sql.=	",'".  $fid ."'";
				$sql.=	",'".  utf8_decode(qT($valor, false)) ."'";	
				$sql.=	",'". 'ACT' ."'";	
				$sql.=	")";

				exec_SQL($sql);
			}

			
		}
    }
}



$reader->close();

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