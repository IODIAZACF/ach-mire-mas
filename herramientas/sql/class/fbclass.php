<?

/**
 ======================
        FBClass
 ======================

 Desenvolvido por: Fábio P. Santos
 www.firebird.eti.br
 fabio@firebird.eti.br

*/

define('TRANS_READ', IBASE_COMMITTED | IBASE_NOWAIT | IBASE_READ);
define('TRANS_WRITE', IBASE_COMMITTED | IBASE_NOWAIT | IBASE_WRITE);

/**
 * fb::fb()
 * Constructor this class - define public connection parameters and
 * call the connect method
 *
 * @param $database
 * @param $user
 * @param $password
 * @param $debug
**/

class FB{

	/* publico: manipulação erro */

	public $erro;
	public $erro_msg;


    public $c_insert;
    public $sql_count;

    /* publico: diversos */

	public $hora_servidor;
	public $generator;
	public $lineas;
	public $campos_tabla = array();

	public $commit;

	/* publico: insert */

	public $param_ent = array();
	public $param_opr = array();
	public $param_sai = array();

	/* privado: diversos */

	private $conex;
	private $result;
	private $intQuery;
	private $coln;

	private $tipocampo;
	private $tamcampo;
    public $bind;
    public $cache;


// Contrutor da Classe

 public function __construct() {
   $this->erro=0;
   $this->erro_msg='';
   $this->lineas=0;
   $this->coln=0;
   $this->intQuery=0;
   $param_ent = array();
   $param_sai = array();
   }

