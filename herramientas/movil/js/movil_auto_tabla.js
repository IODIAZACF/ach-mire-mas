$(document).on('click','ons-list-item[data-tec=enter]', function() {
	
	//var obj = eval( $(this).parent().attr('id').split('_').slice(0,-1).join('_') ) ;
	me_grid.id = $(this).data('val');
	
	//obj.id = $(this).data('val');
	//console.log(obj.id);
	
	var obj = $(this);
	var funcion = obj.data('func');
	var valor  	= 'enter';

	if(funcion) {
		var fn = window[funcion];
		if (typeof fn === "function") {
			fn.apply(null, [obj, valor]);
		} else {
			console.error("Error funcion no encontrada.!! ->" + funcion + ' -> ' + valor );
		}
	}

});

var movil_grid_activo;
var me_grid;

class movil_auto_tabla{
    constructor(){
		this.origen;
		this.ini;
		this.name;
		this.funcion;
		this.debug;
		this.json;
		this.enter = 0;
		this.filtro;
		this.xfiltro;
		this.xoperadores;
		this.orden
		this.id;

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

		if(this.debug) console.log( 'inicializando Grid ->' + this.name );
			
	}
	
	mostrar(){
		movil_grid_activo = this;
		me_grid = this;
		
		app.bringPageTop( modulo + 'templates/' + me_grid.name + '.html').then( function(){
			$('ons-action-sheet#' + me_grid.name + '_opciones ons-action-sheet-button' ).data("funcion",  me_grid.funcion.name );
			$('ons-bottom-toolbar#' + me_grid.name + '_leyenda button').data("funcion",  me_grid.funcion.name );

			 $('#' + me_grid.name + ' ons-button').on('click', function( event ){
				$(this).data("funcion", me_grid.funcion.name);
				
			});
			
			 
		});
		
		
	}
	
	
	mostrar_opciones(){
		movil_grid_activo = this;
		me_grid = this;
		$('ons-action-sheet#' + me_grid.name + '_opciones').show();		
	}

	ocultar_opciones(){
		movil_grid_activo = this;
		me_grid = this;
		$('ons-action-sheet#' + me_grid.name + '_opciones').hide();		
	}

	setfocus(){
		
		setTimeout(function(){
			console.log('fucuseando...');
			//$("." + this.name + "_buscador").focus();
			//$("ons-search-input ." + this.name + "_buscador").focus();
		},300);
	}
	
	setTitle( xtexto ){
		$("ons-toolbar ." + this.name + '_titulo' ).html( xtexto );
	}
	
	buscar( xbusca ){
		
		me_grid = this;
		this.id = '';
		
		if(!xbusca) xbusca = '*';
		
		if(xbusca.substring(0, 1) == '='){

		} else {
			
			if(xbusca!='*') xbusca = '*' + xbusca;
		}

		xbusca = xbusca.toUpperCase();
		var ini = this.ini;

		var tabla   = ini['TABLA'].TABLA;
		var campos  = ini['TABLA'].CAMPOS;
		var busca   = ini['TABLA'].BUSCA;
		var orden   = ini['TABLA'].ORDEN;
		var limite  = ini['TABLA'].LIMITE;
		
		//if(!limite) limite = '50';
		
		var url = server_path + "herramientas/genera_json/genera_json.php";
		
		var params  = 'tabla='   + tabla;
			params += '&campos=' + campos;
			params += '&busca='  + busca;
			params += '&xbusca=' + xbusca;			 
		
		
		if( limite) params += '&limite=' + limite;			 
		if( orden ) params += '&orden='  + orden;
		
		if( this.filtro      ) params += '&filtro='      + this.filtro;
		if( this.xfiltro     ) params += '&xfiltro='     + this.xfiltro;
		if( this.xoperadores ) params += '&xoperadores=' + this.xoperadores;
		
		if(me_grid.debug) {

			console.log( 'debug: ' + me_grid.name + ' - buscar: ' + url + '?' + params); 
		}
		
		enviar2(url, params, 'post', function(data){
			
			if(data.tabla.registro){
				me_grid.json = data.tabla.registro;
				me_grid.rellenar( data.tabla.registro , xbusca );
				
			} else {
				
				var data = [{"ERROR": "No se encontraron registros.."}];
				me_grid.rellenar( data, xbusca);
							
			}
			
			if( me_grid.debug) {
				console.log( 'debug: ' + me_grid.name + ' - buscar: ');
				console.log( data );			
			}
			
		});

	}

