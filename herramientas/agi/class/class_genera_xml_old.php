<?php
class class_genera_xml
{
   var $arbol='tabla';   // Representa el padre del arbol para el xml (Tabla)
   var $data;
   var $condicion; // esta propiedad debe ser un array con la siguiente estructura:
                   //   tag,propiedades y sus valores,condiciones segun campos
                   //
                   //  ej:
                   //                $xml->condicion['EDAD'][0] = 'EDAD,fondo="#FFFFFF",>20';
                   //       $xml->condicion[] = 'registro,fondo="#06E0FF" frente="blue",APELLIDOS="DAZA",NOMBRE="EDSON"';
                   //       $xml->condicion[] = 'CEDULA,fondo="#GREEN",CEDULA="DAZA",NOMBRE="EDSON"';
   var $sql='';
   var $attrib;
   var $totales;
   var $totTipos;
   var $estilo;
   var $resumen;
   var $header;
   var $pagina;
   var $paginas;
   var $registros;
   var $atributos;
   var $convertir_numero_letras = false;
   var $campos;

   function imprime_xml($data = "")
      {
        //rdebug($this,'s');
        $camps = $this->campos;
        $this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
   // rdebug($this,'s');
if ($this->header)
{
header('content-type: text/xml');
header('Expires: Fri, 1 Ene 1980 00:00:00 GMT'); //la pagina expira en fecha pasada
header('Last-Modified: ' . gmdate("D, d M Y H:i:s"));// . ' GMT
header('Cache-Control: no-cache, must-revalidate');//
header('Pragma: no-cache');
echo '<?xml version="1.0"  encoding="iso-8859-1"?>';
}
	if ($this->estilo)
	{

        echo  '<?xml-stylesheet type="text/xsl" href="genera_xsl.php?campos='. $this->campos .'"?'.'>';
	}
	echo  '<' . $this->arbol . '>' ."\n";
    //rdebug($this,'s');
    if($this->data->tipo !='select')
    {
       echo '<![CDATA['.  $this->sql .']]>';
    }
    else
    {
    	if($this->data->erro){
	    	echo '<ERROR>' . "\n";
	    	echo '<![CDATA['. $this->data->erro_msg .']]>' . "\n";
	    	echo '</ERROR>' . "\n";
       		echo '<![CDATA['.  $this->sql .']]>';
        }
        else
        {
	        while ($this->data->next_record())
	        {
	           //echo 'sssss';
	            unset($tag);
                //rdebug($this->data->Record);
	            foreach($this->data->Record as $campo=>$valor)
	            {
	                $this->analiza($campo, $valor, $this->condicion[$campo]);
	                $tmp['tag']   = $campo;
	                $tmp['value'] = strip_tags(html_entity_decode($valor));
	                $tag[] = $tmp;
	            }
	           echo  '  <registro numero="'.++$cont.'" '. $this->atributos['registro'] . '>' ."\n";
	           for($h=0;$h<sizeof($tag);$h++)
	           {
	                $key = $tag[$h]['tag'];
	                $val = $tag[$h]['value'];
	                /***** Marco ******/
	                if (($this->attrib[$h]['TIPO'] == 'N') || ($this->attrib[$h]['TIPO'] == 'I'))
	                {
	                     $this->totales[$key] += $val;
	                }
	                else
	                {
	                     $this->totales[$key] += 1;
	                }
	                $this->totTipos[$key] = $this->attrib[$h]['TIPO'];
	                if (($this->attrib) && (is_array($this->attrib)))
	                {
	                    switch ($this->attrib[$h]['TIPO'])
	                    {
	                        case 'X':
	                        case 'B':
	                            $val = '<![CDATA['.$val.']]>';
	                            break;
	                        case 'T':
	                        case 'D':
	                             break;
	                        default:
	                        $val = '<![CDATA['. $val .']]>';
	                    }
	                }
	                else $val = $this->noTag($val);
	                if($this->attrib[$h]['TIPO']=='T')
	                {
                        $tf     = array();
                        $xfecha = array();
                        if(strlen($val))
                        {
                        	$xfecha = split(' ', $val);
	                    	$tf = explode('-',$xfecha[0]);
	                    	$xfecha[0] = $tf[2] . '/' . $tf[1] . '/' . $tf[0];
                        }
	                    $temporal .= '   <'. $key .' '. $this->atributos[$key] .'>'. ($xfecha[0] ? $xfecha[0] : ' ') . '</'.$key.'>';
	                    $temporal .= '   <'. $key .'_H '. $this->atributos[$key] .'>'. ($xfecha[1] ? $xfecha[1] : ' ') . '</'.$key.'_H>';
	                }
	                else
	                {
	                    if($this->attrib[$h]['TIPO']=='D')
	                    {
	                        if(strlen($val))
	                        {
	                            $xfecha = $val;
	                        	$tf = explode('-',$xfecha);
	                        	$val = $tf[2] . '/' . $tf[1] . '/' . $tf[0];
                            }
	                    }
	                    $temporal .= '   <'. $key .' '. $this->atributos[$key] .'>'. ($val ? $val : ' ') . '</'.$key.'>'."\n";
	                }
	                echo $temporal;
	                $temporal ='';
	           }
	           echo  '  </registro>'."\n";
	           unset($this->atributos);
	          // $this->data->MoveNext();
	        }
        }
	    if (!$this->resumen) // propiedad para no mostrar el resumen (total, query y atributos)
	    {
	        if ($this->sql)
	        {
	               echo '  <query>'."\n";
	               echo '    <![CDATA['.$this->noTag($this->sql). ']]>' . "\n";
	               echo '  </query>'."\n";
	               echo '  <pagina>'."\n";
	               echo '    '.$this->pagina ."\n";
	               echo '  </pagina>'."\n";
	               echo '  <paginas>'."\n";
	               echo '    '.$this->paginas ."\n";
	               echo '  </paginas>'."\n";
	               echo '  <totalregistros>'."\n";
                   echo '    '.$this->data->registros ."\n";
	               echo  '  </totalregistros>'."\n";
	        }
            //rdebug($this,'s');
	        if (($this->attrib) && (is_array($this->attrib)))
	        {
	            echo '  <atributos>'."\n";
	            for ($i=0;$i<count($this->attrib);$i++)
	            {
	                if($this->attrib[$i]['TIPO']=='T')
	                {
	                   echo '    <'.$this->attrib[$i]['NOMBRE'].' TIPO="D" LONGITUD="10"/>'."\n";
	                   echo '    <'.$this->attrib[$i]['NOMBRE'].'_H TIPO="T" LONGITUD="8"/>'."\n";
	                }
	                else
	                {
	                   echo '    <'.$this->attrib[$i]['NOMBRE'].' TIPO="'.$this->attrib[$i]['TIPO'].'" LONGITUD="'.$this->attrib[$i]['LONG'].'"/>' ."\n";
	                }
	            }
	            echo '  </atributos>'."\n";
	        }
	        if (($this->totales) && (is_array($this->totales)))// && ($this->arbol != 'error'))
	        {
	            echo '  <totales>'."\n";
	            foreach ($this->totales as $clave => $valor)
	            {
	                echo '    <'.$clave.'>'.$valor .'</'.$clave.'>'."\n";
	            }
	            echo '  </totales>'."\n";
	        }
	    }
    }
	echo  '</' . $this->arbol . '>'."\n";
	}

   function analiza($key, $valor,  $arreglo)
   {
       if(is_array($arreglo))
       {
            for($i=0;$i<sizeof($arreglo);$i++)
            {
            	$con = split(',',$arreglo[$i]);
                {
//                	if($con[3])
//                    {
                    	$clave = $con[0];
                        switch ($con[2])
                       {
                            case '=':
                                 //echo $valor . "   ". $con[3] . "<br>";
                                 if($valor == $con[3]) $this->atributos[$clave] = $con[1];
                                break;
                            case '>=':

                                 if($valor >= $con[3]) $this->atributos[$clave] = $con[1];
                                break;
                            case '<=':
                                 if($valor <= $con[3]) $this->atributos[$clave] = $con[1];
                                break;
                            case '<>':
                                 if($valor != $con[3]) $this->atributos[$clave] = $con[1];
                            break;
                            case '*':
                                 if(strstr($valor,$con[3])) $this->atributos[$clave] = $con[1];
                                break;
                        }
//                    }
                }
            }
       }
   }

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

   function noFormat($val)
   {
        $val = str_replace(',','.',str_replace('.','',$val));
        if (is_numeric($val)) return $val;
        else return 0;
   }

}
?>