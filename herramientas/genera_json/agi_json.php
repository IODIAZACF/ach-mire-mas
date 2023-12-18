<?php
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/genera_json/class/class_genera_json.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");

$db_server[0]['DB']         = "/opt/lampp/firebird/db/". $_REQUEST['db'] .".gdb";

$Url_Modulo = '';
$Url_Modulo = isset($_REQUEST['url_modulo']) ? dirname($_REQUEST['url_modulo'],2) : '';
if(getvar('origen')){
	if($Url_Modulo!=''){
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
}


$query 				  = new sql();
$query->origen        = strlen($origen) ? $origen : '';
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
$query->pagina        = getvar('pagina');

if (!$query->operador) $query->operador = "*";

if (!$query->limite)
{
	if($query->xbusca=="*")
    {
    	$query->limite = limite;
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

$json = new class_genera_json();
//$json->convertir_numero_letras = true;
$cnumero_letras = new numero_letras();
$cnumero_letras->setGenero(1);
$cnumero_letras->setMoneda("");


$query->tipo = "select";
$query->arma_sql();
$query->ejecuta_query();
if(getvar('debug')) rdebug($query);
if(is_array($query->Record))
{
	foreach($query->Record as $campo=>$valor)
	{
	    if($campos) $campos .= ',';
	    $campos .= $campo;
	}
    $attrib = $query->arreglo_atributos;
    //rdebug($query,'e');
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
	            if($json->convertir_numero_letras)
	            {
	                $ult = sizeof($attrib);
	                $attrib[$ult]['NOMBRE'] = $campo . '_LETRAS';
	                $attrib[$ult]['TIPO'] = 'x';
	                $attrib[$ult]['LONG'] = 'x';
	            }
	    }
	    if($campos) $campos .= ',';
	    $campos .= $campo;
	}

}
$hayError = $query->Error;// !is_array($datos);

if (getvar('resumen')) $json->resumen = "no"; // propiedad para evitar mostrar el resumen (totales, query y atributos)

if ($hayError)
{
	$msg = $datos;
  	$msg = explode('|',$msg);
  	unset($datos);
	$json->arbol = 'error';
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
	            $json->condicion[$condicion[$i]['CAMPO']][] = $condicion[$i]['NIVEL'].','.$condicion[$i]['ATRIBUTOS'].','.$condicion[$i]['OPERADOR'].','.$condicion[$i]['VALOR'];
	        }
	    }
	}
}
$json->campos = $campos;
$json->attrib = $attrib;
$json->sql    = $query->sql;
$json->pagina = getvar('pagina');
$json->paginas = $query->paginas;
$json->registros = $query->regi;
//rdebug($query,'s');

if(!is_array($datos))
{
    for($i=0;$i<sizeof($attrib);$i++)
    {
    	$nombre = $attrib[$i]['NOMBRE'];
    	$json->totales[$nombre] = '';
    }
    $datos = '';
}

$json->data = $query;

//rdebug($json,'s');
$json->estilo = getvar('estilo');
$json->header = getvar('header');

if ($json->estilo) $json->estilo .= '.xsl';
//rdebug($json,'s');

$json->imprime_json();

//echo $json->contenido;
?>