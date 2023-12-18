
var movil_form_activo;
var me_form;
class movil_formulario{
    constructor(){
        this.origen;
		this.ini;
		this.name;
		this.funcion;
		this.debug = 0;
		this.json;
		this.enter = 0;
    }
	
	inicializa(){
		if( inis[this.origen] ){
			this.ini = inis[this.origen];
		} else {
			console.error('No hay ini con este origen --> [' + this.origen + ']');
		}

		this.name =  this.origen;
		
		me_form = this;
		
		app.insertPage( 0, modulo + 'templates/' + this.name + '.html' ).then( function(){

			if( me_form.debug ) console.log( 'inicializando Formulario ->' + me_form.name ); 

			setTimeout( function(){
				me_form.limpiar(); 
			},500 );	
			

			$.each( me_form.ini, function( xsec, xvar){
				if( xsec.substring(0,5) == 'CAMPO' ){
					
					if ( xvar.FORMA == 'SELECCION_TABLA'){
						
						$('select#' + me_form.name + '_' + xvar.CAMPO ).append( new Option('--', '--', true, true) );
						
						var url = server_path + "herramientas/genera_json/genera_json.php";

						var params  = 'tabla='   + xvar.TABLA_DATA;
							params += '&campos=' + xvar.CAMPO_MOSTRAR + ',' + xvar.CAMPO_GUARDAR;
							
						if( xvar.FILTRO      ) params += '&filtro='  + xvar.FILTRO;
						if( xvar.XFILTRO     ) params += '&xfiltro='  + xvar.XFILTRO;
						if( xvar.CAMPO_ORDEN ) params += '&orden='  + xvar.CAMPO_ORDEN;

						enviar2(url, params, 'post', function( data ){
							
							if( data.tabla.ERROR ) {
								console.error( data.tabla.ERROR.msg );
								return;
							};
							
							if(!data.tabla.registro) return;
					
							$.each( data.tabla.registro, function( tabla, registro) {
								var newOption = new Option( registro[xvar.CAMPO_MOSTRAR] , registro[xvar.CAMPO_GUARDAR], false, false);
								$('select#' + me_form.name + '_' + xvar.CAMPO ).append(newOption);
							});

						});
					}
				}
			});

		});
		

	}
	
	mostrar(){
		movil_form_activo = this;	
		me_form = this;
		app.bringPageTop( modulo + 'templates/' + me_form.name + '.html').then(function(){

			$('ons-bottom-toolbar#' + me_form.name + '_leyenda button').data("funcion",  me_form.funcion.name );
			
			$('#' + me_form.name + ' ons-button').on('click', function( event ){
				$(this).data("funcion", me_form.funcion.name);
				
			});
			
			
		});
	}
	
	
	ocultar(){
		//app.popPage( );
		
	}	
		
	setValue( campo, valor){
		$("#" + this.name + '_' + campo ).val( valor );		
		
	}
	
	getValue( campo ){
		
		var obj = $("#" + this.name + '_' + campo );
		var valor = obj.val();
		
		if( obj.data("tipo") == "D" ) {
			var tmp = valor.split('-');
			valor = tmp[2] + '/' + tmp[0] + '/' + tmp[1];
		}
		
		return valor;		
		
	}
	
	setTitle( xtexto ){
		$("#" + this.name + '_titulo' ).html( xtexto );
	}
	
