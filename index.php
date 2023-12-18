<?
header('X-Robots-Tag: noindex,nofollow');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistemas24.com C.A</title>
	<link rel="stylesheet" type="text/css"  href="/herramientas/estilo/bulma/bulma.min.css">
	<script type="text/javascript" src="/herramientas/jquery/javascript/jquery.js"></script>
</head>

<body>

<style>
.container{
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px,1fr));
	grid-gap: 0.5em;
	margin-top: 50px;
}

.card{
	xheight: max-content;
	width: 250px;
	height: 230px;
	transition: transform .2s; /* Animation */
}


.card:hover{
	
	xborder: solid 1px red;
	transform: scale3d(1.03, 1.03, 1);
	box-shadow: 0 .5em 1em -.125em rgba(10,10,10,.5),0 0 0 1px rgba(10,10,10,.02);
    border-radius: .25rem;
	
 	
}


</style>




<div class="container">		
	<input class="input" id="buscador" type="text" placeholder="Buscar" autocomplete="off" >	
</div>

<div class="container">	

<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


$xpath = '/opt/lampp/utilidades';
include_once ($xpath . '/php/clases/class_sql.php');

$dir = '/opt/lampp/firebird/db/';

$files = scandir($dir);

$html = '';

$i = 0;
foreach ($files as $key => $val) {
	
	$tmp = explode(".", $val);
    $sis = '/opt/lampp/htdocs/' . $tmp[0]; 
	$ext = $tmp[1];
	
	if( !is_dir( $sis ) ){
		continue;
	} 

	if($ext != 'gdb'){
		continue;
	}
		
	if($i == 0  ) {
		//$html .= '<div class="container">' . PHP_EOL;
	}
	
	$db  = $tmp[0];
	
	$query  = new sql();
	$query->DBHost     = "127.0.0.1";
	$query->DBDatabase = "/opt/lampp/firebird/db/" . $db . ".gdb";
	$query->DBUser     = "SYSDBA";
	$query->DBPassword = "masterkey";
	$query->Initialize();

	$sql ="SELECT * FROM CONFIGURACION";
	$query->sql = $sql;
	$query->ejecuta_query();
	$query->next_record();

	$razon       = $query->Record['RAZON'];
	$nombres     = $query->Record['NOMBRES'];

	//echo $razon . "<br>";
	$html .= '<div class="card">' . PHP_EOL;
	$html .= '	<figure class="card-image p-2">' . PHP_EOL;
	$html .= '			<center>' . PHP_EOL;
	$html .= '			<a  href="/' . $db . '/herramientas/password/password.php?db='. $db .'">' . PHP_EOL;
	$html .= '				<div  class="image is-128x128" >' . PHP_EOL;
	$html .= '					<img src="/' . $db . '/imagenes/' . $db . '_logo_reporte.jpg" /" alt="Placeholder image">' . PHP_EOL;
	$html .= '				</div>' . PHP_EOL;
	$html .= '			</a>' . PHP_EOL;
	$html .= '			</center>' . PHP_EOL;
	$html .= '	</figure>' . PHP_EOL;
	$html .= '	<div class="card-content" style="padding: 10px;">' . PHP_EOL;
	$html .= '		<div class="content has-text-centered">' . PHP_EOL;
	$html .= '			<a  href="/' . $db . '/herramientas/password/password.php?db='. $db .'"><div class="titulo is-size-7">' . strtoupper($nombres) . '</div></a> ' . PHP_EOL;
	$html .= '		</div>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '</div>' . PHP_EOL;

	$movil = '/opt/lampp/htdocs/' . $tmp[0] . '/movil/menu.ini';

	if( file_exists ($movil) ) {
		
		$html .= '<div class="card">' . PHP_EOL;
		$html .= '	<figure class="card-image p-2">' . PHP_EOL;
		$html .= '			<a  href="/' . $db . '/movil/herramientas/main/index.html">' . PHP_EOL;
		$html .= '				<div  class="image is-128x128" >' . PHP_EOL;
		$html .= '					<img src="/' . $db . '/imagenes/' . $db . '_logo_reporte.jpg" /" alt="Placeholder image">' . PHP_EOL;
		$html .= '				</div>' . PHP_EOL;
		$html .= '			</a>' . PHP_EOL;
		$html .= '	</figure>' . PHP_EOL;
		$html .= '	<div class="card-content" style="padding: 10px;">' . PHP_EOL;
		$html .= '		<div class="content has-text-centered">' . PHP_EOL;
		$html .= '			<a  href="/' . $db . '//movil/herramientas/main/index.html"><div class="titulo is-size-7"> MOVIL -' . strtoupper($nombres) . '</div></a> ' . PHP_EOL;
		$html .= '		</div>' . PHP_EOL;
		$html .= '	</div>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		
	} 	
	
	$i++;
	
	if($i == 5 ) {
		$i = 0;	
		//$html .= '</div>' . PHP_EOL;
	}

	

}

echo $html;

?>
</div>




<script type="text/javascript">
	var xbusca = '';
	
	$( "#buscador" ).on('keyup', function( index ) {
		
		xbusca = $("#buscador").val().toUpperCase();
		console.log( xbusca );
		
		if(xbusca == '') $( "div.card" ).css( "display", "block" );
		else $( "div.card" ).css( "display", "none" );
		
		
		
		$( "div.titulo:contains('" + xbusca + "')" ).closest('div.card').css( "display", "block" );
		
	});
	
	


	
	
	
</script>

</body>
