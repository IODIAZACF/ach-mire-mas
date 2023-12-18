<?php 
header('Content-Type: text/html; charset=iso-8859-1');
include ("../config.php");

include_once (Server_Path . 'herramientas/utiles/comun.php');

include (Server_Path . "herramientas/sql/class/class_sql.php");

encabezado($_SESSION['CONFIGURACION_NOMBRES']);

$menu = getvar('menu','0011');
$xdb_sistema  = getsession('db');
$xsession   = strtoupper(session_id());

$id_m_usuario = getsession('M_USUARIOS_ID_M_USUARIO');
$login		  = getsession('M_USUARIOS_LOGIN');
$nombre       = getsession('M_USUARIOS_NOMBRES');
$id_grupo     = getsession('M_GRUPOS_ID_GRUPOS');
$empresa      = getsession('CONFIGURACION_NOMBRES');
$rif          = getsession('CONFIGURACION_CODIGO1');
$mac          = getsession('M_ESTACIONES_MACADDRESS');
$grupo		  = getsession('M_GRUPOS_GRUPOS');

$sistema      = Sistema;
$ip           = $_SERVER['REMOTE_ADDR'];

?>

<body id="main" scroll="no">

<link rel="stylesheet" type="text/css"  href="main.css">
<link rel="stylesheet" type="text/css"  href="jBox.all.min.css">

<?php

javascript('jquery,utiles,submodal,hmenu2');

?>

<div id="BARRA_LATERAL" class="sidenav">
	
	<div class="separator" style="display: block; padding-bottom:25px;">
		<div class="icon"><i class="fa-solid fa-caret-down fa-2x"></i></div>
	</div>
	
	<div class="separator" id="cliente_correo" onclick="mostrar_cliente_correo();">
		<div class="icon"><i class="fa-regular fa-envelope fa-2x"></i></div>
		<div class="label">Correo<br>Electrónico</div>
	</div>

	<div class="separator" id="video_conferencia" onclick="mostrar_menu_conference();">
		<div class="icon"><i class="fa-solid fa-video fa-2x"></i></div>
		<div class="label">Video<br>Conferencia</div>
	</div>

	<div class="separator" id="cliente_pbx" onclick="mostrar_webphone();" >
		<div class="icon"><i class="fa-solid fa-headset fa-2x"></i></div>
		<div class="label">Web phone</div>
	</div>

	<div class="separator" id="chat_omnicanal" onclick="mostrar_chat_omnicanal();">
		<div class="icon"><i class="fa-solid fa-comment fa-2x"></i></div>
		<div class="label">Chat<br>Omnicanal</div>
	</div>
	
	<div class="separator" id="soporte_remoto" style = "display: block;" onclick="soporte_remoto();">
		<div class="icon"><i class="fa-solid fa-laptop-medical fa-2x"></i></div>
		<div class="label">Soporte<br>Remoto</div>
	</div>

	<div class="separator" id="soporte_remoto" style = "display: block;">
		<a href="/herramientas/meshcentral/MeshCentralAssistant-soporte_dinamico.exe" target="_blank">
			<div class="icon"><i class="fa-solid fa-laptop-medical fa-2x"></i></div>
			<div class="label">Descargar Agente<br>Remoto</div>
		</a>
	</div>

	<div class="separator" id="info" style = "display: block;" onclick="mostrar_info();">
		<div class="icon"><i class="fa-solid fa-info fa-2x"></i></div>
		<div class="label">Info</div>
	</div>

	<div class="separator" id="zoom_panel" style = "display: block;">
		<div class="icon" onclick="cambia_zoom(1);"><i class="fa-solid fa-plus fa-2x"></i></div>
		<div class="icon" onclick="cambia_zoom(-1);"><i class="fa-solid fa-minus fa-2x"></i></div>
		<div class="label">Zoom &nbsp;<span id="zoom_level">&nbsp;</span></div>
	</div>

</div>


<div class="PROGRAMA">
	<div class="proc">
		<iframe name="proceso" id="proceso" src="" scrolling="no" frameborder="0"></iframe>
	</div>
</div>


<div id="DEBUG" class="DEBUG has-text-white">
	<div class="is-text-white is-size-8" style="padding: 1px 20px 20px 20px;" >
		<center><div id="titulo_debug">consola</div></center>
	</div>
	
	<div class="columns">
		<div class="column is-1">#</div>
		<div class="column is-1">Script</div>
		<div class="column is-5">Mensaje</div>
		<div class="column is-5">Url</div>
	</div>
	<div id="ObjDebugO24" name="ObjDebugO24">
	</div>
		
</div>


<script type="text/javascript">

console.log('Inicio del sistema');

var debugVar			= 0;
var debug_autotabla		= 0;
var editVar				= 0;

var xtiempo    			= null;
var id_m_usuario     	= '<?=$id_m_usuario;?>';
var xsession            = '<?=$xsession;?>';
var xusuario_sistema    = '<?=$nombre;?>';
var xdb_sistema     	= '<?=$xdb_sistema;?>';

var id_m_usuario  		= '<?=$id_m_usuario;?>';
var nombre_usuario      = '<?=$nombre;?>';
var login      			= '<?=$login;?>';
var id_grupo    		= '<?=$id_grupo;?>';
var empresa     		= '<?=$empresa;?>';
var ip          		= '<?=$ip;?>';
var rif         		= '<?=$rif;?>';
var sistema     		= '<?=$sistema;?>';

var grupo     			= '<?=$grupo;?>';

var video_conferencia   = '<?=$video_conferencia;?>';  
var cliente_correo   	= '<?=$cliente_correo;?>';  
var chat_omnicanal   	= '<?=$chat_omnicanal;?>';  
var cliente_pbx   		= '<?=$cliente_pbx;?>';  
var ws24_local   		= '<?=$ws24;?>';  

