<?php
header('Content-Type: text/html; charset=iso-8859-1');

include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . "herramientas/utiles/comun.php");
include_once (Server_Path . "herramientas/leyenda/class/class_leyenda.php");

class class_modulo{
     var $origen;
     var $my_ini;
     var $inicio = "";
     var $fin;
	 var $resumen= "";
     var $titulo = "";
     var $onClose = "";
     var $debug=0;

     function class_modulo($origen, $onClose ="")
     {
        $this->origen = $origen;
        $this->my_ini = new ini($this->origen);
        
		if( file_exists( $this->origen . '_resumen.ini') ){
			$this->my_ini_resumen = new ini($this->origen . '_resumen');
			
		} else {
			$this->my_ini_resumen = false;
		}
		
	
        $this->debug  = $this->my_ini->seccion('VENTANA','DEBUG');

        $this->titulo = $this->my_ini->seccion('VENTANA','TITULO');
        $this->onClose = "parent.proceso.location.href='". Server_Path ."/main/inicio.php'";
        
		if($onClose) $this->onClose = $onClose;
        $this->mostrar();
	 }

     function setTitulo($titulo){
     	$this->titulo = $titulo;
		$this->inicio = '';
        $this->mostrar();
     }

     function mostrar()
     {

	 
		if( !$this->origen || $this->origen == '' ) {
			echo loadInis();
			//die($this->inicio);	
			return;
		}
		
        $xancho = $this->my_ini->seccion('VENTANA','ANCHO');
		$tmp_ruta = pathinfo($_SERVER['SCRIPT_NAME']);
        $xorigen = str_replace('/'. Sistema .'/','', $tmp_ruta['dirname']);
        $xini =  $xorigen . '/' . $this->origen;
        $alineacion    = $this->my_ini->seccion('VENTANA','CENTRADO') == 'NO' ? 'left' : 'center';
		
		$this->inicio .= '<div id="info_modulo" style="width:100%; height:100%; display: flex;" data-obj="modulo">' . PHP_EOL ;
        $this->inicio .= '		<div class="grid_contenedor origen" data-origen="' .  end( explode('/', getcwd() ) ) . '/' . $this->origen . '" id="modulo">' . PHP_EOL;
	    $this->inicio .= '  	<div class="grid_title">' . PHP_EOL;
        $this->inicio .= '   		<div class="grid_esquina1"></div>' . PHP_EOL;
        $this->inicio .= '   			<div class="grid_esquina"></div>' . PHP_EOL;
	    $this->inicio .= '     				<div class="grid_barra_titulo">' . PHP_EOL;
	    $this->inicio .= '       			<div class="grid_titulo">' . $this->titulo . '</div>' . PHP_EOL;
        $this->inicio .= '     				<div class="grid_controls_right" ></div>' . PHP_EOL;
	    $this->inicio .= '       			<div class="grid_controles">' . PHP_EOL;
        $this->inicio .= '         				<span onclick="ayuda('. $this->my_ini->seccion('VENTANA','AYUDA') .',\'0\',\''. $this->titulo .'\',\''. $xini .'\');" class="grid_ayuda"></span>' . PHP_EOL;
        $this->inicio .= '         				<span onclick="' . $this->onClose . ' " class="grid_cerrar"></span>' . PHP_EOL;
	    $this->inicio .= '       			</div>' . PHP_EOL;
        $this->inicio .= '     				<div class="grid_controls_left" ></div>' . PHP_EOL;
	    $this->inicio .= '     				</div>' . PHP_EOL;
		$this->inicio .= '     				<div class="grid_esquina2" ></div>' . PHP_EOL;
	    $this->inicio .= '		</div>' . PHP_EOL;

        $this->inicio .= '		<div class="grid_cuadro" id="contenido_modulo" >' . PHP_EOL;
   	    $acampos = $this->my_ini->secciones("ENCABEZADO");
	    if(is_array($acampos))
	    {
	        $this->inicio .= '<div class="etiqueta origen" data-origen="' .  end( explode('/', getcwd() ) ) . '/' . $this->origen . '" style="position:relative">' . PHP_EOL;
	        reset($acampos);

            for ($i=0;$i<sizeof($acampos);$i++)
            {
                $posicion = $acampos[$i]['POSICION'];
                $campos[$posicion][]= $acampos[$i];
	        }

	        reset($campos);
	        while (list($key, $campof) = each($campos))
	        {
                $this->inicio .= '<table class="grid_tabla_pie "  border="0" cellpadding="0" cellspacing="0" data-modulo="encabezado" class="encabezado_modulo">' . PHP_EOL;
	            $this->inicio .= '<tr> ' . PHP_EOL;
				
                for($i=0; $i < sizeof($campof); $i++)
	            {
					
					$xtipo = isset($campof[$i]['TIPO']) ? $campof[$i]['TIPO'] : '';;
                	$xanchos = explode(',' , $campof[$i]['ANCHO']);
	                $xancho1 = $xanchos[0];
	                $xancho2 = $xanchos[1];

					$tipo_linea = 'doble'; 
					
                    if( sizeof($xanchos) == 1)
	                {
	                    $tipo_linea = 'simple';
	                    $xancho2 = $xancho1;
	                }

                    if (!isset($campof[$i]['GRUPO']))
                    {
                        $campof[$i]['GRUPO'] = 'ENCABEZADO';
                    }
                    
					$estilo = 'display: flex; flex:' . $xancho1 . '; min-width: ' . $xancho1 .'px;';
					
					$ancho_rotulo = '';
					$ancho_valor  = '';
					
					if ($tipo_linea == 'simple'){

						$estilo .= 'flex-direction: column;';

						if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
							$estilo = 'display: flex; width:' . $xancho1 . 'px; min-width: ' . $xancho1 .'px; flex-direction: column;';
						}
					}
					else {
						
						$ancho_rotulo = 'width: ' . $xancho1 . 'px;';
						$ancho_valor  = 'flex: '  . $xancho2 . ';';
						
						if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
							$ancho_valor  = 'width: ' . $xancho2 . 'px;';
							$estilo = 'display: flex; min-width: ' . $xancho2 .'px;';
						}

					}
					
 					switch ( substr( $xtipo, 0, 1 ) )
					{
					   
					   case 'N':
						 $alinea = 'right';
						 break;

					   case 'I':
						 $alinea = 'right';
						 break;
						 
					   case 'F':
						 $alinea = 'right';
						 break;

					   case 'D':
						 $alinea = 'center';
						 break;

					   default:
						 $alinea = 'left';
						 break;
					}
					
	            	$this->inicio .= '  <td  class="celda_pie" style=" ' . $estilo . ' " > ' . PHP_EOL;
	            	$this->inicio .= '  <div data-campo="' . $campof[$i]['CAMPO'] . '" class="rotulo_encabezado"  style="text-align: ' . $alinea . ';' . $ancho_rotulo . ' ">' . $campof[$i]['ROTULO'] . '</div> ' . PHP_EOL;
	            	$this->inicio .= '  <div data-tipo="'  . $campof[$i]['TIPO']  . '" class="valor"  style="' . $ancho_valor . ' text-align: ' . $alinea . ';" id="'.$campof[$i]['GRUPO'].$campof[$i]['CAMPO'] . '">';
					$this->inicio .= isset($campof[$i]['VALOR']) ? $campof[$i]['VALOR'] : '';
					$this->inicio .='&nbsp;</div> ' . PHP_EOL;
	            	$this->inicio .= '</td> ' . PHP_EOL;
	            }
	            $this->inicio .= '</tr> ' . PHP_EOL;
	            $this->inicio .= '</table> ' . PHP_EOL;
	        }
            $this->inicio .= '</div>' . PHP_EOL;
	    }

		//$this->inicio .= '<div class="fondo_modulo"> <!-- AQUI COMIENZA EL MODULO -->' . PHP_EOL;
		
		
		
        unset($acampos);
        unset($campos);
        unset($campof);
		
	    $acampos = $this->my_ini->campos("PIE");

		//$this->fin = '</div>  <!-- AQUI TERMINA EL MODULO -->' . PHP_EOL;
        $this->fin = '';

        if(is_array($acampos))
	    {
	        
			$this->fin .= '<div class="etiqueta origen"  data-origen="' .  end( explode('/', getcwd() ) ) . '/' . $this->origen . '"  position: relative;">' . PHP_EOL;
	        reset($acampos);
	        while (list($campo, $cx) = each($acampos))
	        {
	            $campos[$cx['POSICION']][]= $cx;
	        }

	        reset($campos);
	        while (list($key, $campof) = each($campos))
	        {

                $this->fin .= '<table class="grid_tabla_pie" border="0" cellpadding="0" cellspacing="0" class="encabezado_modulo">' . PHP_EOL;
	            $this->fin .= '<tr> ' . PHP_EOL;
	            
				for($i=0;$i<sizeof($campof);$i++){
					
                    $xtipo = isset($campof[$i]['TIPO']) ? $campof[$i]['TIPO'] : '';;

                	$xanchos = explode(',' , $campof[$i]['ANCHO']);
	                $xancho1 = $xanchos[0];
	                $xancho2 = $xanchos[1];

					$tipo_linea = 'doble'; 
					
                    if( sizeof($xanchos) == 1)
	                {
	                    $tipo_linea = 'simple';
	                    $xancho2 = $xancho1;
	                }

                    if (!isset($campof[$i]['GRUPO'])){
						
                        $campof[$i]['GRUPO'] = 'PIE';
                    }
                    
					$estilo = 'display: flex; flex:' . $xancho1 . '; min-width: ' . $xancho1 .'px;';
					
					$ancho_rotulo = '';
					$ancho_valor  = '';
					
					if ($tipo_linea == 'simple'){

						$estilo .= 'flex-direction: column;';

						if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
							$estilo = 'display: flex; width:' . $xancho1 . 'px; min-width: ' . $xancho1 .'px; flex-direction: column;';
						}
					}
					else {
						
						$ancho_rotulo = 'width: ' . $xancho1 . 'px;';
						$ancho_valor  = 'flex: '  . $xancho2 . ';';
						
						if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
							$ancho_valor  = 'width: ' . $xancho2 . 'px;';
							$estilo = 'display: flex; min-width: ' . $xancho2 .'px;';
						}

					}
					
					switch ( substr( $xtipo, 0, 1 ) )
					{
					   
					   case 'N':
						 $alinea = 'right';
						 break;

					   case 'I':
						 $alinea = 'right';
						 break;
						 
					   case 'F':
						 $alinea = 'right';
						 break;

					   case 'D':
						 $alinea = 'center';
						 break;

					   default:
						 $alinea = 'left';
						 break;
					}

					
	            	$this->fin .= '  <td  class="celda_pie" style=" ' . $estilo . ' " > ' . PHP_EOL;
	            	$this->fin .= '  <div data-campo="' . $campof[$i]['CAMPO'] . '"  class="rotulo_pie"  style="text-align: ' . $alinea . ';' . $ancho_rotulo . ' ">' . $campof[$i]['ROTULO'] . '</div> ' . PHP_EOL;
	            	$this->fin .= '  <div data-tipo="'  . $campof[$i]['TIPO']  . '"  class="valor"  style="' . $ancho_valor . ' text-align: ' . $alinea . ';" id="'.$campof[$i]['GRUPO'].$campof[$i]['CAMPO'] . '">';
					$this->fin .= isset($campof[$i]['VALOR']) ? $campof[$i]['VALOR'] : '';
					$this->fin .='&nbsp;</div> ' . PHP_EOL;
	            	$this->fin .= '</td> ' . PHP_EOL;
	            }
	            $this->fin .= '</tr> ' . PHP_EOL;
	            $this->fin .= '</table> ' . PHP_EOL;

	        }

            $this->fin .= '</div>' . PHP_EOL;
	    }

		$this->fin .= '</div>'. PHP_EOL;
	   
		$leyenda = new  class_leyenda();
		$leyenda->origen = $this->origen;
		$leyenda->estilo = 'leyenda';
		$leyenda->enter  = 1;
		$leyenda->armar();

		$pie = strlen($leyenda->contenido_html) ? $leyenda->contenido_html : '<br>&nbsp;<br>';
		$this->fin .= '<div class="grid_leyenda">'.  PHP_EOL;
		$this->fin .= $pie;
		$this->fin .= '</div>'. PHP_EOL;
		
		$this->fin .= '</div>' . PHP_EOL;
		$this->fin .= '</div>' . PHP_EOL;
		
		$this->fin .= loadInis();
		
	   
		/**** RESUMEN *****/
		unset($acampos);
        unset($campos);
        unset($campof);

		if($this->my_ini_resumen){
			
			$acampos = $this->my_ini_resumen->campos("CAMPO");
			$agrupos = $this->my_ini_resumen->campos("GRUPO");
			
			$this->resumen = '';
			
			if(is_array($acampos)){
				
				$xcampos = array();
				foreach( $acampos as $id=>$campo ){
					$pos = $campo['POSICION'];
					$xcampos[$pos][] = $campo;
				}
				
				foreach( $agrupos as $gid=>$grupo ){
					$desde 		= $grupo['LINEA_DESDE'];
					$hasta 		= $grupo['LINEA_HASTA'];
					for($i=$desde;$i<=$hasta;$i++){
						if( isset( $xcampos[$i] ) ){
							$agrupos[$gid]['LINEAS'][$i] = $xcampos[$i];
							unset( $xcampos[$i] );
						}
					}
				}

				$vancho = $this->my_ini_resumen->seccion('VENTANA','ANCHO');
				$valto  = $this->my_ini_resumen->seccion('VENTANA','ALTO');
				
				if( $vancho == 'auto') $vcancho = '100%';
				else $vcancho = $vancho . 'px';

				if( $valto == 'auto') $valto = '100%';
				else $valto = $valto . 'px';

				$this->resumen .= '<div  class="etiqueta origen" data-origen="' .  end( explode('/', getcwd() ) ) . '/' . $this->origen . '_resumen" style="width: ' . $vancho . '; height: ' . $valto . '; ">' . PHP_EOL;
				$this->resumen .= '<table width="100%"><tr><td class="grid_encab grid_encab_resumen" style="width: 100%;">' . $this->my_ini_resumen->seccion('VENTANA','TITULO') . '</td></tr></table>' . PHP_EOL;
				//$this->resumen .= '<div class="etiqueta" style="overflow-y: auto; height:' . $this->my_ini_resumen->seccion('VENTANA','ALTO') . 'px;">' . PHP_EOL;
				
				$this->resumen .= '	<table width="100%">' . PHP_EOL;
				foreach($agrupos as $grupo){
					$this->resumen .= '		<tr>'. PHP_EOL;
					$this->resumen .= '			<td class="grupo_encab" style="width: 100%;">' . $grupo['ROTULO'] . '</td>' .PHP_EOL;
					$this->resumen .= '		</tr>' . PHP_EOL;
					
					foreach($grupo['LINEAS'] as $id => $linea ){
						$this->resumen .= '		<tr> ' . PHP_EOL;
						$this->resumen .= '			<td style="width: 100%;">' . PHP_EOL;
						$this->resumen .= '				<table style="width: 100%; border-collapse: collapse;">' . PHP_EOL;
						$this->resumen .= '					<tr>' . PHP_EOL;
						$this->resumen .= '						<td>' . PHP_EOL;
						$this->resumen .= '							<div style="display: flex;">' . PHP_EOL;
						
						foreach( $linea as $cid => $campo){
							
							$xtipo = isset($campo['TIPO']) ? $campo['TIPO'] : '';

							$xanchos = explode(',' , $campo['ANCHO']);
							$xancho1 = $xanchos[0];
							$xancho2 = $xanchos[1];

							$tipo_linea = 'doble'; 
							
							if( sizeof($xanchos) == 1)
							{
								$tipo_linea = 'simple';
								$xancho2 = $xancho1;
							}
							
							$estilo = 'display: flex; flex:' . $xancho1 . '; min-width: ' . $xancho1 .'px;';
							
							$ancho_rotulo = '';
							$ancho_valor  = '';
							
							if ($tipo_linea == 'simple'){

								$estilo .= 'flex-direction: column;';

								if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
									$estilo = 'display: flex; width:' . $xancho1 . 'px; min-width: ' . $xancho1 .'px; flex-direction: column;';
								}
							}
							else {
								
								$ancho_rotulo = 'width: ' . $xancho1 . 'px;';
								$ancho_valor  = 'flex: '  . $xancho2 . ';';
								
								if( substr($xtipo, 0, 1) == 'N' || substr($xtipo, 0, 1) == 'F' || substr($xtipo, 0, 1) == 'I' || substr($xtipo, 0, 1)=='D' ){
									$ancho_valor  = 'width: ' . $xancho2 . 'px;';
									$estilo = 'display: flex; min-width: ' . $xancho2 .'px;';
								}

							}
							
							switch ( substr( $xtipo, 0, 1 ) )
							{
							   
							   case 'N':
								 $alinea = 'right';
								 break;

							   case 'I':
								 $alinea = 'right';
								 break;
								 
							   case 'F':
								 $alinea = 'right';
								 break;

							   case 'D':
								 $alinea = 'center';
								 break;

							   default:
								 $alinea = 'left';
								 break;
							}
							$this->resumen .= '  								<div  class="celda_pie" style=" ' . $estilo . ' " > ' . PHP_EOL;
							$this->resumen .= '									<div data-campo="' . $campo['CAMPO'] . '"  class="rotulo_pie"  style="text-align: ' . $alinea . ';' . $ancho_rotulo . ' ">' . $campo['ROTULO'] . '</div> ' . PHP_EOL;
							$this->resumen .= '									<div data-tipo="'  . $campo['TIPO']  . '"  class="valor"  style="' . $ancho_valor . ' text-align: ' . $alinea . ';" id="RESUMEN_'.$campo['CAMPO'] . '">';
							$this->resumen .= isset($campo['VALOR']) ? $campo['VALOR'] : '';
							$this->resumen .='&nbsp;</div> ' . PHP_EOL;
							$this->resumen .= '								</div>' . PHP_EOL;
						}
						$this->resumen .= '							</div>' . PHP_EOL;
						$this->resumen .= '						</td>' . PHP_EOL;
						$this->resumen .= '					</tr>' . PHP_EOL;
						$this->resumen .= '				</table>' . PHP_EOL;
						$this->resumen .= '			</td>' .PHP_EOL;
						$this->resumen .= '		<tr> ' . PHP_EOL;
					}
				}
				$this->resumen .= '	</table>' . PHP_EOL;
				$this->resumen .= '</div>' . PHP_EOL;

				$this->resumen .= '		</div>' . PHP_EOL;
				$this->resumen .= '</div>'. PHP_EOL;
			}

		}

	}

}

