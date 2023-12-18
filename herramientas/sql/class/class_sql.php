<?php
$xconfig = 'config.php';
if(isset($_REQUEST['xconfig']) && $_REQUEST['xconfig']) $xconfig = $_REQUEST['xconfig'] . '.php';
include_once(RUTA_HERRAMIENTAS . "/herramientas/utiles/comun.php");
include_once("db_". Dtipo . ".php");
include_once("db_". Dtipo . ".php");
defined('Usa_Log') or define('Usa_Log', true);

class sql extends DB
{
  	var $sql;
    var $sql_log;
    var $sql_count;
  	var $criterio;
  	var $tabla;
  	var $campos;
  	var $busca;
	var $xbusca;
  	var $orden;
  	var $orddesc;
  	var $limite;
  	var $origen;
  	var $operador;
  	var $array_asociativo = true;
    var $procedimiento;
    var $regi;
    var $topen 		= "'";
    var $tclose 	= "'";
    var $prefijo	= "v_";
    var $arreglo;
    var $tipo 		= "select";     // es es tipo de SQL que se va a generar (INSERT, DELETE, UPDATE,SELECT)
    var $my_ini;
    var $camposql;
    var $filtro 	= "";
    var $filtrar 	= "";
    var $filtro_op 	= "";
    var $Error		= "";
    //var $metaTablesSQL = "select rdb\$relation_name, rdb\$description from rdb\$relations where rdb\$system_flag<1 order by rdb\$relation_name " ;//rdb\$relation_name not like 'RDB\$%
	//var $metaColumnsSQL = "select a.rdb\$field_name, a.rdb\$null_flag, a.rdb\$default_source, a.rdb\$description, a.rdb\$field_source,  b.rdb\$field_length, b.rdb\$field_scale, b.rdb\$field_sub_type, b.rdb\$field_precision, b.rdb\$field_type  from rdb\$relation_fields a, rdb\$fields b where a.rdb\$field_source = b.rdb\$field_name and a.rdb\$relation_name = '%s' order by a.rdb\$field_position asc";
    var $conexion;
    var $unico;
    var $cOrden;
    var $zOrden;
    var $pagina;
    var $paginas;
    var $registros;
    var $zcampos;
    var $actureg;
    var $accion;
    var $convertir_numero_letras = false;
    var $tabla_log = '';

    /* agregado por MARCO Y EDSON 28/05/2009 */
    var $contar = true; // sirve para decirle a la clase si va a contar o no los registros (evita hacer el COUNT())
    /* fin de la modificacion */


    //var $cnumero_letras = new numero_letras();

    function sql($id=0)
    {

        $this->Initialize($id);
    }

    function Initialize($id)
    {
        global $db_server;
        $this->DBDatabase = $db_server[$id]['DB'];
        $this->DBHost 	  = $db_server[$id]['SERVIDOR'];
        $this->DBPort     = "";
        $this->DBUser     = $db_server[$id]['USUARIO'];
        $this->DBPassword = $db_server[$id]['PASSWORD'];

        $this->Persistent = false;;
        $this->Uppercase = true;


        //rdebug($this,'s');
        $this->connect();

    }

