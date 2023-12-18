<?php

$xMES[1] ='ENERO';
$xMES[2] ='FEBRERO';
$xMES[3] ='MARZO';
$xMES[4] ='ABRIL';
$xMES[5] ='MAYO';
$xMES[6] ='JUNIO';
$xMES[7] ='JULIO';
$xMES[8] ='AGOSTO';
$xMES[9] ='SEPTIEMBRE';
$xMES[0] ='OCTUBRE';
$xMES[11] ='NOVIEMBRE';
$xMES[12] ='DICIEMBRE';




$query = new sql();
$conta = 0;
$query->sql ="SELECT * FROM V_D_TRANSACCIONES_TOTALIZADA WHERE ID_M_TRANSACCIONES='".getvar('xbusca')."' AND ID_M_CUENTAS_BANCARIAS='" . getvar('ID_M_CUENTAS_BANCARIAS') . "'";
$query->ejecuta_query();

while($query->next_record())
{
    unset($C);
    $conta++;



	$C[] = "PA" . chr(9);       							                    		//PA
	$C[] = str_pad($query->Record['CUENTA_BANCARIA'], 10,  "0", STR_PAD_LEFT) . chr(9);  //cuenta_bancaria
	$C[] = "USD" . chr(9);       							                    		//USD
    $C[] = str_pad(xnum($query->Record['TOTAL']) ,8, " ", STR_PAD_LEFT) . chr(9);     //monto_operacion
	$C[] = "CTA " . chr(9);       							                    		//CTA
    $C[] = $query->Record['TIPO_CUENTA']=='CORRIENTE' ? 'CTE ': 'AHO ';					//tipo de cuenta                                                         //fecha_creacion
	$C[] = "" . chr(9);       							                    			// 
	$C[] = str_pad($query->Record['CUENTA_BANCARIA'], 10,  "0", STR_PAD_LEFT) . chr(9);  //cuenta_bancaria
	$C[] = str_pad($query->Record['DESCRIPCION'], 0, "", STR_PAD_RIGHT) .chr(9);      //descripcion    
    $C[] = $query->Record['NACIONALIDAD']=='N' ? 'C': 'P';								//cedula o pasaporte                                                         //fecha_creacion
	$C[] = "" . chr(9);       							                    			// 
    $C[] = str_pad($query->Record['CODIGO1_TRABAJADOR'] ,10,"0",STR_PAD_LEFT) .chr(9);	//numero_cedula
	$C[] = str_pad($query->Record['NOMBRE_TRABAJADOR'], 0,  "", STR_PAD_RIGHT) . chr(9) . $query->Record['CODIGO_BANCO']; //codigo del banco del trabajador


/*
	//$m =  date('n');
	//$y =  date('Y');
	//echo $xMES[$m]

	$C[] = "MES " . str_pad($xMES[$m], 0,  "0", STR_PAD_LEFT) . chr(9);  //cuenta_bancaria
	
	$C[] = "0036" . chr(9);       							                    		//0036
	$C[] = "" . chr(9);       							                    			// 
	$C[] = str_pad($query->Record['CUENTA_BANCARIA'], 0,  "0", STR_PAD_LEFT) . chr(9);  //cuenta_bancaria
*/	
	//$C[] = $query->Record['CUENTA_EMPRESA'] . chr(9);                                	//numero de cuenta de la empresa
	//$C[] = str_pad($conta, 0, "0", STR_PAD_LEFT) . chr(9);                      		//consecutivo

	$registro = join('', $C);
	echo $registro . "\r\n";
}

unset($C);
$totalreg = ($conta+1);


$salida = ob_get_contents();
ob_end_clean();
@mkdir(Server_Path . "../nomina_digital",0777);
$fp = fopen(Server_Path . "../nomina_digital/".getvar('xbusca') .".txt", "w+");
fwrite($fp,$salida);
fclose($fp); 

die('ok');
?>