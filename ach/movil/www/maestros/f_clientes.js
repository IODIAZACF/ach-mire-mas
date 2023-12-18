var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params );

}


function t_F_CLIENTES(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_CLIENTES = registro;
	}

	switch(tecla){

		case 'guardar':
			

			guardar_formulario("f_M_CLIENTES", function(resp){
				//console.log(resp.tabla.registro['ID_M_CLIENTES']);
				buscar_grid('M_CLIENTES', '='+resp.tabla.registro['ID_M_CLIENTES']);
				
			});

		break;

		case 'esc':
			app.popup.close('#form-popup', true);
		break;
		
		default:
			app.dialog.alert('Opción no programada..!', tituloAlert);
			return false;
		

	}
}