    function arma_sql($tabla = '', $campos = '', $busca = '', $xbusca = '', $operador ='', $orden ='' , $limite ='')
    {
        if($this->origen)
        {


            $my_ini = new ini($this->origen);
            if($this->procedimiento){			
                if(!strlen($my_ini->seccion('SQL',$this->procedimiento)))
                {
                	die('No existe procedimiento: '. $this->procedimiento . " verifique origen..!");
                }

                $this->sql = trim($my_ini->seccion('SQL',$this->procedimiento));
				$this->sql = str_replace("&lt;", '<', $this->sql);
				$this->sql = str_replace("&gt;", '>', $this->sql);


				preg_match_all('/({.+?})/',  $this->sql ,$variables);	 
				
				for($i=0;$i<sizeof($variables[0]);$i++)
				{
					$variable  = $variables[0][$i];
					$xvariable = str_replace('{','', $variable);
					$xvariable = str_replace('}','', $xvariable);
					$xvalor = getvar($xvariable);
					if($xvalor)
					{
						$this->sql = str_replace($variable, $xvalor , $this->sql);
					}
				}
				
                $tmp       = preg_split('/ /', $this->sql ,-1,PREG_SPLIT_NO_EMPTY);
        		$this->tipo= trim(strtolower($tmp[0]));

	            if ($this->tipo=='select')
	            {
	                $this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
	                $this->pagina++;
	                $this->ultimo  = ($this->pagina*$this->limite);
	                $this->primero = ($this->pagina-1)* $this->limite+1;
                    $this->config_sql();
                    $this->sql_log = $this->sql;
	            }
                return;
            }
            $campos_forma = $my_ini->secciones("CAMPO","", "N");
            for($i=0;$i<sizeof($campos_forma);$i++)
            {
            	$campo    	= $campos_forma[$i]["CAMPO"];
	            $acampos[] 	= $campo;
	            $valor    	= escapeshellarg($_POST[$campo]);
	            switch ($campos_forma[$i]["TIPO"])
	            {
	            	case 'CARACTER':
	                     $valor = "'$valor'";
	                     break;
	                case 'NUMERO': 	//-- Este es obvio! --
	                     $valor = str_replace('.','',$valor);
	                     $valor = str_replace(',','.',$valor);
	                     break;
	                case 'FECHA':
	                     $valor = "'".substr($valor,3,2).'/'.substr($valor,0,2).'/'. substr($valor,6)."'";
	                     break;
	            }
	            $valores[] = $valor;
	            $cambiar[] = $campo . "=". $valor;
	        }
	        if(is_array($acampos)) $campos = join(',', $acampos);
        }

	    if($tabla) 		$this->tabla 	= $tabla;
       	if($campos)		$this->campos	= $campos;
        if($busca)		$this->busca	= $busca;
		if($xbusca) 	$this->xbusca 	= $xbusca;
       	if($operador)	$this->operador	= $operador;
        if($orden)		$this->orden	= $orden;
        if($limite)     $this->limite	= $limite;

        if($this->operador == "*")
	    {
	    	$this->operador = " like ";
	    	$this->topen = " '%";
	    	$this->tclose = "%' ";
	    }

        if(strstr($this->campos,':'))
        {
            $xcampos = explode(',',$this->campos);
            for($i=0;$i<sizeof($xcampos);$i++)
            {
               $id = $xcampos[$i];
               if(strstr($xcampos[$i],':'))
               {

                    $xfuncion = explode(':',$xcampos[$i]);
                    for($j=1;$j<sizeof($xfuncion);$j++)
                    {
                       $id = $xfuncion[0];
                       $iy = $zid . " as " . $id ;

                       $this->zcampos[$id][] = $xfuncion[$j];
                       $id = $iy;
                    }
               }
               else
               {
                	$zid = $id;
               }
               $xid[] = $id;
            }
            $xcampos = join(',', $xid);
            $this->campos = $xcampos;
            //rdebug($this->zcampos);
        }


        $busca = explode(',', $this->busca);
        if (strtolower($this->tipo)=='select')
        {
        	$this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
            $this->pagina++;
            $ultimo  = ($this->pagina*$this->limite);
            $primero = ($this->pagina-1)* $this->limite+1;

            $this->sql = "SELECT " . $this->campos . " FROM " . $this->tabla;
            if($this->operador == '=')
            {
               	for($i=0;$i<sizeof($busca);$i++)
                {
                    $arrsql[] = $busca[$i] . " = '" . $this->xbusca . "' ";
                }
                $this->sql .= " WHERE (" . join(' OR ' , $arrsql)  .")";
            	if($this->filtro) $this->sql .= " AND " . $this->filtros();// . "='". $this->filtrar ."'";
                $this->config_sql();
                $this->sql_log = $this->sql;
                return;
            }

            if($this->operador == 'IN')
            {
                $xtmp = implode("','", explode(',',$this->xbusca));
                $this->sql .= " WHERE " . $this->busca . " IN ('". $xtmp  ."')";

            	if($this->filtro) $this->sql .= " AND " . $this->filtros();// . "='". $this->filtrar ."'";
                $this->config_sql();
                $this->sql_log = $this->sql;
                return;
            }

			/*****************************************/
            if($this->xbusca=="" || $this->xbusca=="*")
            {
                $this->xbusca = '';
                if($this->filtro) $this->sql .= " WHERE " . $this->filtros(); //$this->filtro . "='". $this->filtrar ."'";
            }
            else
            {
               if(substr($this->xbusca,0,1)=="*" || substr($this->xbusca,0,1)=="." || substr($this->xbusca,0,1)==",")
                {
	                $prefijo = substr($this->xbusca,0,1);
	                $this->xbusca = substr($this->xbusca,1);
	                $xbusca = explode(',', $this->xbusca);
	                for($i=0;$i<sizeof($xbusca);$i++){
	                    //$zbusca = '';
						$zbusca = array();
	                    for($j=0;$j<sizeof($busca);$j++){
	                        //$zbusca[] =  "cast(" . $busca[$j] ." as varchar(500)) LIKE '%" . $xbusca[$i] . "%' ";
                            $zbusca[] =  $busca[$j] ." LIKE '%" . $xbusca[$i] . "%' ";
	                    }
	                    $arrsql[] = "(" . join(' OR ', $zbusca) . ")";
	                }
	                $this->sql .= " WHERE (" . join(' AND ' , $arrsql) . ")";
	                if($prefijo ==".")  $this->sql .= " WHERE (" . join(' OR ' , $arrsql) . ")";
                    if($this->filtro) $this->sql .= " AND " . $this->filtros();// . "='". $this->filtrar ."'";
	            }
	            else
	            {
                    $xbusca = explode(',', $this->xbusca);
	                for($i=0;$i<sizeof($busca);$i++)
                    {
	                    $zbusca = array();
	                    for($j=0;$j<sizeof($xbusca);$j++)
                        {
	                        $zbusca[] = $xbusca[$j] . "%";
	                    }
	                    $tmpsql = $this->sql ." WHERE " . $busca[$i] . " LIKE '" . join('', $zbusca) . "' ";
                        if($this->filtro) $tmpsql .= " AND " . $this->filtros(); //$this->filtro . "='". $this->filtrar ."'";
                        $arrsql[] = $tmpsql ;
	                }
	                $this->sql =  join(' UNION ' , $arrsql);
	            }
            }
			$this->config_sql();
            $this->sql_log = $this->sql;
            return;
		}
        if (strtolower($this->tipo)=='update')
        {
			for ($j=0;$j<sizeof($this->camposql);$j++)
            {
				$cambiar[]     = $this->camposql[$j]['name'] . "= " . $this->camposql[$j]['value_sql'];
                if($this->camposql[$j]['tipo']=='L')
                {
                    $cambiar_log[] = $this->camposql[$j]['name'] . "= " . "'".base64_encode($this->camposql[$j]['value_sql']) ."'";
                }
                else
                {
					$cambiar_log[] = $this->camposql[$j]['name'] . "= " . $this->camposql[$j]['value_sql'];
                }
			}
        	if (!is_array($cambiar))
            {
            	die('Faltan datos para realizar el UPDATE '. $query);
                //rdebug($this);
            }
           	$cambia = join($cambiar,',');
            $cambia_log = join($cambiar_log,',');
	        if($this->xbusca)
            {
	            for($i=0; $i< sizeof($busca); $i++)
                {
	                if($this->criterio) $this->criterio .= ' and ';
	                $this->criterio .= $busca[$i] . ' = \'' . $this->xbusca .'\'';
	        	}
	        }
           $this->sql     = "update " . $this->tabla . " set " . $cambia     . " where ". $this->criterio;
           $this->sql_log = "update " . $this->tabla . " set " . $cambia_log . " where ". $this->criterio;
           return;
        }

        if (strtolower($this->tipo)=='insert')
        {
			for ($j=0;$j<sizeof($this->camposql);$j++){
				$acampos[] = $this->camposql[$j]['name'];
				$valores[]= $this->camposql[$j]['value_sql'];
                if($this->camposql[$j]['tipo']=='L')
                {
                	$valores_log[]= "'".base64_encode($this->camposql[$j]['value_sql']) . "'";
                }
                else
                {
                	$valores_log[]= $this->camposql[$j]['value_sql'];
                }
			}

			if (!is_array($acampos))  die('Faltan datos para realizar el INSERT');
           	if (!is_array($valores)) die('Flatan datos para realizar el INSERT');
           	$campos = join($acampos,',');
           	$valores= join($valores,',');
            $valores_log   = join($valores_log,',');
           	$this->sql     = "INSERT INTO " . $this->tabla . "(" . $campos . ") values (". $valores .") ";
            $this->sql_log = "INSERT INTO " . $this->tabla . "(" . $campos . ") values (". $valores_log .") ";
            return;
        }

        if(strtolower($this->tipo)=='delete')
        {
            if($this->xbusca){
	            for($i=0; $i< sizeof($busca); $i++){
	                if($this->criterio) $this->criterio .= ' and ';
	                $this->criterio .= $busca[$i] . ' = \'' . $this->xbusca .'\'';
	            }
	        }
           $this->sql     = "Delete from " . $this->tabla." ".$cambia . " where ". $this->criterio;
           $this->sql_log = $this->sql;

        }

    }


