
$$(document).on('click','*[data-funcion]', function() {
 
	var obj = $(this);
	var funcion = obj.data('funcion');
	var opcion  = obj.data('opcion');
	
	if(funcion) {
		var fn = window[funcion];
		if (typeof fn === "function") {
			fn.apply(null, [obj, opcion]);
		} else {
			console.error("Error funcion no encontrada.!! ->" + funcion + ' -> ' + opcion );
		}
	}
});

$$(document).on('page:beforein', function (e, page) {
	//app.preloader.show();

});


$$(document).on('page:afterin', function (e, page) {
	
	//console.log('Cargando pagina [' + page.name + ']');
	
	$$('*[data-name="' + page.name + '"]').find("javascript").each(function(obj){		

		$$('#script_' + page.name).remove();

		if ($$(this).attr('src')) {
			var xrnd  = Math.floor((Math.random() * 100000000) + 1);			
			
			//console.log('creando Script: [' + $$(this).attr('src') + ']' );	
			
			var s = document.createElement( 'script' );
			s.setAttribute( 'src', $$(this).attr('src') + '?rnd=' + xrnd);
			s.setAttribute( 'id', 'script_' + page.name);
			document.body.appendChild( s );
		}
		else{
			eval($$(this).text());
		}
	});
	//app.preloader.hide();
});




/****** UTILES *****/

function tecla_grid(e, id, metodo){
	
	if(e.which != 13) return;
    
	var arr = id.split('-');
    var grid = arr[0];
    var xbusca = $('#' + id ).val().toUpperCase();
	
	if(metodo=='local'){
		buscar_local(grid, xbusca);
	} else {
		buscar_grid(grid, xbusca);
	}
	
	$("#"+id).focus();
    $("#"+id).val('');

	if(app.device.os=='ios' || app.device.os=='android') $("#"+id).blur();

}


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




function buscar_grid(xgrid, xbusca){

    if(!xbusca) return;

	if( $("#"+xgrid).length == 0 ) {
		console.error( 'Error Grid no encontrado ..! ->  Revisa el ID del objeto <ul> debe ser =' + xgrid );
		return;
	}

	if( $("#" + xgrid +"-template").length == 0 ) {
		console.error( 'Error template del Grid no encontrado ..! -> Revisa el ID del objeto <script type="text/template7"> debe ser =' + xgrid );
		return;
	}

	//app.preloader.show();

	localStorage.setItem(xgrid, xbusca);

    
	if(xbusca.substring(0, 1) == '='){

		
	} else {
		
		if(xbusca!='*') xbusca = '*' + xbusca;
		
	}

	if( !server_path || !db ) {
		console.error('falta server_path o db');
		return;
	}
	
	var params =  $.param( $("#" + xgrid ).data() ); 
		params =  "db="+ db +"&key=12345&xbusca=" + xbusca + '&' + params;

	
	var url = server_path + "herramientas/genera_json/agi_json.php";
	

	var debug  = $("#"+xgrid).data("debug");

    if(debug) prompt('Url del Objeto: ' + xgrid ,url + '?' + params);

    enviar2(url, params, 'post', function(data){
		//console.log('buscando...!');
		if(!data.tabla){
			cargar_pagina('/login/');
			return;
		}
		rellenar_grid(xgrid, data.tabla.registro, xbusca);
		//app.preloader.hide();
	});
	/*
	app.request.json(url, function (data) {
		rellenar_grid(xgrid, data.tabla.registro, xbusca);
		app.preloader.hide();
	});
	*/


}

