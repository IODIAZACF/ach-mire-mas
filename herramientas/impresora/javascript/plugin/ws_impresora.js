console.log('ws24Printer') ;
var ws24PrinterAct  = null ;
var ws24PrinterObj  = null ;

class ws24Printer{
    constructor(){
		
        var me	=	this;		
		this.host		= 'wss://localhost:7780/';
		
		this.onMessage 	= null;
		this.onOpen		= null;
		this.onClose	= null;
		this.onError	= null;
		
		this.accion 	= 'HKA.Print';
		this.url_Report	= server_path;
		this.origen		= '';
		this.tipo 		= 'HKA1';
		this.puerto		= '1';
		this.comando	= '';

        this.values={};
        this.params={};

		ws24PrinterAct = this;
		
		this.connect();
		
    }
	
	connect(){	

		ws24PrinterObj = new WebSocket( this.host );
		ws24PrinterObj.onopen 		= _onOpen;
		ws24PrinterObj.onmessage 	= _onMessage;
		ws24PrinterObj.onclose 		= _onClose;
		ws24PrinterObj.onerror  	= _onError;
		
	}
    setParam(name, value){
        if ((typeof value==="undefined" || value === null) && this.params[name]) {
            delete this.params[name];
        }
        else this.params[name]=value;		
    }

    print() {
		console.log( this.params );
		
		var params=[];
		
        for (var i in this.params){
            params.push(i+'='+this.params[i]);
        }
		
		params.push('db=' + db );
		params.push('id_m_usuario=' + id_m_usuario );			
	
		var oJSON = {};
		oJSON.accion 	= this.accion; 
		oJSON.url 		= this.url_Report + this.origen;
		oJSON.param 	= params.join('&');
		oJSON.tipo 		= this.tipo;
		oJSON.puerto	= 'COM' + this.puerto;
		
		console.log( JSON.stringify(oJSON) );
		if(ws24PrinterObj){
			ws24PrinterObj.send(JSON.stringify(oJSON));
		}
    }
	
    sendCmd() {
		console.log( this.params );
		
		var params=[];
		
        for (var i in this.params){
            params.push(i+'='+this.params[i]);
        }
		
		params.push('db=' + db );
		params.push('id_m_usuario=' + id_m_usuario );			
	
		var oJSON = {};
		oJSON.accion 	= this.accion; 
		oJSON.url 		= this.url_Report + this.origen;
		oJSON.param 	= params.join('&');
		oJSON.tipo 		= this.tipo;
		oJSON.puerto	= 'COM' + this.puerto;
		oJSON.comando	= this.comando;
		
		console.log( JSON.stringify(oJSON) );
		if(ws24PrinterObj){
			ws24PrinterObj.send(JSON.stringify(oJSON));
		}
    }
	
}

function _onOpen(msg){
	console.log('ws_Open', msg);
}

function _onMessage(msg){

	var data = JSON.parse ( msg.data ) ;
	
	console.log( data );
	switch( data.modulo ){
		case 'ws':
		case 'HKA':
			if( ws24PrinterAct.onMessage){
				ws24PrinterAct.onMessage ( data );
			}
		break;
	}
}


function _onClose(msg){
	console.log('ws_Close', msg);
	setTimeout(function() {
		ws24PrinterAct.connect();
		}, 3000);	
}

function _onError(msg){
	console.log('ws_Error', msg);
}