var globales			= new Array;

if( id_m_grupo_usuario == 'XXXXGRUP0011') {
	
	var xHtml  = '';
		xHtml += '<div class="EDITOR" style="position:absolute; display:none;">';
		xHtml += '	<div class="editor-close"><i class="fa-solid fa-circle-down"></i></div>';
		xHtml += '	<div class="edit">';
		xHtml += '		<iframe name="editor" id="editor" src="../../../editores/atheos" onload="creAtheos();" scrolling="no" frameborder="0" ></iframe>';
		xHtml += '	</div>';
		xHtml += '</div>';
		
	$.getScript( "javascript/editor.js", function( data, textStatus, jqxhr ) {
	  //console.log( data ); // Data returned
	  //console.log( textStatus ); // Success
	  //console.log( jqxhr.status ); // 200
	  //console.log( "Load was performed." );
	});

	$("body").append( xHtml );


	
}	  

/* Evitar que cierren el sistema por error */	
window.addEventListener("beforeunload", (evento) => {
	if (true) {
		evento.preventDefault();
		evento.returnValue = "";
		return "";
	}
});

</script>

<script src="https://code.responsivevoice.org/responsivevoice.js?key=g1MFBbl3"></script>
<script type="text/javascript" src="jBox.all.min.js"></script>


<script type="text/javascript" src="main.js"></script>

<?php

/* CONDICIONAR EN EL CONFIG.PHP si se carga o no */

if ($video_conferencia){
	// Jitsi
	echo '<script type="text/javascript" src="https://meet.sistemas24.com/external_api.js"></script>' . "\n";
	echo '<script type="text/javascript" src="javascript/video_conferencia.js?' . $JSVERSION . '"></script>' . "\n";
}

if ($cliente_correo){
	echo '<script type="text/javascript" src="javascript/cliente_correo.js?' . $JSVERSION . '"></script>' . "\n";
}


if ($chat_omnicanal){
	echo '<script type="text/javascript" src="javascript/chat_omnicanal.js?' . $JSVERSION . '"></script>' . "\n";
}


if ($ws24){
	echo '<script type="text/javascript" src="javascript/ws24.js?' . $JSVERSION . '"></script>' . "\n";
}


if ($cliente_pbx){
	echo '<script type="text/javascript" src="javascript/cliente_pbx.js?' . $JSVERSION . '"></script>' . "\n";
}



?>


<style>
.phone24{
	position: absolute;
	width: 350px;
	height: 250px;
	bottom: 10px;
	right: 10px;
	border-radius : 15px;
	display: none;
}

.phone24_numero{
	border-radius : 4px;
	width :100%;
	height : 25px;
}

.close_phone{
	width  : 100%;
	height : 30px;
	cursor: pointer;
	font-size: 16px;
	right : 20px;
	text-align: right;
}

.llamada_entrante{
	display: none;
	
	
}

.phone24_config{
	display: none;	
	
}

.phone24_titulo{
	display: flex;
	xbackground-color: green;
}


</style>

<div class="phone24 has-background-link-dark">
	
	<div class="phone24_titulo mx-4 mt-2 has-text-white">
		<div class="mt-2">Phone24</div>
		<div class="close_phone has-text-white mt-2 " onclick="$('.phone24').hide();"><i class="fa-solid fa-circle-xmark"></i></div>
	</div>

	<div class="llamada_saliente">
		<div class="iaxNumero mx-4" ><input id="iaxNumero" class="phone24_numero" id="numero" autocomplete="off" ></div>
		<div class="mx-4 mt-2 buttons">
			<button id="btnCall" 		onclick="Call()" 			class="button is-small has-text-primary"><i class="fa-solid fa-phone"></i></button>
			<button id="btnTerminate"   onclick="TerminateCall()"   class="button is-small has-text-danger"><i class="fa-solid fa-phone-slash"></i></button>
			
			<button class="button is-small"><i class="fa-solid fa-hand"></i></button>
			<button class="button is-small"><i class="fa-solid fa-arrow-right-arrow-left"></i></button>
			<button class="button is-small" onclick="phone24_config();"><i class="fa-solid fa-gear"></i></button>
		</div>	
	</div>		

	<div class="llamada_entrante">
		<div class="iaxNumero px-4" ><input id="iaxLlamante" class="phone24_numero" id="numero" autocomplete="off" ></div>
		<div class="mx-4 pt-2 buttons is-small">
			<button id="btnAcept"  class="button is-small" onclick="AceptCall(this)" ><i class="fa-solid fa-phone-volume"></i> </button>
			<button id="btnReject" class="button is-small" onclick="RejectCall(this)"><i class="fa-solid fa-phone-slash"></i></button>
		</div>
	</div>

	<div class="phone24_config has-text-white px-0 py-2">
		<div class="mx-4">Configuración</div>	
		<div class="mx-4 pt-2 buttons is-small">
			Entrada
			<select id="phone24_INPUT" class="mb-1" style="width:100%" onchange="setAudioDevice( this );">
			</select>

			Salida
			<select id="phone24_OUTPUT" class="mb-1" style="width:100%" onchange="setAudioDevice( this );">
			</select>

			Ring
			<select id="phone24_RING" class="mb-1" style="width:100%" onchange="setAudioDevice( this );">
			</select>
			
		</div>
	</div>

	<div >
		<div id="textStatus"  class="has-text-white px-4 py-2">No Registrado</div>	
		<div id="textConsole" class="has-text-white px-4 py-2"></div>	
	</div>
</div>





</body>
</html>