<?php

include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
include_once (Server_Path . 'herramientas/ini/class/class_ini.php');


javascript('utiles');

$archivos  = scandir( getcwd() );

$ini = new ini();

$inis = "var inis={";
$coma="";

foreach ($archivos as $archivo) {
	$arch = explode('.', $archivo);
	
	if( $arch[1] == 'ini' ){
		
		if ( file_exists( $archivo)) {
			$ini->origen = RUTA_SISTEMA . 'main/' . $arch[0] ;
			$ini->cargar_ini();
			
			if( $ini->seccion('VENTANA','TIPO') == 'GRID' ) {
				$tables[]  = generar_tabla( $archivo );
			}

			if( $ini->seccion('VENTANA','TIPO') == 'CARD' ) {
				$cards[] = generar_card( $archivo );
			}

			if( $ini->seccion('VENTANA','TIPO') == 'GRAPH' ) {
				//$graphs[] = generar_graph( $archivo );
			}

		}
	}

}

//print_r($tables);
$html  = '';

foreach ($cards as $card){
	$html .= $card; 
}


$html .= '<div class="mx-1 columns is-desktop">' . PHP_EOL;

foreach ($tables as $table){
	$html .= '<div class="column p-1">' . PHP_EOL;
	$html .= $table; 
	$html .= '</div>' . PHP_EOL;
}

$html .= '</div>' . PHP_EOL;


$html .= '<div class="mx-1 columns is-desktop">' . PHP_EOL;


foreach ($graphs as $graph){
	$html .= '<div class="column">' . PHP_EOL;
	$html .= $graph; 
	$html .= '</div>' . PHP_EOL;
}

$html .= '</div>' . PHP_EOL;



