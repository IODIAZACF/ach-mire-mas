var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params['x'] );

}

GMaps.geolocate({
  success: function(position) {
	map.setCenter(position.coords.latitude, position.coords.longitude);
  },
  error: function(error) {
	alert('Geolocation failed: '+error.message);
  },
  not_supported: function() {
	alert("Your browser does not support geolocation");
  },
  always: function() {
	alert("Done!");
  }
});	  

function t_MAPA(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
	}

	switch(tecla){

		case 'menu':
			cargar_pagina('/menu/');

		break;

		case 'login':
			cargar_pagina('/login/');

		break;
		
		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}