	buscar( xbusca, callback ){
		
		me_form = this;
		
		var ini = this.ini;
		
		var tabla   = ini['TABLA'].TABLA;
		var campos  = ini['TABLA'].CAMPOS;
		var busca   = ini['TABLA'].INDICE;
		
		var url = server_path + "herramientas/genera_json/genera_json.php";
		
		var params  = 'tabla='   + tabla;
			params += '&campos=' + '*';
			params += '&busca='  + busca;
			params += '&xbusca==' + xbusca;
		
		
		
		enviar2(url, params, 'post', function(data){
			//console.log(data);
			
			if( data.tabla.ERROR ) {
				me_form.json;
				console.error( data.tabla.ERROR.msg );
				return;
			};
			
			if( data.tabla.registro ){
				var registro = data.tabla.registro;
				
				$("#" + me_form.name + "_formulario input" ).each( function( index ){
					var campo = $(this).data("campo");
					var valor = registro[0][campo];
					
					if( $(this).data("tipo") == 'D'){
						var tmp = valor.split('/');
						valor = tmp[2] + '-' + tmp[1] + '-' + tmp[0];
					}
					
					
					$(this).val( valor );
					$(this).data("old", valor);
					
					if( $(this).data("campo") == 'tabla'  ) $(this).val( me_form.ini.TABLA.TABLA );					
					if( $(this).data("campo") == 'busca'  ) $(this).val( me_form.ini.TABLA.INDICE );					
					if( $(this).data("campo") == 'xbusca' ) $(this).val( xbusca );					
					
				});
				
				$("#" + me_form.name + "_formulario select" ).each( function( index ){
					var campo = $(this).data("campo");
					var valor = registro[0][campo];
					$(this).val( valor );
					$(this).data("old", valor);
				});
				
			} 			
			
			if(callback) {
				setTimeout( function(){ callback( data ); }, 100);
			}
			
		});
	}
		
	limpiar(){
		
		me_form = this;
		
		$("#" + me_form.name + "_formulario select" ).each( function( index ){
			$(this).val( '--' );
		});

		$("#" + me_form.name + "_formulario input" ).each( function( index ){
			
			$(this).val( '' );
			
			if( $(this).data("campo") == 'tabla'  ) {
				$(this).val( me_form.ini.TABLA.TABLA );					
				if(me_form.debug) console.log( 'tabla='  +  $(this).val() );
			}
			
			if( $(this).data("campo") == 'busca'  ) {
				$(this).val( me_form.ini.TABLA.INDICE );					
				if(me_form.debug) console.log( 'busca='  +  $(this).val() );
			}
			
			
			if( $(this).data("campo") == 'xbusca' ) {
				$(this).val( '-1' );					
				if(me_form.debug) console.log( 'xbusca=' +  $(this).val() );
			}
			
		});
		
		
	}
	
	submit( callback ){
		var i = 0;
		var campos = [];
		
		$("#" + me_form.name + "_formulario input,select" ).each( function( index ){
		
			if( $(this).data("enviar") == '0') return;
			
			var campo = $(this).data("campo");
			var tipo  = $(this).data("tipo");
			var valor = $(this).val();
			
			//if( $(this).data("old") == $(this).val() ) return;

			if( campo == 'tabla' || campo == 'busca' || campo == 'xbusca' ){
				campos[i] =	campo + '=' + valor;
			} else {
				campos[i] =	'c_' + campo + '_' + tipo + 'SS=' + valor;
			} 	
			
			i++;
		});

		/*
		$("#" + me_form.name + "_formulario select" ).each( function( index ){
			
			
			
		});
		*/

		var url = server_path + "herramientas/utiles/actualizar_registro_json.php";
		var params  = campos.join('&') ;

		if( this.debug ){
			console.log( url + '?' + params );
		}	
		
		enviar2( url, params, 'post', function( data ){

			var result;

			if( data.tabla.estatus == 'ERROR') {
				ons.notification.alert( 'ERROR' + data.tabla.msg );
				result =  data.tabla;
			}
			
			if( data.tabla.estatus == 'OK') {
				ons.notification.toast( 'Registro actualizado..!',{
					timeout: 2000
				});
				result =  data.tabla.registro;
			}

			me_form.json = result;
			
			if(callback) {
				setTimeout( function(){ callback( result ); }, 100);
			}
			
		});
		
	}
	
    setParam(name, value){
        if ((typeof value==="undefined" || value === null) && this.params[name]) {
            delete this.params[name];
        }
        else this.params[name] = value;
    }

    getParam(name){
        return this.params[name]||null;
    }


    clearValues(){
        this.values={};
    }


}
