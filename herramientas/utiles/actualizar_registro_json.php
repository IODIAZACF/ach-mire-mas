<?php
define("Server_Path","../../");
include_once(Server_Path . "herramientas/jwt/jwt.php");
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
include_once(Server_Path . "herramientas/ini/class/class_ini.php");
$Usa_log = true;


//rdebug($_SESSION);
//control_session();
if(getvar('onload')) require(Server_Path . "script/". getvar('onload')  . ".php");
unset($query);

for($i=0;$i<sizeof($db_server);$i++)
{
	$query[$i] = new sql($i);
}

$variables = leer_vars("c_");

for($i=0;$i<sizeof($variables);$i++) {
	
	$variables[$i]['valor'] = strtolower($variables[$i]['valor']) =='undefined' ? "" : $variables[$i]['valor'];

    $valor_sql  = strlen($variables[$i]['valor']) ? $variables[$i]['valor'] : "";
    $valor      = strlen($variables[$i]['valor']) ? $variables[$i]['valor'] : "";
	
    $parameter      = $variables[$i]['nombre']; 
	$xPOS 			= strrpos($parameter, "_"); 
    $parameter_name = substr ($parameter,0, $xPOS);
    $parameter_tipo = substr ($parameter, $xPOS+1,1);

    switch ($parameter_tipo){
        case 'F':
            if(strlen($valor)){
				$valor_sql = $valor;
            }
            else{
				$valor_sql = "NULL";
            }
            break;
        case 'R':
            if(strlen($valor)){
				$valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else{
				$valor_sql = "NULL";
            }
            break;
        case 'C':
            if(strlen($valor)){
				$valor_sql = "'" . addcslashes(  Mayuscula ( $valor ) , "'") . "'";
            }
            else{
				$valor_sql = "''";
            }
			break;
        case 'X':
        case 'B':
        case 'L':
            if(strlen($valor)){
				$valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else {
				$valor_sql = "''";
            }
            break;
        case 'N':
        case 'I':
            if($valor != "") {
                $valor_sql = str_replace(',','', $valor_sql);
            }
            else{
				$valor_sql = 0;
            }
            break;

        case '5':
            if($valor != "") {
                $valor_sql = "'". md5(strtoupper($valor_sql)) . "'" ;
            }
            else {
				$valor_sql = "NULL";
            }
            break;


        case 'D':
            if($valor != ""){
				$valor_sql = "'". $valor . "'";
            }
            else{
				$valor_sql = "NULL";
            }
            break;
    }
    $items['name']      = $parameter_name;
    $items['value']     = $valor;
    $items['value_sql'] = getvar('formato') == '' && $parameter_tipo!='L' ? strtoupper($valor_sql) :  $valor_sql;
    $items['tipo']      = $parameter_tipo;
    if($parameter_name) $query[0]->camposql[]   = $items;
    if($parameter_name) $camposql[]   = $items;
}

$Url_Modulo = '';
$Url_Modulo = isset($_REQUEST['url_modulo']) ? dirname($_REQUEST['url_modulo'],2) : '';
if(getvar('origen')){
	if( strlen ( $Url_Modulo ) > 1 ){
		$origen = Server_Path . $Url_Modulo  . '/' .  getvar('origen');
		if (str_contains($Url_Modulo, 'herramientas')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
		if (str_contains(getvar('origen'), 'maestros/')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
	}else{
		$origen = RUTA_SISTEMA . getvar('origen');
	}
}else{
	$origen         = "";		
}


$procedimiento  = getvar('procedimiento');

if($procedimiento!= '' && $origen!=''){
	 $my_ini = new ini($origen);
	 $query[0]->sql = $my_ini->seccion('SQL',$procedimiento);
	 $query[0]->sql  = str_replace("&lt;", '<', $query[0]->sql);
	 $query[0]->sql  = str_replace("&gt;", '>', $query[0]->sql);

	 preg_match_all('/{(.*?)}/', $query[0]->sql ,$variables);
	 for($i=0;$i<sizeof($variables[1]);$i++){
		$variable  = $variables[1][$i];
		$xvalor = getvar($variable);

		if($xvalor){
		   $query[0]->sql = str_replace('{' . $variable . '}', $xvalor , $query[0]->sql);
		}else{
			$xREG['tabla']['estatus']="ERROR";			
			$xREG['tabla']['msg']=utf8_encode( 'Faltan Parametros para ejecutar el procedimiento '. $variable );
			$fp = fopen($DB_LOG_ERROR . "sql_$i.txt","w+");
			$linea = date("d-m-Y h:i:s "). $query[0]->erro_msg.'|'.$query[0]->sql . "\n";
			fwrite($fp, $linea);
			fclose($fp);
			
			echo  json_encode($xREG);
			die('');
		}
	}
	
	$query[0]->ejecuta_query();
	$unico = $query[0]->unico;
    $query[0]->next_record();

    foreach($query[0]->Record as $campo => $valor)
   	{
     	$tmpReg[$campo]=utf8_encode($valor);
   	}
    $xREG['tabla']['estatus']="OK";
    $xREG['tabla']['registro']=$tmpReg;	

}
else{
	$query[0]->tabla        = getvar('tabla');
	$query[0]->busca        = getvar('busca');
	$query[0]->xbusca       = getvar('xbusca');
	$query[0]->operador     = '=';
	$query[0]->accion       = getvar('accion');
	if(getvar('xbusca')<0)
	{
		$query[0]->tipo   = getvar('xbusca') == -1 ? "insert" : "delete";
		$query[0]->xbusca = substr(getvar('xbusca'),1);
	}
	else
	{
		$query[0]->tipo = "update";
	}

	$query[0]->arma_sql();
	$query[0]->ejecuta_query();
	$unico = $query[0]->unico;

	//rdebug($query[0]);
	if($query[0]->Reg_Afect)
	{
		$xREG['tabla']['estatus']="OK";
		$query[0]->sql = 'select * from '. $query[0]->tabla.' where unico = \''.$unico.'\'';
		$query[0]->ejecuta_query();
		$query[0]->next_record();


		$archivos = leer_vars("a_");
		if(is_array($archivos))
		{
			//rdebug($archivos);
			$xquery = $query[0];
			$XTABLA = $query[0]->tabla;
			for($i=0;$i<sizeof($archivos);$i++)
			{
				if($archivos[$i]['valor']!=''){
					
					$parameter      = $archivos[$i]['nombre'];                    //NOMBRES_TS
					$parameter_name = substr ($parameter,0, strlen($parameter)-4); //NOMBRES
					$parameter_tipo = substr ($parameter, strlen($parameter)-1,1); //F=file o T=tabla
					
					//die($parameter_name);
				   
					$XIDX =  'ID_'. $XTABLA;
					$IDX = $query[0]->Record[$XIDX];
					$xfile = $archivos[$i]['nombre'];
					$extension = strtolower(end( explode('.', $_REQUEST['n_'. $parameter_name . '_6SS'])));

					$nombre_archivo =  $IDX . '.'. $extension;
					if($parameter_tipo=='T') $nombre_archivo =  $IDX . '_'. md5($_REQUEST['n_'. $parameter_name. '_6SS'] . date("YmdHis")) . '.'. $extension;
					$XNOMBRE = 'ID_'. $XTABLA;
					
					$path  = Server_Path . 'imagenes/' . strtolower($XTABLA) . '/';
					@mkdir($path, 0777,true);
					@chmod($path, 0777);
					$contenido = base64_decode($archivos[$i]['valor']);
					$fp = fopen($path . $nombre_archivo, "wb");
					fwrite($fp, $contenido);
					fclose($fp);
					@chmod($path . $nombre_archivo, 0777);
					
					if($parameter_tipo=='T'){
						$xquery->sql = "INSERT INTO D_ARCHIVOS (IDX,TABLA,NOMBRES,CLASE) VALUES ('". $IDX ."','". $XTABLA ."', '". $nombre_archivo ."', 'TAREA')";
						$xquery->ejecuta_query();
					}
				}
			}
		}

		foreach($query[0]->Record as $campo => $valor)
		{
			$tmpReg[$campo]=utf8_encode($valor);
		}
		$xREG['tabla']['estatus']="OK";
		$xREG['tabla']['registro']=$tmpReg;
	}
	else
	{
		$xREG['tabla']['estatus']="ERROR";
		$xREG['tabla']['msg']=utf8_encode($query[0]->erro_msg);
		$xREG['tabla']['QUERY']=utf8_encode($query[0]->sql);
		//$fp = fopen("/opt/lampp/htdocs/sql_$i.txt","w+");
		$fp = fopen($DB_LOG_ERROR . "sql_$i.txt","w+");
		$linea = date("d-m-Y h:i:s "). $query[0]->erro_msg.'|'.$query[0]->sql . "\n";
		fwrite($fp, $linea);
		fclose($fp);
	}
}
echo  json_encode($xREG);
die('');
?>
