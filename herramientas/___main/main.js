/*
$(document).on('DOMSubtreeModified', '.rvNotification', function ( x ) {
	$(".rvButtonAllow").click();
	console.log('salioooo el cartel');
});
*/

var atheos;
var zoom = 100; 	
var soporte;


/* PUSH PARA EVENTOS GENERALES */

var str = document.URL;
var tmp = str.split("/");

var xid_push = 'main';
var push_path = tmp[0] + '/'+  tmp[1] + '/' + tmp[2] +'/' + '/push/subscribe?events=' + xid_push;
var es = new EventSource(push_path);

es.addEventListener('message', function (e){

	var registro = JSON.parse(e.data);
	if ( debug() ) console.log(registro);
	
	//if( registro.db == xdb_sistema){
		var callback = registro.message.comando;
		if( callback ){ 
			console.log('recibido un comando ejecutando..!');
			eval (callback);
		}
	//}
	
});

es.addEventListener('open', function (e){
	console.log('conectado al servidor push..!  canal= ' + xid_push  );
});

es.addEventListener('error', function (e){
	console.error('error en conexion al servidor push..!');
});

menu = new hmenu('menu');
menu.url = server_path + 'herramientas/hmenu2/genera_menu.php';
menu.width = 300;
menu.left = 0;
menu.top  = 0;
menu.init();
menu.show();

var _cprt = new submodal();
_cprt.nombre="_cprt";
_cprt.ancho       = 80;
_cprt.alto        = 50;
_cprt.titulo      = ' ';
_cprt.botonCerrar = true;
_cprt.onClose = function(){
	_cprt.iframe.src="about:blank";
	_cprt.ocultar();
}
_cprt.inicializa();




$(document).ready(function(){
	
	if(video_conferencia) 	$("#video_conferencia").show();
	if(cliente_correo) 		$("#cliente_correo").show();
	if(chat_omnicanal) 		$("#chat_omnicanal").show();
	if(cliente_pbx) 		$("#cliente_pbx").show();

	var xHTML = '';	

	xHTML+= '	<table style="margin-left: auto;">';
	xHTML+= '		<tr class="menu_superior">';
	xHTML+= '			<td class="menu_inactivo" style="font-size:15px;"><i class="fa-solid fa-user" ></i></td>';
	xHTML+= '			<td class="menu_inactivo" style="padding-left: 8px; padding-right: 20px;">' + nombre_usuario + '</td>';
	xHTML+= '			<td class="menu_inactivo" style="font-size:15px; cursor: pointer;" onclick="exit();"><i class="fa-solid fa-power-off"></i></td>';
	xHTML+= '			<td class="menu_inactivo" style="font-size:15px; cursor: pointer;"" onclick="recargar_modulo();"><i class="fa-solid fa-arrows-rotate"></i></td>';
	
	if(id_m_grupo_usuario=='XXXXGRUP0011') {
		xHTML+= '			<td class="menu_inactivo" style="font-size:15px; cursor: pointer;"" onclick="Editor();"><i class="fa-solid fa-pen-to-square"></i></td>';
	}

	xHTML+= '			<td class="menu_inactivo"><input id="botonDebug" type="checkbox" onclick="activaDebug(this);" ></td>';
	xHTML+= '			<td class="" id="sidenav-reserva"></td>';
	xHTML+= '		</tr>';
	xHTML+= '	</table>';


	xHTML+= '<div class="submenu_fondo" id="menu_utilidades" style="display: none;">';
	xHTML+= '</div>';
	
	
	var position = $('.menu_fondo').offset();
	$(".menu_fondo").append(xHTML);
	 
	$('#menu_utilidades').css({
		position: 'absolute',
		top: '33px',
		right: '5px'
	});
	
	$("#menu_utilidades").on('mouseleave', function(){
		$(this).hide();
	});
	
	$('body#main').css('background-image', 'url(' + server_path + 'imagenes/fondo_'+  xdb_sistema +'.jpg)');
	
	var margen_menu =  $(".menu_fondo").outerHeight();
	var sidenav_width = $("#BARRA_LATERAL").width();
	
	$("#BARRA_LATERAL").css('display', 'block');
	$("#BARRA_LATERAL").css('height', margen_menu-4 + 'px');
	//$("#BARRA_LATERAL").css('min-height', margen_menu - 6 + 'px');
	$("#sidenav-reserva").width( sidenav_width + 'px' );

	$("#proceso").css('padding-top', margen_menu + 'px');

	$("#BARRA_LATERAL").mouseover(function(){
		$(this).css('height','100%');
	});

	$("#BARRA_LATERAL").mouseout(function(){
		$(this).css('height', margen_menu - 4 +'px' );
	});

	$("#titulo_debug").click(function(){
		$('#ObjDebugO24').html(''); 
		console.log('limpiando la consola del sistema');
	});

	$("#DEBUG").mouseover(function(){
		$(this).css('height','50%');
	});

	$("#DEBUG").mouseout(function(){
		$(this).css('height', '15px' );
	});
	
	var alto_menu =  $(".menu_fondo").outerHeight();
	$("#sub_container__cprt").css('padding-top', alto_menu + 'px');
	$("#sub_container__cprt").css('zIndex', '0');
	
	menu.setFocus();
	
	$("#proceso").attr('src', '/' + sistema + '/main/inicio.php?x=1');
				
	$("#zoom_level").html(zoom + '%');
	
	soporte = new WinBox("Soporte Remoto", {
		id: "soporte_remoto",
		border: "2px",
		top: alto_menu + 1,
		left: 1,
		right: 1,
		buttom: 1, 
		max: true,
		hidden: true
	});
	
});


