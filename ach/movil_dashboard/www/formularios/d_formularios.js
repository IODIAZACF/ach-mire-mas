var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;
var registro;

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	console.log( params );
	
	
}


$('#M_KOBO_RESPUESTAS').data('filtro'  , 'ID_M_KOBO_FORMULARIOS');
$('#M_KOBO_RESPUESTAS').data('xfiltro' , params.ID_M_KOBO_FORMULARIOS);

buscar_grid('M_KOBO_RESPUESTAS','*');

var r_D_FORMULARIOS;
	

function t_M_KOBO_RESPUESTAS(obj, tecla){
	
	//console.log(obj);
	//console.log(tecla);
	
	
	if( $(obj).data("registro") ) {
		registro =  $(obj).data("registro") ;
		r_D_FORMULARIOS = registro;
	}

	switch(tecla){
		
		case 'enter':
		break;
		
		case 'esc':
			cargar_pagina('/m_formularios/');
		break;
		
		
		default:
		app.dialog.alert('Opción no programada..!', tituloAlert);
		return false;
		

	}
}
