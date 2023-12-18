
//*** jitsi ***//

var Jconference;
var Joptions;

var alto_menu =  $(".menu_fondo").outerHeight();
/*
var conferencia = new WinBox("Titulo", {
    id: "conference_cuadro2",
	border: "2px",
	top: alto_menu
});
*/


var conference = new submodal();
conference.nombre      = "conference";
conference.ancho       = 80;
conference.alto        = 50;
conference.titulo      = ' ';
conference.botonCerrar = true;
conference.usaFrame    = false;
conference.onClose     = function(){
	conference.ocultar();
};

conference.inicializa();
$("#conference_cuadro").attr("data-sala", "");


$("#sub_container_conference").css('padding-top', alto_menu + 'px');
$("#sub_container_conference").css('zIndex', '0');
$("#sub_container_conference .grid_title .grid_cerrar").addClass("grid_minimizar").removeClass("grid_cerrar");


function mostrar_menu_conference(){
	
	var sala_activa = $("#conference_cuadro").data("sala");
	console.log('sala activa=' + sala_activa);
	var url      = server_path + 'herramientas/genera_xml/genera_xml.php';
	var params   = '';
		params  += 'tabla=M_SALAS_CONFERENCIA';
		params  += '&campos=ID_M_SALAS_CONFERENCIA,NOMBRES,COMENTARIOS';
		params  += '&filro=ESTATUS';
		params  += '&xfiltro=ACT';
		
	var xml      = enviar(url, params, 'POST');
	var registro = valida_xml(xml, 'ID_M_SALAS_CONFERENCIA');

	var xHTML = "";

	xHTML+= '  <table cellspacing="0" cellpadding="0">';
	
	$.each( registro, function( index, value ) {
		
		if(sala_activa == registro[index]['NOMBRES']) {
			var xtexto = registro[index]['NOMBRES'].substring(0,1).toUpperCase() + registro[index]['NOMBRES'].substring(1).toLowerCase() + ' (ACTIVA) ';
			var xboton = '<span onclick="colgarconference(\'' + registro[index]['NOMBRES'] + '\')"> X </span>';  
			
		} else {
			var xtexto = registro[index]['NOMBRES'].substring(0,1).toUpperCase() + registro[index]['NOMBRES'].substring(1).toLowerCase();  
			var xboton = '';
		}		
		
		xHTML+= '  <tr style="cursor: pointer;" class="submenu_inactivo" onmouseover="this.className=\'submenu_activo\'" onmouseout="this.className=\'submenu_inactivo\'" >';
		xHTML+= '  	<td></td><td nowrap onclick="mostrar_conference(\'' + registro[index]['NOMBRES'] + '\')" style="width:300px; height:20px;"   >' + xtexto + '</td><td>' + xboton + '</td>';
		xHTML+= '  </tr>';
		xHTML+= '  <tr>';
		xHTML+= '  	<td colspan="10" class="submenu_separador"></td>';
		xHTML+= '  </tr>';
	});
	

	var xboton = '';
	var xtexto = '<b>Sistemas24</b>';
	xnombre  = 'sistemas24';
	xHTML+= '  <tr>';
	xHTML+= '  	<td colspan="10" class="submenu_separador"></td>';
	xHTML+= '  </tr>';
	xHTML+= '  <tr style="cursor: pointer;" class="submenu_inactivo" onmouseover="this.className=\'submenu_activo\'" onmouseout="this.className=\'submenu_inactivo\'" >';
	xHTML+= '  	<td></td><td nowrap onclick="mostrar_conference(\'' + xnombre + '\')" style="width:300px; height:20px;"   >' + xtexto + '</td><td>' + xboton + '</td>';
	xHTML+= '  </tr>';
	xHTML+= '  <tr>';
	xHTML+= '  	<td colspan="10" class="submenu_separador"></td>';
	xHTML+= '  </tr>';
	xHTML+= '  </table>';

	$('#menu_utilidades').html(xHTML);
	$('#menu_utilidades').show();
	
}


function mostrar_conference( xsala ){
	
	xsala = xsala.toLowerCase();
	xprefijo = xdb_sistema; 

	if( xsala.substring(0, 10) == 'sistemas24' ) xprefijo = '';
	
	menu.reset();
	$('#menu_context').hide();
	
	if (!conference.contenedor) {
		conference.inicializa();	
		//conference.mostrar();
		
	}

	var sala_actual = $("#conference_cuadro").data('sala');
	
	console.log('Sala existente :' + sala_actual + ' == Sala nueva :' + xsala);

	
	if( sala_actual == xsala) {
		console.log('Ya existe la sala creada ' + sala_actual + ' == ' + xsala);
		conference.mostrar();
		
	} else {
		
		console.log( 'se crea la conferencia con la nueva sala ->' + xsala);
		$("#conference_cuadro").data("sala", xsala);

		conference.setTitle('Video Conferencia ' + xsala );
		$("#conference_cuadro").html("");
		
		console.log('Iniciando Objeto Video conferencia..!');	
		var domain = 'meet.sistemas24.com';
		Joptions = {
			configOverwrite: { 
				requireDisplayName: false, 
				prejoinPageEnabled: false, 
				LANG_DETECTION: true,
				startWithVideoMuted : false,
				startWithAudioMuted : false,
				readOnlyName: true,
			},
			roomName: xprefijo + '_' + xsala,
			onload : function(){
				/* Evento que se dispara al inicar el iframe de la conferencia */
			},
			parentNode: document.querySelector('#conference_cuadro'),
			userInfo: {
				displayName: nombre_usuario
			}	
		};


		Jconference = new JitsiMeetExternalAPI(domain, Joptions);

		Jconference.addListener('readyToClose', function(x){
			console.clear();
			console.log('cerrada la conferencia');
			
			$("#" + Jconference._frame.id).remove();
			$("#conference_cuadro").data("sala", "");
			conference.ocultar();
		} );

		Jconference.addListener('participantJoined', function(x){
			console.log( x );	
			//var url      = server_path + 'video_conferencias/recibe.php';
			//var params   = JSON.stringify( Jconference.getParticipantsInfo() );
			//var json     = enviar_data(url, params, 'POST');
			//console.log(url + '?' + params);
		} );
		

/*
participantJoined
		
{
    id: string, // the id of the participant
    displayName: string // the display name of the participant
}
*/
		
		setInterval(function(){
			//console.log( Jconference.getParticipantsInfo() );	
			//var url      = server_path + 'video_conferencias/recibe.php';
			//var params   = JSON.stringify( Jconference.getParticipantsInfo() );
			//var json     = enviar_data(url, params, 'POST');
			//console.log(url + '?' + params);

		}, 5000);
		
		
		
		conference.mostrar();
		
	}
	
}

function colgarconference( xsala ){
	conference.setTitle('Video Conferencia ');
	$("#" + Jconference._frame.id).remove();
	$("#conference_cuadro").data("sala", "");
	$('#menu_context').hide();
}


function enviar_data( url, params, xx){

	$.ajax({
		url: url,
		dataType: 'json',
		type: 'post',
		contentType: 'application/json',
		data: params,
		processData: false,
		success: function( data, textStatus, jQxhr ){
			//console.log( data  );
		},
		error: function( jqXhr, textStatus, errorThrown ){
			console.log( errorThrown );
		}
	});	
	
}