function exit(){
	console.log('Salir del Sistema'); 
	var redirect = encodeURI( server_path  + 'herramientas/password/password.php?db=' + db );
	$(location).attr('href', server_path + 'herramientas/password/logout.php?redirect=' + redirect);	
	
}


function tecla_ayuda(objeto, tecla)
{
    if(tecla==27) v_ayuda.ocultar();
}

function ocultar_cprt(){
	_cprt.ocultar();
}


function imprimir(url,tipo)
{
    var x = printApplet.getPrinters();
    var defaultPrinter = printApplet.getDefaultPrinter();
    var state = printApplet.selectPrinter(defaultPrinter);

    if (state != 0){
		alert("No hay impresoras intaladas");
		return;
    }

    if(!tipo) var tipo = 1;

	printApplet.setDocumentType(tipo);
	printApplet.setPaperSize(1);
	printApplet.setQuality(0);
    printApplet.setOrientation(0);

	var ncopies = 1;

    state = printApplet.print(url, ncopies, 'Impresion de Documento');

        if (state != 0)
        {
        alert("PRINT ERROR");
        alert(url);
        alert(ncopies);
        alert(tipo);

        return false;
        }
        else return true;

}

function showPrint(url, tit){
	if (!_cprt.contenedor) {
		_cprt.inicializa();
	}
	_cprt.iframe.src=url;
	_cprt.mostrar();
	_cprt.setTitle(tit);
}

function recargar_modulo(){
	var rnd  = Math.floor((Math.random() * 100000000) + 1);			
	var url = $('#proceso').attr('src') + '&' + rnd; 
	$('#proceso').attr('src',  url );
	
}


function activaDebug(oDebug)
{
	var objD = $('#botonDebug');
	top.debugVar  = oDebug.checked;
	if(debug()){
		$("#DEBUG").show();
		
	} else {
		$("#DEBUG").hide();
		
	}
	
	
}


function mostrar_debug(){
	
	//console.log('mostrar_debug');
  
	var objD = $('#ObjDebugO24');
	
	var objVerDebug = $('#ObjRevisarDebugO24');
	$(objVerDebug).html('debug');
	
	$(".DEBUG").css('height','100%');
	
	/*
	var w = window.open("about:blank","DEBUG");
		
	if(w){
		w.document.open();
		//w.document.writeln("<PRE>");
		w.document.writeln(  $(objD).html() );
		//w.document.writeln("</PRE>");
		w.document.close();
	}
	*/
}


