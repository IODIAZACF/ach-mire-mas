
/*
if(ws24_local){
	try{
		connect_ws();
	} catch(e){
		
		//console.log(e);
		
	}
}
*/


var ws24  = null;
	
function connect_ws(){	
	ws24 = new WebSocket( 'wss://localhost:8088' );
	ws24.onmessage  = ws_Message;
	ws24.onopen 	= ws_Open;
	ws24.onclose 	= ws_Close;
	ws24.onerror  	= ws_Error;
}


function ws_Message(msg){
	//console.log('ws_Message', msg);
	
	var data = JSON.parse ( msg.data ) ;
	
	// Muestra todo lo que llegue del web socket
	// console.log( data );
	switch( data.modulo ){
		case 'IAX':
			processIAX( data );
		break;
		case 'SCAN':
			console.log('evento de el scanner',   data );
		break;
		case 'SERIAL':
			console.log('evento de el scanner',   data );
		break;
	}
}


function ws_Open(msg){
	//Evento cuando el web socket se conecta.
	console.log('Web Socket 24 conectado..!');
	//console.log('ws_Open', msg);
}


function ws_Close(msg){
	//console.log('ws_Close', msg);
	setTimeout(function() {
		connect_ws();
	}, 1000);
	
}

function ws_Error(msg){
	//console.log('ws_Error', msg);
}
