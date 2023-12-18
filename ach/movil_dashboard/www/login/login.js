
var xrnd  = Math.floor((Math.random() * 100000000) + 1);

$('#db').val(db);

//$(".logo").attr('src', '_logo_reporte.png?' + xrnd);

$('.login-screen-content [id="login"]').val( localStorage.getItem("login") );
$('.login-screen-content [id="password"]').val( localStorage.getItem("password") );

$('.login-screen-content input[type="checkbox"]').on('change', function(x){
	// console.log(this.checked);
	if(this.checked) localStorage.setItem("recordar", 'on')
	else localStorage.setItem("recordar", 'off')

});

if( localStorage.getItem("recordar")== 'on' ) $("#recordar").prop("checked", true);

$('.login-screen-content .login-button').on('click', function () {

	if(valida_login()){
		if(valida_password()){
			login();
		}
	}



});


function valida_login(){

	if($('.login-screen-content [id="login"]').val()==''){

	  app.dialog.alert('Ingrese un nombre de usuario', tituloAlert,function () {
		$('#login').focus();
	  });
	  return false;

	}
	return true;

}

function valida_password(){

	if($('.login-screen-content [id="password"]').val()=='') {
		app.dialog.alert('Ingrese una Clave valida', tituloAlert,function () {
			$('#password').focus();
		});
		return false;
	}
	return true;
}

function login(){

	var xrnd  = Math.floor((Math.random() * 100000000) + 1);
	var url = server_path + 'movil_acceso/seguridad.php?';
	var params = 'rnd=' + xrnd + '&login='+ $('#login').val() +'&password='+ $('#password').val() +'&db='+db;
	//prompt('', url + '?' + params);
	
	// app.preloader.show();

    enviar2(url, params, 'post', function(respuesta){
		
		console.log(respuesta);
		if(respuesta.tabla.registro.MENSAJE == "OK"){
				
			ID_M_USUARIOS = respuesta.tabla.registro.UID;
			NOMBRE_USUARIO = respuesta.tabla.registro.NOMBRE_USUARIO;
			NOMBRE_PROFESIONAL = respuesta.tabla.registro.NOMBRE_PROFESIONAL;
			ID_M_PROFESIONALES = respuesta.tabla.registro.ID_M_PROFESIONALES;
			
			localStorage.setItem("db", db);
			localStorage.setItem("server_path", server_path);
			localStorage.setItem("uid", respuesta.tabla.registro.UID);
			localStorage.setItem("auth", respuesta.tabla.registro.AUTH);

			if( localStorage.getItem("recordar")=='on' ){
				localStorage.setItem("login"   , $('#login').val());
				localStorage.setItem("password", $('#password').val());
			} else {
				localStorage.setItem("login"   , '');
				localStorage.setItem("password", '');
			}

			cargar_pagina('/menu_principal/', function(){
				$('#login').val('');
				$('#password').val('');
			});

			//ChangeUrl('','dashboard/index.html');
			//window.location.replace( server_path + 'movil_dashboard/www/dashboard/index.html');
			
			
		} else {
			
			app.dialog.alert('Login o Password Invalido', tituloAlert,function () {
				$('#login').focus();
			});
			
			
		}
		
	});
	
}

function ChangeUrl(page, url) {
	if (typeof (history.pushState) != "undefined") {
		var obj = { Page: page, Url: url };
		history.pushState(obj, obj.Page, obj.Url);
	} else {
		alert("Browser does not support HTML5.");
	}
}

function t_REGISTRO(obj, tecla){
	
	switch(tecla){

		case 'registro':

			cargar_formulario('login/f_registro.html', '-1', function(estatus){		
				app.popup.open('#form-popup');
			});

			break;
			
		case 'cancelar':
			app.popup.close('#form-popup');

		break;

		case 'guardar':

			if ( $$("#CORREO").val().indexOf("@") < 0 || $$("#CORREO").val().indexOf(".") <0 ){
				
				app.alert('Formato de correo Incorrecto', tituloAlert, function(){
					$$("#CORREO").focus();	
				});
				return false;
			}

			var rnd  = Math.floor((Math.random() * 100000000) + 1);
			var url  = server_path + 'movil/www/login/registro.php';

			var params  = 'x=1';
				params += '&CODIGO1='    + $$("#CODIGO1").val();
				params += '&NOMBRES='    + $$("#NOMBRES").val();
				params += '&CORREO='     + $$("#CORREO").val();
				params += '&CLAVE='      + $$("#CODIGO1").val();
				params += '&rnd=' + rnd;	
		
				prompt('', url + '?' + params);
				
				enviar2(url, params, 'post', function(data){
					//data.tabla.registro;
					//app.preloader.hide();
					var html = '';
					if(data.tabla.ESTATUS=='OK'){
						html+= '<p>Se ha realizado el Registro de manera exitosa</p>';
						html+= '<p>Ahora debe Activar su registro..!</p>';
						html+= '<p>Para realizar la activación haz click aqui</p>';
						html+= '<p>Para realizar la activación haz click aqui</p>';
						
						
						
					}
					
					$('.registro').html(data.tabla.ESTATUS);
					
				});
		break;
		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;

	}
}
