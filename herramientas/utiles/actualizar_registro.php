<?
include_once(Server_Path . "herramientas/utiles/comun.php");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
include_once(Server_Path . "herramientas/ini/class/class_ini.php");
$Usa_log = true;

control_session();
if(getvar('onload')){
	$script = RUTA_SISTEMA . "script/". getvar('onload')  . ".php";
	if(!file_exists ( $script) ) die('Script Trigger OnLoad: '. getvar('onload') . ' No Existe');
	include_once ( $script ); 
} 

if(getvar('unload')){
	$script = RUTA_SISTEMA . "script/". getvar('unload')  . ".php";
	if(!file_exists ( $script) ) die('Script Trigger UnLoad: '. getvar('unload') . ' No Existe');
} 


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


if(strlen(getvar('forma')))
{
	if(getvar('forma')==1) {
	    $query[0]->sql = "DELETE FROM ". getvar('tabla') . " where ".  getvar('busca')  ."='". getvar('c_IDX_CSN') ."'";
        $xsql = $query[0]->sql;

        for($i=0;$i<sizeof($db_server);$i++)
        {
            $query[$i]->sql = $xsql;
            $query[$i]->ejecuta_query();
        }

        $query[0]->tabla        = getvar('tabla');
	    $query[0]->busca        = getvar('busca');
	    $query[0]->xbusca       = getvar('xbusca');
	    $query[0]->operador     = '=';
        $query[0]->accion       = getvar('accion');
        $query[0]->tipo         = "insert";
        $query[0]->arma_sql();
        $xsql = $query[0]->sql;

        for($i=0;$i<sizeof($db_server);$i++)
        {
            $query[$i]->sql = $xsql;
            $query[$i]->ejecuta_query();
        }

        $query[0]->sql = "SELECT * FROM " . getvar('tabla') . " INNER JOIN P_MAESTROS ON(P_MAESTROS.TABLA = '". substr(getvar('tabla'),2) ."') WHERE IDX = '" . getvar('c_IDX_CSN') . "'";
        $query[0]->ejecuta_query();
		

	    
    	$salida = '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
    	$salida = '<tabla>' . "\n";
    	$salida .='<registro>' . "\n";

	    while($query[0]->next_record())
        {
            $campo = $query[0]->Record['NOMBRES'];
            $valor = $query[0]->Record[$campo];
         	$valor = '<![CDATA['.  stripslashes ($valor) .']]>';
         	$salida .='<'.$campo.'>'.$valor.'</'.$campo.'>' . "\n";
    	}

	    $salida .='<ERROR>' . "\n";
	    $salida .='<![CDATA['. $query[0]->erro_msg .']]>' . "\n";
	    $salida .='</ERROR>' . "\n";

	    $salida .='<QUERY>'. "\n";
	    $salida .='<![CDATA['. $query[0]->sql .']]>';
	    $salida .='</QUERY>' . "\n";
	    $salida .='<UNICO>'. $query[0]->unico .'</UNICO>' . "\n";
	    $salida .='<RETORNO>'. getvar('ejecutar') .'</RETORNO>' . "\n";
	    $salida .='</registro>' . "\n";
	    $salida .='</tabla>' . "\n";
		
		if(getvar('unload')){
			$script = RUTA_SISTEMA . "script/". getvar('unload')  . ".php";
			if(!file_exists ( $script) ) die('Script Trigger UnLoad: '. getvar('unload') . ' No Existe');
			include_once( $script );
		} 

		header('Content-Type: application/xml');
	    echo $salida;
        die();

    }
	if(getvar('forma')!='1')
    {
        for($i=0;$i<sizeof($camposql);$i++)
	    {
	        $xsql = "DELETE FROM ". getvar('tabla') . " WHERE ".  getvar('busca')  ."='". getvar('xbusca') ."' AND ". getvar('filtro') ."='". $camposql[$i]['name'] ."'";
	        $query[0]->sql = $xsql;
	        for($j=0;$j<sizeof($db_server);$j++)
	        {
	            $query[$j]->sql = $xsql;
	            $query[$j]->ejecuta_query();
	        }
        }

        for($i=0;$i<sizeof($camposql);$i++)
	    {
            //$camposql[$i]['name']= substr($camposql[$i]['name'],1);
	        if($camposql[$i]['value'])
	        {
	            $campo = '';
	            switch ($camposql[$i]['tipo'])
	            {
	               case 'D':
	                  $campo = 'VALOR_FECHA';
	                  break;

	               case 'N':
	                  $campo = 'VALOR_NUMERO';
	                  break;

	               default:
	                  $campo = 'VALOR_TEXTO';
	                  break;

	            }
	            $xsql = "INSERT INTO " .  getvar('tabla') ."(". getvar('busca') . ", ". getvar('filtro') .",". $campo .") VALUES('". getvar('xbusca') ."','". $camposql[$i]['name'] ."', ". $camposql[$i]['value_sql'] .")";
                $query[0]->sql = $xsql;
                for($s=0;$s<sizeof($db_server);$s++)
                {
                    $query[$s]->sql = $xsql;
                    //echo $xsql . "<br>";
                    $query[$s]->ejecuta_query();
                }
	        }
	    }
        //guardo el rotulo de los botones maestros para los reportes o herramientas ..ojo...
        $variables = leer_vars("r_");
        for($i=0;$i<sizeof($variables);$i++)
	    {
	        $parameter      = $variables[$i]['nombre'];                    //NOMBRES_TS
	        $parameter_name = substr ($parameter,0, strlen($parameter)-4); //NOMBRES
	        $parameter_tipo = substr ($parameter, strlen($parameter)-3,1); //TS /TN
            $variables[$i]['nombre'] = substr($parameter_name,1);

	        $xsql = "UPDATE " .  getvar('tabla') ." SET ROTULO='".  $variables[$i]['valor'] . "' WHERE ". getvar('busca')  ."='". getvar('xbusca') ."' AND " . getvar('filtro') . "='" . $variables[$i]['nombre'] . "'";
            $query[0]->sql = $xsql;
            for($s=0;$s<sizeof($db_server);$s++)
            {
            	$query[$s]->sql = $xsql;
                $query[$s]->ejecuta_query();
            }
        }
        //rdebug($variables,'ss');
		header('Content-Type: application/xml');
        echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
        echo '<tabla>' . "\n";
        echo '<registro>' . "\n";
        echo '<ERROR>' . "\n";
        echo '<![CDATA['. $query[0]->erro_msg .']]>' . "\n";
        echo '</ERROR>' . "\n";
        echo '<QUERY>'. "\n";
        echo '<![CDATA['. $xsql .']]>';
        echo '</QUERY>' . "\n";
        echo '<UNICO>'. $query[0]->unico .'</UNICO>' . "\n";
        echo '<RETORNO></RETORNO>' . "\n";
        echo '</registro>' . "\n";
        echo '</tabla>' . "\n";
		if(getvar('unload')){
			$script = RUTA_SISTEMA . "script/". getvar('unload')  . ".php";
			if(!file_exists ( $script) ) die('Script Trigger UnLoad: '. getvar('unload') . ' No Existe');
			include_once( $script );
		} 


        die();

	}
}
else
{	
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

		 //echo $query[0]->sql;
		  //Curly Brackets
		 preg_match_all('/{(.*?)}/', $query[0]->sql ,$variables);
		 //print_r($variables);
		 //die();
         for($i=0;$i<sizeof($variables[1]);$i++){
            $variable  = $variables[1][$i];
            //$xvariable = str_replace('{','', $variable);
	        //$xvariable = str_replace('}','', $xvariable);
            $xvalor = getvar($variable);

            if($xvalor){
               $query[0]->sql = str_replace('{' . $variable . '}', $xvalor , $query[0]->sql);
            }else{
				header('Content-Type: application/xml');
	            echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
	            echo '<tabla>' . "\n";
	            echo '<registro>' . "\n";
	            echo '<ERROR>';
	            echo '<![CDATA[Faltan Parametros para ejecutar el procedimiento '. $variable .']]>' . "\n";
	            echo '</ERROR>' . "\n";
	            echo '<QUERY>';
	            echo '<![CDATA[]]>';
	            echo '</QUERY>' . "\n";
	            echo '<UNICO></UNICO>' . "\n";
	            echo '<RETORNO></RETORNO>' . "\n";
	            echo '</registro>' . "\n";
	            echo '</tabla>' . "\n";
                die();
            }
         }

        $xsql = $query[0]->sql;
		
        for($i=0;$i<sizeof($db_server);$i++){
            $query[$i]->sql = $xsql;
            $query[$i]->ejecuta_query();
        }
		header('Content-Type: application/xml');
	    echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
	    echo '<tabla>' . "\n";
	    echo '<registro>' . "\n";
	    echo '<ERROR><![CDATA['. trim($query[0]->erro_msg)  .']]></ERROR>' . "\n";
	    echo '<QUERY>';
	    echo '<![CDATA['. $xsql .']]>';
	    echo '</QUERY>' . "\n";
	    echo '<UNICO>'. $query[0]->unico .'</UNICO>' . "\n";
	    echo '<RETORNO></RETORNO>' . "\n";
	    echo '</registro>' . "\n";
	    echo '</tabla>' . "\n";
		if(getvar('unload')){
			$script = RUTA_SISTEMA . "script/". getvar('unload')  . ".php";
			if(!file_exists ( $script) ) die('Script Trigger UnLoad: '. getvar('unload') . ' No Existe');
			include_once( $script );
		} 


        die();

    }
    else
    {
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
        $xsql = $query[0]->sql;
		

        for($i=0;$i<sizeof($db_server);$i++)
	    {
            $query[$i]->sql          = $xsql;
	        $query[$i]->tabla        = getvar('tabla');
	        $query[$i]->busca        = getvar('busca');
	        $query[$i]->xbusca       = getvar('xbusca');
	        $query[$i]->operador     = '=';
	        $query[$i]->tipo = getvar('xbusca') == -1 ? "insert" : "update";
			$query[$i]->ejecuta_query();
            $unico = $query[0]->unico;
	        if (!$query[$i]->erro)
	        {

	            $query[$i]->sql = 'select * from '. $query[$i]->tabla.' where unico = \''.$unico.'\'';
                $query[$i]->ejecuta_query();
	        }
            else
            {
                //$fp = fopen("/opt/lampp/htdocs/sql_$i.txt","w+");
                $fp = fopen($DB_LOG_ERROR . "sql_$i.txt","w+");
                $linea = date("d-m-Y h:i:s "). $query[$i]->erro_msg.'|'.$query[$i]->sql . "\n";
                fwrite($fp, $linea);
                fclose($fp);
            }
	    }
	//---- MARCO: 30/01/2006..
        //rdebug($query,'s');
    }
