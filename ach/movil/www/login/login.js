var xrnd  = Math.floor((Math.random() * 100000000) + 1);

$('#db').val(db);


$("#TITULO").html(tituloAlert);
$(".logo").attr('src', server_path + 'imagenes/' + db + '_logo_reporte.jpg?' + xrnd);

$('.login-screen-content [id="login"]').val( localStorage.getItem("login_"+db) );
$('.login-screen-content [id="password"]').val( localStorage.getItem("password_"+db) );

$('.login-screen-content input[type="checkbox"]').on('change', function(x){
	// console.log(this.checked);
	if(this.checked) localStorage.setItem("recordar_"+db, 'on');
	else localStorage.setItem("recordar_" + db, 'off');

});

if( localStorage.getItem("recordar_"+db)== 'on' ) $("#recordar").prop("checked", true);

$('#entrar').on('click', function () {

	if(valida_login()){
		if(valida_password()){
			login();
		}
	}
});

$('#registro').on('click', function () {
	window.location = "registro.html";
});

function valida_login(){

	if($('.login-screen-content [id="login"]').val()==''){

	  app.dialog.alert('Ingrese un nombre de usuario', 'Sistemas24',function () {
		$('#login').focus();
	  });
	  return false;

	}
	return true;

}

function valida_password(){

	if($('.login-screen-content [id="password"]').val()=='') {
		app.dialog.alert('Ingrese un Clave valida', 'Sistemas24',function () {
			$('#password').focus();
		});
		return false;
	}
	return true;
}

function login(){

	var xrnd  = Math.floor((Math.random() * 100000000) + 1);
	var xurl = server_path + 'movil_acceso/seguridad.php?rnd=' + xrnd + '&login='+ $('#login').val() +'&password='+ $('#password').val() +'&db='+db;
	//prompt('', xurl);

	app.preloader.show();

	app.request.get(xurl, {id: xrnd}, function (data)
	{
		var respuesta     = data.substring(data.lastIndexOf("<MENSAJE>")+9,data.lastIndexOf("</MENSAJE>"));
		if(respuesta=='OK'){
			var xhost         = data.substring(data.lastIndexOf("<HOST>")+6,data.lastIndexOf("</HOST>"));
			xsistema          =  data.substring(data.lastIndexOf("<SISTEMA>")+9,data.lastIndexOf("</SISTEMA>"));
			xauth     		  =  data.substring(data.lastIndexOf("<AUTH>")+6,data.lastIndexOf("</AUTH>"));
			xuid      		  =  data.substring(data.lastIndexOf("<UID>")+5,data.lastIndexOf("</UID>"));
			x_id_m_grupos     =  data.substring(data.lastIndexOf("<ID_M_GRUPOS>")+13,data.lastIndexOf("</ID_M_GRUPOS>"));
			x_id_m_vendedores =  data.substring(data.lastIndexOf("<ID_M_VENDEDORES>")+17,data.lastIndexOf("</ID_M_VENDEDORES>"));
			xnombre_usuario   =  data.substring(data.lastIndexOf("<NOMBRE_USUARIO>")+16,data.lastIndexOf("</NOMBRE_USUARIO>"));
			llave   		  =  data.substring(data.lastIndexOf("<LLAVE>")+7,data.lastIndexOf("</LLAVE>"));

			//console.log(llave);
			
			var xrnd  = Math.floor((Math.random() * 100000000) + 1);

			//localStorage.setItem("db", db);
			//localStorage.setItem("server_path", server_path);
			//localStorage.setItem("uid", xuid);
			//localStorage.setItem("auth",xauth);

			if( localStorage.getItem("recordar_"+db )=='on' ){
				localStorage.setItem("login_"+db   , $('#login').val());
				localStorage.setItem("password_"+db, $('#password').val());
			} else {
				localStorage.setItem("login_"+db   , '');
				localStorage.setItem("password_"+db, '');
			}

			//Cambio de pagina
			cargar_pagina('/menu_principal/', function(){
				$('#login').val('');
				$('#password').val('');
			});

			app.preloader.hide();

		}
		else{
			app.preloader.hide();
			app.dialog.alert('Login o Password Invalido', tituloAlert,function () {
				$('#login').focus();
			});
      }
	  

	  
	});
}