function generar_graph  ( $ini ){

	$ini_tabla = new ini();

	$arch = explode('.', $ini);
	
	$ini_tabla->origen = RUTA_SISTEMA . 'main/' . $arch[0] ;
	$ini_tabla->cargar_ini();
	$xid = $arch[0];
	$xtitulo = $ini_tabla->seccion('VENTANA','TITULO');

	$sql = $ini_tabla->seccion('GRAFICO','DATA'); 
	
	$query = new sql();
	$query->sql = $sql;
	$query->ejecuta_query();
	
	while( $query->next_record() ){
		$tmp['name'] = '';
		$tmp['label'] = utf8_encode( $query->Record[ 'ROTULO'] );
		//$tmp['x'] = $query->Record[ 'VALOR1'];
		$tmp['y'] = $query->Record[ 'VALOR2'];
		$registro[] = $tmp;
		
	}
	
	$data['type'] = "column";
	$data['dataPoints'] = $registro;
		
	
	echo json_encode ($data);	
    
	$xHtml .= '<div id="grafico" style="height: 300px; max-width: 500px; margin: 0px auto;"></div>' . PHP_EOL;
	$xHtml .= '<script type="text/javascript">' . PHP_EOL;
	
	$xHtml .= 'chart = new CanvasJS.Chart("grafico",{' . PHP_EOL;
	$xHtml .= '	title:     { text: "Titulo", fontSize: 16 },' . PHP_EOL;
	$xHtml .= '	subtitles: [{ text: "Sub Titulo" , fontSize: 12}],' . PHP_EOL;
	$xHtml .= '	toolTip:   { content: "{name} {label} {y}" },' . PHP_EOL;
    $xHtml .= '    legend:    {' . PHP_EOL;
    $xHtml .= '        fontSize: 10,' . PHP_EOL;
	$xHtml .= '		cursor: "pointer",' . PHP_EOL;
    $xHtml .= '        itemclick: function (e) {' . PHP_EOL;
    $xHtml .= '            //console.log("legend click: " + e.dataPointIndex);' . PHP_EOL;
    $xHtml .= '            //console.log(e);' . PHP_EOL;
    $xHtml .= '            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {' . PHP_EOL;
    $xHtml .= '                e.dataSeries.visible = false;' . PHP_EOL;
    $xHtml .= '            } else {' . PHP_EOL;
    $xHtml .= '                e.dataSeries.visible = true;' . PHP_EOL;
    $xHtml .= '            }' . PHP_EOL;
    $xHtml .= '            e.chart.render();' . PHP_EOL;
    $xHtml .= '        }' . PHP_EOL;
    $xHtml .= '    },' . PHP_EOL;
	$xHtml .= '	axisX:  { title: "Titulo X", titleFontSize: 12, labelFontSize: 10, interval: 1, labelAngle: -20, labelWrap: true },' . PHP_EOL;
	$xHtml .= '	axisY:  { title: "Titulo Y", titleFontSize: 12, labelFontSize: 10 },' . PHP_EOL;
	$xHtml .= '	data: [' . json_encode ($data) . ']' . PHP_EOL;
	$xHtml .= '	' . PHP_EOL;
	$xHtml .= '});' . PHP_EOL;
	$xHtml .= '	chart.render();' . PHP_EOL;	

	$xHtml .= '</script>' . PHP_EOL;
	
	return $xHtml;

	/*
	$acampos = $ini_tabla->secciones("CAMPO");

	$xHtml  = '';

	if( is_array($acampos) ){
		$xHtml .= '			<div class="tile is-parent is-ancestor has-text-centered">' .PHP_EOL;
		
		foreach ( $acampos as $campo ){
			
			$sql = $campo['DATA']; 
			
			//echo $sql . PHP_EOL;
			
			$query = new sql();
			$query->sql = $sql;
			$query->ejecuta_query();
			
			while( $query->next_record() ){
				
				$xHtml .= '	<div class="tile is-parent">' . PHP_EOL;
				$xHtml .= '		<article class="tile is-child box p-3 ">' . PHP_EOL;
				$xHtml .= '			<p class="title is-size-5">' . $query->Record[ $campo['CAMPO'] ] . '</p>' . PHP_EOL;
				$xHtml .= '			<p class="subtitle is-size-6">' .  $campo['ROTULO']  . '</p>' . PHP_EOL;
				$xHtml .= '		</article>' . PHP_EOL;
				$xHtml .= '	</div>' . PHP_EOL;
			}	
			
			
			//echo $campo['CAMPO'] . PHP_EOL;
		}

		$xHtml .= '			</div>' . PHP_EOL;
		
	}
	*/
	return $xHtml;
	
}


function generar_card  ( $ini ){

	$ini_tabla = new ini();

	$arch = explode('.', $ini);
	
	$ini_tabla->origen = RUTA_SISTEMA . 'main/' . $arch[0] ;
	$ini_tabla->cargar_ini();
	$xid = $arch[0];
	$xtitulo = $ini_tabla->seccion('VENTANA','TITULO');

	$acampos = $ini_tabla->secciones("CAMPO");

	$xHtml  = '';

	if( is_array($acampos) ){
		$xHtml .= '			<div class="tile is-parent is-ancestor has-text-centered">' .PHP_EOL;
		
		foreach ( $acampos as $campo ){
			
			$sql = $campo['DATA']; 
			
			//echo $sql . PHP_EOL;
			
			$query = new sql();
			$query->sql = $sql;
			$query->ejecuta_query();
			
			while( $query->next_record() ){
				
				$tipo  = $campo['TIPO'];
				$valor = $query->Record[ $campo['CAMPO'] ] ;
				if($tipo == 'D') $valor = date("d-m-Y", strtotime($valor));
				
				$xHtml .= '	<div class="tile is-parent">' . PHP_EOL;
				$xHtml .= '		<article class="tile is-child box p-3 ">' . PHP_EOL;
				$xHtml .= '			<p class="title is-size-5">' . $valor . '</p>' . PHP_EOL;
				$xHtml .= '			<p class="subtitle is-size-6">' .  $campo['ROTULO']  . '</p>' . PHP_EOL;
				$xHtml .= '		</article>' . PHP_EOL;
				$xHtml .= '	</div>' . PHP_EOL;
			}	
			
			
			//echo $campo['CAMPO'] . PHP_EOL;
		}

		$xHtml .= '			</div>' . PHP_EOL;
		
	}
	
	return $xHtml;
	
}


