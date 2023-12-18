var movil_pagina_activo;
var me_pagina;

class movil_pagina{
    constructor(){
		this.origen;
		this.ini;
		this.name;
		this.funcion;
		this.debug;

	}
	
	inicializa(){
		
		if( inis[this.origen] ){
			this.ini = inis[this.origen];
		} else {
			console.error('No hay ini con este origen --> [' + this.origen + ']');
		}
		
		this.name =  this.origen;
		
		// Se inyectan la paginas al DOM
		app.insertPage( 0, modulo + 'templates/'  + this.name + '.html' );
			
	}
	
	mostrar(){
		movil_pagina_activo = this;
		me_pagina = this;
			
		app.bringPageTop( modulo + 'templates/' + me_pagina.name + '.html' ).then( function(){
			$( "." + me_pagina.name + "_pagina ons-list-item").data("funcion", me_pagina.funcion.name );

			$('ons-bottom-toolbar#' + me_pagina.name + '_leyenda button').data("funcion",  me_pagina.funcion.name );
			
		});
		
		
	}
	
		
	setTitle( xtexto ){
		$("ons-toolbar ." + this.name + '_titulo' ).html( xtexto );
	}
	

	limpiar(){
		this.id = '';
		
	}
	
	
	

}
