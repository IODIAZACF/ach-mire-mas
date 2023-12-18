<?php
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/genera_xml/class/class_genera_xml.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

$Url_Modulo = '';
$Url_Modulo = isset( $_REQUEST['url_modulo'] ) ? dirname( $_REQUEST['url_modulo'] , 2 ) : '';

if( getvar('origen') ) {
	if($Url_Modulo!=''){
		$origen = Server_Path . $Url_Modulo  . '/' .  getvar('origen');
		if (str_contains( $Url_Modulo, 'herramientas') ) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
		if (str_contains(getvar('origen'), 'maestros/')) {
			$origen = RUTA_SISTEMA . getvar('origen');
		}
	}else{
		$origen = RUTA_SISTEMA . getvar('origen');
	}
}

//file_put_contents ('/opt/tmp/genera_xml.txt', strtoupper ( ( getvar('xbusca') )) . PHP_EOL , FILE_APPEND ) ;

control_session();
$query 				  = new sql();
$query->origen        = strlen($origen) ? $origen : '';
$query->orden         = getvar('orden');
$query->tabla         = getvar('tabla');
$query->campos        = urldecode(getvar('campos'));
$query->busca         =  ( getvar('busca') );
$query->xbusca        = strtoupper( utf8_decode ( getvar('xbusca') ));   // hacer con el config.....luis
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

$xml = new class_genera_xml();
//$xml->convertir_numero_letras = true;
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

//print_r ( $query );

if(getvar('debug')) rdebug($query);
if(is_array($query->Record))
{
    $attrib = $query->arreglo_atributos;

    for($i=0;$i<sizeof($query->arreglo_atributos);$i++)
	{
	    $tipo = $query->arreglo_atributos[$i]['TIPO'];
        $campo = $query->arreglo_atributos[$i]['NOMBRE'];
	    switch($tipo)
	    {
	        case 'I':
	        case 'D':
            case 'T':
	        case 'F':
            case 'N':
	            if($xml->convertir_numero_letras)
	            {
	                $ult = sizeof($attrib);
	                $attrib[$ult]['NOMBRE'] = $campo . '_LETRAS';
	                $attrib[$ult]['TIPO'] = 'x';
	                $attrib[$ult]['LONG'] = 'x';
	            }
	    }
	}
}
$xcampos = '';
foreach($query->reg_campos as $campo=>$valor)
{
    if($xcampos) $xcampos .= ',';
    $xcampos .= $campo;
}


$hayError = $query->Error;// !is_array($datos);

if (getvar('resumen')) $xml->resumen = "no"; // propiedad para evitar mostrar el resumen (totales, query y atributos)

if ($hayError)
{
	$msg = $datos;
  	$msg = explode('|',$msg);
  	unset($datos);
	$xml->arbol = 'error';
	$datos[0]['MENSAJE']       = $msg[0];
	$datos[0]['QUERY']         = $msg[1];
}
else
{
	if($query->origen)
	{
  		$ini = new ini($query->origen);
		$condicion = $ini->secciones("CONDICION","","N");

        if(is_array($condicion))
	    {
	        for ($i=0;$i<sizeof($condicion);$i++)
	        {
	            $xml->condicion[$condicion[$i]['CAMPO']][] = $condicion[$i]['NIVEL'].','.$condicion[$i]['ATRIBUTOS'].','.$condicion[$i]['OPERADOR'].','.$condicion[$i]['VALOR'];
	        }
	    }
	}
	//rdebug($xml,'s');
}

$xml->campos = $xcampos;
$xml->attrib = $attrib;
$xml->sql    = $query->sql;
$xml->pagina = getvar('pagina');
$xml->paginas = $query->paginas;
$xml->registros = $query->regi;
//rdebug($xml,'s');

if(!isset($datos))
{
    for($i=0;$i<sizeof($attrib);$i++)
    {
    	$nombre = $attrib[$i]['NOMBRE'];
    	$xml->totales[$nombre] = '';
    }
    $datos = '';
}

$xml->data = $query;

//rdebug($xml,'s');
$xml->estilo = getvar('estilo');
$xml->header = getvar('header');

//rdebug($xml,'s');

if ($xml->estilo) $xml->estilo .= '.xsl';
//rdebug($xml,'s');

$xml->imprime_xml();

//file_put_contents ('/opt/tmp/genera_xml.txt', print_r ( $query, true ) , FILE_APPEND) ;

//echo $xml->contenido;
?>
