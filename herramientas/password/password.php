<?php
header("content-type: text/html; charset=utf-8");
if(isset($_REQUEST['cerrar']))
{
	$salir = '$(document).ready(function() {' . "\n";
    $salir.= ' top.doUnload(true);' . "\n";
    $salir.= '    ocultaCarga();' . "\n";
    $salir.= '});' . "\n";
}

if(!isset ( $_REQUEST['db'] ))
{
echo <<<EOT

<script type="text/javascript">

	alert('Debe especificar una base de datos\\nSe recomienda cerrar y abrir nuevamente el programa');
	window.close();


</script>


EOT;

die();
}else{	
	$tdb = $_REQUEST['db'];
}



include_once (RUTA_HERRAMIENTAS . '/herramientas/utiles/comun.php');
encabezado('Acceso a Sistema');
echo '<body onload="ocultaCarga();" scroll="no" style="">' . "\n";
$remoto   = isset($_REQUEST['remoto']) ? $_REQUEST['remoto'] :'';

$img_fondo = WWW_PATH .'/imagenes/fondo_'. $tdb . '.jpg';

echo '<img id="xfondo" src="'. $img_fondo  .'" style="position:absolute; display:none; left:0; top:0;">';

cargando();
javascript('utiles,password,jquery');
echo <<<EOT

<script type="text/javascript">

var remoto = '{$remoto}';
var db     = '{$tdb}';

</script>

EOT;
?>


<script type="text/javascript">

var im=document.getElementById('xfondo');
if(im){
	im.style.width=document.body.offsetWidth;
	im.style.height=document.body.offsetHeight;
	im.style.display='block';
}


var pwd      = new password('pwd');

pwd.remoto   = remoto;
pwd.db       = db;
pwd.width    = 600;
pwd.height   = 300;
pwd.url      = server_path + 'herramientas/password/seguridad.php';
pwd.login    = true;

pwd.caption  = 'Login,Contraseña';
pwd.title    = 'Acceso a Sistema';
pwd.callFunc = function(e){
	var evt     = window.event || e;
	var Elem    = evt.srcElement || evt.target;
	var KeyCode = evt.keyCode || evt.which;

	switch (KeyCode)
	{
	case 27: // Escape
	  cancelaTecla(evt);
	  called(false);
	}
}

pwd.init();
pwd.show();

pwd.setFocus();


document.onkeydown=tecla_doc;

function called(aceptado)
{
  if(!aceptado) window.close();
}

function tecla_doc(e)
{
}

</script>

<style>
html,

.bot-login{
	
	background-color: var(--color-boton-oscuro);

}


.hero.is-success {
  background: #f2f6fa;
}

.hero .nav,
.hero.is-success .nav {
  -webkit-box-shadow: none;
  box-shadow: none;
}

.box {
  xmargin-top: 5rem;
  height : 500;
  background-color: #fff;
  opacity : 0.9;
}

input {
  font-weight: 300;
  background-color : #fff;
 }

p {
  font-weight: 700;
}

p.subtitle {
  padding-top: 1rem;
}

a {
  color: #fff;
}

a:hover {
  color: #bababa;
}


</style>
 
</body>




