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

  var $Link_ID;
  var $Parse_ID;
  var $Query_ID;

  var $Record   = array();
  var $Row;

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


  /* public: constructor */
  function DB($query = "") {
      $this->query($query);
  }

  function connect() {
    if (!$this->Connected)
    {
      $this->Query_ID  = 0;
      $this->Persistent = true;

      /*$RAC_CONN_STR =
     "(DESCRIPTION =
     (ADDRESS_LIST=
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.3.150.73)(PORT = 1523))
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.3.150.71)(PORT = 1523))
      )
     (LOAD_BALANCE = yes)
     (CONNECT_DATA =
     (SERVER = SHARED)
     (SERVICE_NAME = o24)
     (FAILOVER_MODE =
     (TYPE = SELECT)
     (METHOD = BASIC)
     (RETRIES = 3)
     (DELAY = 3)
     )
     )
     )";*/

      $RAC_CONN_STR = "(ADDRESS_LIST=
          (ADDRESS=(PROTOCOL=TCP)(HOST=10.1.150.1)(PORT=1521))
        (CONNECT_DATA=(SERVICE_NAME=o24))";

      /*"(DESCRIPTION =
       (ADDRESS = (PROTOCOL = TCP)(HOST = 10.2.150.70)(PORT = 1521))
       (CONNECT_DATA =
         (SERVER = DEDICATED)
         (SERVICE_NAME = O24)
       )
     )";                */

      //$this->DBUser.'-'.$this->DBPassword.'-'.$this->DBHost.':/'.$this->DBDatabase;

      $this->Link_ID = oci_connect($this->DBUser, $this->DBPassword,$this->DBHost.':/'.$this->DBDatabase, Dcharset);

      //print_r($this->Link_ID);
      //die();

      //$this->Link_ID = oci_connect($this->DBUser, $this->DBPassword, $RAC_CONN_STR, Dcharset);

      if (!$this->Link_ID)
      {
        $e = oci_error($this->Link_ID);
        $this->Halt("Cannot connect to Database: " . $e['message']);
        return false;
      }

      $qry = oci_parse($this->Link_ID, "alter session set NLS_TERRITORY='SPAIN'");
      $qry = oci_execute($qry, OCI_DEFAULT);
      @oci_commit($this->Link_ID);

      $qry = oci_parse($this->Link_ID, "alter session set NLS_DATE_FORMAT='MM-DD-YYYY HH24:MI:SS'");
      $qry = oci_execute($qry, OCI_DEFAULT);
      @oci_commit($this->Link_ID);

      $qry = oci_parse($this->Link_ID, "alter session set NLS_TIMESTAMP_FORMAT='MM-DD-YYYY HH24:MI:SS'");
      $qry = oci_execute($qry, OCI_DEFAULT);
      @oci_commit($this->Link_ID);

      $qry = oci_parse($this->Link_ID, "alter session set NLS_NUMERIC_CHARACTERS='.,'");
      $qry = oci_execute($qry, OCI_DEFAULT);
      @oci_commit($this->Link_ID);


      //echo 'marco...';

      $this->Connected = true;
    }
  }

  function config_sql()
  {
    $this->ultimo  = ($this->pagina*$this->limite);
    $this->primero = ($this->pagina-1)* $this->limite+1;

    /* revisar marco    === order\s+by\s+[\w*\s*\,\s*]*    */

    $pat = '/order\s+by\s+[\w*\s*\,\s*\.*]*/i';
    $ssql = $this->sql;
    $ssql = preg_replace($pat, '', $ssql);

    $this->sql_count = 'SELECT COUNT(*) AS REGISTROS FROM ('. $ssql . ') RX '.'/* '.getsession('M_USUARIOS_NOMBRES').' - '.$_SERVER['REMOTE_ADDR'].' */';

    if($this->orden)  $this->sql .= ' ORDER BY ' . $this->orden;


    if($this->limite && $this->ultimo>0)
    {
      $query = "select * from ( select a.*, ROWNUM rnum from ( ".$this->sql." ) a where ROWNUM <= ".$this->ultimo.") where rnum >= ".$this->primero;
      $this->sql = $query;
    }


    //$this->sql .= ' ROWS ' . $this->primero . ' TO ' . $this->ultimo;
    $this->sql_log = $this->sql_count;

    //echo $this->sql;
    //die();
  }

  function cuenta_registros()
  {
    if(strlen($this->sql_count))
    {
        $this->query($this->sql_count,false);
        $this->next_record();
        $this->registros = $this->f("REGISTROS");
        if (!is_numeric($this->limite)) $this->limite=$this->registros;

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
    if ($this->Parse_ID) {
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

    try
    {
      $this->Parse_ID = oci_parse($connection, $Query_String) or die($Query_String);

      foreach($this->lob_vars as $nom => $var) // agregado por marco mayo 2010
      {
        @oci_bind_by_name($this->Parse_ID, ':'.$nom, $var);
      }
    }
    catch(Exception $e)
    {
      die($Query_String.'(1)');
    }


    if (!$this->Parse_ID)
    {
      $e = oci_error($connection);
      print $e['message'];
      exit;
    }

    $ejecuto = false;
    try
    {

      if($this->Query_ID = @oci_execute($this->Parse_ID, OCI_DEFAULT))// or die('Error: '.$this->LastSQL))
      {
        $ejecuto = true;
        $position = strpos(trim(strtoupper($Query_String)), "SELECT ");
        if ($position !== 0) oci_commit($this->Link_ID);
      }
      else
      {
        $e = oci_error($connection);
        $this->erro     = '';
        $this->erro_msg = $e['message']."\n\n".$this->LastSQL;
        if ($this->in_transaction) $this->transaction_error = $this->erro;
        $position = strpos(trim(strtoupper($Query_String)), "SELECT ");
        if ($position !== 0) oci_rollback($this->Link_ID);
      }
    }
    catch(Exception $e)
    {
       $this->erro     = '';
       $this->erro_msg = $e['message']."\n\n".$this->LastSQL;
       if ($this->in_transaction) $this->transaction_error = $this->erro;
       $ejecuto = false;
       if ($position === false) oci_rollback($this->Link_ID);
    }

    if (!$ejecuto)
    {
       $e = oci_error($this->Parse_ID);
       $this->erro     = '';
       $this->erro_msg = $e['message']."\n\n".$this->LastSQL;
       $this->sql = $e['message']."\n\n".$this->LastSQL;
       if ($this->in_transaction) $this->transaction_error = $this->erro;
       $ejecuto = false;
       if ($position === false) oci_rollback($this->Link_ID);
    }

    $this->Row   = 0;
    return $ejecuto;

    //die($this->Query_ID);
    //return $this->Query_ID;
  }
  function getConnection()
  {
      if (!$this->Link_ID) return 0;
      return $this->Link_ID;
  }

  function beginTransaction()
  {
      /*$result = ibase_trans(0,$this->Link_ID);
      if (!$result)
      {
	      $this->erro     = 'X';
	      $this->erro_msg = 'could not start a transaction';
        return false;
      }*/
      $this->transaction_id = $this->Link_ID;
      $this->in_transaction = true;
  }

  function commit()
  {
      if (!$this->in_transaction) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'No hay transaccion en progreso';
          return false;
      }
      /*
      if($this->transaction_error)
      {
          $this->rollback();
	      $this->erro     = 'X';
	      $this->erro_msg = 'No se puede realizar fin de la transaccion';
          return false;
	  } */
      if (!@oci_commit($this->transaction_id)) {
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
      /*if ($this->transaction_id && !@ibase_rollback($this->transaction_id)) {
	      $this->erro     = 'X';
	      $this->erro_msg = 'No hay transaccion en progreso';
          return false;
      }
      $this->in_transaction = false;
      $this->transaction_id = 0;
      $this->transaction_error = 0;*/
  }

  function attributos()
  {
      //$count = ibase_num_fields($this->Query_ID);
      $count = oci_num_fields($this->Parse_ID);



      for ($i = 1; $i <= $count; $i++)
      {
        $nombre = oci_field_name($this->Parse_ID, $i);
        $longitud = oci_field_size($this->Parse_ID, $i);
        $precision = oci_field_precision($this->Parse_ID, $i);
        $ftype = oci_field_type($this->Parse_ID, $i);
        $tipo = substr($ftype,0,4);

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
            case 'CLOB':
                $tipo = 'X';
                break;
            case 'INTE':
            case 'BIGI':
                $tipo = 'I';
                break;
            //case 'NUMB':
                //break;
                /*$tipo='I';
                break;*/
            case 'NUMB':
            case 'NUME':
            case 'DOUB':
            case 'FLOA':
            case 'DECI':
                $tipo = 'N';      //30455
                //$longitud = 8;
        }

        $this->reg_campos[$nombre] 	= $i-1;
        $this->reg_campos_tipos[$i-1] = $ftype;

        $this->arreglo_atributos[$i-1]['NOMBRE'] = $nombre;
        $this->arreglo_atributos[$i-1]['TIPO']   = $tipo;
        $this->arreglo_atributos[$i-1]['LONG']   = $longitud;
	   }
  }

  function next_record()
  {
    if (!$this->Parse_ID) return 0;
    //die($this->Query_ID);
    //die($this->LastSQL);

    $this->Record = @oci_fetch_array($this->Parse_ID, OCI_ASSOC+OCI_RETURN_NULLS+OCI_RETURN_LOBS);
    if (!is_array($this->Record)) return false;
    //print_r($this->Record);
    /*die();*/
    //die('Marco='.sizeof($this->Record));
    $stat = is_array($this->Record);
    //$stat=true;

    if ($stat)
    {
      $this->Row++;

      $count = oci_num_fields($this->Parse_ID);
      for ($i = 1; $i <= $count; $i++)
      {
         $nombre = oci_field_name($this->Parse_ID, $i);
         $tipo = oci_field_type($this->Parse_ID, $i);

  	   	 /*if ($tipo=='BLOB')
         {
            $blob_data = @oci_blob_info($this->Record[$nombre]);
            $blob_id = @oci_blob_open($this->Record[$nombre]);
            $campo = @ibase_blob_get($blob_id, $blob_data[0] );
            $this->Record[$nombre] = $campo;
  	   	 }*/
       }




    } else if ($this->Auto_Free) {
      //$this->free_result();
    }
    return $stat;
  }

  function free_result() {
    if (is_resource($this->Parse_ID)) {
      @oci_free_statement($this->Parse_ID);
    }
    $this->Query_ID = 0;
  }

  function seek($pos) {
    $i = 0;
    while($i < $pos && @oci_fetch_row($this->Parse_ID)) { $i++; }
    $this->Row += $i;
    return true;
  }

  /* function num_rows() {} */

  function num_fields() {
    return oci_num_fields($this->Parse_ID);
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
      oci_close($this->Link_ID);
      $this->Connected = false;
    }
  }


  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>ORACLE Error</b><br>\n");
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