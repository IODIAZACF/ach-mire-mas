<?php

//error_reporting(E_ALL);
//error_reporting(-1);
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);


header('Content-Type: text/html; charset=utf-8');

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$sistema = explode('/', $actual_link)[3];

include( '/opt/lampp/htdocs/' . $sistema . '/config.php' );

include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

encabezado('Generador de Módulo Móvil');

$modulo = $_REQUEST['modulo'];

if( !$modulo ){
	
	die( 'Debe especificar el modulo a generar ');
}

$ruta_modulo = '/opt/lampp/htdocs/' . $sistema . '/movil/' . $modulo . '/';
$ruta_menu = '/opt/lampp/htdocs/' . $sistema . '/movil/';


if( !file_exists( $ruta_modulo ) ){
	
	die( 'No existe la carpeta ' . $ruta_modulo);

}


loadMenu( $ruta_menu );

delete_files( $ruta_modulo . 'templates');

loadInis( $ruta_modulo );
loadTemplate( $ruta_modulo , $modulo );

function loadMenu( $ruta_menu ){


	$ini = new ini();
	$ini->origen = $ruta_menu . "menu";
	$ini->cargar_ini();
	$cont = $ini->generaObj();
	$cont = utf8_string_array_encode($cont);

	
	$html_opciones = '';	
		
	$aopciones = $ini->secciones("OPCION");
	if( is_array($aopciones) ){

		foreach ( $aopciones as $opcion ){
			$html_opciones .= '		<ons-list-item data-funcion="' . $opcion['FUNCION'] . '" data-valor="' . $opcion['VALOR'] . '" data-grupos="' . $opcion['GRUPOS'] .  '">' . PHP_EOL;;
			//$html_opciones .= '			<div class="content">'  . $opcion['TITULO'] . '</div>' . PHP_EOL; 
			//$html_opciones .= '			<div class="content">'  . $opcion['SUBTITULO'] . '</div>' .  PHP_EOL;;
			//$html_opciones .= '<ons-icon icon="md-face"></ons-icon>' . PHP_EOL;

			$icono = str_replace( 'fa-solid','', $opcion['ICONO'] );
			$icono = str_replace( ' ','', $icono );
			
			$html_opciones .= '<div class="left">' . PHP_EOL;
			$html_opciones .= '  <ons-icon icon="' . $icono . '" class="list-item__icon"></ons-icon>' . PHP_EOL;
			$html_opciones .= '</div>' . PHP_EOL;
			$html_opciones .= '<div class="center">' . PHP_EOL;
			$html_opciones .= '  <span class="list-item__title">' . $opcion['TITULO'] .'</span>' . PHP_EOL;
			$html_opciones .= '  <span class="list-item__subtitle">' . $opcion['SUBTITULO'] . '</span>' . PHP_EOL;
			$html_opciones .= '</div>' . PHP_EOL;

			$html_opciones .= '		</ons-list-item>' . PHP_EOL;
		}	
	}

	$template = file_get_contents('templates/template-menu_principal.html', true);
	$template = str_replace('{opciones}', $html_opciones , $template);
			  
	$x = file_put_contents( $ruta_menu . "menu_principal.html" , $template);

	$ret = "var ini_menu=" . json_encode($cont);
	echo 'Generando Menu Principal html ->' . $ruta_menu . "menu_principal.html <br>" . PHP_EOL; 
	
	$x = file_put_contents( $ruta_menu . "menu_principal.js" , $ret);
	echo 'Generando Menu Principal js ->  ' . $ruta_menu . "menu_principal.js <br>" . PHP_EOL; 
	
}

