<?
define("Server_Path","../../");
include_once(Server_Path . "herramientas/sql/class/class_sql.php");
include_once(Server_Path . "herramientas/utiles/xml2.php");

$Usa_log = true;
$xUrl = getvar('url');
$url = parse_url($xUrl);
parse_str($url['query'],$query);

while(list($k,$v) = each($query))
{
    $v = urlencode($v);
    $tUrl[]= $k.'=' .$v ;
}

$tUrl = $xUrl;

$fXML = file_get_contents($tUrl);


$forma_xml = xml2array($fXML);

//rdebug($forma_xml,'');
$variables = leer_vars('CAMPO');
$query = new sql();


echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n\n";
echo '<tabla>' . "\n";

$xbusca = getvar('xbusca');
$nuevo = ($xbusca=='-1');

if ($xbusca=='-1')
{
    $query->sql = 'SELECT NOMBRES FROM M_CUESTIONARIOS WHERE ID_M_CUESTIONARIOS = \'' . getvar('xfiltro') . '\'';

    $query->ejecuta_query();
    $query->next_record();
    $nombre = $query->Record['NOMBRES'];


    $query->sql = 'INSERT INTO M_RESPUESTAS (IDX,TABLA,ID_M_CUESTIONARIOS,NOMBRES) VALUES(' . '\'' . getvar('idx') .'\',\''. getvar('tabla').'\',\''.getvar('xfiltro').'\',\''.$nombre.'\')';
    $query->ejecuta_query();

    $query->sql = 'SELECT ID_M_RESPUESTAS FROM M_RESPUESTAS WHERE UNICO = \'' . $query->unico . '\'';
    $query->ejecuta_query();
    $query->next_record();
    $xbusca = $query->Record['ID_M_RESPUESTAS'];
}

