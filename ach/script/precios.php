<?php
include_once (Server_Path . "herramientas/genera_xml/class/class_genera_xml.php");
include_once (Server_Path . "herramientas/numero_letras/numero_letras.php");
$query = new sql();
$xml = new class_genera_xml();

$sql  = "SELECT * FROM S_ACTUALIZACION_PRECIOS(";
$sql .= getvar('DESCRIPCION') == '' ? "NULL" : "'". getvar('DESCRIPCION') . "'";
$sql .= getvar('ID_M_MODELOS') == '' ? ",NULL" : ",'". getvar('ID_M_MODELOS') . "'";
$sql .= getvar('PRECIO1') == '' ? ",NULL" : ",". getvar('PRECIO1');
$sql .= getvar('PRECIO2') == '' ? ",NULL" : ",". getvar('PRECIO2');
$sql .= getvar('PRECIO3') == '' ? ",NULL" : ",". getvar('PRECIO3');
$sql .= getvar('PRECIO4') == '' ? ",NULL" : ",". getvar('PRECIO4');
$sql .= getvar('PRECIO5') == '' ? ",NULL" : ",". getvar('PRECIO5');
$sql .= getvar('PRECIO6') == '' ? ",NULL" : ",". getvar('PRECIO6');
$sql .= ')' ;

$query->sql = $sql;
$query->ejecuta_query();


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
	            if($xml->convertir_numero_letras)
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

}
$xml->campos = $campos;
$xml->attrib = $attrib;
$xml->sql    = $query->sql;
$xml->pagina = 0;
$xml->paginas   = $query->paginas;
$xml->registros = $query->regi;

if(!is_array($datos))
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

if ($xml->estilo) $xml->estilo .= '.xsl';
//rdebug($xml,'s');

$xml->imprime_xml();

die();



?>