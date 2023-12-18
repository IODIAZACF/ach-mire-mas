<?php
include_once("db_interbase.php");
class sql extends DB
{
  	var $sql;
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
    var $convertir_numero_letras = false;
    //var $cnumero_letras = new numero_letras();

    function sql()
    {

    }

    function Initialize()
    {
        $this->Persistent = false;;
        $this->Uppercase = true;
        $this->connect();

    }

    function arma_sql($tabla = '', $campos = '', $busca = '', $xbusca = '', $operador ='', $orden ='' , $limite ='')
    {
        if($this->origen)
        {
            $my_ini = new ini($this->origen);
            if($this->procedimiento){
                $this->sql = trim(parse_texto($my_ini->seccion('SQL',$this->procedimiento), leer_vars($this->prefijo)));
                $tmp       = preg_split('/ /', $this->sql ,-1,PREG_SPLIT_NO_EMPTY);
        		$this->tipo= trim(strtolower($tmp[0]));

	            if ($this->tipo=='select')
	            {
	                $this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
	                $this->pagina++;
	                $this->ultimo  = ($this->pagina*$this->limite);
	                $this->primero = ($this->pagina-1)* $this->limite+1;
                    $this->config_sql();
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
               	for($i=0;$i<sizeof($busca);$i++){
                    $arrsql[] = $busca[$i] . " = '" . $this->xbusca . "' ";
                }
                $this->sql .= " WHERE (" . join(' OR ' , $arrsql)  .")";
            	if($this->filtro) $this->sql .= " AND " . $this->filtros();// . "='". $this->filtrar ."'";
                $this->config_sql();
                return;
            }

            if($this->operador == 'IN')
            {
                $xtmp = implode("','", explode(',',$this->xbusca));
                $this->sql .= " WHERE " . $this->busca . " IN ('". $xtmp  ."')";

            	if($this->filtro) $this->sql .= " AND " . $this->filtros();// . "='". $this->filtrar ."'";
                $this->config_sql();
                return;
            }


            if($this->xbusca=="" || $this->xbusca=="*"){
                $this->xbusca = '';
                if($this->filtro) $this->sql .= " WHERE " . $this->filtros(); //$this->filtro . "='". $this->filtrar ."'";
            }
            else
            {
               if(substr($this->xbusca,0,1)=="*" || substr($this->xbusca,0,1)==".")
                {
	                $prefijo = substr($this->xbusca,0,1);
	                $this->xbusca = substr($this->xbusca,1);
	                $xbusca = explode(',', $this->xbusca);
	                for($i=0;$i<sizeof($xbusca);$i++){
	                    $zbusca = '';
	                    for($j=0;$j<sizeof($busca);$j++){
	                        $zbusca[] =  "cast(" . $busca[$j] ." as varchar(500)) LIKE '%" . $xbusca[$i] . "%' ";
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
	                for($i=0;$i<sizeof($busca);$i++){
	                    $zbusca = '';
	                    for($j=0;$j<sizeof($xbusca);$j++)
                        {
	                        $zbusca .= $xbusca[$j] . "%";
	                    }
	                    $tmpsql = $this->sql ." WHERE " . $busca[$i] . " LIKE '" . $zbusca . "' ";
                        if($this->filtro) $tmpsql .= " AND " . $this->filtros(); //$this->filtro . "='". $this->filtrar ."'";
                        $arrsql[] = $tmpsql ;
	                }
	                $this->sql =  join(' UNION ' , $arrsql);
	            }
            }
			$this->config_sql();
            return;
		}
        if (strtolower($this->tipo)=='update')
        {
			for ($j=0;$j<sizeof($this->camposql);$j++){
				$cambiar[] = $this->camposql[$j]['name'] . "= " . $this->camposql[$j]['value_sql'];
			}
        	if (!is_array($cambiar)) die('Faltan datos para realizar el UPDATE');
           	$cambia = join($cambiar,',');
	        if($this->xbusca)
            {
	            for($i=0; $i< sizeof($busca); $i++)
                {
	                if($this->criterio) $this->criterio .= ' and ';
	                $this->criterio .= $busca[$i] . ' = \'' . $this->xbusca .'\'';
	        	}
	        }
           $this->sql = "update " . $this->tabla . " set " . $cambia . " where ". $this->criterio;
           return;
        }

        if (strtolower($this->tipo)=='insert')
        {
			for ($j=0;$j<sizeof($this->camposql);$j++){
				$campos[] = $this->camposql[$j]['name'];
				$valores[]= $this->camposql[$j]['value_sql'];
			}
			if (!is_array($campos)) die('Faltan datos para realizar el INSERT');
           	if (!is_array($valores)) die('Flatan datos para realizar el INSERT');
           	$campos = join($campos,',');
           	$valores= join($valores,',');
           	$this->sql = "Insert into " . $this->tabla . "(" . $campos . ") values (". $valores .") ";
            return;
        }

        if (strtolower($this->tipo)=='delete')
        {
            if($this->xbusca){
	            for($i=0; $i< sizeof($busca); $i++){
	                if($this->criterio) $this->criterio .= ' and ';
	                $this->criterio .= $busca[$i] . ' = \'' . $this->xbusca .'\'';
	            }
	        }
           $this->sql = "Delete from " . $this->tabla." ".$cambia . " where ". $this->criterio;
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
	            $Query = spliti("values",$this->sql);
                $Query[0] = trim($Query[0]);
                $Query[1] = trim($Query[1]);
                $Query[0] = substr($Query[0],0, strrchr ($Query[0], ')')-1) . ", " . $Campo . ")";
	            $Query[1] = substr($Query[1],0, strrchr ($Query[1], ')')-1) . ", " . $Valor . ")";
	            $this->sql= $Query[0] . "  values " . $Query[1] ;
                $query->tipo = "insert";
			break;
	        case 'update':
	            $Query = spliti(" where ",$this->sql);
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
        $this->unico = strtoupper(md5($randval));
        $tmp         = preg_split('/ /', $this->sql ,0,PREG_SPLIT_NO_EMPTY);
        $this->tipo  = trim(strtolower($tmp[0]));



        if (strtolower($this->tipo)=='select')
        {
            $this->query($this->sql);
            if (!$this->erro) $this->attributos();
        }
        else
        {
            $this->query($this->sql);
        }

        if ($this->erro)
        {
            $this->regi['ERROR'] = $this->erro_msg.'|'.$this->sql;
            $this->Error = $this->erro;
        }

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
            $xop = strlen($filtro_op[$i]) ? $filtro_op[$i] : ' = ';
            $tmp_sql .= $xfiltros[$i] . $xop . "'" . $xfiltrar[$i] . "' ";
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


?>