	function Agregar_Sql($Campo, $Valor)
    {
        //$tipo_sql 	= strtoupper(trim($this->sql));
		//$tmp 		= explode(' ',$tipo_sql);
        //$tipo_sql 	= $tmp[0];
        switch($this->tipo)
        {
        	case 'insert':
                $Query      = preg_split("/values/i", $this->sql);
                $Query[0] = trim($Query[0]);
                $Query[1] = trim($Query[1]);
				$Query[0]  = substr($Query[0],0, -1) . ", " . $Campo . ")";
				$Query[1]  = substr($Query[1],0, -1) . ", " . $Valor . ")";
	            $this->sql= $Query[0] . "  values " . $Query[1] ;
                $query->tipo = "insert";
			break;
	        case 'update':
	            //$Query = spliti(" where ",$this->sql);
                $Query      = preg_split("/ where /i", $this->sql);
	            $Query[0]  .= ", ". $Campo ."=" . $Valor;
                $this->sql  = $Query[0] . "  where ";
                $XQuery     = array_shift ($Query);
                $tQuery     = join(' where ', $Query);
				$this->sql .= $tQuery;
                $query->tipo = "update";
            break;
        }
    }


    function ejecuta_query ()
    {
        global $Sistema;
       // echo $this->sql;
        $this->sql   = str_replace("\'", "''", $this->sql);
		srand((double)microtime()*1000000);
		$randval  = mt_rand (1000,999999999);
        $randval .= session_id();
		$this->unico = strtoupper(md5($randval . $this->sql ) );

        $tmp         = preg_split('/ /', $this->sql ,0,PREG_SPLIT_NO_EMPTY);
        $this->tipo  = trim(strtolower($tmp[0]));

        if(Usa_Log)
        {
            if($this->tipo == "insert"){
                $tabla   = str_replace('_','',$tmp[2]);
                $tabla   = substr($tabla,0,5);
                $c       = crc32($this->sql . date("YdmHis") . microtime());
                $id      = sprintf("%u" , $c);
                $id      = substr(str_pad($id, 15 , "0"),0,15);
                $this->Agregar_Sql('BARCODE',"'" . $tabla . $id . "'");
            }
            $tipo        = $this->tipo == "update" ? "U" : "I";
            $this->Agregar_Sql('ID_M_USUARIOS',"'" . getsession("M_USUARIOS_ID_M_USUARIO") . "'");
            $this->Agregar_Sql('ID_M_AREAS', "'" . getsession("M_ESTACIONES_ID_M_AREA") . "'");
			
			$this->unico = strtoupper(md5($randval . $this->sql ) );
			
            $this->Agregar_Sql('UNICO',  "'". $this->unico . "'");
            if($this->accion!='R') $this->Agregar_Sql('ACCION',  "'". $tipo . "'");
			$this->sql_log = $this->sql ;
		
        }

		$log_db  = $_SERVER['DOCUMENT_ROOT'] . '/txt-databases/log_'.Sistema;
		@mkdir($log_db);
        $tmp_tabla       = "log_" . date("Y-m-d");
		$file_log        = $log_db . "/log_" . date("Y-m-d") . '.txt';
		if(!file_exists( $file_log )){
			file_put_contents($file_log, "id#tiempo#ip#session_id#fecha_ini#fecha_fin#usuario#modulo#script#unico#sql\n");
		}

        $ini        = time();
        $ip 		= $_SERVER['REMOTE_ADDR'];
		$fecha_ini  = microtime();
        $usuario    = getsession('M_USUARIOS_NOMBRES');
        $xmodulo    = isset($_REQUEST['url_modulo']) ? $_REQUEST['url_modulo'] : ''; 
        if($xmodulo=='') $xmodulo = $_SERVER['SCRIPT_NAME'];
        $modulo 	= $xmodulo;
        $session_id = session_id();
        $script     = $_SERVER['SCRIPT_NAME'];
		
		if($this->sql_log=='') $this->sql_log = $this->sql;
		
        //$isql       = addcslashes(preg_replace('/\s\s+/', ' ', $this->sql_log), "'");
		$isql       = $this->sql_log;
        $sql_log    = $isql;

        $tim = new Timer();
        $tim->timerStart('query');
        if (strtolower($this->tipo)=='select')
        {
            $this->query($this->sql.' /* '.$usuario.' - '.$ip.' */');
            if (!$this->erro) $this->attributos();
        }
        else {			
            $this->query( $this->sql .' /* '.$usuario.' - '.$ip.' */');
			
            if($this->tipo == "insert"){
				if( $this->DBDatabase == '/opt/lampp/firebird/db/protecseguros.gdb'){
					file_put_contents(__DIR__ . '/query.txt', print_r( $_REQUEST, true ), FILE_APPEND);
					$variables = leer_vars("c_");
					file_put_contents(__DIR__ . '/query.txt', print_r( $variables, true ), FILE_APPEND);					
				}
            }
			
			
        }

        if ($this->erro)
        {
            $this->regi['ERROR'] = $this->erro_msg.'|'.$this->sql;
            $this->Error = $this->erro;
        }
        
		$tiempo     = $tim->timerStop( 'query' );
        $fecha_fin  = microtime();     
		$unico = $this->unico;
		
		file_put_contents($file_log, "$tiempo#$ip#$session_id#$fecha_ini#$fecha_fin#$usuario#$modulo#$script#$unico#$sql_log\n", FILE_APPEND);
		
    }