function mostrar_info(){
	var xHTML = "";
	xHTML+= '<table border="0" width="350px;">';

	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-regular fa-user fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">Usuario</div><b>' + nombre_usuario + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>';

	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-solid fa-database fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">Sistema</div><b>' + sistema + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>';

	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-solid fa-user-group fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">Grupo</div><b>' + grupo + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>';

	
	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-solid fa-network-wired fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">Ip</div><b>' + ip + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>';


	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-regular fa-building fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">Empresa</div><b>' + empresa + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>'; 

	xHTML += '	<tr class="submenu_inactivo">';
	xHTML += '		<td style="text-align:center;"><i class="fa-solid fa-barcode fa-lg" style="color: var(--color-iconos-menu)"></i></td>';
	xHTML += '		<td nowrap><div style="width: 150px;">RUC</div><b>' + rif + ' </b></td>';
	xHTML += '		<td></td>';
	xHTML += '	</tr>';
	xHTML += '    <tr>';
	xHTML += '        <td class="submenu_separador" colspan="4"></td>';
	xHTML += '    </tr>'; 

	xHTML += '</table>';

	$('#menu_utilidades').html(xHTML);
	$('#menu_utilidades').show();
	
}


function hablar( texto ){
	
	//responsiveVoice.allowSpeechClicked(true);

	responsiveVoice.setDefaultVoice("Spanish Latin American Male");
	responsiveVoice.speak(texto);
		
	/*
	var synth = window.speechSynthesis;
	var utterThis = new SpeechSynthesisUtterance('Hola. como estas?, esta es una prueba de texto hablado para Luis');

	utterThis.lang = 'es-ES';
	synth.speak(utterThis);
	*/	
	
}

function zoom( valor ){
	
	//var zoom = proceso.document.body.style.zoom;
	if(zoom == '') zoom = '100';	
	zoom = parseFloat( zoom );
	console.log(zoom);
	
	if( valor == -1 ){
		proceso.document.body.style.zoom = (zoom - 5) + '%';
		//document.body.style.zoom="75%"
	}	
		
	if( valor == 1 ){
		proceso.document.body.style.zoom = (zoom + 5) + '%';
		
	}
	
	$("#zoom_level").html(zoom);
	
}


function soporte_remoto(){

	var url  = server_path + 'herramientas/meshcentral/meshctrl.php';
	var params = 'comando=ListDevices';
	console.log(url + '?' + params);
	var json = enviar(url, params, 'GET');
		json = JSON.parse(json);

	console.log(json);
	var xHTML  = '<div class="table-container">';
		xHTML += '<table class="table">';

	$.each(json, function( recno, data ){
		console.log('Buscando equipos del grupo -> ' + sistema);		
		if( data.groupname == sistema){
			console.log( '' + data.rname);
			xHTML += '<tr>';
			xHTML += '	<td class="nodo" data-nodo="' + data._id + '">' + data.rname + '</td>';
			xHTML += '</tr>';
		}
		
		console.log('Buscando equipos del grupo -> soporte_dinamico');		
		if( data.groupname == 'soporte_dinamico'){
			console.log( 'equipo ->' + data.rname);
			console.log( data.sessions );
			xHTML += '<tr>';
			xHTML += '	<td class="nodo" data-nodo="' + data._id + '">' + data.rname + '</td>';
			xHTML += '</tr>';
		}
		
		//console.log(data.sessions);
		//console.log(data.groupname);
		
	});
	
	
  
	xHTML += '</table>';
	xHTML += '</div>';
	soporte.body.innerHTML = xHTML;
	
	$('.nodo').on('click', function(){

		soporte.body.innerHTML = '<iframe id="iframe_soporte"></iframe>';

		var nodo = $(this).data('nodo').replace('node//','');
		var urlmesh = 'https://soporte.sistemas24.com/?viewmode=11&hide=31&user=edson&pass=424639&node=';
		//var nodo 	= 'RzNW8NoCIDB1a6ur6NhFsmp7eBCStU$wVDrqw9lRe3LGVYUmLUb3ggUogHsiD6WO';

		console.log(urlmesh + nodo );
		soporte.setUrl(urlmesh + nodo, function(){ 
			console.log('cargado el meshcentral');
		});

	});

	soporte.show();
	
	//window.open(urlmesh + nodo ,'prueba');
	//_soporte.iframe.src= urlmesh + nodo;
	//_soporte.mostrar();
}