	limpiar(){
		this.id = '';
		$("." + this.name  + "_grid").html('');	
		
	}
	
	rellenar( data , xbusca){
		me_grid = this;
		this.id = '';

		$("#" + this.name  + "_grid").html('');
		
		var xHtml = '';
		
		if(data[0].ERROR){
			
			xHtml += '<ons-list-item>';
			xHtml += data[0].ERROR;
			xHtml += '</ons-list-item>';
			$("." + this.name  + "_grid").html( xHtml );
			return;
		} 			

		var ini = this.ini;

		var x = 0;
		var i = 0;
		var xcolor = 'claro';
		
		
		var cols = [];
		
		
		$.each(ini, function( xsec, xvar){
			if( xsec.substring(0,3) == 'COL' ){
				cols.push( xvar );
			}
		
		});
	
		var cols = cols.sort( (a,b) => a.POSICION.localeCompare(b.POSICION) );

		$.each( data, function( xtabla, xregistro){
			xHtml += '<ons-list-item class="list-grid-item-24" tappable data-func="' + me_grid.funcion.name + '" data-val="' + i + '" data-tec="enter">';

			i++;
			x++;
			xcolor = '';
			if( x == 2 ){
				xcolor = 'oscuro';
				x = 0;
			} 

			var xpos_old = '';

			$.each(cols, function( xsec, xvar){
				
					var xpos     = xvar.POSICION;
					
					var xtipo    = xvar.TIPO;
					
					var xanchos  = xvar.ANCHO.split(',');
					var xancho1  = parseFloat(xanchos[0]);
					var xancho2  = parseFloat(xanchos[1]);
					

					var xrotulo = xvar.ROTULO;
					var xvalor  = xregistro[xvar.CAMPO];

					var tmp      = xtipo;
					var xtipo    = tmp.substring(0,1); 
					var xdecimal = tmp.substring(2,1).trim();
					if( xdecimal == '' ) xdecimal = 0;
					
					var class_align = 'has-text-left';
					switch (xtipo){
						
						case 'I': // ENTERO
							class_align = 'has-text-right';
							
							var tmp = xvalor.split(".");
							if (tmp.length>0) xvalor = tmp[0];
							break;

						case 'N': // NUMERICO
							class_align = 'has-text-right';
							if( !xdecimal || xdecimal == 0 ) xdecimal = 2;
							xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);
							break;

						case 'F': // FORMATEADO
							class_align = 'has-text-right';
							xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);
							xvalor = format( xvalor , xdecimal ) ;
							break;

						default:
							break;
					}
					
					if( xpos != xpos_old ){
						if( xpos_old != ''){
							xHtml += '</div>';	
						}
						xpos_old = xpos;
						
						xHtml += '<div class="mb-1 is-flex" style="width: 100%">';
					}

					if(xvalor)	{
						xvalor = xvalor.replace(/(?:\r\n|\r|\n)/g, '<br>');
					} else {
						xvalor = '&nbsp;';		
					}
					
					if (xanchos.length > 1){
						xHtml += '<div class="' + xvar.ESTILO + ' is-flex" style="width: ' + (xancho1 + xancho2 ) + '%">'
						xHtml += '<div class="rotulos px-1" style="width: ' + (xancho1 / (xancho1 + xancho2 ) * 100) + '%;">' + xrotulo + '</div>';
						xHtml += '<div class="valores px-1 ' + class_align + '"  style="width: ' + (xancho2 / (xancho1 + xancho2 ) * 100) + '%;">' + xvalor + '</div>';
						xHtml += '</div>'
					} else {
						xHtml += '<div class="' + xvar.ESTILO + ' is-flex-direction-column" style=" width:' + xancho1 + '%;">'
						xHtml += '<div class="rotulos px-1 ' + class_align + '">' + xrotulo + '</div>';
						xHtml += '<div class="valores px-1 ' + class_align + '">' + xvalor + '</div>';
						xHtml += '</div>'
					}
	
			});
			xHtml += '</div>';
			xHtml += '</ons-list-item>';
			
			
			
		});

		xHtml += '';
		
		$("." + this.name  + "_grid").html( xHtml );
		
	}
	
	ocultar_opcion( valor ){
		$("#" + this.name  + "_opciones ons-action-sheet-button[data-valor='" + valor + "']").hide();
	}
	
	mostrar_opcion( valor ){
		$("#" + this.name  + "_opciones ons-action-sheet-button[data-valor='" + valor + "']").show();
	}

}
