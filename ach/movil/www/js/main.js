window.onload = () => {
  'use strict';

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('./sw.js');
  }
}



var tituloAlert = 'Demo24';
var str = document.URL;
var url = str.split("/");

var db = "demo24";
var server_path = url[0] + "//" + url[2] + "/" + db + "/";

var r_M_CHAT;

$("title").text(tituloAlert);

var xauth;
var xuid;
var x_id_m_grupos;
var x_id_d_contactos;
var xnombre_usuario;
var llave;
var xid_m_almacenes = '0011';


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
  cache: false
});

// Init/Create views
var mainView = app.views.create('#view-main', {
  url: '/login/'
});

var formView = app.views.create('#view-form', {
  url: '/blank/'
});

$$(document).on('DOMContentLoaded', function(){
	
});

//valida_dispositivo();


function valida_dispositivo(){
	
	var block = '';

	var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

	if(getMobileOperatingSystem()=='iOS' && window.matchMedia('(display-mode: standalone)').matches ==false){
		block = 'pages/instrucciones_iphone.html';
	}

	if(getMobileOperatingSystem()=='iOS' && navigator.standalone==false){
		block = 'pages/instrucciones_iphone.html';
	}

	if(getMobileOperatingSystem()=='Android' && window.matchMedia('(display-mode: standalone)').matches ==false){
		//console.log(is_chrome);	

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
	
	
}

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
