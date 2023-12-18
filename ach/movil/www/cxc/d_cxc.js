var page = $('div.page-current')[0].f7Page;

var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( params);
	var filtro ='';
	var xfiltro ='';
	
	if(params.filtro){
		
		filtro = params.filtro;
		xfiltro =  params.xfiltro;
		xoperadores = params.xoperadores

		$('#d_cxc').attr('data-filtro', filtro);
		$('#d_cxc').attr('data-xfiltro', xfiltro );
		$('#d_cxc').attr('data-xoperadores', xoperadores );
	}
	
/* 	$('#d_cxc').attr('data-filtro', 'ID_M_CLIENTES;SALDO');
	$('#d_cxc').attr('data-xfiltro', r_M_CLIENTES['ID_M_CLIENTES'] + ';0' );
	$('#d_cxc').attr('data-xoperadores', '=;<>'); */

	$('#titulo-grid').html('<b>' + r_M_CLIENTES['NOMBRE_CLIENTE'] + '</b>' );
	buscar_grid('d_cxc','*');
	
	
}





function t_d_cxc(obj, tecla){  
	
	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
		r_M_DOCUMENTOS = registro;
	}

	switch(tecla){

		case 'insert':
		break;

		case 'enter':
		break;

		case 'esc':
			cargar_pagina('/menu_cliente/',r_M_CLIENTES);
		break;

		case 'f10':
		break;

			
		case 'f12':
		break;
		
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;
	}
}

Template7.registerHelper('tipo', function (TIPO, COMENTARIOS){
  var ret = '';
  
  if(TIPO=='PR'){
	  ret = COMENTARIOS;
  }
  
  return ret;
});

