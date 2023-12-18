<?php

//DB InterBase Class @0-CD587C69
/*
 * Database Management for PHP
 *
 * db_interbase.php
 *
 */

class DB {
  var $DBHost     = "";
  var $DBDatabase = "";
  var $DBUser     = "";
  var $DBPassword = "";
  var $Persistent = false;
  var $Uppercase  = false;

  var $Link_ID  = 0;
  var $Query_ID = 0;
  var $Record   = array();
  var $Row;
  var $Reg_Afect = 0;

  var $Auto_Free = 1;     ## Set this to 1 for automatic sqlanywhere_free_result()
  var $Connected = false;

  var $reg_campos;
  var $reg_campos_tipos;
  var $arreglo_atributos;
  var $erro;
  var $erro_msg;
  var $LastSQL;
  var $in_transaction    = false;
  var $transaction_id    = 0;
  var $transaction_error = 0;

  var $primero;
  var $ultimo;
  var $sql;
  var $xwhere;

  var $campo_blob;
  var $next_id;
  


  /* public: constructor */
  function DB($query = "") {
      $this->query($query);
  }

  function connect() {
    if (!$this->Connected)
    {
    $this->Query_ID  = 0;
    $this->Persistent = false;
      if ($this->Persistent)
        $this->Link_ID = ibase_pconnect($this->DBHost . ":" . $this->DBDatabase, $this->DBUser, $this->DBPassword,'ISO8859_1',0,3);
		//$this->Link_ID = ibase_pconnect($this->DBHost . ":" . $this->DBDatabase, $this->DBUser, $this->DBPassword,'UTF8',0,3);
      else
        $this->Link_ID = ibase_connect($this->DBHost . ":" . $this->DBDatabase, $this->DBUser, $this->DBPassword,'ISO8859_1',0,3);
		//$this->Link_ID = ibase_connect($this->DBHost . ":" . $this->DBDatabase, $this->DBUser, $this->DBPassword,'UTF8',0,3);

      if (!$this->Link_ID) {
        $this->Halt("Cannot connect to Database: " . ibase_errmsg());
        return false;
      }
    }
      $this->Connected = true;
  }

  function config_sql()
  {
  	$this->ultimo  = ($this->pagina*$this->limite);
    $this->primero = ($this->pagina-1)* $this->limite+1;

    $cquery = preg_replace("/SELECT(.*?)FROM(.*?)/","SELECT COUNT(*) AS REGISTROS FROM \\2",$this->sql);
    $this->sql = 'SELECT * FROM ('. $this->sql . ')';
    $this->sql_count = 'SELECT SUM(REGISTROS) AS REGISTROS FROM ('. $cquery . ') '.'/* '.getsession('M_USUARIOS_NOMBRES').' - '.$_SERVER['REMOTE_ADDR'].' */';
    if($this->orden)  $this->sql .= ' ORDER BY ' . $this->orden;
    if($this->limite) $this->sql .= ' ROWS ' . $this->primero . ' TO ' . $this->ultimo;
    $this->sql_log = $this->sql_count;
  }

  function cuenta_registros()
  {
    if(strlen($this->sql_count))
    {
        $this->query($this->sql_count,false);
        $this->next_record();
        $this->registros = $this->f("REGISTROS");
        if($this->limite && $this->registros)
        {
            $this->paginas = ceil($this->registros/$this->limite);
        }
    }
  }

  function query($Query_String,$sw=true) {

    if ($Query_String == "")
      return 0;

    $this->connect();
    $this->Record           = array();
    $this->reg_campos	    = array();
    $this->reg_campos_tipos = array();
    $this->arreglo_atributos = array();
    $ejecuto = false;
    // New query, discard previous result.
    if ($this->Query_ID) {
      $this->free_result();
    }
    $this->LastSQL = $Query_String;
	$connection = $this->getConnection();
	

  /* CAMBIO REALIZADO POR MARCO: MAYO 2009: SE CONTARA LUEGO DE TRAER LOS REGISTROS (SI HACE FALTA. VER CLASS_GENERA_XML:151-171) */

  if ($this->contar) // agregado por MARCO Y EDSON el 28/05/2009
  {
    if(strlen($this->sql_count) && $sw) $this->cuenta_registros();
	}

  /* FIN DEL CAMBIO */

    if ($this->Query_ID = @ibase_query ($connection, $Query_String))
    {
      $ejecuto = true;
      $position = strpos(trim(strtoupper($Query_String)), "SELECT ");
      //if($position === false || $position > 0)
        //ibase_commit();
    } else {
      $this->erro     = ibase_errcode();
      $this->erro_msg = ibase_errmsg();
      if ($this->in_transaction) $this->transaction_error = $this->erro;

    }
    $this->Reg_Afect = @ibase_affected_rows($connection);
    $this->Row   = 0;
    return $ejecuto;

    //die($this->Query_ID);
    //return $this->Query_ID;
  }
  
  function Next_ID($tabla){
	  return ibase_gen_id ("G_". $tabla ."_ID",1);		
  }
  
  function getConnection()
  {
      if (!$this->Link_ID) return 0;
      if ($this->in_transaction)
      {
          return $this->transaction_id;
      }
      return $this->Link_ID;
  }