//== MARCO: OCTUBRE-2021

function loadInis(){
    $ini = new ini();
	$script = '';

	
	if( file_exists('./index.js' ) ) $script .= file_get_contents("./index.js");
	if( file_exists('./index.php') ) $script .= file_get_contents("./index.php");
 
	$ret = "var inis={";

    $coma="";
    $inis = preg_match_all("/lista\((\"|\')(.*?)(\"|\')\)/", $script, $matches);
	
	if( file_exists('./modulo.ini') )         $matches[2][] = end( explode('/', getcwd() ) )  . '/modulo';
	if( file_exists('./modulo_resumen.ini') ) $matches[2][] = end( explode('/', getcwd() ) )  . '/modulo_resumen';
	
	
	if ( count( $matches[2] )) {
        for( $i=0; $i < count( $matches[2]); $i++){

			$ini->origen = "../" . $matches[2][$i];
			
            if ( file_exists( $ini->origen . ".ini")) {
				$ini->cargar_ini();
				$cont = $ini->generaObj();
				$cont = utf8_string_array_encode($cont);
				$ret.=$coma."'".$matches[2][$i]."': ".json_encode($cont);
				$coma=",";
            }
        }
    }

    $inis = preg_match_all("/formulario2\((\"|\')(.*?)(\"|\')\)/", $script, $matches);
    if (count($matches[2])) {
        for($i=0;$i<count($matches[2]);$i++){
            $ini->origen = "../".$matches[2][$i];
            if (file_exists($ini->origen.".ini")) {
              $ini->cargar_ini();
              $cont = $ini->generaObj();
              $cont = utf8_string_array_encode($cont);
              //print_r($cont);
              $ret.=$coma."'".$matches[2][$i]."': ".json_encode($cont);
              $coma=",";
            }
        }
    }
    
    $inis = preg_match_all("/armar\((\"|\')(.*?)(\"|\')\)/", $script, $matches);
    if (count($matches[2])) {
        for($i=0;$i<count($matches[2]);$i++){
            $ini->origen = "../".$matches[2][$i];
            if (file_exists($ini->origen.".ini")) {
              $ini->cargar_ini();
              $cont = $ini->generaObj();
              $cont = utf8_string_array_encode($cont);
              //print_r($cont);
              $ret.=$coma."'".$matches[2][$i]."': ".json_encode($cont);
              $coma=",";
            }
        }
    }

    $inis = preg_match_all("/\.origen\s*\=\s*(\"|\')(.*?)(\"|\')/", $script, $matches);
    if (count($matches[2])) {
        for($i=0;$i<count($matches[2]);$i++){
            $ini->origen = "../".$matches[2][$i];
            if (file_exists($ini->origen.".ini")) {
              $ini->cargar_ini();
              $cont = $ini->generaObj();
              $cont = utf8_string_array_encode($cont);
              //print_r($cont);
              $ret.=$coma."'".$matches[2][$i]."': ".json_encode($cont);
              $coma=",";
            }
        }
    }
    
	
	
	
    $ret .= "}";
    
	$js = '' . PHP_EOL;
	$js .= '<script type="text/javascript">' . PHP_EOL;
	$js .= $ret . PHP_EOL;
	$js .= '</script>' . PHP_EOL;
	
	return  $js ;
}

?>