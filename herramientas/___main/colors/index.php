<?php 

include ("../config.php");

include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/sql/class/class_sql.php");
include_once (Server_Path . "herramientas/ini/class/class_ini.php");

encabezado('Colores');

$ini = new ini( RUTA_SISTEMA ."variables" );


$xHtml = '<table class="table">';


$xHtml .= crear( 'Iconos del Menu Principal' 	, 'color-iconos-menu' , $ini->seccion('root', 'color-iconos-menu' )   );
$xHtml .= crear( 'Color Principal Oscuro'		, 'color-azul-oscuro' , $ini->seccion('root', 'color-azul-oscuro' )   );
$xHtml .= crear( 'Color Principal Claro'      	, 'color-azul-claro'  , $ini->seccion('root', 'color-azul-claro'  )   );

$xHtml .= crear( 'Color Botones Oscuro'   		, 'color-boton-oscuro' , $ini->seccion('root', 'color-boton-oscuro' )   );
$xHtml .= crear( 'Color Botones Claro'    		, 'color-boton-claro'  , $ini->seccion('root', 'color-boton-claro'  )   );

$xHtml .= crear( 'Color Gris Oscuro' 	  		, 'color-gris-oscuro' , $ini->seccion('root', 'color-gris-oscuro'  )   );
$xHtml .= crear( 'Color Gris Medio'  	  		, 'color-gris-medio'  , $ini->seccion('root', 'color-gris-medio'  )   );
$xHtml .= crear( 'Color Gris Claro'  	  		, 'color-gris-claro'  , $ini->seccion('root', 'color-gris-claro'  )   );

$xHtml .= crear( 'Color Negro'       	  		, 'color-negro'       , $ini->seccion('root', 'color-negro'  )   );

$xHtml .= '</table>';

function crear( $rotulo, $id, $color ){
	
	$tHtml  = '';
	$tHtml .= '<tr>'  . PHP_EOL;
	$tHtml .= '<td><label for="' .$id  . '">' . $rotulo . '</label></td>' . PHP_EOL;
	$tHtml .= '<td><input type="color" id="' . $id . '" name="' . $id . '" value="' . $color .'"></td>' . PHP_EOL;
	$tHtml .= '</tr>' . PHP_EOL;
	
	return $tHtml;
	
}

?>




<body scroll="no">

<?php

javascript('jquery,utiles');

echo $xHtml . PHP_EOL;

?>


</body>
</html>