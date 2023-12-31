<?php

//DB MySQL Class @0-51A1A087
/*
* Database Management for PHP
*
* Copyright (c) 1998-2000 NetUSE AG
*                    Boris Erdmann, Kristian Koehntopp
*        Modified by Vitaliy Radchuk (vitaliy.radchuk@yessoftware.com)
*
* db_mysql.php
*
*/

class DB{

	/* public: connection parameters */
	var $DBHost     = "";
	var $DBPort     = "";
	var $DBDatabase = "";
	var $DBUser     = "";
	var $DBPassword = "";
	var $Persistent = false;

	/* public: configuration parameters */
	var $Auto_Free     = 1;     ## Set to 1 for automatic mysql_free_result()
	var $Debug         = 0;     ## Set to 1 for debugging messages.
	var $Halt_On_Error = "yes"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
	var $Seq_Table     = "db_sequence";
	var $Connected     = false;

	/* public: result array and current row number */
	var $Record   = array();
	var $Row;

	/* public: current error number and error text */
	var $Errno    = 0;
	var $Error    = "";

	/* public: this is an api revision, not a CVS revision. */
	var $type     = "mysql";
	var $revision = "1.2";

	/* private: link and query handles */
	var $Link_ID  = 0;
	var $Query_ID = 0;


	var $CmdExecution;
	var $Fichero_Upload;
	var $ValidoOk = true;
	var $InsertID;
	var $SQLArray=array();
	var $enTrans;

	var $primero;
	var $ultimo;
	var $sql;
    var $xwhere;

	/* public: constructor */
	function DB($query = "") {
		$this->query($query);
	}

	/* public: some trivial reporting */
	function link_id() {
		return $this->Link_ID;
	}

	function query_id() {
		return $this->Query_ID;
	}

	function try_connect($DBHost = "", $DBPort = "", $DBUser = "", $DBPassword = "")
    {
		$this->Query_ID  = 0;
		/* Handle defaults */
		if ("" == $DBHost)       $DBHost     = $this->DBHost;
		if ("" == $DBPort)       $DBPort     = $this->DBPort;
		if ("" == $DBUser)       $DBUser     = $this->DBUser;
		if ("" == $DBPassword)   $DBPassword = $this->DBPassword;

		if($DBPort != "") $DBHost .= ":" . $DBPort;

		if($this->Persistent)
		$this->Link_ID = @mysql_pconnect($DBHost, $DBUser, $DBPassword);
		else
		$this->Link_ID = @mysql_connect($DBHost, $DBUser, $DBPassword);

		$this->Connected = $this->Link_ID ? true : false;

		return $this->Connected;
	}

	/* public: connection management */
	function connect($DBDatabase = "", $DBHost = "", $DBPort = "", $DBUser = "", $DBPassword = "") {
		/* Handle defaults */
		if ("" == $DBDatabase)   $DBDatabase = $this->DBDatabase;
		if ("" == $DBPort)       $DBPort     = $this->DBPort;
		if ("" == $DBHost)       $DBHost     = $this->DBHost;
		if ("" == $DBUser)       $DBUser     = $this->DBUser;
		if ("" == $DBPassword)   $DBPassword = $this->DBPassword;


		if($DBPort != "") $DBHost .= ":" . $DBPort;

		/* establish connection, select database */
		if (!$this->Connected) {
			$this->Query_ID  = 0;
			if($this->Persistent)
			$this->Link_ID=mysql_pconnect($DBHost, $DBUser, $DBPassword);
			else
			$this->Link_ID=mysql_connect($DBHost, $DBUser, $DBPassword);

			if (!$this->Link_ID) {
				$this->halt("pconnect($DBHost, $DBUser, \$DBPassword) failed.");
				return 0;
			}

			if (!@mysql_select_db($DBDatabase,$this->Link_ID)) {
				$this->halt("cannot use database ".$this->DBDatabase);
				return 0;
			}
			$this->Connected = true;
		}

		return $this->Link_ID;
	}

	/* public: discard the query result */
	function free_result() {
		if(is_resource($this->Query_ID)) {
			@mysql_free_result($this->Query_ID);
		}
		$this->Query_ID = 0;
	}

	function config_sql()
	{
  		$this->ultimo  = ($this->pagina*$this->limite);
    	$this->primero = ($this->pagina-1)* $this->limite;
        $this->sql_count = '';
	    if($this->orden)  $this->sql .= ' ORDER BY ' . $this->orden;
	    $this->sql_count = $this->sql ;
	    if($this->limite) $this->sql .= ' LIMIT ' . $this->primero . ' , ' . $this->limite;
	}

