<?php
header("content-type: text/html; charset=utf-8");

/*
if(isset($_REQUEST['cerrar'])){
	$salir = '$(document).ready(function() {' . "\n";
    $salir.= ' top.doUnload(true);' . "\n";
    $salir.= '    ocultaCarga();' . "\n";
    $salir.= '});' . "\n";
}



if(!isset ( $_REQUEST['db'] )){

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
*/

include_once (RUTA_HERRAMIENTAS . '/herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/movil/class/class_movil_modulo.php');


encabezado('Acceso a Sistema');
javascript('jquery,movil');

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";


//$img_fondo = WWW_PATH .'/imagenes/fondo_movil_'. $tdb . '.jpg';

//echo '<img id="xfondo" src="'. $img_fondo  .'" style="position:absolute; display:none; left:0; top:0;">';


cargando();




?>

<link rel="stylesheet" href="../../herramientas/estilo/onsenui/onsenui.min.css">
<link rel="stylesheet" href="../../herramientas/estilo/onsenui/onsen-css-components.min.css">
<link rel="stylesheet" href="../../herramientas/estilo/onsenui/estilo.css">


<ons-navigator id="app" swipeable swipe-target-width="80px">
	<ons-page>
		<ons-splitter id="appSplitter">
			<!--ons-splitter-side id="sidemenu" page="sidemenu.html" swipeable side="right" collapse="" width="260px"></ons-splitter-side-->
			<ons-splitter-content page="login.html"></ons-splitter-content>
		</ons-splitter>
	</onsp-page>
</ons-navigator>



<template id="login.html">
	<ons-page id="login">
		<ons-toolbar>
			<div class="center">Acceso al sistema</div>
		</ons-toolbar>

		
		<ons-list class="fondo_formulario" style="padding: 30px 30px 30px 30px; background-color:transparent; ">
			<ons-list-item class="input-items">
				<label class="center">
					<span class="input_editable_label">Login</span>
					<input id="user" class="input_editable" type="text" placeholder="" autocomplete="off">
				</label>
			</ons-list-item>
      
			<ons-list-item class="input-items">
				<label class="center">
					<span class="input_editable_label">Password</span>
					<input id="password" class="input_editable" type="password" placeholder="" autocomplete="off">
				</label>
			</ons-list-item>
			
			<ons-list-item class="input-items">
				<ons-button onclick="login()">
					<i class="fa fa-sign-in fa-1x main-image"></i>
					&nbsp;Ingresar
				</ons-button>				
			</ons-list-item>
			
			<ons-card >
				<center><img src=""  style="width: 250px"></center>
			</ons-card>
			
		</ons-list>
	
	
	</ons-page>
</template>


<script type="text/javascript">
/*
var im = document.getElementById('xfondo');

if(im){
	im.style.width=document.body.offsetWidth;
	im.style.height=document.body.offsetHeight;
	im.style.display='block';
}
*/

//$("#login .page__background").css('background-image', 'url("../../imagenes/fondo_movil.jpg")');





function login(){
	
	console.log('aquiiiii');
	console.log(db);
	
	var url = server_path +  'herramientas/password/seguridad.php';
	
	var params = '';
	params += 'login='		+ $('#user').val();
	params += '&password='	+ $('#password').val();
	//params += '&db='		+ db;
	
	console.log(url + '?' + params);

	enviar2( url, params, 'post', function(data){
		
		console.log(data);
		parser = new DOMParser();
		xmlDoc = parser.parseFromString(data, "text/xml");
		console.log(xmlDoc);
		var mensaje = xmlDoc.getElementsByTagName("MENSAJE")[0].childNodes[0].nodeValue;
		if( mensaje == 'ok'){
			
			
		} else {
			
			alert(mensaje);
		}
		
		//document.getElementById('app').pushPage('menu_principal.html', { data: { title: 'xxxx' } });
	});
	
}

	
/*	if (!registro.length)
{
	alert('Error al buscar la informacion');
	var obj = document.getElementById(login.name + '_login');
	obj.select();
	obj.focus();
	return false;
}

var mensaje = '';
var result = true;

if (registro[0]['MENSAJE']) mensaje = registro[0]['MENSAJE'];

if (mensaje && (mensaje != 'OK'))
{
	//edson
	$("#mensaje").html(mensaje);
	
	setTimeout(function(){
		$("#mensaje").html('');
		$("#" + loginName + '_xbusca' ).val('');	
	},3000);
	
	var obj = document.getElementById(login.name + '_login');
	obj.select();
	obj.focus();
	result = false;
}
	else
{
	result = true;
}


if (login.callFunc) login.callFunc(true);
if (registro[0]['URL']) document.location.href = registro[0]['URL'];
*/
//





var app;
var page;

$( document ).ready(function() {
	console.log( "ready!" );
	
	app = document.getElementById('app');
	
	$("#login .page__background").css('background-image', 'url("../../imagenes/fondo_movil.jpg")');
});



document.addEventListener('init', function(event) {
	page = event.target;
	console.log(page.id);
});




</script>
 
</body>




