<?php
class ini {
	var $origen      ;
    var $archivo_ini ;
	var $error		;
	var $contenido ='';
	var $oconte ='';
    var $xml         ;
	

    function __construct($origen = "")
    {
    	if ($origen != "")
        {
        	$this->origen = $origen;
            $this->cargar_ini();
        }
    }

    function cargar_ini()
    {
		$idx = array();
    	$this->origen .=  ".ini";
        if(!file_exists($this->origen)) {
            $this->xml  = '<?xml version="1.0"?>' . "\n\n";
            $this->xml .= '<error>'."\n";
			$this->xml .= '  <ERROR'.$seccion.'>'."\n";
			$this->xml .= '    <![CDATA[No existe el origen: '. $this->origen  .']]>'."\n";
			$this->xml .= '  </ERROR>'."\n";
            $this->xml .= '</error>'."\n"; 
			return ;
		}
		
		$oconte = file_get_contents ($this->origen); 
		
		preg_match_all('/SQL[\s\S]"[^"]*"/', $oconte, $Match);		
		foreach($Match[0] as $exp){
			$cadena = preg_replace("/[\n\r]/", "__S24_EOL__", $exp);
			$oconte = str_replace($exp,$cadena,$oconte);			
		}	       
	
		$sql = explode('[VARIABLE]', $oconte);
		//print_r ( $sql  );
	
		if( isset( $sql[1]) ){
			
			$p = strpos ( $sql[1], '[');
			$xBloque = trim( substr($sql[1], 1, $p -1 ) );
			
			$nBloque = '';	
			
			$bsql = str_replace('"', '', $xBloque);
			$l = explode("\n", $bsql);
			$tsql='';
			
			foreach( $l as $isql ){
				$t = explode('=', $isql);
				if( isset ($t[1] )){
					$p = explode(' ', $t[1] );
					$inst = strtoupper ($p[0]);
					if(strtoupper ($inst) == 'SELECT' ){
						if($nBloque!='') $nBloque .='"'  . PHP_EOL;
						$nBloque .= $t[0]. '="';
						$xR = array_shift($t);
					}
					$nBloque .= join('=', $t) . '__S24_EOL__';					
				}
			}		
			if($nBloque!='') $nBloque .='"'  .PHP_EOL;
			$nBloque = str_replace('__S24_EOL__"',  '"', $nBloque );		
			$oconte = str_replace($xBloque, $nBloque, $oconte );
			
		}
		
		
		$tc= explode("\n", $oconte);
		$atc = array();
		$osec = array();
        for($i=0;$i<sizeof($tc);$i++)
        {
			$tc[$i] = trim($tc[$i]);
			$tc[$i] = str_replace("\n", "", $tc[$i]);
			$tc[$i] = preg_replace("[\n|\r|\n\r]", "", $tc[$i]);
			if($tc[$i]=='') continue;
			if(!mb_detect_encoding($tc[$i], 'utf-8', true)){
					$tc[$i] = utf8_encode($tc[$i]);
			}

			if( mb_detect_encoding( $tc[$i] )  =='UTF-8') {
				$tc[$i] = utf8_decode( $tc[$i] );
				$tc[$i] = $this->noTag($tc[$i]);
			}

			if(substr($tc[$i],0,1)=='['){
				$oname = str_replace(array('[',']'),array('', ''), $tc[$i]);
				$n = str_replace('[','',preg_replace ("/[0-9]/", "", $tc[$i]));
				$n = trim(str_replace(']','', $n));
				switch ($n){
					case 'CAMPO':
					case 'COLUMNA':
					case 'GRUPO':
					case 'LEYENDA':
					case 'OPCION':
					case 'PIE':
					case 'ENCABEZADO':
					case 'CONDICION':
						//echo "$n\n";
						if(!isset($idx[$n])){
							$idx[$n] = 0;
						} 
						$idx[$n]++;
						$id = $idx[$n];
						$tc[$i] = '[' . $n . $id. ']';
				}
				$nname = str_replace(array('[',']'),array('', ''), $tc[$i]);
				$osec[$nname] = $oname;
			}
			$tc[$i] = $this->noTag( $tc[$i] );
            $t = explode("=", $tc[$i]);
			
			$variable = array_shift( $t ) ;
			$xvalor   = $t[0];
			if( isset ($t[1] )){
				$xvalor = join ('=',  $t);						
			}
			unset($t);
			
			$t[0] = $variable;
			$t[1] = $xvalor;
			
			switch (trim($t[0])){
				case 'POSICION':
					$x = preg_replace('/[^0-9]/', '', trim($t[1]));					
					$tc[$i] = trim($t[0]) .  '='. $x ;
				break;
				case 'TITULO':					
				case 'ROTULO':
				case 'ICONO':
				case 'COMENTARIO':
				case 'DB':
					$x  = trim($t[1]);
					$x1 = substr($x,0,1);
					$x2 = substr($x,-1,1);
					
					
					if( $x1== "'" && $x2== "'"  ) {
						$x  = substr($x, 1, -1);	
						$x1 = '"';
						$x2 = '"';						
					}
					
					$c1='"';
					$c2='"';
					if($x1=='"') $c1='';
					if($x2=='"')
					{
						$c2='';
					}
					else
					{
						if($x1=='"')
						{
							$p1 = strpos ($x, '"');
							$p2 = strripos ($x, '"');
							if($p1!=$p2) $x = substr($x, 0, $p2);
						}

					}
				$tc[$i] = trim($t[0]) .  '='. $c1 . $x . $c2 ;
				break;
					default:
						$n = preg_replace ("/[0-9]/", "", $t[0]);
						if($n=='ALERTA'){
							$tc[$i] = $this->Tag( $tc[$i]);
						}
				break;

			}
			$atc[] = $tc[$i];
        }
		
		$oconte =  $this->Tag( join("\n", $atc) );
		
        $this->contenido = str_replace('__S24_EOL__', ' ', $oconte);
        $this->archivo_ini = parse_ini_string($this->contenido, true, INI_SCANNER_RAW);
		$this->oconte = parse_ini_string($oconte, true, INI_SCANNER_RAW);
		foreach($osec as $c=>$v){
			$this->archivo_ini[$c]['ONOMBRE'] = $v;
			$this->oconte[$c]['ONOMBRE'] = $v;
		}
		
		if($this->archivo_ini==''){
			$this->error = 'Error: No se puedo procesar o existe un error en el archivo -> ' . $this->origen;
			return;
		} 
        $this->generaXML();
    }

