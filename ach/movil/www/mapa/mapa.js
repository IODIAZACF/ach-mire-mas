var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params['x'] );

}

map = new GMaps({
	el: '#map',
	lat: -0.17630079987136343,
	lng: -78.48161408456734,
	zoomControl : true,
	zoomControlOpt: {
		style : 'SMALL',
		position: 'TOP_LEFT'
	},
	panControl : false,
	streetViewControl : false,
	mapTypeControl: false,
	overviewMapControl: false
});

map.addMarker({
  lat: -0.17630079987136343,
  lng: -78.48161408456734,
  title: 'Quicentro',
  click: function(e) {
	alert('You clicked in this marker');
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