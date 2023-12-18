<?php
class ini {
	var $origen			;
	var $archivo_ini	;
    var $xml			;

    function ini($origen = "")
	{
    	if ($origen != ""){
        	$this->origen = $origen;
            $this->cargar_ini();
        }
	}

    function cargar_ini(){
    	$this->origen .=  ".ini";
        $this->origen = strtolower($this->origen);
        if(!file_exists($this->origen)) die('No existe el archivo ' . $this->origen);
		$this->archivo_ini = parse_ini_file($this->origen, true);
        $this->generaXML();
    }

	function seccion($seccion, $variable = ""){
        if(strlen($variable)){
        	return $this->archivo_ini[$seccion][$variable];
        }else{
            return $this->archivo_ini[$seccion];
        }
	}

    function variable($seccion, $variable,$grupo=""){
        if(!is_array($grupo))
        {
        	return $this->archivo_ini[$seccion][$variable];
        }else
        {
	        $seccion = $this->archivo_ini[$seccion];
            $exp = '/^' . $variable . '\S+$/i';
	        while (list($xvariable, $valor) = each($seccion))
	         {
	            if(preg_match ($exp, $xvariable))
                {
	                $a = str_replace($variable,'',$xvariable);
	                $xresp[$variable]     	=  $valor;
                    while (list($id,$vgrupo) = each($grupo))
                    {
                    	$xresp[$vgrupo]    	=  $seccion[$vgrupo . $a];
                    }
	                $resp[] = $xresp;
	            }
	        }
	        return $resp;

        }
	}

	function secciones($seccion, $variable='',$retorno = "N"){
        reset($this->archivo_ini);
        while (list($KEY, $CAMPO) = each($this->archivo_ini))
		 {
            $exp = "/^" . $seccion. "\S+$/i";
		 	if(preg_match ($exp, $KEY)){
	            $aresp[$KEY][$variable] = $CAMPO[$variable];
	            $sresp[] = $CAMPO[$variable];
                $xresp[] = $CAMPO;
		 	}

		}
		if(!is_array($sresp)) return ;
        switch (strtoupper($retorno)){
			case 'S':
            	return join(',', $sresp);
            case 'A':
            	return $aresp;
            case 'N':
            	return $xresp;
            default:
            	return join(',', $sresp);
        }
	}

	function campos($seccion="CAMPO"){
        reset($this->archivo_ini);
        while (list($KEY, $CAMPO) = each($this->archivo_ini))
		 {
           $exp = "/^" . $seccion. "\S+$/i";
           if(preg_match ($exp, $KEY, $c)){
            	$campo = $CAMPO['CAMPO'];
                $xresp[$campo] = $CAMPO;
                $xresp[$campo]['REGLAS'] = $this->variable($c[0],'REGLA',array('ALERTA'));
		 	}
		}
		if(!is_array($xresp)) return ;
        return $xresp;
	}


	function campos2($seccion="CAMPO"){
        reset($this->archivo_ini);
        while (list($KEY, $CAMPO) = each($this->archivo_ini))
		 {
           $exp = "/^" . $seccion. "\S+$/i";
           if(preg_match ($exp, $KEY, $c)){
                $xresp[$KEY] = $CAMPO;
		 	}
		}
		if(!is_array($xresp)) return ;
        return $xresp;
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
	  return $s;
	}

    function generaXML($encoding = "iso-8859-1")
    {
	    $this->xml  = '<?xml version="1.0"  encoding="'. $encoding .'"?>' . "\n\n";
	    $this->xml .= '<ini>'."\n";

	    while (list($seccion, $valores) = each($this->archivo_ini)) {
	        if(is_array($valores))
	        {
	            $this->xml .= '  <'.$seccion.'>'."\n";
	            while (list($clave, $valor) = each($valores))
	            {
	               if(strlen($valor)) $this->xml .= '    <'.$clave.'>'. $this->noTag($valor).'</'.$clave.'>'."\n";
	            }
	            $this->xml .= '  </'.$seccion.'>'."\n";
	        }
	    }
	    $this->xml .= '</ini>'."\n";
	}
}
?>