    function seccion($seccion, $variable = "")
    {
	    if(strlen($variable))
	    {
	        return isset($this->archivo_ini[$seccion][$variable]) ? $this->archivo_ini[$seccion][$variable] : '';
	    }
	    else
	    {
            return isset($this->archivo_ini[$seccion]) ? $this->archivo_ini[$seccion] : '';
        }
    }

    function variable($seccion, $variable,$grupo="")
    {
        if(!is_array($grupo))
        {
        	return isset($this->archivo_ini[$seccion][$variable]) ? $this->archivo_ini[$seccion][$variable] : '';
        }
        else
        {
        	$seccion = $this->archivo_ini[$seccion];
            $exp = '/^' . $variable . '\S+$/i';
			foreach($seccion as $xvariable => $valor)
            {
            	if(preg_match ($exp, $xvariable))
            	{
                	$a = str_replace($variable,'',$xvariable);
                    $xresp[$variable] =  $valor;
                    while (list($id,$vgrupo) = each($grupo))
                    {
                    	$xresp[$vgrupo]=  $seccion[$vgrupo . $a];
                    }
                    $resp[] = $xresp;
                }
            }
            return $resp;

        }
    }

    function secciones($seccion, $variable='',$retorno = "N")
    {
    	reset($this->archivo_ini);
		foreach($this->archivo_ini as $KEY => $CAMPO)
        {
        	$exp = "/^" . $seccion. "\S+$/i";
            if(preg_match ($exp, $KEY))
            {
            	$aresp[$KEY][$variable] = isset($CAMPO[$variable]) ? $CAMPO[$variable] : '';
            	$sresp[] = isset($CAMPO[$variable]) ? $CAMPO[$variable] : '';
            	$xresp[] = $CAMPO;
        	}
        }
        if(!isset($sresp)) return ;
        switch (strtoupper($retorno))
        {
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

    function campos($seccion="CAMPO")
    {
        reset($this->archivo_ini);
		foreach($this->archivo_ini as $KEY => $CAMPO)
        {
           $exp = "/^" . $seccion. "\S+$/i";
           if(preg_match ($exp, $KEY, $c))
           {
           	$campo = isset($CAMPO['CAMPO']) ? $CAMPO['CAMPO'] : $KEY;
            $xresp[$campo] = $CAMPO;
            $xresp[$campo]['REGLAS'] = $this->variable($c[0],'REGLA',array('ALERTA'));
           }
        }
        if(!isset($xresp)) return ;
        return $xresp;
    }

    function campos2($seccion="CAMPO")
    {
    	reset($this->archivo_ini);
		foreach($this->archivo_ini as $KEY => $CAMPO)
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
		
        function Tag($val)
        {
          $s = str_replace("&lt;", "<", str_replace("&gt;", ">", $val) );
          $s = str_replace("&Ntilde;", "Ñ", $s);
          $s = str_replace("&ntilde;", "ñ", $s);
          $s = str_replace("&#193;", "Á", $s);
          $s = str_replace("&#201;", "É", $s);
          $s = str_replace("&#205;", "Í", $s);
          $s = str_replace("&#211;", "Ó", $s);
          $s = str_replace("&#218;", "Ú", $s);
          $s = str_replace("&#225;", "á", $s);
          $s = str_replace("&#233;", "é", $s);
          $s = str_replace("&#237;", "í", $s);
          $s = str_replace("&#243;", "ó", $s);
          $s = str_replace("&#250;", "ú", $s);
          return $s;
        }

    function generaXML($encoding = "iso-8859-1")
    {
			//header('Content-Type: text/html; charset=UTF-8');
            $this->xml  = '<?xml version="1.0"  encoding="'. $encoding .'"?>' . "\n\n";
            $this->xml .= '<ini>'."\n";
           //rdebug($this->archivo_ini);
            $conta['CAMPO'][0] = 1;
            $conta['CAMPO'][1] ='CAMPO';
            $conta['COLUM'][0] = 1;
            $conta['COLUM'][1] ='COLUMNA';
            foreach ($this->archivo_ini as $seccion =>$valores) {
                if(is_array($valores))
                {
                    $ts = substr($seccion,0,5);
                    if(isset($conta[$ts]))
                    {
                    	$seccion = $conta[$ts][1];
                        $seccion.= $conta[$ts][0];
                        $conta[$ts][0]++;
                    }
                    $this->xml .= '  <'.$seccion.'>'."\n";
                    foreach ($valores as $clave => $valor){
						//$valor = variable($valor);
                       if(strlen($valor)) $this->xml .= '    <'.$clave.'><![CDATA['. $valor  .']]></'.$clave.'>'."\n";
                    }
                    $this->xml .= '  </'.$seccion.'>'."\n";
                }
            }
            $this->xml .= '</ini>'."\n"; 
	}
		
	function generaObj(){
		$obj = array();
		reset($this->archivo_ini);
		foreach($this->archivo_ini as $seccion => $valores)
		{
			if(is_array($valores))
			{
				$obj[$seccion] = array();
				while (list($clave, $valor) = each($valores))
				{
				   $obj[$seccion][$clave] = $valor;
				}
			}
		}
		return $obj;
	}	
		
		
}

function quitar_acentos($s)
{
	$s = ereg_replace("[áàâãª]","a",$s);
	$s = ereg_replace("[ÁÀÂÃ]","A",$s);
	$s = ereg_replace("[ÍÌÎ]","I",$s);
	$s = ereg_replace("[íìî]","i",$s);
	$s = ereg_replace("[éèê]","e",$s);
	$s = ereg_replace("[ÉÈÊ]","E",$s);
	$s = ereg_replace("[óòôõº]","o",$s);
	$s = ereg_replace("[ÓÒÔÕ]","O",$s);
	$s = ereg_replace("[úùû]","u",$s);
	$s = ereg_replace("[ÚÙÛ]","U",$s);
	$s = str_replace("ç","c",$s);
	$s = str_replace("Ç","C",$s);
	$s = str_replace("[ñ]","n",$s);
	$s = str_replace("[Ñ]","N",$s);

	return $s;
}



?>