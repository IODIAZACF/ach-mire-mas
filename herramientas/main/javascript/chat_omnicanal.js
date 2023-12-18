var chat_omnicanal = new submodal();
chat_omnicanal.nombre      = "chat_omnicanal";
chat_omnicanal.ancho       = 80;
chat_omnicanal.alto        = 50;
chat_omnicanal.titulo      = 'Chat Omnicanal';
chat_omnicanal.usaFrame    = true;
chat_omnicanal.botonCerrar = true;
chat_omnicanal.onClose = function(){
	chat_omnicanal.ocultar();
};
chat_omnicanal.inicializa();	

var alto_menu =  $(".menu_fondo").outerHeight();
$("#sub_container_chat_omnicanal").css('margin-top', alto_menu + 'px');
$("#sub_container_chat_omnicanal").css('zIndex', '0');

var url = server_path + 'suite/chat_omnicanal/index.php';

chat_omnicanal.iframe.src = url;

function mostrar_chat_omnicanal(){
	menu.reset();
	chat_omnicanal.mostrar();
}