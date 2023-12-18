var r_M_PAISES;
var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(localStorage.getItem('M_PAISES')) buscar_grid('M_PAISES', localStorage.getItem('M_PAISES'));

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params['x'] );
}


function t_M_PAISES(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_PAISES = registro;
	}

	switch(tecla){

		case 'enter':
			//cargar_pagina('/menu_cliente/', r_M_PAISES);

		break;
		
		case 'ins':
			//cargar_formulario('/f_clientes/', '-1');
			//app.popup.open('#form-popup');
			cargar_formulario('/f_clientes/', '-1', function(estatus){
				app.popup.open('#form-popup');
			});

		break;

		case 'esc':
			cargar_pagina('/menu_admin/');
		break;

		case 'catalogo':
			cargar_pagina('/catalogo/', r_M_PAISES);
		break;
		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}