    function filtros()
    {
        $xfiltros  = explode(';',$this->filtro);
        $xfiltrar  = explode(';',$this->filtrar);
        $filtro_op = explode(';',$this->filtro_op);
        $tmp_sql = '';
        for($i=0;$i<sizeof($xfiltros);$i++)
        {
            if(strlen($tmp_sql)) $tmp_sql .= ' AND ';
            if(strlen($filtro_op[$i]))
            {
                switch(trim($filtro_op[$i]) ){
					case 'OR';
						$xop = ' OR ' ;
						$xfiltrar[$i] = str_replace(',','\',\'', $xfiltrar[$i]);
						$tf = explode (',' ,  $xfiltrar[$i]  );
						$tmp_sql .= '(';
						for( $k = 0; $k <sizeof( $tf ) ;$k++ ){
							if( $k > 0 ) $tmp_sql .= ' OR ';
							$tf[$k] = str_replace("'", "",  $tf[$k] );
							$tmp_sql .= $xfiltros[$i] . ' = ' . "'" . $tf[$k] . " '" ;							
						}
						$tmp_sql .= ')';
						
					break;
					case 'IN';
						$xop = ' IN ' ;
						$xfiltrar[$i] = str_replace(',','\',\'', $xfiltrar[$i]);
						$tmp_sql .= $xfiltros[$i] . $xop . "('" . $xfiltrar[$i] . "') " ;
					break;
					case 'NOTIN';
						$xop = ' NOT IN ' ;
						$xfiltrar[$i] = str_replace(',','\',\'', $xfiltrar[$i]);
						$tmp_sql .= $xfiltros[$i] . $xop . "('" . $xfiltrar[$i] . "') " ;
					break;
					default:
						$xop = strlen($filtro_op[$i]) ? $filtro_op[$i] : ' = ';
						$tmp_sql .= $xfiltros[$i] . $xop . "'" . $xfiltrar[$i] . "' " ;	
				}
				
            }
            else
            {
                $xop = ' = ';
                $tmp_sql .= $xfiltros[$i] . $xop . "'" . $xfiltrar[$i] . "' " ;

            }

        }
        return $tmp_sql;
    }

    function reemplazo($xFormula)
    {
        $variable = $xFormula[0];
        if(array_key_exists($variable, $this->actureg)){
            return $this->actureg[$variable];
        }
		return $variable;
	}

}

class Timer
{
    var $timers = array();

    function __construct()
    {
        // Nothing
    }
    function timerStart($name = 'default')
    {
        $time_portions = explode(' ', microtime());
        $actual_time = $time_portions[1] . substr($time_portions[0], 1);
        $this->timers['$name'] = $actual_time;
    }

    function timerStop($name = 'default')
    {
        $time_portions = explode(' ', microtime());
        $actual_time = $time_portions[1] . substr($time_portions[0], 1);
        $elapsed_time = bcsub($actual_time, $this->timers['$name'], 4);
        return $elapsed_time;
    }
}

?>