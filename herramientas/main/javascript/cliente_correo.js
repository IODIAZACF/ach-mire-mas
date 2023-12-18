var cliente_correo = new submodal();
cliente_correo.nombre      = "cliente_correo";
cliente_correo.ancho       = 80;
cliente_correo.alto        = 50;
cliente_correo.titulo      = 'Email';
cliente_correo.usaFrame    = true;
cliente_correo.botonCerrar = true;
cliente_correo.onClose = function(){
	cliente_correo.ocultar();
};

cliente_correo.inicializa();	

var alto_menu =  $(".menu_fondo").outerHeight();
$("#sub_container_cliente_correo").css('padding-top', alto_menu + 'px');
$("#sub_container_cliente_correo").css('zIndex', '0');
$("#sub_container_cliente_correo .grid_title .grid_cerrar").addClass("grid_minimizar").removeClass("grid_cerrar");


var url      = server_path + 'herramientas/genera_xml/genera_xml.php';
var params   = 'tabla=M_USUARIOS&campos=*&operador==&busca=ID_M_USUARIO&xbusca=' + id_m_usuario;
var xml      = enviar(url, params, 'POST');
var registro = valida_xml(xml, 'CORREO_LOGIN');
var url 	 = '/herramientas/webmail-lite/';

if(registro) {
	var user_mail = registro[0]['CORREO_LOGIN'];
	var pass_mail = registro[0]['CORREO_CLAVE'];
	$("#cliente_correo_iframe").attr( "onLoad","logear_coreo()" );

}

cliente_correo.iframe.src = url;
	
function mostrar_cliente_correo(){
	cliente_correo.mostrar();
}


function logear_coreo(){
	setTimeout(function(){
		
		console.log('Cargando correo -> usuario :' + registro[0]['CORREO_LOGIN']);	
		//$("#cliente_correo_iframe").contents().find("body").get(0).style.setProperty("--color-azul-oscuro", "red");

		
		$("#cliente_correo_iframe").contents().find("#login_login").val(user_mail);
		$("#cliente_correo_iframe").contents().find("#login_password").val(pass_mail);
		$("#cliente_correo_iframe").contents().find("button.login").click();
		
	},2000);

}