for($i=0;$i<sizeof($variables);$i++)
{

    echo '<registro>' . "\n";
    $CAMPO = 'CAMPO'. $variables[$i]['nombre'];
    $xCOMENTARIOS = getvar('COMENTARIOS' . $variables[$i]['nombre']);

    $xCAMPO      = $forma_xml['ini'][$CAMPO]['CAMPO']['value'];
    $xTABLA      = $forma_xml['ini'][$CAMPO]['TABLA']['value'];
    $xTIPO       = $forma_xml['ini'][$CAMPO]['TIPO']['value'];
    $xID         = $forma_xml['ini'][$CAMPO]['ID_M_PREGUNTAS']['value'];
    $xCONDICION  = $forma_xml['ini'][$CAMPO]['CONDICION1']['value'];
    $xROTULO     = $forma_xml['ini'][$CAMPO]['ROTULO']['value'];
    $xCUESTIONARIO= $forma_xml['ini'][$CAMPO]['ID_M_CUESTIONARIOS']['value'];
    $valor       = $variables[$i]['valor'];
    $valor_sql   = $variables[$i]['valor'];

    switch ($xTIPO)
    {
        case 'R':
            if(strlen($valor)){
                    $valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else
            {
                    $valor_sql = "NULL";
            }
            break;
        case 'X':
        case 'B':
        case 'C':
        case 'L':
            if(strlen($valor)){
                    $valor_sql = "'" . addcslashes($valor, "'") . "'";
            }
            else
            {
                    $valor_sql = "''";
            }
            break;
        case 'N':   //-- Este es obvio! --
        case 'I':   //-- Este es obvio! --
            if($valor != "") {
                $valor_sql = str_replace(',','', $valor_sql);
            }
            else
            {
                    $valor_sql = 0;
            }
            break;
        case 'D':
            if($valor != ""){
                $fecha = explode('/',$valor);
                $valor_sql = "'". $fecha[2] .'-'. $fecha[1] . '-'. $fecha[0] . "'";
            }
            else{
                    $valor_sql = "NULL";
            }
            break;
    }
    $valor_sql = $xTIPO!='L' ? strtoupper($valor_sql) :  $valor_sql;


    if($xCONDICION=='E')
    {
      $sql = 'SELECT ' . getvar('indice') . ' FROM  V_D_RESPUESTAS WHERE ID_M_RESPUESTAS = \'' . $xbusca . '\' AND ID_M_PREGUNTAS=\''.$xID.'\'';
      $query->sql = $sql;
      $query->ejecuta_query();
      $query->next_record();
      $nuevo = $query->Record[getvar('indice')];

      if (!$nuevo)
      {
    	$sql = 'INSERT INTO ' . $xTABLA . '('. $xCAMPO .',ID_M_PREGUNTAS, COMENTARIOS,ID_M_RESPUESTAS,ROTULO) values ('. $valor_sql .',\''. $xID . '\',\''. $xCOMENTARIOS . '\',\''.$xbusca .'\',\''.$xROTULO .'\')';
      }
      else
      {
        $sql = 'UPDATE ' . $xTABLA . ' SET '. $xCAMPO .' = ' . $valor_sql . ', COMENTARIOS=\''. $xCOMENTARIOS. '\' WHERE ID_M_RESPUESTAS = \'' . $xbusca . '\' AND ID_M_PREGUNTAS=\''.$xID.'\'';
      }
    }
    else
    {

        $sql1 = 'SELECT ' . getvar('indice') . ' FROM  V_D_RESPUESTAS WHERE ID_M_PREGUNTAS =\'' . $xID . '\' AND IDX = \''.getvar('idx') .'\' AND TABLA=\''. getvar('tabla').'\'';
        $query->sql = $sql1;
        $query->ejecuta_query();

        $query->next_record();

    	$sql = 'INSERT INTO ' . $xTABLA . '('. $xCAMPO .',ID_M_PREGUNTAS, COMENTARIOS,ID_M_RESPUESTAS,ROTULO) values ('. $valor_sql .',\''. $xID . '\',\''. $xCOMENTARIOS . '\',\''.$xbusca .'\',\''.$xROTULO .'\')';
        $xRegistro = $query->Record[getvar('indice')];
        if ($xRegistro)
        {
           $sql = 'UPDATE ' . $xTABLA . ' SET '. $xCAMPO .' = ' . $valor_sql . ', COMENTARIOS=\''. $xCOMENTARIOS. '\' WHERE ' . getvar('indice'). '=\'' . $xRegistro . '\'';
        }
    }

    $query->sql = $sql;
    $query->ejecuta_query();

    echo '<QUERY>'. "\n";
    echo '<![CDATA['. $sql1  . "\n" . $query->sql  .']]>'. "\n";

    echo '</QUERY>' . "\n";

    echo '<ERROR>' . "\n";
    echo '<![CDATA['. $query->erro_msg .']]>' . "\n";
    echo '</ERROR>' . "\n";

    echo '<UNICO>'. $query->unico .'</UNICO>' . "\n";
    echo '</registro>' . "\n";


}
    echo '<RETORNO>'. getvar('ejecutar') .'</RETORNO>' . "\n";
    echo '</tabla>' . "\n";


   function noTag($val)
   {

     $s = str_replace("<", "&lt;", str_replace(">", "&gt;", $val) );
     $s = str_replace("Ñ", "&#209;", $s);
     $s = str_replace("ñ", "&#208;", $s);
     $s = str_replace("Á", "&#193;", $s);
     $s = str_replace("É", "&#201;", $s);
     $s = str_replace("Í", "&#205;", $s);
     $s = str_replace("Ó", "&#211;", $s);
     $s = str_replace("Ú", "&#218;", $s);
     $s = str_replace("á", "&#225;", $s);
     $s = str_replace("é", "&#233;", $s);
     $s = str_replace("í", "&#237;", $s);
     $s = str_replace("ó", "&#243;", $s);
     $s = str_replace("ú", "&#250;", $s);
     //$s = str_replace("\"","&quot;", $s);
     return $s;
   }

?>