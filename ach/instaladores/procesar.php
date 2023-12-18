<?php


include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
//echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
encabezado('hola');


$xquery = getvar('query');
//error_reporting(E_ALL ^ E_NOTICE);

echo <<<EOT
<body>

<div >
<table class="grid_cuadro_activo">



EOT;



$query = new sql(0);
$query->sql = $xquery;

$query->ejecuta_query();

if(strlen($query->regi['ERROR'])){
    echo ($query->regi['ERROR']) ;
}

else {
	$detalle = '';	
	if($query->tipo!='select'){
		echo "ok";
	}else{		
		while ($query->next_record()){
			unset($tag);
			
			$detalle .= '<tr class="grid_celda" onmouseover="this.className=\'grid_row_activo\'" onmouseout="this.className=\'grid_celda\'">' . "\n";
			
			foreach($query->Record as $campo=>$valor)
			{

				$tmp['tag']   = $campo;
				$tmp['value'] = $valor;
				$tag[] = $tmp;

				$detalle .= '<td class="grid_celda" onmouseover="this.className=\'grid_celda_resaltada\'" onmouseout="this.className=\'grid_celda\'">' . $valor . '</td>' . "\n";
			}
			
			$detalle .= '</tr>' . "\n";

		}

		
		$cabecera = '<tr>' . "\n";
		
		for($h=0; $h<sizeof($tag); $h++){
			$cabecera .= '<td class="grid_encab">' . $tag[$h]['tag'] . '</td>' . "\n";
		}
		$cabecera .= '</tr>' . "\n";
	}
	echo $cabecera;
	echo $detalle;
	
	//echo "QUERY OK";
}


echo <<<EOT

</table>
</div>
</body>
</html>
EOT;


?>