function loadTemplate( $ruta_modulo, $modulo ){
    $ini = new ini();
	$script = '';
	
	if( file_exists( $ruta_modulo . 'index.js'  ) )  $script .= file_get_contents($ruta_modulo .  'index.js');
	if( file_exists( $ruta_modulo . 'index.html') )  $script .= file_get_contents($ruta_modulo .  'index.html');


    $inis = preg_match_all("/\.origen\s*\=\s*(\"|\')(.*?)(\"|\')/", $script, $matches);
    if (count($matches[2])) {
        for($i=0; $i<count($matches[2]); $i++){

			$nombre_obj = $matches[2][$i];
			$ini->origen = $ruta_modulo . "" . $matches[2][$i];
			
			if ( file_exists( $ini->origen . ".ini")) {
				
				
				$ini->cargar_ini();
				//echo '--->' . $ini->origen . PHP_EOL;

				$aopciones = $ini->secciones("OPCION");
				$acampos   = $ini->secciones("CAMPO");
				$acolumnas = $ini->secciones("COLUMNA");
				 
				$template = "";
				$tipo_template = "";
				
				if( is_array($aopciones) && sizeof($aopciones) > 0 ){
					$tipo_template = "pagina";
					$template = file_get_contents('templates/template-pagina.html', true);
				}

				if( is_array($acampos) && sizeof($acampos) > 0 ){
					$tipo_template = "form";
					$template = file_get_contents('templates/template-form.html', true);
	
					$html_formulario = '';
					$html_formulario = armarForm( $acampos );
				}

				if( is_array($acolumnas) && sizeof($acolumnas) > 0 ){
					$tipo_template = "grid";
					$template = file_get_contents('templates/template-grid.html', true);
				}

				echo "Tipo de Template -> " . $tipo_template . " -> " ;
				
				$aopciones = $ini->secciones("OPCION");
				
				$html_opciones = '';

				if( is_array($aopciones) && $tipo_template == "grid" ){
							
					foreach ( $aopciones as $opcion ){
						$html_opciones .= '			<ons-action-sheet-button icon="' . $opcion['ICONO'] . '" data-funcion="" data-valor="' . $opcion['VALOR'] . '">' . $opcion['ROTULO'] . '</ons-action-sheet-button>' . PHP_EOL;
					}	
				}
				
				if( is_array($aopciones) && $tipo_template == "pagina" ){
					

					foreach ( $aopciones as $opcion ){
						
						if(  $opcion['TIPO'] == 'ROTULO'  ){
							$html_opciones .= '		<ons-list-header>' . $opcion['ROTULO'] . '</ons-list-header> ' . PHP_EOL;
							$html_opciones .= '		<ons-list-item><span id="' . $opcion['CAMPO'] . '" class="list-item__title"></span></ons-list-item> ' . PHP_EOL;
							
						}

						if(  $opcion['TIPO'] == 'BOTON'  ){
							$html_opciones .= '		<ons-list-item data-funcion="' . $opcion['FUNCION'] . '" data-valor="' . $opcion['VALOR'] . '" data-grupos="' . $opcion['GRUPOS'] .  '">' . PHP_EOL;;
							$html_opciones .= '			<div class="center">' . PHP_EOL;
							$html_opciones .= '  			<span class="list-item__title">' . $opcion['ROTULO'] .'</span>' . PHP_EOL;
							$html_opciones .= '			</div>' . PHP_EOL;
							$html_opciones .= '		</ons-list-item>' . PHP_EOL;
						}

					}	
				}
				
				$aleyendas = $ini->secciones("LEYENDA");
				$html_leyendas = '';
				
				if( is_array($aleyendas) ){
					
					foreach ( $aleyendas as $leyenda ){
						
						$html_leyendas .= '		<button id="{objeto}_' . $leyenda['VALOR'] . '" class="tabbar__button" data-panel="' . $leyenda['ENTER'] . '" data-funcion="" data-valor="' . $leyenda['VALOR'] . '">' . PHP_EOL;
						$html_leyendas .= '			<div class="tabbar__icon">' .PHP_EOL;
						$html_leyendas .= '				<ons-icon icon="' . $leyenda['ICONO'] . '"></ons-icon>' . PHP_EOL;
						$html_leyendas .= '			</div>' .PHP_EOL;
						$html_leyendas .= '			<div class="tabbar__label">' . $leyenda['ROTULO'] . '</div>' . PHP_EOL;
						$html_leyendas .= '		</button>' . PHP_EOL;
						
					}
					
				}
				
				$titulo = $ini->seccion('VENTANA','TITULO');
				
				$template = str_replace('{VENTANA-TITULO}', $titulo         , $template);
				$template = str_replace('{leyenda}'       , $html_leyendas  , $template);
				$template = str_replace('{opciones}'      , $html_opciones  , $template);
				$template = str_replace('{formulario}'    , $html_formulario, $template);
				$template = str_replace('{objeto}'        , $nombre_obj     , $template);
				$template = str_replace('{modulo}'        , $modulo     	, $template);
				$template = str_replace('fa-solid '       , ''     			, $template);
			  
				//echo $template . PHP_EOL;
				//echo $ruta_modulo . 'templates/' .  $archivo . ".html" . PHP_EOL;
				$x = file_put_contents( $ruta_modulo . 'templates/' .  $nombre_obj . ".html" , $template);
				echo 'Generando  ' . $ruta_modulo . 'templates/' .  $nombre_obj . ".html <br>" . PHP_EOL; 
            }
        }
    }
    
}