  function beginTransaction()
  {
      $result = ibase_trans(0,$this->Link_ID);
      if (!$result) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'could not start a transaction';
          return false;
      }
      $this->transaction_id = $result;
      $this->in_transaction = true;
  }

  function commit()
  {
      if (!$this->in_transaction) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'No hay transaccion en progreso';
          return false;
      }
      if($this->transaction_error)
      {
          $this->rollback();
	      $this->erro     = 'X';
	      $this->erro_msg = 'No se puede realizar fin de la transaccion';
          return false;
	  }
      if (!@ibase_commit($this->transaction_id)) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'No se puede realizar fin de la transaccion';
          return false;
      }
      $this->in_transaction    = false;
      $this->transaction_id    = 0;
      $this->transaction_error = 0;
  }

  function rollback()
  {
      if ($this->transaction_id && !@ibase_rollback($this->transaction_id)) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'No hay transaccion en progreso';
          return false;
      }
      $this->in_transaction = false;
      $this->transaction_id = 0;
      $this->transaction_error = 0;
  }

  function attributos()
  {
      $count = ibase_num_fields($this->Query_ID);
      for ($i = 0; $i < $count; $i++)
      {
        $fieldinfo = ibase_field_info($this->Query_ID, $i);
        $nombre   = $fieldinfo['alias'] ? $fieldinfo['alias'] : $fieldinfo['name'];
        $longitud = $fieldinfo['length'];
        $tipo = substr($fieldinfo['type'],0,4);

        switch($tipo)
        {
            case 'CHAR':
            case 'VARC':
                $tipo = 'C';
                break;
            case 'TIME':
                $tipo = 'T';
                break;
            case 'DATE':
                $tipo = 'D';
                break;
            case 'BLOB':
                $tipo = 'X';
                break;
			case 'SMAL':
            case 'INTE':
            case 'BIGI':
                $tipo = 'I';
                break;
            case 'NUME':
            case 'DOUB':
            case 'FLOA':
            case 'DECI':
                $tipo = 'N';      //30455
        }

        $this->reg_campos[$nombre] 	= $i;
        $this->reg_campos_tipos[$i] = $fieldinfo['type'];

        $this->arreglo_atributos[$i]['NOMBRE'] = $nombre;
        $this->arreglo_atributos[$i]['TIPO']   = $tipo;
        $this->arreglo_atributos[$i]['LONG']   = $longitud;
	}
  }

  function next_record()
  {
    if (!$this->Query_ID) return 0;
    $this->Record = ibase_fetch_assoc($this->Query_ID);
   // $this->Record = array_change_key_case($this->Record , CASE_UPPER);
    $stat = is_array($this->Record);
    if ($stat)
    {
      $this->Row++;

      $count = ibase_num_fields($this->Query_ID);
      for ($i = 0; $i < $count; $i++)
      {
         $col_info = ibase_field_info($this->Query_ID, $i);
         $nombre   = $col_info['name'] ? $col_info['name'] : $col_info['alias'];
  	   	 if ($col_info['type']=='BLOB')
         {
            //echo $this->Record[$nombre];
            $blob_data = @ibase_blob_info($this->Record[$nombre]);
            $blob_id = @ibase_blob_open($this->Record[$nombre]);
            $campo = @ibase_blob_get($blob_id, $blob_data[0] );
            $this->Record[$nombre] = $campo;
  	   	 }
       }




    } else if ($this->Auto_Free) {
      $this->free_result();
    }
    return $stat;
  }

  function free_result() {
    if (is_resource($this->Query_ID)) {
      @ibase_free_result($this->Query_ID);
    }
    $this->Query_ID = 0;
  }

  function seek($pos) {
    $i = 0;
    while($i < $pos && @ibase_fetch_row($this->Query_ID)) { $i++; }
    $this->Row += $i;
    return true;
  }

  /* function num_rows() {} */

  function num_fields() {
    return ibase_num_fields($this->Query_ID);
  }

  function f($Name) {
    if($this->Uppercase) $Name = strtoupper($Name);
    return $this->Record && array_key_exists($Name, $this->Record) ? $this->Record[$Name] : "";
  }

  function p($Name) {
    if($this->Uppercase) $Name = strtoupper($Name);
    print $this->Record[$Name];
  }

  function close() {
    if ($this->Query_ID) {
      $this->free_result();
    }
    if ($this->Connected) {
      ibase_close($this->Link_ID);
      $this->Connected = false;
    }
  }


  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>InterBase Error</b><br>\n");
    die("Session halted.");
  }

  function listar_tablas($tablas='')
  {
        set_time_limit(0);
        if(strlen($tablas)) $xtablas = ' and RDB$RELATION_NAME LIKE \'' . $tablas . '%\'';
        $tsql = 'SELECT DISTINCT RDB$RELATION_NAME as TABLA FROM RDB$RELATION_FIELDS WHERE RDB$SYSTEM_FLAG=0 ' . $xtablas;
        $this->query($tsql);
        unset($tmp);
        while ($this->next_record())
	    {
            $xtt = substr(trim($this->Record['TABLA']),0,2);
            if($xtt!='V_')
            {
                $tmp[]['TABLA']= trim($this->Record['TABLA']);
            }
	    }
        reset($tmp);
        for($i=0;$i<sizeof($tmp);$i++)
        {
            $tsql = 'SELECT FIRST(1) RDB$FIELD_NAME AS CAMPO FROM RDB$RELATION_FIELDS WHERE RDB$RELATION_NAME=\'' . trim($tmp[$i]['TABLA']) . '\' ORDER BY RDB$FIELD_POSITION';
            $this->query($tsql);
            $this->next_record();
	        $tmp[$i]['CAMPO'] = trim($this->Record['CAMPO']);
            $campo = trim($tmp[$i]['CAMPO']);
            $tsql = 'SELECT COUNT('.  $campo  .') AS REGISTROS FROM ' . $tmp[$i]['TABLA'];
            $tt = date("H:i:s");
            $this->query($tsql);
            $this->next_record();
            $tmp[$i]['REGISTROS'] = $this->Record['REGISTROS'];
            $tmp[$i]['TIEMPO'] = $tt . "  " . date("H:i:s");
        }
        return $tmp;

  }

}

//End DB InterBase Class


?>