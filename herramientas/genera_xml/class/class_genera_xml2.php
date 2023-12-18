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
        $camps = $this->campos;
        $this->pagina  = intval($this->pagina) <= 0 ? 0 : intval($this->pagina);
if ($this->header)
{
header( 'Content-Type: application/xml' );
header( 'Expires: Fri, 1 Ene 1980 00:00:00 GMT' );
header( 'Last-Modified: ' . gmdate("D, d M Y H:i:s") ); 
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Pragma: no-cache' );
echo '<?xml version="1.0"  encoding="iso-8859-1"?'.'>';
}else{
	 header( 'Content-Type: application/xml' );
	 echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
}
	if ($this->estilo)
	{
        echo  '<?xml-stylesheet type="text/xsl" href="genera_xsl.php?campos='. $this->campos .'"?'.'>';
	}/*Eduardo */


	echo  '<' . $this->arbol . '>' ."\n";
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
        }
        else
        {
	        while ($this->data->next_record())
	        {
				print_r ( $this->data );
	        
	        }

	        /* CAMBIO REALIZADO POR MARCO: MAYO 2009: ACA SE REALIZA LA CUENTA DE LOS REGISTROS (SI FUERE NECESARIO)*/


	        if (!$this->contar) /* cambio realizado por MARCO Y EDSON: 28/05/2009 */
	        {
    	        if ($this->data->limite)
    	        {
                if (!$this->data->pagina) $this->data->pagina=0;
                if (($this->data->pagina * $this->data->limite + $cont) >= $this->data->limite)
                {
                  if(strlen($this->data->sql_count)) $this->data->cuenta_registros();
                  $this->paginas = $this->data->paginas;
                }
                else
                {
                  $this->data->registros = $cont;
                  $this->paginas = 1;
                }
              }
              else if(strlen($this->data->sql_count))
              {
                $this->data->cuenta_registros();
                $this->paginas = $this->data->paginas;
              }
          }
          /* FIN DEL CAMBIO REALIZADO DE MARCO */

        }
	    if (!$this->resumen) // propiedad para no mostrar el resumen (total, query y atributos)
	    {
           echo "\n";
           echo '  <resumen>'."\n";
           echo '  <FECHA>'."\n";
           echo  date("d/m/Y") . "\n";
           echo '  </FECHA>'."\n";
           echo '  <HORA>'."\n";
           echo  date("H:i:s")  . "\n";
           echo '  </HORA>'."\n";
           echo '  </resumen>'."\n";
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
	                echo '    <'.$clave.'>'.format($valor,$this->totTipos[$clave]).'</'.$clave.'>'."\n";
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
            	$con = explode(',',$arreglo[$i]);
                {
                    	$clave = $con[0];
                        switch ($con[2])
                       {
                            case '=':
                                 if($valor == $con[3]) $this->atributos[$clave] = $con[1];
                                break;
                            case '<':
                                 if($valor < $con[3]) $this->atributos[$clave] = $con[1];
                                break;
                            case '>':
                                 if($valor > $con[3]) $this->atributos[$clave] = $con[1];
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
                }
            }
       }
   }


        function noTag($val)
        {
      return $val;
          $s = str_replace("<", "&lt;", str_replace(">", "&gt;", $val) );
          $s = str_replace("Ñ", "&Ntilde;", $s);
          $s = str_replace("ñ", "&ntilde;", $s);
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