<?php
header("Content-type: application/json; charset=utf-8");

$comando = $_REQUEST['comando'];


$respuesta = shell_exec("node /opt/lampp/htdocs/herramientas/meshcentral/node_modules/meshcentral/meshctrl --url wss://192.168.1.130:4432 --loginuser edson --loginpass 424639 " . $comando . " --json" );

//echo $resultado;

echo $respuesta;
//$capture = false;

//print_r( $resultado ); 

/*
foreach( $resultado as $res ){
	$t = substr( $res , 0, 3);
	if( $t == '---' ){
		$capture = true;
		continue;
	}
	if( $capture  ){
		$res = str_replace('"', '', $res);
		$r = explode(',',  $res );
		$tmp['id'] 		= $r[0];
		$tmp['name'] 	= $r[1];
		$tmp['icon'] 	= $r[2];
		$tmp['conn'] 	= $r[3];
		$tmp['pwr'] 	= $r[4];
		 
		$device[] = $tmp;		
	}	
}
*/

//echo json_encode ( $device );
//print_r ( $device  );

?>