 public function __destruct() {
   //echo "Destruindo objeto...\n";
   }

/*--------------------------------------------------------------*/

/**
     * fb::Conecta()
     * Abre uma conexão com banco
	 *
     * @return id da conexao
     */

public function Conecta($host, $db, $user, $pass){
	$thist->host = $host;
	$this->db=$host.':'.$db;
	$this->user  = $user;
	$this->pass  = $pass;

	$this->erro=0;
	$this->erro_msg='';

	$this->commit=0;

	$this->conex=@ibase_connect($this->db, $this->user, $this->pass,'ISO8859_1');
	if (!$this->conex) {
		$this->erro=ibase_errcode();
		$this->erro_msg=ibase_errmsg();
	}
	if($this->erro==0) {
	   @ibase_trans(IBASE_COMITTED,IBASE_READ,IBASE_REC_VERSION,IBASE_NOWAIT,$this->conex);
	}
}

public function ErrorMsg()
{
	return $this->erro;
}
public function Execute($query)
{   $result=0;
	$this->result=@ibase_query($this->conex,$query);
    //echo $query . "<br>";
	if($this->result)
    {
	  $this->erro=0;
	  $this->erro_msg='';
      $result=$this->result;
	} else {
	  $this->erro=ibase_errcode();
	  $this->erro_msg=ibase_errmsg();
	}
    return $result;
}

private function Commit() {
	//ibase_commit_ret($this->conex);
	ibase_commit($this->conex);
}

public function LeRegistro(){
 if($this->erro==0) {
   $this->registro = @ibase_fetch_assoc ($this->result);
   //$this->result=$result;
   return $this->registro;
 }
}

/*--------------------------------------------------------------*/

public function Result(){
  if ($this->erro==0) {
    return $result=@ibase_fetch_object($this->result);
  }
}

public function result_assoc(){
	return mysql_fetch_assoc($this->query);
}

public function Campostabla($tabla) {
  if($this->tablaExiste($tabla)) {

  	$this->tipocampo=null;
  	$this->tamcampo=null;

  	$sql="select distinct ";
  	$sql.="A.RDB\$FIELD_POSITION as F_ID, ";
  	$sql.="A.RDB\$FIELD_NAME as F_NAME, ";
  	$sql.="case ";
  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then 'N' ";
  	$sql.="when C.RDB\$TYPE_NAME='LONG' then 'I' ";
  	$sql.="when C.RDB\$TYPE_NAME='DATE' then 'D' ";
  	$sql.="when C.RDB\$TYPE_NAME='TIMESTAMP' then 'T' ";
  	$sql.="when C.RDB\$TYPE_NAME='SHORT' then 'I' ";
  	$sql.="when C.RDB\$TYPE_NAME='VARYING' then 'C' ";
  	$sql.="when C.RDB\$TYPE_NAME='TEXT' then 'X' ";
  	$sql.="when C.RDB\$TYPE_NAME='BLOB' then 'B' ";
  	$sql.="else ";
  	$sql.="C.RDB\$TYPE_NAME ";
  	$sql.="end as F_TIPO, ";
  	$sql.="case ";
  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then ";
  	$sql.="''||cast(B.RDB\$FIELD_PRECISION as ";
  	$sql.="varchar(2))||','||cast(B.RDB\$FIELD_SCALE*-1 as varchar(2))||''";
  	$sql.="when B.RDB\$CHARACTER_LENGTH is null then  '0' ";
  	$sql.="else ";
  	$sql.="B.RDB\$CHARACTER_LENGTH ";
  	$sql.="end as F_TAMANHO, ";
  	$sql.="F.RDB\$FIELD_NAME as F_Clave ";
  	$sql.="from ";
  	$sql.="RDB\$RELATION_FIELDS A ";
  	$sql.="left join RDB\$FIELDS  B on A.RDB\$FIELD_SOURCE=B.RDB\$FIELD_NAME "; echo "<hr>";
  	$sql.="left join RDB\$TYPES C on C.RDB\$FIELD_NAME='RDB\$FIELD_TYPE' and  ";
  	$sql.="B.RDB\$FIELD_TYPE=C.RDB\$TYPE ";
  	$sql.="left join RDB\$RELATION_CONSTRAINTS E on A.RDB\$RELATION_NAME=E.RDB\$RELATION_NAME ";
  	$sql.="and E.RDB\$CONSTRAINT_TYPE='PRIMARY KEY' ";
  	$sql.="left join RDB\$INDEX_SEGMENTS F on E.RDB\$INDEX_NAME=F.RDB\$INDEX_NAME and ";
  	$sql.="A.RDB\$FIELD_NAME=F.RDB\$FIELD_NAME ";
  	$sql.="where ";
  	$sql.="A.RDB\$RELATION_NAME = '$tabla'";


  	unset($this->campos_tabla);

    $res=ibase_query($this->conex,$sql);

    while($row=ibase_fetch_object($res)) {
      if($row->F_Clave==$row->F_NAME) {
        $Clave='S';
      } else {
      	$Clave='N';
      }
      $n = trim($row->F_NAME);
      $this->campos_tabla[$n]=array('ID' => $row->F_ID,
                     'NOMBRE' => $n,
                     'TIPO' => trim($row->F_TIPO),
                     'TAMANO' => $row->F_TAMANHO,
                     'CLAVE' => $Clave);
      if(strlen($this->c_insert)) $this->c_insert .= ',';
      $this->c_insert .= $n;
    }

    ibase_free_result($res);
  }
}

public function ValorGenerator($tabla) {

  if($this->tablaExiste($tabla))	{

  $this->generator=0;

  $sql="select rdb\$trigger_source as TEXTO from rdb\$triggers where rdb\$relation_name = '$tabla'";

  $this->erro=0;
  $this->erro_msg='Valor generator tabla '.$tabla.' retornado com sucesso';

  $result=@ibase_query($this->conex,$sql);

  if($result) {

  while($res = ibase_fetch_object($result))  {

    //$res=@ibase_fetch_object($result);
    $blob_data = @ibase_blob_info($res->TEXTO);
    $blob_id = @ibase_blob_open($res->TEXTO);

    $texto = @ibase_blob_get($blob_id, $blob_data[0]);

    $texto=strtoupper($texto);

    if(stripos($texto,'GEN_ID')) {
      $st=explode('GEN_ID',$texto);
      $st=$st[1];
      $st=explode(',',$st);
      $st=$st[0];
      $generator=substr($st,1,strlen($st));
    }
  }

    if(empty($generator)) {
      $this->erro=99;
      $this->erro_msg='Nenhum GENERATOR para a tabla '.$tabla.'.';
    } else {
       $sql="select GEN_ID($generator,0) as AID from RDB\$DATABASE";
       $res=@ibase_query($this->conex,$sql);
       $res=@ibase_fetch_object($res);
       $this->generator=$res->AID;
    }

  } else {
      $this->erro=ibase_errcode();
      $this->erro_msg=ibase_errmsg();
  }
 } else {
    $this->erro=99;
    $this->erro_msg='tabla '.$tabla.' não existe.';
 }
}

function leer_attributos()
{
    if($this->result)
    {
    	$coln = ibase_num_fields($this->result);
	    for ($i = 0; $i < $coln; $i++)
        {
            $col_info = ibase_field_info($this->result, $i);
            $nombre   = $col_info['name'];
	        $longitud = $col_info['length'];
            $tipo = substr($col_info['type'],0,4);

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
                case 'NUME':
                case 'INTE':
                case 'DOUB':
                case 'FLOA':
                case 'DECI':
                    $tipo = 'N';
            }

            $this->bind[$nombre] = $i;
            $this->cache[$i]     = $col_info['type'];
            $attrib[$i]['NOMBRE'] = $nombre;
            $attrib[$i]['TIPO']   = $tipo;
            $attrib[$i]['LONG']   = $longitud;
	    }
        return $attrib;
	}

}


}

?>