function generar_tabla ( $ini ){
	
	$ini_tabla = new ini();

	$arch = explode('.', $ini);
	
	$ini_tabla->origen = RUTA_SISTEMA . 'main/' . $arch[0] ;
	$ini_tabla->cargar_ini();
	$xid = $arch[0];
	$xtitulo = $ini_tabla->seccion('VENTANA','TITULO');
	$xalto = $ini_tabla->seccion('VENTANA','ALTO');

	
	$sql = $ini_tabla->seccion('SQL','DATA'); 
		
	$query = new sql();
	$query->sql = $sql;
	$query->ejecuta_query();


	
	$xHtml  = '';
	$xHtml .= '<div class="card events-card">' . PHP_EOL;
	$xHtml .= '	<header class="card-header">' . PHP_EOL;
	$xHtml .= '		<p class="card-header-title" id="' . $xid . '_title">' . PHP_EOL;
	$xHtml .= '' . $xtitulo;
	$xHtml .= '		</p>' . PHP_EOL;
	$xHtml .= '	</header>' . PHP_EOL;
	$xHtml .= '	<div class="card-table">' . PHP_EOL;
	$xHtml .= '		<div id="' . $xid . '_table" class="content" style=" height: ' . $xalto . 'px; overflow: auto;">' . PHP_EOL;
	$xHtml .= '			<table class="table is-fullwidth is-striped is-hoverable" >' . PHP_EOL;
	$xHtml .= '			<thead>' . PHP_EOL;

	$acampos = $ini_tabla->secciones("CAMPO");
	//print_r ($acampos);
	if( is_array($acampos) ){
		
		$xHtml .='<tr>' . PHP_EOL;
		foreach ( $acampos as $campo ){
			$class = 'has-text-left';
			if( $campo['TIPO']  == 'N' ) $class='has-text-right';
			if( $campo['TIPO']  == 'I' ) $class='has-text-right';
			if( $campo['TIPO']  == 'D' ) $class='has-text-centered';

			$xHtml .='<th class="' . $class . '">' . $campo['ROTULO'] . '</th>' . PHP_EOL;
		}
		$xHtml .='</tr>' . PHP_EOL;
		
	}

	$xHtml .= '			</thead>' . PHP_EOL;
	$xHtml .= '			<tbody>' .PHP_EOL;

	while( $query->next_record() ){
		$xHtml .='<tr>' . PHP_EOL;
		foreach ( $acampos as $campo ){
			
			$class = 'has-text-left';
			if( $campo['TIPO']  == 'N' ) $class='has-text-right';
			if( $campo['TIPO']  == 'I' ) $class='has-text-right';
			if( $campo['TIPO']  == 'D' ) $class='has-text-centered';
			
			$tipo  = $campo['TIPO'];
			$valor = $query->Record[ $campo['CAMPO'] ] ;
			if($tipo == 'D') $valor = date("d/m/Y", strtotime($valor));
		
			$xHtml .='<td class="' . $class . '">' . $valor  . '</td>' . PHP_EOL;
		}
		$xHtml .='</tr>' . PHP_EOL;
	}	

	$xHtml .= '			</tbody>' . PHP_EOL;
	$xHtml .= '			</table>' . PHP_EOL;
	$xHtml .= '		</div>' . PHP_EOL;
	$xHtml .= '	</div>' . PHP_EOL;
	$xHtml .= '	<footer class="card-footer">' . PHP_EOL;
	$xHtml .= '	<a href="#" class="card-footer-item">&nbsp;</a>' . PHP_EOL;
	$xHtml .= '	</footer>' . PHP_EOL;
	$xHtml .= '	</div>' . PHP_EOL;
	
	return $xHtml;	
	
}


?>


</body>

</html>