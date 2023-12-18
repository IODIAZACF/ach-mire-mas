<?php
header("Access-Control-Allow-Origin:*");
define("Server_Path", "../../");
define("", "../../");
global $Verifica_session;
// echo "<pre>";
// print_r($_SERVER);
// die();

include_once (Server_Path . "herramientas/jwt/jwt.php");
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/genera_json/class/class_genera_json.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

Verifica_Session();
//control_session();


$query 				  = new sql();
$query->origen        = getvar('origen') ? Server_Path . getvar('origen') : "";
$query->orden         = getvar('orden');
$query->tabla         = getvar('tabla');
$query->campos        = urldecode(getvar('campos'));
$query->busca         = getvar('busca');
$query->xbusca        = strtoupper(getvar('xbusca'));   // hacer con el config.....luis
$query->limite        = getvar('limite');
$query->operador      = getvar('operador');
$query->procedimiento = getvar('procedimiento');
$query->filtro        = getvar('filtro');
$query->filtrar       = getvar('xfiltro');
$query->filtro_op     = getvar('xoperadores');
$query->pagina        = getvar('pagina',0);
$query->contar        = false;

if (!$query->operador) $query->operador = "*";

$tmp = substr($query->xbusca,0,1);
if($tmp=='=')
{
	$query->operador      = '=';
	$query->xbusca        = substr($query->xbusca,1);
}

if (!$query->limite)
{
	if($query->xbusca=="*")
    {
    	//$query->limite = limite;
        $query->xbusca = '';
    }
}
else
{
	if($query->xbusca=="*")
    {
    	$query->xbusca = '';
    }
}

$js = new class_genera_json();
//$js->convertir_numero_letras = true;
$cnumero_letras = new numero_letras();
$cnumero_letras->setGenero(1);
$cnumero_letras->setMoneda("");

$query->tipo = "select";
$query->arma_sql();


$tmp    = preg_split('/ /', $query->sql ,0,PREG_SPLIT_NO_EMPTY);
$tipo   = trim(strtolower($tmp[0]));
if($tipo!='select')
{
    echo "No esta permitido ejecutar esta instruccion:<br>" . $query->sql . "<br>Utilice <b>actualizar_registro.php<b>";
    die();
}
$query->ejecuta_query();

//if(getvar('debug')) rdebug($query);
if(is_array($query->Record))
{
    $attrib = $query->arreglo_atributos;

    for($i=0;$i<sizeof($query->arreglo_atributos);$i++)
	{
	    $tipo = $query->arreglo_atributos[$i][TIPO];
        $campo = $query->arreglo_atributos[$i][NOMBRE];
	    switch($tipo)
	    {
	        case 'I':
	        case 'D':
            case 'T':
	        case 'F':
            case 'N':
	            if($js->convertir_numero_letras)
	            {
	                $ult = sizeof($attrib);
	                $attrib[$ult]['NOMBRE'] = $campo . '_LETRAS';
	                $attrib[$ult]['TIPO'] = 'x';
	                $attrib[$ult]['LONG'] = 'x';
	            }
	    }
	}
}
foreach($query->reg_campos as $campo=>$valor)
{
    if($xcampos) $xcampos .= ',';
    $xcampos .= $campo;
}


$hayError = $query->Error;// !is_array($datos);

if (getvar('resumen')) $js->resumen = "no"; // propiedad para evitar mostrar el resumen (totales, query y atributos)

if ($hayError)
{
	$msg = $datos;
  	$msg = explode('|',$msg);
  	unset($datos);
	$js->arbol = 'error';
	$datos[0]['MENSAJE']       = $msg[0];
	$datos[0]['QUERY']         = $msg[1];
}
else
{
	if(getvar('origen'))
	{
  		$ini = new ini(Server_Path . getvar('origen'));
		$condicion = $ini->secciones("CONDICION","","N");

        if(is_array($condicion))
	    {
	        for ($i=0;$i<sizeof($condicion);$i++)
	        {
	            $js->condicion[$condicion[$i]['CAMPO']][] = $condicion[$i]['NIVEL'].','.$condicion[$i]['ATRIBUTOS'].','.$condicion[$i]['OPERADOR'].','.$condicion[$i]['VALOR'];
	        }
	    }
	}
	//rdebug($js,'s');
}

$js->campos = $xcampos;
$js->attrib = $attrib;
$js->sql    = $query->sql;
$js->pagina = getvar('pagina');
$js->paginas = $query->paginas;
$js->registros = $query->regi;
//rdebug($js,'s');

if(!is_array($datos))
{
    for($i=0;$i<sizeof($attrib);$i++)
    {
    	$nombre = $attrib[$i]['NOMBRE'];
    	$js->totales[$nombre] = '';
    }
    $datos = '';
}
$js->data = $query;
//rdebug($js,'s');
$js->estilo = getvar('estilo');
$js->header = getvar('header');

//rdebug($js,'s');

if ($js->estilo) $js->estilo .= '.xsl';
//rdebug($js,'s');
$js->imprime_json();

//echo $js->contenido;
?>
