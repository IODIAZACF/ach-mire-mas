<?php

echo "<pre>";

define('path_Utilidades',   '/opt/lampp/utilidades/');
include_once (path_Utilidades 	. 'php/clases/class_sql.php');

$db =$_SESSION['db'];

$query = new sql();
$query->DBHost     = "127.0.0.1";
$query->DBDatabase = "/opt/lampp/firebird/db/". $db .".gdb";
$query->DBUser     = "SYSDBA";
$query->DBPassword = "masterkey";
$query->Initialize();
	
require(__DIR__ . '/XLSXReader.php');

@mkdir('../../../../tmp/', 0777);


/******************** inventario ******************************/
echo "Procesando: " . strtolower($_FILES['inventario']['name']) . ";\n";
$recibido = $_FILES["inventario"]["tmp_name"];
move_uploaded_file($recibido, "../../../../tmp/inventario.xlsx");
$xlsx = new XLSXReader("../../../../tmp/inventario.xlsx");

$sheetNames = $xlsx->getSheetNames();

/*print_r(array_values($sheetNames));*/
/*$xhoja  = "a18072021";*/
$hoja  = $_REQUEST['hoja'];

$sheet = $xlsx->getSheet($hoja);
$xdata = $sheet->getData();

/*
$query->sql = "DELETE FROM TMP_CARGA_PEDIDOS";
$query->ejecuta_query();
*/


$xcliente=''; /* Nombre del cliente en fila 1 del excel */
$col = 4; /* Columna del excel */
while ($xcliente <> 'VENDIDO') {
echo($xcliente);
	$col++;
	$xcliente= trim($xdata[0][$col]) ;

	if($xcliente <> 'VENDIDO'){ 
		echo $xcliente . ";\n";
		$cantidad2 = 0;
		for($fila=1;$fila<sizeof($xdata);$fila++)
		{
			$xsobrante= trim($xdata[$fila][0]) ;
			$xproducto= trim($xdata[$fila][1]) ;
			$xcantidad2= $xdata[$fila][$col] ;
			if($xcantidad2==""){
				$xcantidad2=0;
			}
			if(is_string($xcantidad2)){
				continue;
			}
			$ar_xcliente = explode(" ", $xcliente);
			$xpedido = sizeof($ar_xcliente);
			if($xpedido>1){
				$xpedido = $xpedido -1;
				if(is_numeric($ar_xcliente[$xpedido]) ){
					$pedido =   $ar_xcliente[$xpedido];
				}
				else{
					$pedido=1;
				}
			}
			else{
				$pedido = 1;
			}
		    $sql = "UPDATE OR INSERT INTO TMP_CARGA_PEDIDOS(";
			$sql.= "CLIENTE,";
			$sql.= "SOBRANTE,";
			$sql.= "PRODUCTO,";
			$sql.= "CANTIDAD,";
			$sql.= "PEDIDO,";
			$sql.= "HOJA";
			$sql.= ") VALUES (";
			$sql.= "'". trim( str_replace($pedido,"",$xcliente) ) ."',";
			/*$sql.= "'". utf8_decode($xsobrante)  ."',";*/
			/*$sql.= "'". $xsobrante  ."',";*/
			$sql.= "'". utf8_decode($xsobrante)  ."',";
			$sql.= "'". utf8_decode($xproducto)  ."',";
			$sql.= "'". $xcantidad2 ."',";
			$sql.= "'". $pedido ."',";
			$sql.= "'". $hoja ."'";
			$sql.= ")";
			$sql.= " MATCHING(CLIENTE, SOBRANTE, PRODUCTO, PEDIDO, HOJA)";
			
			echo $sql . ";\n";
			
			$query->sql =  $sql;
			$query->ejecuta_query(); 
			if($query->erro_msg!=''){
				print_r($query);
				if(substr($query->erro_msg, 0, 9)=='exception'){
					$t = explode('<', explode('>', $query->erro_msg)[0])[1];
					echo '<br><b>' . $t . '</b>';
				
					$query->sql = "DELETE FROM TMP_CARGA_PEDIDOS";
					$query->ejecuta_query();
					die();
				}
			die('Error: ' . $sql);    
			}
		}
		//echo 'fin - ' . $xproducto . ";\n";
	}
	echo 'fin - ' . $xcliente . ";\n";
}
echo 'fin ';
echo " OK\n";
echo " Fin de Proceso";

function xNum($n){
	$n = str_replace('.', '', $n);
	$n = str_replace(',', '.', $n);
	return $n;
	
} 
?>