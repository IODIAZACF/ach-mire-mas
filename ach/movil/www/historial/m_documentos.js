var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params );
	
}

$('#M_DOCUMENTOS').data('xfiltro', r_M_CLIENTES.ID_M_CLIENTES);
buscar_grid("M_DOCUMENTOS","*");


var r_M_DOCUMENTOS;

function t_M_DOCUMENTOS(obj, tecla){

	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_DOCUMENTOS = registro;
	}

	switch(tecla){
		case 'enter':
			//console.log(registro);
			cargar_pagina('/d_documentos/', r_M_DOCUMENTOS);
			
		break;
		
		case 'ins':
		break;

		case 'esc':
			cargar_pagina('/menu_cliente/');
		break;

		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}


Template7.registerHelper('evaluar', function (valor1){
	

	switch(valor1) {
	  case 'FAX':
			return 'ANULADA';
		break;
	  case 'FAC':
			return 'FACTURA';
		break;
	  default:
			return '<span style="border:solid 0px red">--</span>';
	}	
});
