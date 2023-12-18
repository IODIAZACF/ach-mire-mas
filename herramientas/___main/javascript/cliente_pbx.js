console.log('Incianlizando libreria IAX ..!');


$( document ).ready(function() {
    //console.log( "ready!" );
	user_pbx();	
});

var estatus_call;

function user_pbx(){
	if( !ws24_local ) return;

	var url      = server_path + 'herramientas/genera_json/genera_json.php';
	var params   = '';
		params  += 'tabla=M_USUARIOS';
		params  += '&campos=PBX_HOST, PBX_EXTENSION, PBX_PASSWORD';
		params  += '&filtro=ID_M_USUARIO';
		params  += '&xfiltro=' + id_m_usuario;
	
	var json      = enviar(url, params, 'POST');
	//var registro = valida_xml(xml, 'ID_M_USUARIO');
	console.log(url + '?' + params);
	
	console.log(json);
	
	if( registro[0]['PBX_HOST'] && registro[0]['PBX_EXTENSION'] && registro[0]['PBX_PASSWORD'] ){
		connect_ws();
		setTimeout(function(){
				register_pbx( registro[0].PBX_HOST, registro[0].PBX_EXTENSION, registro[0].PBX_PASSWORD ); 
				console.log('registrando IAX');
			}, 2000); 
			
	} else {
		console.log('Usuario sin extensión telefónica..!');
	}
	
	
}

function register_pbx( host, user, password){
	
	var oJSON = {};	
    oJSON.accion 		= 'IAX.Conect';
	oJSON.extension 	= user;
	oJSON.usuario 		= user;
	oJSON.clave 		= password;
	oJSON.host 			= host;
	if(ws24){
		ws24.send( JSON.stringify(oJSON) );
	}
}


function processIAX(data){
	
	//console.log(data);
	switch(data.event){
		case 'register_status':
			if(data.Registration_reply == '18' || data.Registration_reply == '-1'){
				$('#textStatus').text( 'Registrado' );
			}
			
			if(data.Registration_reply == '6'){
				$('#textStatus').text( 'No Registrado' );
			}
			
			break;

			case 'outgoing_call_ring':
			$('#btnTerminate').show();
			
			$('#textConsole').text( 'llamando.....' );
			break;
			
			
		case 'outgoing_call_acept':
			
			$('#textConsole').text( 'llamada saliente en curso.....' );
		
			break;

		case 'incoming_call_ring':
			$('.phone24').show();
			
			$('.llamada_saliente').hide();
			$('.llamada_entrante').show();
			
			//$('#btnAcept').data('numero', data.CallNo);
			//$('#btnReject').data('numero', data.CallNo);
			
			$('#textConsole').text( 'llamada entrante: ' + data.sRemoteName );			
			
			break;
		
		case 'incoming_call_acept':
			$('.llamada_entrante').hide();
			$('.llamada_saliente').show();
			$('#btnTerminate').show();
			
			$('#textConsole').text( 'llamada en curso ' + data.sRemoteName );
			
			break;

		case 'outgoing_or_incoming_call_hang_up':
			$('.llamada_saliente').show();
			
			$('.llamada_entrante').hide();			
			//$('#btnTerminate').hide();
			
			$('#textConsole').text( 'llamada Finalizada' );
			setTimeout(function(){
				$('#textConsole').text( '' );
			}, 3000);
			
			break;

		case 'outgoing_call_hang_reject':
			
			$('#textConsole').text( 'llamada rechazada' );
			break;
		
		case 'audiodevice':
			//console.log(data.registro);
			var tipo = '';
			$.each( data.registro, function(xreg, b){
				$("#phone24_" + b.tipo ).append(new Option( b.nombre,  b.nombre ));
				localStorage.setItem("phone24_" + b.tipo , b.nombre);
				console.log(b.nombre);
			});
			
			$('#phone24_INPUT option:last-child').attr('selected','true');
			$('#phone24_OUTPUT option:last-child').attr('selected','true');
			$('#phone24_RING option:last-child').attr('selected','true');
			
		break;
		
		default:
			$('#textConsole').text( data.event );
		break;
	}

}



function mostrar_webphone(){
	$(".phone24").show();
}

 
function Call(){
	var numero  = $('#iaxNumero').val();
	var oJSON = {};
    oJSON.accion 		= 'IAX.Call';	
	oJSON.numero 		= numero;
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
		estatus_call = true;
	}
}

function AceptCall(obj){

	var oJSON = {};
    oJSON.accion 		= 'IAX.Acept';	
	oJSON.numero 		= $(obj).data('numero');
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
}

function RejectCall(obj){

	var oJSON = {};
    oJSON.accion 		= 'IAX.Reject';	
	oJSON.numero 		= $(obj).data('numero');
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
}

function TerminateCall(){
	var oJSON = {};
    oJSON.accion 		= 'IAX.TerminateCall';
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
}


function GetAudioDevice(){
	var oJSON = {};
    oJSON.accion 		= 'IAX.GetAudioDevice';
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
}

function phone24_config() {
	$(".phone24_config").show();
	GetAudioDevice();
	
	$(".llamada_entrante").hide();
	$(".llamada_saliente").hide();
	
	
}

function setAudioDevice( obj ){
	
	console.log(obj);
	
	localStorage.setItem( obj.id , obj.value);
	
	var oJSON = {};
    oJSON.accion 		= 'IAX.SetAudioDevice';	
	oJSON.INPUT 		= localStorage.getItem("phone24_INPUT");
	oJSON.OUTPUT 		= localStorage.getItem("phone24_OUTPUT");
	oJSON.RING 			= localStorage.getItem("phone24_RING");
	
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
		
}

function SendMessage(){
	var oJSON = {};
    oJSON.accion 	= 'SCAN.Doc';
	oJSON.url 		= 'https://desarrollo.sistemas24.com/protecseguros/herramientas/uploader/php/receive.php';
	oJSON.folder 	= 'documentos/scanner/0016';
	oJSON.nombre	= 'prueba';
    oJSON.usuarioID = '0016';
	
	oJSON.usuarioName = 'LUIS NUÑEZ';
	if(ws24){
		ws24.send(JSON.stringify(oJSON));
	}
}