//---- MARCO: fin de cambios..
}

	if($query[0]->erro_msg==''){
		$salida =  '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
		$salida ='<tabla>' . "\n";
		$salida .='<registro>' . "\n";
		$n = 0;
		if($query[0]->next_record()){
			if($query[0]->xbusca=='-1')
			{
				$log_db  = Server_Path . 'txt-databases/log_'.Sistema;			
				@mkdir($log_db);
				$tmp_tabla       = "log_" . date("Y-m-d");
				$file_log        = $log_db . "/log_" . date("Y-m-d") . '.txt';
				if(!file_exists( $file_log )){
					file_put_contents($file_log, "id#tiempo#ip#session_id#fecha_ini#fecha_fin#usuario#modulo#script#unico#sql\n");
				}

				$ini = time();
				$ip         = $_SERVER['REMOTE_ADDR'];
				$fecha_ini  = 0;
				$usuario    = getsession('M_USUARIOS_NOMBRES');
				$modulo     = '***ACTUALIZA_REGISTRO***';
				$session_id = session_id();
				$script     = '***ACTUALIZA_REGISTRO***';
				$sql        = 'UPDATE ' . $query[0]->tabla.' SET '. $query[0]->busca .' =\''. $query[0]->Record[$query[0]->busca] .'\' where unico = \''.$unico.'\'';
				$tiempo     = 0;
				$fecha_fin  = 0;
				$unico 		= $query[0]->unico;
				file_put_contents($file_log, "$tiempo#$ip#$session_id#$fecha_ini#$fecha_fin#$usuario#$modulo#$script#$unico#$sql_log\n", FILE_APPEND);				
			}

		   foreach($query[0]->Record as $campo => $valor)
		   {
			 $valor = '<![CDATA['.  stripslashes ($valor) .']]>';
			 $salida .='<'.$campo.'>'.$valor.'</'.$campo.'>' . "\n";
		   }
		}
		$salida .='<ERROR>';
		$salida .='<![CDATA['. $query[0]->erro_msg .']]>' . "\n";
		$salida .='</ERROR>' . "\n";

		$salida .='<QUERY>';
		$salida .='<![CDATA['. $query[0]->sql .']]>';
		$salida .='</QUERY>' . "\n";
		$salida .='<UNICO>'. $query[0]->unico .'</UNICO>' . "\n";
		$salida .='<RETORNO>'. getvar('ejecutar') .'</RETORNO>' . "\n";
		$salida .='</registro>' . "\n";
		$salida .='</tabla>' . "\n";
		if(getvar('unload')){
			$script = RUTA_SISTEMA . "script/". getvar('unload')  . ".php";
			if(!file_exists ( $script) ) die('Script Trigger UnLoad: '. getvar('unload') . ' No Existe');
			include_once( $script );
		} 		
	}else{
		$salida =  '<?xml version="1.0" encoding="ISO-8859-1"?>' . "\n\n";
		$salida .='<error>' . "\n";
		$salida .='<ERROR>';
		$salida .='<![CDATA['. $query[0]->erro_msg .']]>' . "\n";
		$salida .='</ERROR>' . "\n";
		$salida .='  <query>'."\n";
		$salida .='    <![CDATA['. $query[0]->sql . ']]>' . "\n";
		$salida .='  </query>'."\n";		
		$salida .='  <resumen>'."\n";
		$salida .='  <FECHA>'. date("d/m/Y") . '  </FECHA>'."\n";
		$salida .='  <HORA>'.  date("H:i:s") . '  </HORA>'."\n";
		$salida .='  </resumen>'."\n";
		$salida .='</error>' . "\n";
	}


	header('Content-Type: application/xml');
    echo $salida;

?>
