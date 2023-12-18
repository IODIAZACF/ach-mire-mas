var tituloAlert = 'Mecanismo Intersectorial de Respuesta en Emergencias';
var str = document.URL;
var url = str.split("/");

var db = "ach";

var server_path = url[0] + "//" + url[2] + "/" + db + "/";

/* PUSH */
var str = document.URL;
var tmp = str.split("/");
var url = tmp[2].split(":");
/*
xid_push = 'chat';
var push_path = 'http://' + url[0] + ':8887/subscribe?events=' + xid_push;

var es = new EventSource(push_path);

es.addEventListener('message', function (e){
  
	var event = JSON.parse(e.data);
	console.log(event);

	var registro = event.data;
	
	var actual = $("#IDENTIFICADOR").val();
	var nueva  = registro.IDENTIFICADOR;
	
	if(actual==nueva) {
		insertChat(registro);
	}
	

});

*/
var r_M_CLIENTES;
var r_D_CLIENTES;

var r_D_TIPO_CARGA;

var r_M_PRODUCTOS;

var llave;
var xauth;
var xuid;
var ID_M_GRUPOS;

var ID_M_USUARIOS;
var NOMBRE_USUARIO;

var ID_M_PROFESIONALES;
var NOMBRE_PROFESIONAL;

var ID_M_CLIENTES;
var ID_D_CLIENTES;


// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app  = new Framework7({
	root: '#app', // App root element
	id: 'io.framework7.testapp', // App bundle ID
	name: 'Framework7', // App name
	theme: 'md', // Automatic theme detection // md / ios / auto
	// App routes
	routes: routes,
	cache: false,
	

});


var block = '';

var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

if(getMobileOperatingSystem()=='iOS' && window.matchMedia('(display-mode: standalone)').matches ==false){
	block = 'pages/instrucciones_iphone.html';
}

if(getMobileOperatingSystem()=='iOS' && navigator.standalone==false){
	block = 'pages/instrucciones_iphone.html';
}

if(getMobileOperatingSystem()=='Android' && window.matchMedia('(display-mode: standalone)').matches ==false){
	console.log(is_chrome);	

	if(!is_chrome) {
		app.dialog.alert('No se han cargado los datos Locales', tituloAlert);
	}
	
	block = 'pages/instrucciones_android.html';
}

if(block!=''){
	var rnd  = Math.floor((Math.random() * 100000000) + 1);			
	app.request.get(block + '?' + rnd, function (data) {
		$$('#form-popup').html(data);
		app.popup.open('#form-popup');

		if(!is_chrome){
			$$("#NOTA").html('Debes abrir el link desde Google Chrome..!!');
			
		}
		
		
	});
	
	
}	


var mainView = app.views.create('#view-main', {
  url: '/login/'
});

$$(document).on('DOMContentLoaded', function(){




});

function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

      // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return "unknown";
}


