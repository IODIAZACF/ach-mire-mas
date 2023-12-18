<?php
/* Clase que genera la leyenda */
include_once(Server_Path . "herramientas/ini/class/class_ini.php");

class class_leyenda
{
   var $origen;
   var $contenido_html;
   var $enter   = 0;
   var $estilo  = 'leyenda';
   var $xestilo = 'class="boton_leyenda td_leyenda_inactiva"'; // onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="this.className=\'td_leyenda_click\'" onmouseout="this.className=\'td_leyenda_inactiva\'"';
   var $leyenda = array();
   

   function armar()
   {
	    if($this->origen){
	        $my_ini         = new ini($this->origen);
	        $this->leyenda  = $my_ini->campos2('LEYENDA','','N');
	    }
        if(!is_array($this->leyenda))  return;

    	while(list($id,$elem) = each($this->leyenda))
    	{
            $t = explode(',', $elem['ANCHO']);
			$elem['ANCHO'] = array_sum($t);
        	$linea 	 = 	$elem['POSICION'];

			$xancho = intval($elem['ANCHO']);	

            $icono = '';
			
			if( isset( $elem['ICONO'] ) ) {
				$icono = $elem['ICONO'];
			}
			
			if( !isset( $elem['ICONO'] ) ) {
				$icono = default_icon( $elem['TECLA'] );
			}
		
			if( $icono ) $xancho = $xancho + 35;
			
			$tmp 	= '<div panel="' . $elem['PANEL'] . '" data-campo="' . $elem['TECLA'] . '" id="' . $elem['NOMBRE'] .  '" onselectstart="return false;" style="width: '. $xancho  .'px;"' . $this->xestilo . '>' . "\n";
			
			if( $icono ) {
				$tmp    .= '<span class="icono_leyenda"><i class="' . $icono . '"></i></span>' . "\n";
			}
			
			$tmp    .= '  	<span class="texto_leyenda">' . "\n";
			$tmp    .= '		<div class="tecla_leyenda">' . "\n";
			$tmp    .= '			[' . $elem['TECLA']  . ']' . "\n";
			$tmp    .= '		</div>' . "\n";
			$tmp    .= '		<div class="rotulo_leyenda">' . "\n";
            $tmp    .= '			'  . $elem['ROTULO'] . "\n";
			$tmp    .= '		</div>' . "\n";
			$tmp    .= '	</span>' . "\n";
			$tmp    .= '</div>' . "\n";
            $tmp_array[$linea][] = $tmp;
       	}

		while(list($a,$b) = each($tmp_array)){
	        	$this->contenido_html .= '<div class="tabla_leyenda">'."\n";
               for($i=0;$i<sizeof($b);$i++){
               		$this->contenido_html .= $b[$i];
               }
               $this->contenido_html .= "</div>\n";
        }
   }


}

function default_icon( $tecla ){
	
	$class_icono = '';
	switch ( strtolower($tecla) ) {
		case 'ins':
		//case 'insert':
			$class_icono = 'fa-solid fa-plus fa-2x';
		break;
		
		case 'supr':
		case 'del' :
			$class_icono = 'fa-solid fa-minus fa-2x';
		break;
		
		case 'selec':
			$class_icono = 'fa-solid fa-arrow-right-to-bracket fa-2x';
		break;

		case 'edit':
			$class_icono = 'fa-solid fa-pen-to-square fa-2x';
		break;

		case 'f12':
			$class_icono = 'fa-solid fa-plus fa-2x';
		break;

		case 'esc':
			$class_icono = 'fa-solid fa-arrow-right-from-bracket fa-2x';
			
		break;
	}	
	
	return $class_icono;
	
}


?>