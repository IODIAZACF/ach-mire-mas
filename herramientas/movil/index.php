<?php
header('Content-Type: text/html; charset=utf-8');

include ("../../config.php");

include_once (Server_Path . 'herramientas/utiles/comun.php');
//include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
//include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

encabezado('Editor Móvil');

echo '<body style="background-color: #fff !important" onload="ocultaCarga();" >' . "\n";

//$modulo = new class_movil_modulo();

cargando();

javascript('jquery');

?>

<script type="text/javascript" src="../js/utiles.js"></script>


<link rel="stylesheet" type="text/css"  href="index.css">

<table width="100%" border="0" >
	<tr>
		<td id="MODULO_RESUMEN" >
			<div id="controls" class="box p-5 m-5" style="width: 300px;">
				<div>
					<span class="label_form" for="iframeURL">URL:</label>
					<input type="text" id="iframeURL" placeholder="" />
				</div>
				<div>
					<label class="label_form" for="iframeURL">emulador:</label>
					<select id="tipo_emulador" onchange="refresh( this.value );">
						<option value="375,812" >Iphone X</option>
						<option value="820,1180">Ipad Air</option>
						<option value="360,740" >Samsung Galaxy S8</option>
						<option value="768,1024">Ipad Mini</option>
					</select>
				</div>

				<div>
					<label for="origen">Origen:</label>
					<select id="origen" onchange="carga_secciones( this.value );">
					</select>
				</div>
				<div>
					<label for="seccion">Seccion:</label>
					<select id="seccion" onchange="carga_formulario( this.value );">
					</select>
				</div>
				<div>
					<button onclick="cargar_inis();">Cargar Inis</button>
				</div>
				<div>
					<button onclick="compilar_modulo();">Compilar Modulo</button>
				</div>
			</div>

		</td>
		<td> 
		</td>
	</tr>
</table>

<div class="phone view" id="phone_1">
	<iframe id="emulador" src=""></iframe>
</div>


<div class="botones">
	<div class="recargar" onclick="recargar_modulo();">
		<div class="icon"><i class="fa-solid fa-arrows-rotate fa-1x"></i></div>
	</div>
	<div class="editar" onclick="javascript:Editor(this);">
		<div class="icon"><i class="fa-solid fa-pen-to-square fa-1x"></i></div>
	</div>
</div>



<!--Controls etc.-->

<script type="text/javascript">

var inis = [];
var ini;
var modulo;

$( document ).ready(function() {
	refresh('375,812');

	$(".editor-close").click(function(){
		$(".EDITOR").hide();
	})
	$("#emulador").attr('src', server_path + 'movil/herramientas/main/index.html?id_m_usuario=' + id_m_usuario );
	
	setInterval( function(){ 
		cargar_inis();
	}, 1000);

});




function cargar_inis(){
	
	if( !document.getElementById("emulador").contentWindow.inis  ) {
		$("#origen" ).empty().append( new Option( '--', '--') );
		$("#seccion").empty().append( new Option( '--', '--') );
		return;
	} 
	
	if(document.getElementById("emulador").contentWindow.inis.length == 0){
		$("#origen" ).empty().append( new Option( '--', '--') );
		$("#seccion").empty().append( new Option( '--', '--') );
		return;
	}
	
	if( inis == document.getElementById("emulador").contentWindow.inis) return;
	
	inis   = document.getElementById("emulador").contentWindow.inis;
	console.log(inis);
	
	modulo = document.getElementById("emulador").contentWindow.modulo;
	modulo = modulo.replace('..','');
	
	
	var xHtml = '';
	$("#origen" ).empty().append( new Option( '--', '--') );
	$("#seccion").empty().append( new Option( '--', '--') );

	$.each(inis, function( archivo, secciones ){
		$("#origen").append( new Option( archivo, archivo) );
	});
	
}


function carga_secciones(origen){

	$("#seccion").empty().append( new Option( '--', '--') );

	ini = inis[origen];
	$.each(inis[origen], function( seccion, valor ){
		var xval = seccion;
		
		if( seccion.substr(0,3) == 'COL' || seccion.substr(0,3) == 'CAM') xval = seccion + ' ' + valor.ROTULO;
		
		
		$("#seccion").append( new Option( xval, seccion) );
		
		
	});
	
}

