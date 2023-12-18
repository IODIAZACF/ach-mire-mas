var url = $(location).attr('href');
var server_path = url.split('/').slice(0,4).join('/') + '/';

var db = url.split('/').slice(3,4)[0];

window.onload = () => {
  'use strict';

	if ('serviceWorker' in navigator) {
	navigator.serviceWorker
			 .register( server_path + 'movil/sw.js');
	}
}


$(document).on('click','*[data-funcion]', function() {
 
	var obj = $(this);
	var funcion = obj.data('funcion');
	var valor  	= obj.data('valor');
	var tecla  	= obj.data('tecla');
	
	if(funcion) {
		var fn = window[funcion];
		if (typeof fn === "function") {

			if(tecla){
				fn.apply(null, [obj, valor, tecla]);
				return;
			}
			
			fn.apply(null, [obj, valor]);
			return;
			
		} else {
			console.error("Error funcion no encontrada.!! ->" + funcion + ' -> ' + valor );
		}
	}
});


function  enviar2(url, params, metodo, callback){

	xauth = '';
	params = 'auth=' + xauth + '&' + params + '&db=' + db; 
	
	
	var paso = false;
    var resp= null;
    $.ajax({
    	async: true,
        method: metodo,
	    dataType: "text",
	    url: url,
        data: params,
		headers: {
			'db': db
		},
	    success: function(data)
	    {
			//console.log(data);
			//console.log('conexion establecida ' + Date.now()) ;
            //resp = data;
	    },
	    complete: function(data){
			
			var result;
			
			//console.log(data);
			try {
				result = JSON.parse(data.responseText);
			} 
			catch (e) {
				result = data.responseText;
			}

			if(callback) {
				
				setTimeout( function(){ callback( result ); }, 100);
					
			} else {
				return result;
			}
			
	    },
		statusCode: {
			404: function (xhr) {
					app.preloader.hide();
					app.dialog.alert('No hay Conexion al servidor', tituloAlert)
				}
		}
	});
}



/****** UTILES *****/


function buscar_local(xgrid, xbusca){
	
	var tabla = $("#" + xgrid).data('tabla');	
	var busca = $("#" + xgrid).data('busca');
	
	var xdata = JSON.parse(localStorage.getItem( tabla ));	
	
	if(!xdata) {
		app.dialog.alert('No se han cargado los datos Locales', tituloAlert)
		return;
	}
	
	if(xbusca=='*'){
		var data = xdata.tabla.registro;
		
	} else {
		
		var db = new loki('data.db');
		var xtabla = db.addCollection( tabla );
		xtabla.insert(xdata.tabla.registro);
		
		var tmp1 =  busca.split(',');
		var tmp2 = xbusca.split(',');
		
		var data = [];
		
		for (var j = 0; j < tmp1.length; j++) {

			var arr = [];
			for (var i = 0; i < tmp2.length; i++) {
				arr.push( { [tmp1[j]] : { '$contains' : tmp2[i] } } ); 	
			}
			//console.log(xtabla.find( {'$and': arr }) );
			xtabla.find( {'$and': arr }).forEach(function(xtabla, xregistro){
				data.push(xtabla);
			});		
			
		}
		
	}
	
	rellenar_grid(xgrid, data, xbusca);
}


String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

/*
function actualizaHTML(obj, json){

	const regex = /{{([^}]*)}}/g;
	var grupos;

	while ((grupos = regex.exec($(obj).html())) !== null) {
		$(obj).html( $(obj).html().replace('{{' + grupos[1] +'}}', json[grupos[1]]));
	}	
}
*/

function actualizaHTML( prefijo, json ){

		$.each( json, function( campo, valor){
			//console.log( campo, valor );
			$('#' + prefijo + campo  ).html(valor);
		});

}




/*
Template7.registerHelper('format', function (valor, decimales){
	//return valor;
	
	if(typeof(decimales)!= 'number' ) {
		var decimales = 2;
	} 
	
	var valor = format(valor, decimales);

	return valor;
});

*/

function getvar(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
}

function unformat(valor){
  
   if( typeof valor === "number") return valor;
   if(valor=='') return '';
   valor = trim(valor);
   return parseFloat(valor.replace(/,/g, ''));
}

function trim(value) {
  return value.replace(/^\s+|\s+$/g,"");
}

function format(valor, decimales){
	
	if(valor == undefined) return '';

	if( typeof(valor) != 'string' && typeof(valor) != 'number') return '';

	if( typeof(valor) == 'string' ) valor = parseFloat( valor );

	if(typeof(valor) == 'string'){
		valor = valor.replaceAll(',','');
		valor = parseFloat(valor);
	}
	
	if(typeof(valor) != 'number' ) {
		return 'NaN';
	} 
	
	if(typeof(decimales) != 'number' ) {
		decimales = parseFloat(decimales);
	} 

	valor = valor.toLocaleString("en-US", { minimumFractionDigits: decimales });
	return valor;

	
}	


function ejecutar_procedimiento( origen, procedimiento, opciones , callback ){
	
	var url = server_path + "herramientas/utiles/actualizar_registro_json.php";
	var params  = 'origen='   		+ origen;
		params += '&procedimiento=' + procedimiento;
		params += '&' + $.param( opciones ) ; 

	enviar2(url, params, 'post', function(data){
		//console.log(data);
		//if(!data.tabla) return;
			
		
		ons.notification.toast( 'Registro actualizado..!',{
			timeout: 2000
		});
		
		if( data.tabla.estatus == 'ERROR') {
			ons.notification.alert( 'ERROR' + data.tabla.msg );
		}
		
		if( data.tabla.estatus == 'OK') {
			ons.notification.toast( 'Registro actualizado..!',{
				timeout: 2000
			});
		}		

		
		if(callback) {
			setTimeout( function(){ callback(data); }, 100);
		} else {
			return data;
		}
	});		
			
			
}
