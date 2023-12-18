<?php
class class_genera_json
{
   var $arbol='tabla';   // Representa el padre del arbol para el xml (Tabla)
   var $data;
   var $condicion;
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
   var $aconte;

	function imprime_json($data = "")
	{
        $camps = $this->campos;
	    $this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
    	if($this->data->erro)
        {
        	$xx['tabla']['ERROR']['msg']= $this->data->erro_msg;
            $xx['tabla']['ERROR']['sql']=$this->sql;
            echo  json_encode($xx);
            die();
        }
		 

        $nreg=0;
        while ($this->data->next_record())
        {
            unset($tag);
            $nreg++;
            foreach($this->data->Record as $campo=>$valor)
            {
                //$this->analiza($campo, $valor, $this->condicion[$campo]);
                $tmp['tag']   = $campo;
                $tmp['value'] = $valor;// utf8_encode((noTag($valor)));//$valor;
                $tag[] = $tmp;
            }
            unset($tmpReg);
	        for($h=0;$h<sizeof($tag);$h++)
	        {
	                $key = $tag[$h]['tag'];
	                $val = $tag[$h]['value'];
	                /***** Marco ******/
	                if (($this->attrib[$h]['TIPO'] == 'N') || ($this->attrib[$h]['TIPO'] == 'I'))
	                {
                         $this->totales[$key] += intval($val);
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
                            case 'C':
                            case 'X':
	                        case 'B':
	                           $val = stripslashes($val); // $val = '<![CDATA['.$val.']]>';
	                            break;
	                        case 'T':
	                        case 'D':
	                             break;
	                        default:
	                        $val = format($val, $this->attrib[$h]['TIPO']);
	                    }
	                }
	                else $val = format($this->noTag($val), $this->attrib[$h]['TIPO']);
	                if($this->attrib[$h]['TIPO']=='T')
	                {
                        $tf     = array();
                        $xfecha = array();
                        if(strlen($val))
                        {
                        	$xfecha = explode(' ', $val);
	                    	$tf = explode('-',$xfecha[0]);
	                    	$xfecha[0] = strlen($tf[2]) ? $tf[2] . "/": "";
                            $xfecha[0].= strlen($tf[1]) ? $tf[1] . "/": "";
                            $xfecha[0].= strlen($tf[0]) ? $tf[0]: "";
                            $val = $xfecha[0];

                        }
                        $tmpReg[$key.'_H'] = utf8_encode(($xfecha[1] ? $xfecha[1] : ' '));
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
	                }
                    $tmpReg[$key]=utf8_encode($val);
	           }
            $tmpReg['registro']=utf8_encode($nreg);
            $registros['registro'][]=$tmpReg;
        }
	    
		$registros['query']['sql']=$this->sql;
		//$registros['tabla']['query']['sql']=$this->sql;
        if (($this->attrib) && (is_array($this->attrib)))
        {
            for ($i=0;$i<count($this->attrib);$i++)
            {
                unset($atmp);
                if($this->attrib[$i]['TIPO']=='T')
                {
                   $nid = $this->attrib[$i]['NOMBRE'];
                   $atmp['TIPO']='D';
                   $atmp['LONGITUD']='10';
                   $registros['atributos'][$nid] = $atmp;
                   unset($nid);

                   $nid = $this->attrib[$i]['NOMBRE'].'_H';
                   $atmp['TIPO']='T';
                   $atmp['LONGITUD']='8';
                   $registros['atributos'][$nid] = $atmp;
                }
                else
                {
                   $nid = $this->attrib[$i]['NOMBRE'];
                   $atmp['TIPO']=$this->attrib[$i]['TIPO'];
                   $atmp['LONGITUD']=$this->attrib[$i]['LONG'];
                   $registros['atributos'][$nid] = $atmp;
                }
            }
        }
        //rdebug($this->attrib);
        if (($this->totales) && (is_array($this->totales)))// && ($this->arbol != 'error'))
        {
            unset($tmpReg);
            foreach ($this->totales as $clave => $valor)
            {
                $tmpReg[$clave]=utf8_encode(format($valor,$this->totTipos[$clave]));
            }
            $registros['totales']=$tmpReg;
        }
        $xx['tabla']=$registros;
        $xtmp = json_encode($xx);
        if($this->estilo)
        {
         echo _format_json($xtmp);
        }else
        {
		    echo $xtmp;
        }
	}
}


	/**
	 * Formats a JSON string for pretty printing
	 *
	 * @param string $json The JSON to make pretty
	 * @param bool $html Insert nonbreaking spaces and <br />s for tabs and linebreaks
	 * @return string The prettified output
	 * @author Jay Roberts
	 */
         function _format_json($json, $html = false) {
		$tabcount = 0;
		$result = '';
		$inquote = false;
		$ignorenext = false;

		if ($html) {
		    $tab = "&nbsp;&nbsp;&nbsp;";
		    $newline = "<br/>";
		} else {
		    $tab = "\t";
		    $newline = "\n";
		}

		for($i = 0; $i < strlen($json); $i++) {
		    $char = $json[$i];

		    if ($ignorenext) {
		        $result .= $char;
		        $ignorenext = false;
		    } else {
		        switch($char) {
		            case '{':
		                $tabcount++;
		                $result .= $char . $newline . str_repeat($tab, $tabcount);
		                break;
		            case '}':
		                $tabcount--;
		                $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
		                break;
		            case ',':
		                $result .= $char . $newline . str_repeat($tab, $tabcount);
		                break;
		            case '"':
		                $inquote = !$inquote;
		                $result .= $char;
		                break;
		            case '\\':
		                if ($inquote) $ignorenext = true;
		                $result .= $char;
		                break;
		            default:
		                $result .= $char;
		        }
		    }
		}

		return $result;
	}
	
	function Error($msg)
	{
		$xREG['tabla']['estatus']="ERROR";
		$xREG['tabla']['msg']=utf8_encode($msg);
		$xREG['tabla']['QUERY']=utf8_encode($msg);
		echo  json_encode($xREG);		
      die();
	}
?>