function rellenar_grid(xgrid, registro, xbusca){

	var template = $('#' + xgrid + '-template').html().replaceAll('accion_grid','accion_grid_' + xgrid).replaceAll('registro','registro_' + xgrid);
	
	var compiledTemplate = Template7.compile(template);

    var onselect = $("#" + xgrid).data("onselect");
	if(!onselect && xgrid!='grid_maestro') console.error('El Grid [' + xgrid + '] No tiene definida tecla de funcion data-onselect' );

    $("#" + xgrid).html('');

	var html = "";
	if(registro){
		
		if(xbusca != '*') {
			html  = '<li class="item-content">';
			html += '	<div class="item-inner">';
			html += '		<div class="item-title"><div class="">' + xbusca + ' ' + registro.length + ' registros</div></div>';
			html += '	</div>';
			html += '</li>';
		}
		
		var x = 0;

		registro.forEach(function(xtabla, xregistro){
			x++;
			xcolor = '';
			if( x == 2 ){
				xcolor = 'oscuro';
				x = 0;
			} 
			
			var xhtml = compiledTemplate(xtabla).replace("REGISTRO","data-registro='" + JSON.stringify(xtabla) + "'").replace("XCOLOR",xcolor);
			html+= xhtml;
		});

		$("#" + xgrid).html(html);

		$('.accion_grid_' + xgrid).on('click', function () {
			
			var obj   = $(this).parent('div').prev('div').find('a');
			var tecla = $(this).data('tecla');
			
			if(onselect) {
				alert();
				var fn = window[onselect];
				if (typeof fn === "function") fn.apply(null, [obj, tecla]);
			}
		});
		
		$('.registro_' + xgrid).on('click', function () {
			
			var obj   = $(this);
			
			var tecla = 'enter';
			
			if(onselect) {
				var fn = window[onselect];
				if (typeof fn === "function") fn.apply(null, [obj, tecla]);
			}
		});
		
		

	} else {
		localStorage.removeItem(xgrid);
		
		html  = '<li class="item-content">';
		html += '	<div class="item-inner">';
		html += '		<div class="item-title">No se encontraron registros..</div>';
		html += '	</div>';
		html += '</li>';
		
		$("#" + xgrid).html(html);
	}
	
}


function cargar_pagina(ruta, params, callback){
	//app.preloader.show();
	
	var myParams;

	if(params){
	
		switch( typeof(params) ){
			case 'object':
				myParams = params;
			break;

			case 'string':	
				var hash;
				var myParams = {};
				var hashes = params.split('&');
				for (var i = 0; i < hashes.length; i++) {
					hash = hashes[i].split('=');
					myParams[hash[0]] = hash[1];
				}
			break;
		}
			
	}
	mainView.router.navigate(ruta,{
		context: myParams
		
	});


	if(callback) {
		setTimeout( function(){ callback('OK'); }, 250 );
		//app.preloader.hide();
			
	} else {
		//app.preloader.hide();
		return 'OK';
	}	

}


String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};


function actualizaHTML(obj, json){

	const regex = /{{([^}]*)}}/g;
	var grupos;

	while ((grupos = regex.exec($(obj).html())) !== null) {
	  $(obj).html($(obj).html().replace('{{' + grupos[1] +'}}', json[grupos[1]]));
	}	
}


function guardar_formulario(idForm, callback){
    var xForm = '';
	var debug = $$('#'+ idForm).data('debug');

	if (!$$('#' + idForm)[0].checkValidity()) {

	    app.dialog.alert('Revise los Datos', tituloAlert)

		return;
	}
	
	if( !$$('#' + idForm).data('tabla') ) {
		console.error('Error al guardar falta la propiedad tabla en el objeto ' + idForm); 
		return;
	} 

	var tabla  = $$('#'+ idForm).data('tabla');
	var busca  = $$('#'+ idForm).data('busca');
	var xbusca = $$('#'+ idForm).data('xbusca');
	
	var funcion = $$('#'+ idForm).data('funcion');
	var xCampo='';
    var xValido = true;

    $.each($$("#" + idForm + " [data-campo]"), function(x, campo){
         
		 
		 var obj = "#"+idForm + ' #' + campo.id;
         var xenviar = $$(obj).data('enviar');

		 
		 if(xenviar=='1' && xValido)
         {
            xRequerido = $$(obj).data('requerido');
            if(xRequerido=='S' && $$(obj).val()=='')
            {
                xValido=false;
				var xAlerta  = $$(obj).data('alerta');
				if(xAlerta===undefined || xAlerta === null) xAlerta = 'Debe Especificar un valor';
				
                app.dialog.alert(xAlerta, '',function(){
                    if($$(obj).prop("tagName")=='TEXTAREA' || $$(obj).attr('type')=='INPUT'); 
					$$(obj).focus();
                });
			
            }

			var xOld = $$(obj).data('old');
			var xNew = $$(obj).val();
			if(xOld!=xNew)
            {
				xCampo = $$(obj).data('prefijo');
				if(xCampo===undefined || xCampo === null) xCampo='c';
				xCampo+= '_'+ $$(obj).data('campo');
				xCampo+= '_'+ $$(obj).data('tipo') + 'S';
				var xDestino = $$(obj).data('destino');
				if(xDestino===undefined || xDestino === null) xDestino='S'; //por ahora solo para las imagenes f=file,t=tabla
				xCampo+= xDestino;
				xCampo+= '=' +  encodeURIComponent($$(obj).val());
				xForm += xForm.length >0 ? '&': '';
				xForm += xCampo;
                //alert(xRequerido);
			}
         }
    });
	
    if(xValido == false) return false;
	if(xForm =='') return false;

    var xForm = 'tabla=' + tabla + '&busca=' + busca + '&xbusca=' + xbusca + '&' + xForm;
	var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
	//console.log(url + '?' + xForm);

	if(debug=='1') prompt('Url del Formulario:', url + '?' + xForm);
	var resp = enviar2(url, xForm, 'POST', function(resp){
		
		if(resp.tabla.estatus=='ERROR'){
			console.error('Error al guardar ');
			console.error(resp);
		} 
		
		if(callback) {
			setTimeout( function(){ callback(resp); }, 100);
				
		} else {
			return resp;
		}
		
	});
	

}