function armarForm( $acampos ){
	
	
	$ocultos = '';
	$html_campo  = '';
	$html_campo .= '<input id="{objeto}_tabla"  type="hidden" data-campo="tabla"  value="">';
	$html_campo .= '<input id="{objeto}_busca"  type="hidden" data-campo="busca"  value="">';
	$html_campo .= '<input id="{objeto}_xbusca" type="hidden" data-campo="xbusca" value="">';
	
	foreach ( $acampos as $campo ){

		$tipo   = '';
		$type   = '';
		$noedit = '';
		$input  = '';
		
		
		switch ( $campo['FORMA'] ) {
			case 'OCULTO':
				$ocultos .= '<input id="{objeto}_' . $campo['CAMPO'] . '" data-campo="' . $campo['CAMPO'] . '" type="hidden" data-tipo="' . $campo['TIPO'] . '" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				break;

				case 'TEXTO_EDITABLE':
				$tipo  = 'C';
				$type  = 'text';
				$input = '<input id="{objeto}_' . $campo['CAMPO'] . '" data-campo="' . $campo['CAMPO'] . '" class="input_editable" type="' . $type . '" data-tipo="' . $campo['TIPO'] . '" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				break;

			case 'TEXTO_NOEDITABLE':
				$tipo  = 'C';
				$type  = 'text';
				$input = '<input id="{objeto}_' . $campo['CAMPO'] . '"  data-campo="' . $campo['CAMPO'] . '" class="input_editable" type="' . $type . '" data-tipo="' . $campo['TIPO'] . '" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off" readonly>' . PHP_EOL;
				break;

			case 'NUMERO_EDITABLE':
				$tipo  = 'N';
				$type  = 'number';
				$input = '<input id="{objeto}_' . $campo['CAMPO'] . '"  data-campo="' . $campo['CAMPO'] . '" class="input_editable" type="' . $type . '" data-tipo="' . $campo['TIPO'] . '" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				break;

			case 'FECHA':
				$tipo  = 'D';
				$type  = 'date';
				$input = '<input id="{objeto}_' . $campo['CAMPO'] . '"  data-campo="' . $campo['CAMPO'] . '" class="input_editable" type="' . $type . '" data-tipo="' . $campo['TIPO'] . '" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				break;
			
			case 'SELECCION_SIMPLE':
				$selected = $campo['VALOR'];
				
				$xopciones = explode(',', $campo['OPCIONES'] );
				$input = '<select id="{objeto}_' . $campo['CAMPO'] . '" data-campo="' . $campo['CAMPO'] . '" class="input_editable" data-tipo="' . $campo['TIPO'] .'" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				$input .= '		<option value="">--</option>' . PHP_EOL;
				
				foreach ( $xopciones as $opcion ){
					
					$tmp = explode(':', $opcion);
					
					$valor  = $tmp[0];
					$opcion = $tmp[0];
					
					if( sizeof($tmp) > 1 ){
						$opcion = $tmp[1];
					}
					
					if( $selected == $valor ){
						$input .= '		<option value="' . $opcion . '" selected>' . $valor . '</option>' . PHP_EOL;
					} else {
						$input .= '		<option value="' . $opcion . '">' . $valor . '</option>' . PHP_EOL;
					}
					
				}

				$input .= '</select>' . PHP_EOL;
				
				break;

			case 'SELECCION_TABLA':
				$input  = '<select id="{objeto}_' . $campo['CAMPO'] . '" data-campo="' . $campo['CAMPO'] . '" class="input_editable" data-tipo="' . $campo['TIPO'] .'" data-leer="' . $campo['LEER'] . '" data-enviar="' . $campo['ENVIAR'] . '" data-old="" autocomplete="off">' . PHP_EOL;
				$input .= '</select>' . PHP_EOL;	
				break;
			
			case 'TEXT_AREA_EDITABLE':	
			case 'TEXT_AREA_NOEDITABLE':
				break;
				
				
		}						

		$html_campo .= '		<ons-list-item class="input-items">' . PHP_EOL;
		$html_campo .= '			<label class="center">' . PHP_EOL;
		$html_campo .= '				<span class="input_editable_label">' . $campo['ROTULO'] . '</span>' . PHP_EOL;

		$html_campo .= '				' .$input . PHP_EOL;
		
		$html_campo .= '			</label>' . PHP_EOL;
		$html_campo .= '		</ons-list-item>' . PHP_EOL;									  
	}
	
	$html_campo .= $ocultos;
	return $html_campo;
	
}






