<?php
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");

control_session();
$query = new sql();
$query->sql = $_REQUEST['query'];
$query->ejecuta_query();

if(!$query->erro){
	$tmp['arreglo_atributos'] = $query->arreglo_atributos;
	while($query->next_record()){
		$tmp['registro'][] = utf8_converter( $query->Record );
	}
}

echo json_encode ( $tmp);

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    }); 
    return $array;
}


?>