function enviar2(url, params, metodo, callback){

	prompt('', url + '?' + params);
	
	app.preloader.show();
 
	params = 'auth=' + xauth + '&' + params; 
	
    var paso = false;
    var resp= null;
    app.request({
    	async: true,
        method: metodo,
	    dataType: "json",
	    url: url,
        data: params,
	    success: function(data)
	    {
			
			//console.log('conexion establecida ' + Date.now()) ;
            //resp = data;
	    },
	    complete: function(data){
			//app.preloader.hide();
			if(callback) {
				app.preloader.hide();
				setTimeout( function(){ callback(JSON.parse(data.response)); }, 100);
					
			} else {
				return data;
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


function enviar(url, params, metodo, tipo='json'){
    
	params = 'auth=' + xauth + '&' + params; 
	//app.preloader.show();
	
    var paso = false;
    var resp= null;
    app.request({
    	async:false,
        method: metodo,
	    dataType: tipo,
	    url: url,
        data:params,
	    success: function(data)
	    {
     		//app.preloader.hide();
            resp = data;
	    },
	    complete: function(data){
        	app.preloader.hide();
	        paso = true;
	    },
	  error: function(xhr, textStatus, errorThrown){
	    //app.preloader.hide();
        paso = true;
	  }
	});
    while(!paso){}
	
	//app.preloader.hide();
    return resp;
}

function cargar_formulario(ruta, xbusca, callback){

	var myParams;
	
	formView.router.navigate(ruta, {
		context: myParams,
		on : {
	
			pageInit: function(e, page) {

				var obj = $$(page.el).find('form');
				
				if(!xbusca || xbusca == '' || xbusca == '-1' ){
				   if(xbusca == '1') $$(obj).attr('data-xbusca', xbusca);	

				} else {
									
					var tabla  = $$(obj).data('tabla');
					var busca  = $$(obj).data('busca');
					var debug  = $$(obj).data('debug');
					
					$$(obj).attr('data-xbusca', xbusca);	
			
					//app.preloader.show();
					var url = server_path + "herramientas/genera_json/genera_json.php";
					var par = 'tabla=' + tabla + '&campos=*&busca=' + busca + '&xbusca=' + xbusca + '&operador==';
					var resp = enviar(url, par, 'POST');
					
					if(debug) {
						prompt('Url Formulario', url + '?' + par);
						console.log(resp);
					}
					
					if(resp.tabla.estatus=='ERROR') {
						console.error('Error al cargar datos ' + '\n' + resp.tabla.msg);
						return;
					}
					
					if(resp.tabla.registro){
						app.form.fillFromData(obj, resp.tabla.registro[0]);		
					}
			
					//app.preloader.hide();
				} 
			

			}
		
		}
	});

	if(callback) {
		setTimeout( function(){ callback('OK'); }, 100 );
	} 
	
	
}


function format(valor, decimales){

	if(valor == undefined) return '';

	if( typeof(valor) != 'string' && typeof(valor) != 'number') return '';
	
	if(typeof(valor) == 'string'){
		valor = valor.replaceAll(',','');
		valor = parseFloat(valor);
	}
	
	if(typeof(valor) != 'number' ) {
		return 'NaN';
	} 
	
	if(typeof(decimales) != 'number' ) {
		var decimales=2;
	} 

	valor = valor.toFixed(decimales);	
	valor = valor.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	return valor;	
	
}

Template7.registerHelper('format', function (valor, decimales){
	//return valor;
	
	if(typeof(decimales)!= 'number' ) {
		var decimales = 2;
	} 
	
	var valor = format(valor, decimales);

	return valor;
});


/*
 * jQuery MD5 Plugin 1.2.1
 * https://github.com/blueimp/jQuery-MD5
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 * 
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/*jslint bitwise: true */
/*global unescape, jQuery */

(function ($) {
    'use strict';

    /*
    * Add integers, wrapping at 2^32. This uses 16-bit operations internally
    * to work around bugs in some JS interpreters.
    */
    function safe_add(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }

    /*
    * Bitwise rotate a 32-bit number to the left.
    */
    function bit_rol(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    /*
    * These functions implement the four basic operations the algorithm uses.
    */
    function md5_cmn(q, a, b, x, s, t) {
        return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
    }
    function md5_ff(a, b, c, d, x, s, t) {
        return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }
    function md5_gg(a, b, c, d, x, s, t) {
        return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }
    function md5_hh(a, b, c, d, x, s, t) {
        return md5_cmn(b ^ c ^ d, a, b, x, s, t);
    }
    function md5_ii(a, b, c, d, x, s, t) {
        return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
    }

    /*
    * Calculate the MD5 of an array of little-endian words, and a bit length.
    */
    function binl_md5(x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << ((len) % 32);
        x[(((len + 64) >>> 9) << 4) + 14] = len;

        var i, olda, oldb, oldc, oldd,
            a =  1732584193,
            b = -271733879,
            c = -1732584194,
            d =  271733878;

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = md5_ff(a, b, c, d, x[i],       7, -680876936);
            d = md5_ff(d, a, b, c, x[i +  1], 12, -389564586);
            c = md5_ff(c, d, a, b, x[i +  2], 17,  606105819);
            b = md5_ff(b, c, d, a, x[i +  3], 22, -1044525330);
            a = md5_ff(a, b, c, d, x[i +  4],  7, -176418897);
            d = md5_ff(d, a, b, c, x[i +  5], 12,  1200080426);
            c = md5_ff(c, d, a, b, x[i +  6], 17, -1473231341);
            b = md5_ff(b, c, d, a, x[i +  7], 22, -45705983);
            a = md5_ff(a, b, c, d, x[i +  8],  7,  1770035416);
            d = md5_ff(d, a, b, c, x[i +  9], 12, -1958414417);
            c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
            b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
            a = md5_ff(a, b, c, d, x[i + 12],  7,  1804603682);
            d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
            c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
            b = md5_ff(b, c, d, a, x[i + 15], 22,  1236535329);

            a = md5_gg(a, b, c, d, x[i +  1],  5, -165796510);
            d = md5_gg(d, a, b, c, x[i +  6],  9, -1069501632);
            c = md5_gg(c, d, a, b, x[i + 11], 14,  643717713);
            b = md5_gg(b, c, d, a, x[i],      20, -373897302);
            a = md5_gg(a, b, c, d, x[i +  5],  5, -701558691);
            d = md5_gg(d, a, b, c, x[i + 10],  9,  38016083);
            c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
            b = md5_gg(b, c, d, a, x[i +  4], 20, -405537848);
            a = md5_gg(a, b, c, d, x[i +  9],  5,  568446438);
            d = md5_gg(d, a, b, c, x[i + 14],  9, -1019803690);
            c = md5_gg(c, d, a, b, x[i +  3], 14, -187363961);
            b = md5_gg(b, c, d, a, x[i +  8], 20,  1163531501);
            a = md5_gg(a, b, c, d, x[i + 13],  5, -1444681467);
            d = md5_gg(d, a, b, c, x[i +  2],  9, -51403784);
            c = md5_gg(c, d, a, b, x[i +  7], 14,  1735328473);
            b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

            a = md5_hh(a, b, c, d, x[i +  5],  4, -378558);
            d = md5_hh(d, a, b, c, x[i +  8], 11, -2022574463);
            c = md5_hh(c, d, a, b, x[i + 11], 16,  1839030562);
            b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
            a = md5_hh(a, b, c, d, x[i +  1],  4, -1530992060);
            d = md5_hh(d, a, b, c, x[i +  4], 11,  1272893353);
            c = md5_hh(c, d, a, b, x[i +  7], 16, -155497632);
            b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
            a = md5_hh(a, b, c, d, x[i + 13],  4,  681279174);
            d = md5_hh(d, a, b, c, x[i],      11, -358537222);
            c = md5_hh(c, d, a, b, x[i +  3], 16, -722521979);
            b = md5_hh(b, c, d, a, x[i +  6], 23,  76029189);
            a = md5_hh(a, b, c, d, x[i +  9],  4, -640364487);
            d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
            c = md5_hh(c, d, a, b, x[i + 15], 16,  530742520);
            b = md5_hh(b, c, d, a, x[i +  2], 23, -995338651);

            a = md5_ii(a, b, c, d, x[i],       6, -198630844);
            d = md5_ii(d, a, b, c, x[i +  7], 10,  1126891415);
            c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
            b = md5_ii(b, c, d, a, x[i +  5], 21, -57434055);
            a = md5_ii(a, b, c, d, x[i + 12],  6,  1700485571);
            d = md5_ii(d, a, b, c, x[i +  3], 10, -1894986606);
            c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
            b = md5_ii(b, c, d, a, x[i +  1], 21, -2054922799);
            a = md5_ii(a, b, c, d, x[i +  8],  6,  1873313359);
            d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
            c = md5_ii(c, d, a, b, x[i +  6], 15, -1560198380);
            b = md5_ii(b, c, d, a, x[i + 13], 21,  1309151649);
            a = md5_ii(a, b, c, d, x[i +  4],  6, -145523070);
            d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
            c = md5_ii(c, d, a, b, x[i +  2], 15,  718787259);
            b = md5_ii(b, c, d, a, x[i +  9], 21, -343485551);

            a = safe_add(a, olda);
            b = safe_add(b, oldb);
            c = safe_add(c, oldc);
            d = safe_add(d, oldd);
        }
        return [a, b, c, d];
    }

    /*
    * Convert an array of little-endian words to a string
    */
    function binl2rstr(input) {
        var i,
            output = '';
        for (i = 0; i < input.length * 32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
        }
        return output;
    }

    /*
    * Convert a raw string to an array of little-endian words
    * Characters >255 have their high-byte silently ignored.
    */
    function rstr2binl(input) {
        var i,
            output = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0;
        }
        for (i = 0; i < input.length * 8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
        }
        return output;
    }

    /*
    * Calculate the MD5 of a raw string
    */
    function rstr_md5(s) {
        return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
    }

    /*
    * Calculate the HMAC-MD5, of a key and some data (raw strings)
    */
    function rstr_hmac_md5(key, data) {
        var i,
            bkey = rstr2binl(key),
            ipad = [],
            opad = [],
            hash;
        ipad[15] = opad[15] = undefined;                        
        if (bkey.length > 16) {
            bkey = binl_md5(bkey, key.length * 8);
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5C5C5C5C;
        }
        hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
        return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
    }

    /*
    * Convert a raw string to a hex string
    */
    function rstr2hex(input) {
        var hex_tab = '0123456789abcdef',
            output = '',
            x,
            i;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hex_tab.charAt((x >>> 4) & 0x0F) +
                hex_tab.charAt(x & 0x0F);
        }
        return output;
    }

    /*
    * Encode a string as utf-8
    */
    function str2rstr_utf8(input) {
        return unescape(encodeURIComponent(input));
    }

    /*
    * Take string arguments and return either raw or hex encoded strings
    */
    function raw_md5(s) {
        return rstr_md5(str2rstr_utf8(s));
    }
    function hex_md5(s) {
        return rstr2hex(raw_md5(s));
    }
    function raw_hmac_md5(k, d) {
        return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
    }
    function hex_hmac_md5(k, d) {
        return rstr2hex(raw_hmac_md5(k, d));
    }
    
    $.md5 = function (string, key, raw) {
        if (!key) {
            if (!raw) {
                return hex_md5(string);
            } else {
                return raw_md5(string);
            }
        }
        if (!raw) {
            return hex_hmac_md5(key, string);
        } else {
            return raw_hmac_md5(key, string);
        }
    };
    
}(typeof jQuery === 'function' ? jQuery : this));