	function cuenta_registros()
	{
	    if(strlen($this->sql_count))
	    {
	        $this->query($this->sql_count,false);
	        $this->registros = $this->num_rows();
	        if($this->limite && $this->registros)
	        {
	            $this->paginas = ceil($this->registros/$this->limite);
	        }
	    }
	}


	/* public: perform a query */
	  function query($Query_String,$sw=true) {
		/* No empty queries, please, since PHP4 chokes on them. */
		if ($Query_String == "")
		/* The empty query string is passed on from the constructor,
		* when calling the class without a query, e.g. in situations
		* like these: '$db = new DB_Sql_Subclass;'
		*/
		return 0;
		$tmp = explode(' ',trim($Query_String));
		if (!$this->connect()) {
			return 0; /* we already complained in connect() about that. */
		};

		# New query, discard previous result.
		if ($this->Query_ID) {
			$this->free_result();
		}

		if ($this->Debug)
		printf("Debug: query = %s<br>\n", $Query_String);
        $this->LastSQL = $Query_String;

        if(strlen($this->sql_count) && $sw)
        {
          /*--- calculo de registros ---*/
          /*----- hecho por marco ------*/
          /*--- tomo el sql y saco solo las condiciones where -----*/
          $q=$Query_String;

          if (stripos($q,'UNION')!==false)  //---- si se hizo alguna busqueda
          {
            $p=strripos($q,'LIMIT');
            if ($p!==false)
            {
              $q=substr($q,0,$p);
              $p=strripos($q,'ORDER');
              if ($p!==false)
              {
                $q=substr($q,0,$p);
              }
            }
            $q = 'SELECT COUNT(*) cant FROM ('.$q.') t';
          }
          else  //---- si se no hizo alguna busqueda
          {
            $p=stripos($q,'FROM');
            if ($p!==false)
            {
              $q=substr($q,$p-1);
              $p=strripos($q,'LIMIT');
              if ($p!==false)
              {
                $q=substr($q,0,$p);
                $p=strripos($q,'ORDER');
                if ($p!==false)
                {
                  $q=substr($q,0,$p);
                }
              }
            }
            $q = 'SELECT COUNT(*) cant '.$q;
          }

          $tot = @mysql_query($q,$this->Link_ID);

          if ($r=@mysql_fetch_array($tot,MYSQL_ASSOC))
          {
            $this->registros = $r['cant'];
            if($this->limite && $this->registros)
            {
                $this->paginas = ceil($this->registros/$this->limite);
            }
          }
          /*----- fin de marco ----*/


          //$this->cuenta_registros();
        }

		$this->Query_ID = @mysql_query($Query_String,$this->Link_ID);


		$this->Row   = 0;
		$this->Errno = mysql_errno();
		$this->Error = mysql_error();
		if (!$this->Query_ID) {
			$this->halt("Invalid SQL: ".$Query_String);
		}
		# Will return nada if it fails. That's fine.
		return $this->Query_ID;
	}

	/* public: walk result set */
	function next_record() {
		if (!$this->Query_ID) {
			$this->halt("next_record called with no query pending.");
			return 0;
		}

		$this->Record = @mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
	   	if(is_array($this->Record)) $this->Record = array_change_key_case($this->Record , CASE_UPPER);

		$this->Row   += 1;
		$this->Errno  = mysql_errno();
		$this->Error  = mysql_error();

		$stat = is_array($this->Record);
		if (!$stat && $this->Auto_Free) {
			$this->free_result();
		}
		return $stat;
	}

	/* public: position in result set */
	function seek($pos = 0) {
		$status = @mysql_data_seek($this->Query_ID, $pos);
		if ($status) {
			$this->Row = $pos;
		} else {

			/* half assed attempt to save the day,
			* but do not consider this documented or even
			* desireable behaviour.
			*/
			@mysql_data_seek($this->Query_ID, $this->num_rows());
			$this->Row = $this->num_rows();
		}
		return true;
	}

	/* public: table locking */
	function lock($table, $mode="write") {
		$this->connect();

		$query="lock tables ";
		if (is_array($table)) {
			while (list($key,$value)=each($table)) {
				if ($key=="read" && $key!=0) {
					$query.="$value read, ";
				} else {
					$query.="$value $mode, ";
				}
			}
			$query=substr($query,0,-2);
		} else {
			$query.="$table $mode";
		}
		$res = @mysql_query($query, $this->Link_ID);
		if (!$res) {
			$this->halt("lock($table, $mode) failed.");
			return 0;
		}
		return $res;
	}

	function unlock() {
		$this->connect();

		$res = @mysql_query("unlock tables");
		if (!$res) {
			$this->halt("unlock() failed.");
			return 0;
		}
		return $res;
	}