//== MARCO: OCTUBRE-2021

function loadInis( $ruta_modulo ){
    $ini = new ini();
	$script = '';
	
	if( file_exists( $ruta_modulo . 'index.js'  ) )  $script .= file_get_contents($ruta_modulo .  'index.js');
	if( file_exists( $ruta_modulo . 'index.html') )  $script .= file_get_contents($ruta_modulo .  'index.html');

	$ret = "inis={";

    $coma="";
    $inis = preg_match_all("/lista\((\"|\')(.*?)(\"|\')\)/", $script, $matches);

	
	//if( file_exists( $ruta_modulo . 'modulo.ini') )          $matches[2][] = end( explode('/', getcwd() ) )  . '/modulo';
	//if( file_exists( $ruta_modulo . '/modulo_resumen.ini') ) $matches[2][] = end( explode('/', getcwd() ) )  . '/modulo_resumen';
	
	if ( count( $matches[2] )) {
        for( $i=0; $i < count( $matches[2]); $i++){

			$ini->origen = $ruta_modulo  . $matches[2][$i];
			
			
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
    
    $inis = preg_match_all("/\.origen\s*\=\s*(\"|\')(.*?)(\"|\')/", $script, $matches);
    if (count($matches[2])) {
		for( $i=0; $i < count( $matches[2] ); $i++ ){
			$ini->origen = $ruta_modulo . '' .  $matches[2][$i];
			
            if (file_exists( $ini->origen . ".ini")) {
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
	//$js .= '<script type="text/javascript">' . PHP_EOL;
	$js .= $ret . PHP_EOL;
	//$js .= '</script>' . PHP_EOL;
	//echo $js;
	
	$x = file_put_contents( $ruta_modulo . "inis.js" , $js);
	//return  $js ;
	echo 'Gereando inis    ' . $ruta_modulo . "inis.js <br>" . PHP_EOL; 
	
	
}



function delete_files( $folder ){

	$files = glob($folder . '/*');

	//Loop through the file list.
	foreach($files as $file){
		if(is_file($file)){
			unlink($file);
		}	
	
	
	}

}


?>

