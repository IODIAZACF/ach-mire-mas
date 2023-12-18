var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( params);
	
}

$('#ID_M_DOCUMENTOS').html(r_M_DOCUMENTOS.ID_M_DOCUMENTOS + ' ' + r_M_DOCUMENTOS.FECHA_DOCUMENTO );
$('#D_DOCUMENTOS').data('xfiltro', r_M_DOCUMENTOS.ID_M_DOCUMENTOS);

buscar_grid('D_DOCUMENTOS', '*');

function t_D_DOCUMENTOS(obj, tecla){  
	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
		r_X_DOCUMENTOS = registro;
	}

	switch(tecla){

		case 'insert':
		break;

		case 'enter':
		break;

		
		case 'esc':
			cargar_pagina('/m_documentos/');
		break;
		
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

function t_f_edicion(obj, tecla){  

	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
	}

	switch(tecla){
		case 'esc':
			app.sheet.close('#form-sheet');	
		break;

		case 'supr':
			var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
			var params = 'tabla=X_DOCUMENTOS&busca=ID_D_DOCUMENTOS&xbusca=-' + r_X_DOCUMENTOS.ID_D_DOCUMENTOS;				

			var data = enviar(url,params,'POST');
			app.sheet.close('#form-sheet');	
			
			buscar_grid('d_pedidos','*');
			
		break;

		case 'f12':
		
			console.log( $$("#f_edicion CANTIDAD").val()    );
			guardar_formulario('f_edicion', function(resp){
				buscar_grid('d_pedidos','*');
				app.sheet.close('#form-sheet');	
			});
			
		
		break;
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

function t_f_cierre(obj, tecla){  
	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
	}
	
	switch(tecla){
		case 'esc':
			app.sheet.close('#form-sheet');	
		break;

		case 'f12':
			
			var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
			var params  = 'tabla=X_M_DOCUMENTOS&busca=ID_X_M_DOCUMENTOS&xbusca=' + ID_X_M_DOCUMENTOS;
				params += '&c_ESTATUS_CSS=PEN';				
				params += '&c_COMENTARIOS_CSS=' + $$("#COMENTARIOS").val();
				params += '&c_CAMPO1_CSS=' + $$("#CAMPO1").val();

			//prompt('',url+'?'+params);

			var data = enviar2(url,params,'POST',function(data){
				app.sheet.close('#form-sheet');	
				cargar_pagina('/m_clientes/');
			});

			
		break;
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