	/* public: evaluate the result (size, width) */
	function affected_rows() {
		return @mysql_affected_rows($this->Link_ID);
	}

	function num_rows() {
		return @mysql_num_rows($this->Query_ID);
	}

	function num_fields() {
		return @mysql_num_fields($this->Query_ID);
	}

	/* public: shorthand notation */
	function nf() {
		return $this->num_rows();
	}

	function np() {
		print $this->num_rows();
	}

	function f($Name) {
		return isset($this->Record[$Name]) ? $this->Record[$Name] : "";
	}

	function p($Name) {
		print $this->Record[$Name];
	}

	/* public: sequence numbers */
	function nextid($seq_name) {
		$this->connect();

		if ($this->lock($this->Seq_Table)) {
			/* get sequence number (locked) and increment */
			$q  = sprintf("select nextid from %s where seq_name = '%s'",
			$this->Seq_Table,
			$seq_name);
			$id  = @mysql_query($q, $this->Link_ID);
			$res = @mysql_fetch_array($id);

			/* No current value, make one */
			if (!is_array($res)) {
				$currentid = 0;
				$q = sprintf("insert into %s values('%s', %s)",
				$this->Seq_Table,
				$seq_name,
				$currentid);
				$id = @mysql_query($q, $this->Link_ID);
			} else {
				$currentid = $res["nextid"];
			}
			$nextid = $currentid + 1;
			$q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
			$this->Seq_Table,
			$nextid,
			$seq_name);
			$id = @mysql_query($q, $this->Link_ID);
			$this->unlock();
		} else {
			$this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
			return 0;
		}
		return $nextid;
	}

  function attributos()
  {
      $count = mysql_num_fields($this->Query_ID);
      for ($i = 0; $i < $count; $i++)
      {
	    $nombre     = mysql_field_name  ($this->Query_ID, $i);
	    $tipo       = mysql_field_type  ($this->Query_ID, $i);
	    $longitud   = mysql_field_len   ($this->Query_ID, $i);
        $tipo       = strtoupper(substr($tipo,0,5));
        $tipo       = str_pad($tipo, 5);

        switch($tipo)
        {
            case 'CHAR ':
            case 'VARCH':
            case 'STRIN':
                $tipo = 'C';
                break;
            case 'TIMES':
            case 'TIME ':
            case 'DATET':
                $tipo = 'T';
                break;
            case 'DATE ':
                $tipo = 'D';
                break;
            case 'BLOB ':
                $tipo = 'X';
                break;
            case 'INTEG':
            case 'INT  ':
            case 'BIGI ':
                $tipo = 'I';
                break;
            case 'NUMER':
            case 'DOUBL':
            case 'FLOAT':
            case 'DECIM':
                $tipo = 'N';      //30455
        }

        $nombre = strtoupper($nombre);
        $this->reg_campos[$nombre] 	= $i;
        $this->reg_campos_tipos[$i] = strtoupper(mysql_field_type  ($this->Query_ID, $i));

        $this->arreglo_atributos[$i]['NOMBRE'] = strtoupper($nombre);
        $this->arreglo_atributos[$i]['TIPO']   = strtoupper($tipo);
        $this->arreglo_atributos[$i]['LONG']   = $longitud;
	}
  }


	/* public: return table metadata */
	function metadata($table='',$full=false) {
		$count = 0;
		$id    = 0;
		$res   = array();

		/*
		* Due to compatibility problems with Table we changed the behavior
		* of metadata();
		* depending on $full, metadata returns the following values:
		*
		* - full is false (default):
		* $result[]:
		*   [0]["table"]  table name
		*   [0]["name"]   field name
		*   [0]["type"]   field type
		*   [0]["len"]    field length
		*   [0]["flags"]  field flags
		*
		* - full is true
		* $result[]:
		*   ["num_fields"] number of metadata records
		*   [0]["table"]  table name
		*   [0]["name"]   field name
		*   [0]["type"]   field type
		*   [0]["len"]    field length
		*   [0]["flags"]  field flags
		*   ["meta"][field name]  index of field named "field name"
		*   The last one is used, if you have a field name, but no index.
		*   Test:  if (isset($result['meta']['myfield'])) { ...
		*/

		// if no $table specified, assume that we are working with a query
		// result
		if ($table) {
			$this->connect();
			$id = @mysql_list_fields($this->DBDatabase, $table);
			if (!$id)
			$this->halt("Metadata query failed.");
		} else {
			$id = $this->Query_ID;
			if (!$id)
			$this->halt("No query specified.");
		}

		$count = @mysql_num_fields($id);

		// made this IF due to performance (one if is faster than $count if's)
		if (!$full) {
			for ($i=0; $i<$count; $i++) {
				$res[$i]["table"] = @mysql_field_table ($id, $i);
				$res[$i]["name"]  = @mysql_field_name  ($id, $i);
				$res[$i]["type"]  = @mysql_field_type  ($id, $i);
				$res[$i]["len"]   = @mysql_field_len   ($id, $i);
				$res[$i]["flags"] = @mysql_field_flags ($id, $i);
			}
		} else { // full
		$res["num_fields"]= $count;

		for ($i=0; $i<$count; $i++) {
			$res[$i]["table"] = @mysql_field_table ($id, $i);
			$res[$i]["name"]  = @mysql_field_name  ($id, $i);
			$res[$i]["type"]  = @mysql_field_type  ($id, $i);
			$res[$i]["len"]   = @mysql_field_len   ($id, $i);
			$res[$i]["flags"] = @mysql_field_flags ($id, $i);
			$res["meta"][$res[$i]["name"]] = $i;
		}
		}

		// free the result only if we were called on a table
		if ($table && is_resource($id)) @mysql_free_result($id);
		return $res;
	}

	function close()
	{
		if ($this->Query_ID) {
			$this->free_result();
		}
		if ($this->Connected && !$this->Persistent) {
			mysql_close($this->Link_ID);
			$this->Connected = false;
		}
	}

	/* private: error handling */
	function halt($msg) {
		$this->Error = @mysql_error($this->Link_ID);
		$this->Errno = @mysql_errno($this->Link_ID);
		if ($this->Halt_On_Error == "no")
		return;

		$this->haltmsg($msg);

		if ($this->Halt_On_Error != "report")
		die("Session halted.");
	}

	function haltmsg($msg) {
		printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
		printf("<b>MySQL Error</b>: %d (%s)<br>\n",
		$this->Errno,
		$this->Error);
	}

	function table_names() {
		$this->query("SHOW TABLES");
		$i=0;
		while ($info=mysql_fetch_row($this->Query_ID))
		{
			$return[$i]["table_name"]= $info[0];
			$return[$i]["tablespace_name"]=$this->DBDatabase;
			$return[$i]["database"]=$this->DBDatabase;
			$i++;
		}
		return $return;
	}


	function AddInsert($Campo, $Valor){
		$NSQL = array();
		$Query = explode(" VALUES ",$this->SQL);
		$Query[0] = str_replace(")","",$Query[0]);
		$Query[1] = str_replace(")","",$Query[1]);

		$NSQL[0] .= $Query[0] . ", " . $Campo . ")";
		$NSQL[1] .= $Query[1] . ", " . $Valor . ")";

		$this->SQL= $NSQL[0] . "  VALUES " . $NSQL[1] ;

	}
	function BeginTrans(){
		$this->enTrans = true;
		if (!$this->connect()) {
			return false;
		}
		$Query_ID = mysql_query("BEGIN",$this->Link_ID);
		return true;
	}

	function EndTrans(){
		if (!$this->connect()) {
			return false;
		}
		if(!$this->ValidoOk){
			$Query_ID = mysql_query("ROLLBACK",$this->Link_ID);
			$this->Errors->addError("Todos las Operaciones realizadas fueron descartadas.");
			return false;
		}
		$Query_ID = mysql_query("COMMIT",$this->Link_ID);
		$this->enTrans = false;
		return true;
	}
	function CancelTrans(){
		if (!$this->connect()) {
			return false;
		}
		$Query_ID = mysql_query("ROLLBACK",$this->Link_ID);
		$this->enTrans = false;
		return true;
	}

	function GetAutoId($Tabla){
		if (!$this->connect()) {
			return false; /* we already complained in connect() about that. */
		};

		if ($this->Query_ID) {
			$this->free_result();
		}

		$Query_ID = @mysql_query("SHOW TABLE STATUS FROM " . $this->DBDatabase ,$this->Link_ID);
		while ($row = mysql_fetch_array($Query_ID)){
			$Name = $row[Name];
			$server_tablas[$Name]['Name'] = $row[Name];
			$server_tablas[$Name]['Type'] =	$row[Type];
			$server_tablas[$Name]['Rows'] =	$row[Rows];
			$server_tablas[$Name]['Auto_increment'] = $row[Auto_increment];
			$server_tablas[$Name]['Create_time'] = $row[Create_time];
			$server_tablas[$Name]['Update_time'] = $row[Update_time];
			if($Tabla == $Name)	break;
		}
		return $server_tablas[$Tabla]['Auto_increment'];
	}
}

//End DB MySQL Class


?>