function carga_formulario(seccion){
	//if( seccion == '--' || seccion = '' ) return;
	
	var origen = $("#origen").val();
	origen = 'movil' +  modulo + 'ini/' + origen;
	
	var fHtml = '';
	var sHtml = '';
	fHtml += '				<tr><td class="is-size-7">ORIGEN</td><td><input name="origen" class="input" type="text" value = "' + origen + '" placeholder="" readonly></td></tr>';
	
	$.each( ini, function(xsec, xvar){
		
		if( xsec ==  seccion ){
			
			seccion  =  xsec;
			sHtml += '				<tr><td class="is-size-7">SECCION</td><td><input name="seccion"   class="input" type="text" value = "' + seccion   + '" placeholder="" readonly></td></tr>';

			$.each( xvar, function(campo, valor){
				if(campo == 'ONOMBRE' || campo == 'COMENTARIO') return;

				fHtml += '			<tr><td class="is-size-7">' + campo + '</td><td><input name="v_' + campo + '"   class="input" type="text" value = "' + valor   + '" placeholder=""></td></tr>';
			});
			
		}
	});

	if(fHtml == '') return;
	
	//if(!tipo) tipo = 'C';

	var xHtml  = '';
		xHtml += '<div id="modal_form" class="modal is-active">';
		xHtml += '	<div class="modal-background"></div>';
		xHtml += '	<div class="modal-card">';
		xHtml += '		<header class="modal-card-head py-3	">';
		xHtml += '			<p class="modal-card-title is-size-6">Editor de Archivo INI</p>';
		xHtml += '			<button class="delete cancel" aria-label="close"></button>';
		xHtml += '		</header>';
		xHtml += '		<section class="modal-card-body">';
		xHtml += '			<form id="ini_form">';
		xHtml += '			<table class="table" width="100%" border="0">';

		xHtml +=  sHtml;
		xHtml +=  fHtml;
		
		xHtml += '			</table>';
		xHtml += '			</form>';
		xHtml += '		</section>';
		xHtml += '		<footer class="modal-card-foot py-3">';
		xHtml += '			<button class="button is-success save">Guardar</button>';
		xHtml += '			<button class="button is-success cancel">Cancel</button>';
		xHtml += '			<button class="button is-success editar_ini">Editar Archivo</button>';
		xHtml += '		</footer>';
		xHtml += '	</div>';
		xHtml += '</div>';			
	
	$("body").append(xHtml);
	
	$(document).on('click', '.cancel' , function(){
		$("#modal_form").remove();
		$('.grid_encab,  [class^="grid_rotulo_pie"], [class^="rotulo_"], .grid_titulo, .grid_status').css('border','none');

	});
	
	$(document).on('click', '.save' , function(event){
		event.stopPropagation();
		var url = server_path + 'herramientas/ini/guardar.php'; 
		var params = $( "#ini_form" ).serialize()
		
		console.log(params);
		
		var x = enviar2(url, params, 'GET', function(){
			$("#modal_form").remove();
		});

	});
	
	$(document).on('click', '.editar_ini' , function(event){
		if(!origen) return;
		
		var src = parent.$("#emulador").attr('src');

		console.log(src);
		
		var path = '/opt/lampp/htdocs/' + src.split('/').slice(3, -2).join('/') + modulo + 'ini';

		var file = path + '/' + origen + '.ini';
		console.log(file);
		
		editFile( file );
		origen = '';
		$("#modal_form").remove();
	});


	
}


function recargar_modulo(){
	var rnd  = Math.floor((Math.random() * 100000000) + 1);			
	var url = $('#emulador').attr('src') + '&' + rnd; 
	$('#emulador').attr('src',  url );
	
}

function refresh( valor ) {
	
	var tmp = valor.split(',');	
	var phone_width  = tmp[0];
	var phone_height  = tmp[1];
	
	var alto = $( window ).height() - 20;
	var ancho  = ( alto / phone_height) * phone_width;

	$(".phone").css('width' , ancho);
	$(".phone").css('height', alto);
	
}


function Editor(oEdit){
	
	var src = $("#emulador").attr('src');
	var modulo =  document.querySelector("#emulador").contentWindow.myNavigator.topPage.attributes['data-modulo'].nodeValue;
	var path = '/opt/lampp/htdocs/' + src.split('/').slice(3, -3).join('/') + '/' + modulo;
	
	top.Editor( path );
	
	/*
	if( path && top.document.querySelector("#editor").contentWindow.atheos.active ){
		
		top.editFile( path + '/index.js' );
	}
	*/
}






</script>



</body>
</html>

