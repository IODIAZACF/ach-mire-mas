$(document).on( 'click', '.grid_encab, [class^="grid_rotulo_pie"], [class^="rotulo_"], .grid_titulo, .grid_status, .rotulo_formulario, .texto_leyenda, .boton_leyenda, .tecla_leyenda', function(){
	
	//console.log( $(this).attr("class") );
	
	if ( !debug() ) return;
	
	var origen = $(this).closest('.origen').data('origen');
	//console.log('--->' + origen);
	
	//console.log('------->' + origen);
	
	//$('.grid_encab, [class^="grid_rotulo_pie"], [class^="rotulo_"], .grid_titulo, .grid_status').css('border','none');

	var clase = '';
	

	if( $(this).hasClass('contenedor_barra_titulo') ){

		clase = 'VEN';
		$(this).parents('.submodal').last().find('.fondo_formulario' ).each(function(){
			
			if ( $(this).css('display')	== 'block' ){
				origen = $(this).find('form').data('origen');
				return;
			}
		});
		
	}
	
	if( $(this).hasClass('grid_encab') ){
		clase = 'COL';
	}

	if( $(this).hasClass('grid_encab_resumen') ){
		clase = 'VEN';
	}

	if( $(this).hasClass('grid_status') ){
		clase = 'TAB';
	}
	
	if( $(this).hasClass('grid_rotulo_pie') || $(this).hasClass('grid_rotulo_pie_num') || $(this).hasClass('grid_rotulo_pie_date') ){
		clase = 'PIE';
	}

	if( $(this).hasClass('rotulo_pie')  ){
		clase = 'PIE';
	}

	if( $(this).hasClass('rotulo_encabezado') ){
		clase = 'ENC';
	}
	
	if( $(this).hasClass('rotulo_resumen') ){
		clase = 'CAM';
	}

	if( $(this).hasClass('grid_titulo') ){
		clase = 'VEN';
	}
	
	if( $(this).hasClass('rotulo_formulario') ){
		clase = 'CAM';
	}
	
	if( $(this).hasClass('boton_leyenda') || $(this).hasClass('rotulo_leyenda') || $(this).hasClass('tecla_leyenda') || $(this).hasClass('texto_leyenda') ){
		clase = 'LEY';
		return;
	}
	
	if(!origen) return;

	//$(this).css('border','solid 2px green');
	
	var ini = inis[origen];
	if( !ini ){
		console.error('El ini ' + origen + ' no está en memoria..! ');	
		return;
	} 
	//console.log(ini);
	
	var campo    = $(this).data('campo');
	var seccion	 = '';
	var tipo     = '';
	
	var sHtml = '';
	var fHtml = '';
	fHtml += '				<tr><td class="is-size-7 is-vcentered">ORIGEN</td><td><input name="origen" class="input is-small" type="text" value = "' + origen + '" placeholder="" readonly></td></tr>';
	
	$.each( ini, function(xsec, xvar){
		//console.log( xsec + ' - ' + xvar  );
		
		if( clase ==  xsec.substring(0,3) && xvar.CAMPO == campo ){
			
			seccion  =  xsec;
			sHtml += '				<tr><td class="is-size-7 is-vcentered">SECCION</td><td><input name="seccion"   class="input is-small" type="text" value = "' + seccion   + '" placeholder="" readonly></td></tr>';

			$.each( xvar, function(campo, valor){
				if(campo == 'ONOMBRE' || campo == 'COMENTARIO') return;

				fHtml += '				<tr><td class="is-size-7 is-vcentered">' + campo + '</td><td><input name="v_' + campo + '"   class="input is-small" type="text" value = "' + valor   + '" placeholder=""></td></tr>';
			});
			
		}
	});

	if(fHtml == '') return;
	
	if(!tipo) tipo = 'C';

	var xHtml  = '';
		xHtml += '<div  id="modal_form" class="modal is-active">';
		xHtml += '	<div class="modal-background"></div>';
		xHtml += '	<div class="modal-card">';
		xHtml += '		<header class="modal-card-head py-3	">';
		xHtml += '			<p class="modal-card-title is-size-6">Editor de Archivo INI</p>';
		xHtml += '			<button class="delete cancel" aria-label="close"></button>';
		xHtml += '		</header>';
		xHtml += '		<section class="modal-card-body">';
		xHtml += '			<form id="ini_form">';
		xHtml += '			<table class="table" width="100%" border="0">';

		xHtml +=  sHtml;
		xHtml +=  fHtml;
		
		xHtml += '			</table>';
		xHtml += '			</form>';
		xHtml += '		</section>';
		xHtml += '		<footer class="modal-card-foot py-3">';
		xHtml += '			<button class="button is-success save">Guardar</button>';
		xHtml += '			<button class="button is-success cancel">Cancel</button>';
		xHtml += '			<button class="button is-success editar_ini">Editar Archivo</button>';
		xHtml += '		</footer>';
		xHtml += '	</div>';
		xHtml += '</div>';			
	
	$("body").append(xHtml);
	
	$(document).on('click', '.cancel' , function(){
		$("#modal_form").remove();
		$('.grid_encab,  [class^="grid_rotulo_pie"], [class^="rotulo_"], .grid_titulo, .grid_status').css('border','none');

	});
	
	$(document).on('click', '.save' , function(event){
		event.stopPropagation();
		var url = server_path + 'herramientas/ini/guardar.php'; 
		var params = $( "#ini_form" ).serialize()
		
		loading(1);
		var x = enviar(url, params, 'GET');
		//console.log( url + '?' + params)
		loading(0);
		
		$("#modal_form").remove();
	});
	
	$(document).on('click', '.editar_ini' , function(event){
		if(!origen) return;
		var src = parent.$("#proceso").attr('src');

		var path = '/opt/lampp/htdocs' + src.split('/').slice(0, -2).join('/');

		if( src.split('/').slice(2, -2).join('/') == '/herramientas/maestro') {
			path = '/opt/lampp/htdocs' + src.split('/').slice(0, -4).join('/');
		}
		
		var file = path + '/' + origen + '.ini';
		
		parent.editFile( file );
		origen = '';
		$("#modal_form").remove();
	});

});




/************************************************************/
/*** Variables del manejo de Teclas		 					*/
/************************************************************/
var _enter=13;
var _esc=27;
var _insert=45;
var _supr=46;
var _f1=112;
var _f2=113;
var _f3=114;
var _f4=115;
var _f5=116;
var _f6=117;
var _f7=118;
var _f8=119;
var _f9=120;
var _f10=121;
var _f11=122;
var _f12=123;
var _tab=9;
var _barra=32;
var _pg_arriba=33;
var _pg_abajo=34;
var _inicio=36;
var _fin=35;
var _der=39;
var _izq=37;
var _arriba=38;
var _abajo=40;

var Findex = 0;
var Factivo = '';
var Fgrid = new Array();
var zIndexGral = 0;
var xmlhttp;
var valor_nulo = '';
var xGrids = new Array();
var tagJS = 'javascript';

var mPrompt = false;
var contLines = 0
var f_activo;

if(window.XMLHttpRequest)
{
  xmlhttp = new XMLHttpRequest();
}
else if(window.ActiveXObject)
{
  xmlhttp = new ActiveXObject("MSXML2.XMLHTTP");
}

/********************************************/
/*	devuelve el nombre de quien 			*/
/* llamó a la funcion actual				*/
/********************************************/

function getStackTrace () {
	var stack;
	try {
		throw new Error('');
	}
	catch (error) {
		stack = error.stack || '';
	}
	return  stack.split('\n')[3].split(' at ')[1];
}


/************************************************************/
/*** Funcion para enviar a la consola del sistema 			*/
/*** errores y debug										*/
/************************************************************/

function _prompt( funcion, mensaje, url ){
	
	var objD = $('#ObjDebugO24', window.parent.document);

	contLines++;

	var modulo = window.location.pathname.split('/');

	if(url){
		
		var tipo = url.substring(0,5).toLowerCase();
		if(tipo=='http:' || tipo=='https'){
			//texto2 ='<a href="' + texto2 + '" target="_blank">'+ texto2 +'</a>';
			url = '<a class="has-text-grey" href="' + url + '" target="_blank"> ver </a>';
		}
		else{
			url = url.htmlEntities() ;
		}
	}

	var xHtml  = '';
		xHtml += '<div class="columns">';
		xHtml += '<div class="column is-1">' + contLines  + '</div>';
		xHtml += '<div class="column is-1">' + funcion 	 + '</div>';
		xHtml += '<div class="column is-5">' + mensaje    + '</div>';
		xHtml += '<div class="column is-5" onclick="navigator.clipboard.writeText($(this))">' + url 		 + '</div>';
		xHtml += '</div>';
	$('#ObjDebugO24', window.parent.document).prepend(xHtml);
	//console.log(url);
	//console.log(xHtml);
  

}


/************************************************************/
/*** Funcion para reemplazar dinamicamente 					*/
/*** el contenido de los Encabezado y Pie de Modulo			*/
/*** por el tipo de dato y decimales que tenga en el ini	*/
/************************************************************/



var prevent = [];

$(document).on('DOMSubtreeModified', '.valor', function ( x ) {
	
	var id = $(this).attr('id');
	
	if( prevent[id] ) return;


	if( ! $(this).data('tipo') ) return;

	var xvalor = $(this).html();

	var xdecimal = 0;
	
	var tmp = $(this).data('tipo');
	var xtipo    = tmp.substring(0,1); 
	var xdecimal = tmp.substring(2,1).trim();
	
	if( xdecimal == '' ) xdecimal = 0;

	if( !xtipo || !xvalor ) return;
	
	switch (xtipo){
		case 'I': // ENTERO
			if( xvalor == "&nbsp;") xvalor = 0;
			
			var tmp = xvalor.split(".");
			if (tmp.length>0) xvalor = tmp[0];
			
			$(this).css( 'text-align', 'right' );
			break;

		case 'N': // NUMERICO
			if( xvalor == "&nbsp;") xvalor = 0;

			if( !xdecimal || xdecimal == 0 ) xdecimal = 2;
			xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);

			$(this).css( 'text-align', 'right' );
			break;

		case 'F': // FORMATEADO
			if( xvalor == "&nbsp;") xvalor = 0;

			xvalor = parseFloat(unformat(xvalor)).toFixed(xdecimal);
			xvalor = format( xvalor , xdecimal ) ;

			$(this).css( 'text-align', 'right' );
			break;

		case 'D': // Fecha
			$(this).css( 'text-align', 'center' );
			
			break;

		default:
			break;
	}

	prevent[id] = true;
	$(this).html( xvalor );
	prevent[id] = false;

});



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

/************************************************************/
/*** Funcion que detecta que un elemento del dom 			*/
/*** tiene las propiedades data-funcion y data-opcion		*/
/*** y al hacer click sobre este ejecuta esa funcion 		*/
/*** y le pasa el valor de la opcion 						*/
/************************************************************/
$(document).on('click','*[data-funcion]', function() {
 
	var obj = $(this);
	var funcion = obj.data('funcion');
	var opcion  = obj.data('opcion');
	var valor   = obj.data('valor');
	if (opcion) valor = opcion; 
	
	if(funcion) {
		var fn = window[funcion];
		if (typeof fn === "function") {
			fn.apply(null, [obj, valor]);
		} else {
			console.error("Error funcion no encontrada.!! ->" + funcion + ' -> ' + opcion );
		}
	}
});


/********************************************************/
/*** funcion para mostrar figura de espera 				*/
/*** estatus = 1 muestra								*/
/*** estatus = 0 oculta 								*/
/********************************************************/

function loading( estatus ){

	if( estatus == 1){
		$('#LOADING').show();
	} else {
		$('#LOADING').hide();
		
	}
}

function valida_xml(xml, campo){
	//parser = new DOMParser();
	//xmlDoc = parser.parseFromString(xml,"text/xml");

	//console.log( xmlDoc.getElementsByTagName("error")[0].childNodes[0].nodeValue );
	//document.getElementById("error").innerHTML =
	//console.log( xmlDoc.getElementsByTagName("error")[0].childNodes[0].nodeValue );
	
	var registro = XML2Array(xml);
    if(!registro) return false;
    if(!registro[0]) return false;
    if(campo) if(!registro[0][campo]) return false;
    return registro;
}


function enviar(url, params, metodo, callback)
{
	var var_async = false;
	var var_type  = "html";
	
	if(callback) {
		var_async = true;
		var_type  = "jsonp";
	}
		
	var resp = new String;
	var modulo = window.location.pathname;
	var aurl=url.split('?');
	url = aurl[0];
	
	if (aurl[1]){
		params+='&'+aurl[1];
	}

	if(typeof isS24Maestro === 'undefined'){
		if(params){
			params+='&url_modulo=' + modulo ;
		}
		else
		{
			params+='url_modulo=' + modulo ;
		}	  
	}

	
	if (debug()){
		_prompt("utiles.js", 'Funcion: enviar', url + "?" + params + '&header=1');
	}
		
	metodo.toUpperCase();
	var paso = false;
	var resp = '';
	
	
	$.ajax({
		async: var_async,
		type: metodo,
		dataType: var_type,
		url: url,
		data: params,
		success: function(data){
			resp = data;
		},
		
		complete: function(data){
			paso = true;
			if(callback) {
				setTimeout( function(){ callback(data.response); }, 100);
			} else {
				return data.response;
			}
		},

		statusCode: {
			404: function (xhr) {
					console.error('No hay Conexion al servidor');
				}
		}			
	});

	if(!var_async){
		while(!paso)
		{

		}
		return resp;
		
	}
}



function enviarAsinc(url, params, func, funcErr, objName, msg){
	
	var resp = new String;
	var modulo = window.location.pathname;
	var aurl=url.split('?');
	url = aurl[0];

	if (aurl[1]){
		params+='&'+aurl[1];
	}

	if(typeof isS24Maestro === 'undefined'){
		//alert('no existe')
		if(params){
			params+='&url_modulo='+ modulo ;
		}
		else{
			params+='url_modulo='+ modulo ;
		}	  
	}

	if (debug()){
		_prompt("utiles.js", 'Funcion: enviarAsinc', url + "?" + params + '&header=1');
	}

	//'onSuccess'   : function(req){var f = eval(func); if (f) f(req.responseText, objName, msg)},
    var paso = false;
    var resp = '';
        $.ajax({
        async:true,
        type: 'POST',
        dataType: "html",
        url: url,
        data: params,
        success: function(data){
            resp = data;
            var funcion = eval(func);
            funcion(data, objName, msg);
        },
        complete: function(data){
            paso = true;
            },
        });
}

function Salir() {
    parent.proceso.location.href = server_path + 'main/inicio.php';
}

function centrarObj(obj, debugging)
{
    if(obj.style.position != 'absolute') obj.style.position = "absolute";

	obj.parentNode.style.position = "relative";

	obj.style.left = "50%";
	obj.style.top = "50%";
	obj.style.transform = "translate(-50%,-50%)";
	return;
}


function mostrarLeyenda(panel){
    $("div#modulo .tabla_leyenda div[panel]").hide();
    $("div#modulo .tabla_leyenda div[panel="+panel+"]").show();
}

function etiqLeyenda(tecla, texto, ancho, accion, class_icono){

	var tecla = tecla.trim();	
	if( typeof ancho  == 'string') ancho = parseFloat(ancho);
	
	if(!class_icono) class_icono = standard_iconos(tecla);
	
	if(class_icono) ancho = ancho + 35;
	
	var e  = '';
		e += '<div onselectstart="return false;" style="width:' + ancho + 'px"; class="td_leyenda_inactiva"  onclick="' + accion + '">';
		
		if(class_icono){
			e +=' <span class="icono_leyenda"><i class="' + class_icono + '"></i></span>';
		}
		
		e += '	<span class="texto_leyenda">';
		e += '		<div class="tecla_leyenda">';
		e += 			tecla;	
		e += '		</div>';
		e += '		<div class="rotulo_leyenda">';			
		e +=  			texto;		
		e += '		</div>';	
		e += '	</span>';
		e += '</div>';

	return e;
  
}

function standard_iconos( tecla ){

	var class_icono = '';
	
	switch ( tecla.toLowerCase() ){
		case 'ins':
		case 'insert':
			class_icono = 'fa-solid fa-plus fa-2x';
		break;
		
		case 'supr':
		case 'del' :
			class_icono = 'fa-solid fa-minus fa-2x';
		break;
		
		case 'selec':
			class_icono = 'fa-solid fa-arrow-right-to-bracket fa-2x';
		break;

		case 'edit':
			class_icono = 'fa-solid fa-pen-to-square fa-2x';
		break;

		case 'f12':
			class_icono = 'fa-solid fa-plus fa-2x';
		break;

		case 'esc':
			class_icono = 'fa-solid fa-arrow-right-from-bracket fa-2x';
			
		break;
		
		default:
			class_icono = 'fa-solid fa-arrows-to-circle fa-2x';
	}
	
	return class_icono;
	
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};



function enviar2(url, params, metodo, callback){
	
	var modulo = window.location.pathname;
	xauth = '';
	llave = '12345';
	params = 'auth=' + xauth + '&' + params + '&db=' + db + 'url_modulo=' + modulo ;; 
	
	var paso = false;
    var resp= null;
    $.ajax({
    	async: true,
        method: metodo,
	    dataType: "text",
	    url: url,
        data: params,
		headers: {
			'llave': llave,
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


function fechaYMD(d1) //Fromato YYYYMMDD
{
  if(!d1) return;
  d1=d1.substr(6,4)+d1.substr(3,2)+d1.substr(0,2);
  return d1;
}

function fechaMDY(d1)    //Fromato MM/DD/YYY
{
  if(!d1) return;
   d1=d1.substr(3,2)+'/'+d1.substr(0,2)+'/'+d1.substr(6,4);
  return d1;
}


function limpiarElementos(idPrefijo){	
	var prefijos=idPrefijo.split(',');
	for (var i=0;i<prefijos.length;i++){
		$("[id^='" + prefijos[i] + "']").html("&nbsp;");
	}
}


function _(str_id){
  return document.getElementById(str_id);
}

function comparaFechas(d1,d2) //Valida si Fecha d1 es menor que d2
{
    if(!d1 || !d2) return 1;
    if (trim(d1)=='/  /') return 1;
    if (trim(d2)=='') return 1;
    if (trim(d2)=='/  /') return 1;
    var v = (fechaYMD(d2)-fechaYMD(d1))
    if(v>0) return 1
    if(v==0) return 0
	if(v<0) return -1
}


String.prototype.htmlEntities = function ()
{
	return this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
};



function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 10; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

/*************************************/
/*            TECLADO                */
/*************************************/

var ver    = parseFloat (navigator.appVersion.slice(0,4));
var verIE  = (navigator.appName == "Microsoft Internet Explorer" ? ver : 0.0);
var verNS  = (navigator.appName == "Netscape" ? ver : 0.0);
var verOP  = (navigator.appName == "Opera"    ? ver : 0.0);
var verOld = (verIE < 4.0 && verNS < 5.0);
var isMSIE = (verIE >= 4.0);


function tecla(e) {

    var myKeyCode;
    var evt = window.event || e;
    if (evt) myKeyCode = window.event.keyCode;
    else e.which;
//    var mySrcElement   = (!isMSIE) ? e.target : e.srcElement;
//    var isShiftPressed = e.shiftKey;
//    var isCtrlPressed  = e.ctrlKey;
//    var isAltPressed   = e.altKey;

    if (verOld) return true;

    // Enter(13), Shift(16), Ctrl(17), Alt(18), CapsLock(20) keys?
    //if (myKeyCode >= 13 && myKeyCode <= 20)
    //    return true;

    /************************************************/
    // alert("La Tecla Presionada fue: "+ myKeyCode);
    /************************************************/


    eval(buscatecla(myKeyCode));
}


function buscatecla(keycode) {

    for (var i = 0; i < mapa.length; i++)
    {
        if (mapa[i].codigo == keycode) return mapa[i].funcion;
    }

    for (var i = 0; i < mapa.length; i++)
    {
        if (mapa[i].codigo == -1) return mapa[i].funcion;
    }

}

/****************************************************************************
  Funcion para importar los datos de un XML...
****************************************************************************/
var xmlDoc;

var moz = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined');
var ie = (typeof window.ActiveXObject != 'undefined');

if (moz)
{
    xmlDoc = document.implementation.createDocument("", "", null)
    xmlDoc.async = false;
    xmlDoc.onload = function () {};
}
else if (ie)
{
    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async = false;
    while(xmlDoc.readyState != 4) {};
}


/**********************************************************************
  Eso es para la creacion de un Array en base a un texto XML bien formado
  Estructura retornada:
        RetData[0] = array de parametros

  RetData[0]["campo"] => valor del campo "campo" del registro 0  (1)
  RetData[5]["color"] => parametro asignado al campo "color" del registro 5  (6)

**********************************************************************/
function XML2Array(textoXML, rama, filtro){
   
   //console.log( XML2Array.caller );
      
   var aData = new Array;
   var aParams = new Array;
   var uxmlDoc = null;

	if(textoXML=='') return aData;
	try{
		xmlDoc.async = false;

		xmlDoc.loadXML(textoXML);
	 
		if(xmlDoc.parseError && xmlDoc.parseError.errorCode != 0){
			var errorMsg = "XML2Array\nXML Parsing Error: " + xmlDoc.parseError.reason
							  + " at line " + xmlDoc.parseError.line
							  + " at position " + xmlDoc.parseError.linepos;

			if(textoXML != '<tabla><registro></registro></tabla>'){
				var xxxx=  _prompt('XML2Array: ',textoXML);
				console.error( 'XML2Array -->' + textoXML + '--' + errorMsg);
			}
			return aData;
		}

	}
	catch(e){
		if (document.implementation.createDocument){
			var parser = new DOMParser();
			uxmlDoc = parser.parseFromString(textoXML, "text/xml");
		}
		
		if((xmlDoc == null) && (uxmlDoc == null)){
			alert("XML Doc Load Failed (catch)");
			return aData;
		}
	}
   
	if (!uxmlDoc) uxmlDoc = xmlDoc;

	var error = uxmlDoc.getElementsByTagName("error");
	
	if( error.length > 0 ){
		var query = uxmlDoc.getElementsByTagName("query");
		_prompt('utiles.js', 'Funcion: XML2Array - Origen: ' + getStackTrace() , error[0].textContent + query[0].textContent );
		
		return false;
	}
		
	var tabla = uxmlDoc.getElementsByTagName("tabla");

	if (!rama)
	{
     if (tabla.length > 0) regs = tabla[0].getElementsByTagName("registro");
     else return aData;

     if (!regs) var registros = 0; else var registros = regs.length;

     for (var i=0; i<registros; i++)
     {
       aData[i] = new Array;
       aParams[i] = new Array;
       for (var j=0; j<regs[0].childNodes.length; j++)
       {
         nodo = regs[0].childNodes[j].nodeName;
         objNodo = uxmlDoc.getElementsByTagName(nodo);

         if (objNodo.length)
         {
           var elem = objNodo[i].firstChild;
           if (elem)
           {
             switch (elem.nodeType)
             {
               case 4:
                 valor = elem.data;
                 break;
               case 3:
                 valor = elem.nodeValue;
                 break;
               default:
                 valor = valor_nulo;
             }
           } else valor = valor_nulo;

           aData[i][nodo] = valor;
         }
       }
/*       if(aData[i]['ERROR'])
       {
        alert(aData[i]['ERROR']);
        return null;
       }
*/
	 }
	}
   else
   {
     if (tabla.length > 0) regs = tabla[0].getElementsByTagName(rama);
     else return aData;

     aData[0] = new Array;

     if (!regs) var registros = 0; else var registros = regs.length;

     try
     {
       for (j=0; j<regs[0].childNodes.length; j++)
       {
         nodo = regs[0].childNodes[j].nodeName;
         elem = regs[0].childNodes[j].firstChild;

         if (elem)
         {
           switch (elem.nodeType)
           {
             case 4:
               valor = elem.data;
               break;
             case 3:
               valor = elem.nodeValue;
               break;
             default:
               valor = valor_nulo;
           }
         } else valor = valor_nulo;

         aData[0][nodo] = valor;
       }
     }
     catch(e){}
     finally{}
   }

   return aData;
}


/**********************************
/         MANEJO DE EVENTOS       /
**********************************/
 /*
 * X-browser event handler attachment and detachment
 *
 * @argument obj - the object to attach event to
 * @argument evType - name of the event - DONT ADD "on", pass only "mouseover", etc
 * @argument fn - function to call
 */

function addEvent(obj, evType, fn){
	
	$(obj).on( evType, fn );
	
	return;
	/*
	if (obj.addEventListener){
		obj.addEventListener(evType, fn, true);
		return true;
	} else if (obj.attachEvent){
		var r = obj.attachEvent("on" + evType, fn);
		return r;
	} else {
		return false;
	}
	*/
}

function removeEvent(obj, evType, fn, useCapture){
	if (obj.removeEventListener){
		obj.removeEventListener(evType, fn, useCapture);
		return true;
	} else if (obj.detachEvent){
		var r = obj.detachEvent("on"+evType, fn);
		return r;
	} else {
		alert("Handler could not be removed");
	}
}


/* funcion
HTML : actualiza los innerHTML segun un XML (Marco) */

function actualizaHTML(xml, prefijo, rama){
    limpiarElementos(prefijo);
	
	if(!rama) rama = 'registro';

    var a = XML2Array(xml, rama);
    for (var x in a[0]){
		if( a[0][x]  ){
			$('#' + prefijo + x ).html( a[0][x] );
			
		}
    }
}

function actualizaTag(nombre, valor){
	$("#" + nombre).html( valor );
}

/*---------- BARRA DE PROGRESO --------------------*/
function progreso(x, y, alto, ancho, maximo)
{
    this.x        = x;
    this.y        = y;
    this.alto     = alto;
    this.ancho    = ancho;
    this.maximo   = maximo;
    this.prog     = null;
    this.tabla    = null;
    this.posic    = 0;

    var oTabla = document.createElement('table');
    var oTBody = document.createElement('tbody');
    var oTr = document.createElement('tr');
    var oTd1 = document.createElement('td');
    var oTd2 = document.createElement('td');

    oTr.appendChild(oTd1);
    oTr.appendChild(oTd2);
    oTBody.appendChild(oTr);
    oTabla.appendChild(oTBody);

    oTabla.style.display  = 'none';
    oTabla.style.position = 'absolute';
    oTabla.style.width    = this.ancho;
    oTabla.style.height   = this.alto;
    oTabla.style.top      = y;
    oTabla.style.left     = x;
    oTabla.style.border   = 'solid 1px #000000';

    oTd1.style.backgroundColor = '#FF0000';
    oTd1.style.width           = 0;

    this.tabla = oTabla;
    this.prog  = oTd1;

    document.body.appendChild(oTabla);
}

progreso.prototype.posicion = function(posic)
{
  if (!this.maximo) return;
  if (!posic)
  {
    this.prog.style.display = 'none';
    return;
  }
  this.prog.style.display = 'block';
  npos = (posic * this.ancho) / this.maximo;
  this.prog.style.width = npos + 'px';
  this.posic = posic;
};

progreso.prototype.mostrar = function()
{
  this.tabla.style.display = 'block';
};

progreso.prototype.ocultar = function()
{
  this.tabla.style.display = 'none';
};

progreso.prototype.avanzar = function()
{
  if (this.posic >= this.maximo) return;
  this.prog.style.display = 'block';
  this.posic++;
  this.posicion(this.posic);
};

/*  USO DE LA BARRA DE PROGRESO ..
p = new progreso(100,100,50,400,20);
p.posicion(0);
p.mostrar();
*/



/*
------------------------------------------------------------------------------
Funcion parseINI(xml)
  Funcion generica para convertir xml en array de tipo
    "Arr[seccion][campo] = valor"
------------------------------------------------------------------------------
*/
function parseINI(xml, xTag)
{
   //alert('xxx: '+ xml);
   var xData = new Array;
   var uxmlDoc = false;

   try
   {
     xmlDoc.loadXML(xml);
	 if(xmlDoc.parseError.errorCode)
	 {
	        alert("Parse XML2Array\nLine:"+ xmlDoc.parseError.line +"\nLine Pos:"+ xmlDoc.parseError.linepos +"\nXML Doc Load Failed\n" + xmlDoc.parseError.srcText);
	        return;
	 }
   }
   catch(e)
   {
     if (document.implementation.createDocument)
     {
       var parser = new DOMParser();
       uxmlDoc = parser.parseFromString(xml, "text/xml");
     }
     if((xmlDoc == null) && (uxmlDoc == null))
     {
       alert("XML Doc Load Failed");
       return;
     }
   }

   if (!uxmlDoc) uxmlDoc = xmlDoc;

    var error = uxmlDoc.getElementsByTagName("error");
	
	if( error.length > 0 ){

		var ERROR = uxmlDoc.getElementsByTagName("ERROR");
		_prompt('utiles.js', 'Funcion: parseINI - Origen: ' + getStackTrace() , ERROR[0].textContent );
		return false;
	}

	otabla = uxmlDoc.getElementsByTagName(xTag?xTag:"ini");

   for (var j=0;j<otabla[0].childNodes.length;j++)
   {
       var oseccion = otabla[0].childNodes[j];  ///getElementsByTagName("VENTANA"));
       var seccion  = oseccion.nodeName;

       xData[seccion] = new Array;

           for (var i=0;i<oseccion.childNodes.length;i++)
           {
                var campo = oseccion.childNodes[i].nodeName;
                var valor=null;

                if (oseccion.childNodes[i].firstChild)
                {
                   var elem = oseccion.childNodes[i].firstChild;
                   switch (elem.nodeType)
                   {
                     case 4:
                       valor = elem.data;
                       break;
                     case 3:
                       valor = elem.nodeValue;
                       break;
                     default:
                       valor = valor_nulo;
                   }
                }
                else valor = ' ';
                xData[seccion][campo] = valor;
           }
   }
   return xData;

}




function ayuda(codigo,enter,titulo,origen)
{
  obj = parent;
  var modulo = window.location.pathname;
  var n = 0;

  while (obj && (!obj.v_ayuda) && (n<20))
  {
    obj = obj.parent;
    n++;
  }

  if((!obj)||(!obj.v_ayuda) || (n>=20))
  {
     alert('Debug: La ventana de Ayuda No esta Definida');
     return;
  }

  var xenter='';
  if((enter!='undefined') &&  (enter !=null))
  {
    xenter = '&enter='+ enter;
  }
  var randomnumber=Math.floor(Math.random()*10000000)

  var url  = server_path + 'herramientas/ayuda/ayuda.php?codigo=' + codigo + xenter + '&rnid='+ randomnumber + '&titulo='+ escape(titulo) + '&origen='+ origen + '&nombre_usuario='+ top.xusuario_sistema + '&url='+ modulo;

  obj.v_ayuda.leeUrl(url);
  //centrarObj(obj.v_ayuda.contenedor);
  obj.v_ayuda.mostrar();
  obj.v_ayuda.setFocus();
}

function debug()
{
    return top.debugVar;
}

function edit()
{
    return top.editVar;
}

function getexpirydate( nodays){
  var UTCstring;
  Today = new Date();
  nomilli=Date.parse(Today);
  Today.setTime(nomilli+nodays*24*60*60*1000);
  UTCstring = Today.toUTCString();
  return UTCstring;
}

function setCookie(name,value,nDays){
/*  removeCookie(name);
  if (!nDays) nDays = 365;
  cookiestring=name+"="+escape(value)+"; EXPIRES="+getexpirydate(nDays);
  parent.document.cookie=cookiestring;
  document.cookie=cookiestring;*/
  var top=parent;
  while (!top.globales)
  {
    top=top.parent;
  }
  top.globales[name] = value;
}


function getCookie(name) {
/*  var cookiestring=""+document.cookie;
  var arCookies = cookiestring.split("; ");

  var val = getParentCookie(name);
  if (val) return val;

  for (var i=0;i<arCookies.length;i++)
  {
    if (arCookies[i].indexOf(name+'=') == 0) val = arCookies[i];
  }
  if (!val) return "";

  return unescape(val.substring(val.indexOf('=')+1,val.length));*/


  if (parent.globales)
  {
    var top=parent;
    while (!top.globales)
    {
     top=top.parent;
    }
    return top.globales[name];

  }
  else return false;

}

function getParentCookie(name) {
  var cookiestring=""+parent.document.cookie;
  var arCookies = cookiestring.split("; ");

  var val = '';
  for (var i=0;i<arCookies.length;i++)
  {
    if (arCookies[i].indexOf(name+'=') == 0) val = arCookies[i];
  }
  if (!val) return "";

  return unescape(val.substring(val.indexOf('=')+1,val.length));
}

function  removeCookie(name)
{
  cookiestring=name+"=; EXPIRES="+getexpirydate(-1);
  parent.document.cookie=cookiestring;
  document.cookie=cookiestring;
}


function cancelaTecla(evt)
{
  try
  {
    if (evt.preventDefault)
    {
      evt.preventDefault();
      evt.stopPropagation();
    }
    else
    {
      try
      {
        evt.keyCode=0;
        evt.returnValue = false;
        evt.cancelBubble=true;
      }
      catch(e){}
    }
  }
  catch(e)
  {}
  finally
  {}
}



function trim(value) {
  return value.replace(/^\s+|\s+$/g,"");
}

function getCookieXML(nombreCookie)
{
  var obj = new Array;
  var xml = getCookie(nombreCookie);
  if (xml)
  {
    var registro = XML2Array(xml);
    if (registro && registro[0])
    for (i in registro[0])
    {
      obj[i] = registro[0][i];
    }
  }
  return obj;
}

function waitExec(msg, callStr, msecs, x,y){
        var self=this;
        var divWait = document.querySelector(".wait");
        msg=msg||"";
        
        if (!divWait) {
                var waitGlass = document.createElement("div");
                waitGlass.className="wait-glass";
                waitGlass.style.zIndex=Math.MAXINT-1;
                waitGlass.onclick=function(){
                        if (top.menu) top.menu.reset();
                        else if (window.menu) window.menu.reset();
                }
                document.body.appendChild(waitGlass);
                var divWait = document.createElement("div");
                divWait.className = "wait";
                divWait.style.zIndex=Math.MAXINT;
                divWait.setAttribute("id", "wait");
                divWait.style.display="none";

                var html = [];
                html.push('<div class="wait-titulo grid_barra_titulo">Espere...</div>');
                html.push('<div class="wait-cuadro" align="center">'+msg+'</div>');
                html.push('<div bgcolor="#333333" style="height:1px;"></div>');
                divWait.innerHTML = html.join("");
                document.body.appendChild(divWait);

                this.close=()=>{
                        divWait.remove();
                        waitGlass.remove();
                }
        }
        else {
                clearTimeout(divWait.dataset.to);
                divWait.querySelector(".wait-cuadro").innerHTML=msg;
        }
        setTimeout(()=>{
                divWait.style.display="block";
        }, 1);

        if (typeof callStr === "string") {
                divWait.dataset.to=setTimeout(()=>{
                        eval(callStr);
                        self.close();
                },msecs);
        }
        else if (typeof callStr === 'function')
        {
                callStr();
                divWait.dataset.to=setTimeout(function(){
                        divWait.remove();
                        waitGlass.remove();
                }, 3000);
        }
        return this;
}

function waitClose(){
        var waitGlass = document.querySelector(".wait-glass");
        var divWait = document.querySelector(".wait");
        if (divWait) divWait.remove();
        if (waitGlass) waitGlass.remove();
}


function genera_xml(tabla, campos, busca, xbusca, operador, url, xconfig, filtro, xfiltro, xoperadores)
{
  if (!url) url = server_path + 'herramientas/genera_xml/genera_xml.php';
  var params = '';
  if (!tabla)
  {
    alert('Es necesario establecer el par�metro tabla en la funci�n generaXml()');
    return false;
  }
  if (!campos) campos = '*';
  if (!busca) busca = '*';
  if (!xbusca) xbusca = '*';
  if (!operador) operador = '*';


  params += 'tabla='     + tabla;
  params += '&campos='   + campos;
  params += '&busca='    + busca;
  params += '&xbusca='   + xbusca;
  params += '&operador=' + operador;

  if (filtro && xfiltro)
  {
    if (!xoperadores) xoperadores='';
    params += '&filtro=' + filtro;
    params += '&xfiltro=' + xfiltro;
    params += '&xoperadores=' + xoperadores;
  }

  if (xconfig) params += '&xconfig=' + xconfig;

  //if (debug()) _prompt('Utiles->genera_xml: ',url + '?' + params);
  var xml = enviar(url, params, 'POST');
  return xml;
}

function genera_xml_asinc(func, tabla, campos, busca, xbusca, operador, url, xconfig, filtro, xfiltro, xoperadores)
{
  if (!url) url = server_path + 'herramientas/genera_xml/genera_xml.php';
  var params = '';
  if (!tabla)
  {
    alert('Es necesario establecer el par�metro tabla en la funci�n generaXml()');
    return false;
  }
  if (!campos) campos = '*';
  if (!busca) busca = '*';
  if (!xbusca) xbusca = '*';
  if (!operador) operador = '*';


  params += 'tabla='     + tabla;
  params += '&campos='   + campos;
  params += '&busca='    + busca;
  params += '&xbusca='   + xbusca;
  params += '&operador=' + operador;

  if (filtro && xfiltro)
  {
    if (!xoperadores) xoperadores='';
    params += '&filtro=' + filtro;
    params += '&xfiltro=' + xfiltro;
    params += '&xoperadores=' + xoperadores;
  }

  if (xconfig) params += '&xconfig=' + xconfig;

  //if (debug()) _prompt('Utiles->genera_xml_asinc: ',url + '?' + params);
  var xml = enviarAsinc(url, params, func);
  return xml;
}

function Campo(valor, tipo)
{
  this.tipo = tipo;
  this.valor = valor;
}

function actualizar_registro(tabla, arCampos, busca, xbusca, url, operador, xconfig)
{
  if (!url) url = server_path + 'herramientas/utiles/actualizar_registro.php';
  if (operador) operador='=';

  if (!tabla)
  {
    alert('Es necesario establecer el par�metro "tabla" en la funci�n actualizarRegistro()');
    return false;
  }

  if (!busca)
  {
    alert('Es necesario establecer el par�metro "busca" en la funci�n actualizarRegistro()');
    return false;
  }

  if (!xbusca)
  {
    alert('Es necesario establecer el par�metro "xbusca" en la funci�n actualizarRegistro()\n(-1 si es nuevo registro)');
    return false;
  }

  if (typeof(arCampos) != 'object')
  {
    var msg;
    msg =  'Para la funci�n actualizarRegistro() es necesario que los valores\n';
    msg += 'sean pasados en forma de Array especificando el valor y el tipo\n';
    msg += 'de campo donde sea necesario (por defecto "C"). Ejemplo:\n\n';
    msg += '   var arr = new Array;\n';
    msg += '   arr[\'TEXTO\'] = new Campo(\'VALOR1\');\n';
    msg += '   arr[\'NUMERO\'] = new Campo(1245, \'N\');\n';
    msg += '   arr[\'FECHA\'] = new Campo(\'31/12/2005\',\'D\');\n';
    msg += '   arr[\'FECHA_HORA\'] = new Campo(\'31/12/2005 14:25:36\',\'T\');\n';
    msg += '    :\n';
    msg += '    :';
    alert(msg);
    return false;
  }

  var params = 'tabla='+tabla;
  params += '&busca='+busca;
  params += '&xbusca='+xbusca;

  var tipo = '';

  var n=0;
  for (campo in arCampos)
  {
    if (!arCampos[campo].tipo) tipo = 'C';
    else tipo = arCampos[campo].tipo;

    if (!arCampos[campo].valor) valor = '';
    else valor = arCampos[campo].valor;
    params += '&c_'+campo+'_'+tipo+'SS=' + escape(valor);
    n++;
  }

  if (!n)
  {
    alert('No se especificaron campos para guardar');
    return false;
  }

  params += '&operador='+operador;
  if (xconfig) params += '&xconfig=' + xconfig;

  var xml = enviar(url, params, 'POST');
  return xml;
}



function unformat(valor)
{
  
   if( typeof valor === "number") return valor;
   if(valor=='') return '';
   valor = trim(valor);
   return parseFloat(valor.replace(/,/g, ''));
}

function unformat2(valor)
{
   valor = trim(valor);
   if(valor=='') return '';
   return valor.replace(/,/g, '');
}

function redondear(x, n) {
  if (n < 1 || n > 14) return false;
  var e = Math.pow(10, n);
  var k = (Math.round(x * e) / e).toString();
  if (k.indexOf('.') == -1) k += '.';
  k += e.toString().substring(1);
  return k.substring(0, k.indexOf('.') + n+1);
}

function visualizar(xdocumento,tipo,xgrid,salida,xurl)
{
   var plantilla = 'r_documento';
   switch(tipo)
   {
     case 'AJU':
        var plantilla = 'r_ajuste';
        break;
   }

    var obj = parent;
    var n = 0;
    while( obj && !obj.v_auxiliar && (n<20))
    {
       obj = obj.parent;
       n++;
    }

    if(!obj || !obj.v_auxiliar || (n>=20))
    {
      alert('La ventana Auxiliar no esta Definida');
      return;

    }
    var xsalida = '';

    if(salida=='pdf') xsalida = '&salida=pdf';
    var url = server_path + 'herramientas/genera_reporte/genera_reporte.php?&salida=xpdf&origen=reportes/' + plantilla + '&ID_M_DOCUMENTOS='+ xdocumento + xsalida;
    if(xurl) url = xurl;

    obj.v_auxiliar.leeUrl(url);
    obj.v_auxiliar.mostrar();
    obj.v_auxiliar.setFocus();    obj.v_auxiliar.onClose = function () {xgrid.setFocus();};

}




function pideClave(func,nivel,usuario)
{
  xclave.modal=true;
  xclave.nivel=0;
  if(nivel) xclave.nivel=nivel;
  if(usuario) xclave.login=true;
  xclave.caption = 'clave ';
  xclave.callFunc = eval(func);

  xclave.init();

  centrarObj(xclave.container);
 xclave.clear();
 xclave.regenerate();
 xclave.show();
 xclave.setFocus();
}

/* agregado por marco el 08/08/2008 */
/* retorna el html para una barrita de porcentaje */

function htmlPorcentaje(ancho, alto, porcentaje, colorBarra, colorFondo, colorTexto)
{
  if (!ancho) ancho=100;
  if (!alto)  alto=5;

  var html='<DIV style="';

  if ( (!porcentaje&&porcentaje!=0) || (porcentaje<0||porcentaje>100) )
  {
    return 'error!';
  }

  if (!colorBarra) colorBarra='#0000ff';
  if (!colorFondo) colorFondo='#ffffff';

  porcentaje=parseInt(porcentaje);
  html += 'WIDTH: '+ancho+'px; ';
  html += 'HEIGHT: '+alto+'px; ';
  html += '">';

  var tx=0;
  html += '<SPAN STYLE="height:'+alto+'px; position:relative; left:'+tx+'px;"><SMALLEST>'+porcentaje+'%</SMALLEST></SPAN>';
  html += '<TABLE ';

  html += 'STYLE="';
  html += 'WIDTH: '+ancho+'px; ';
  html += 'HEIGHT: '+alto+'px; ';
  html += 'BORDER-RIGHT: #000000 1px solid; ';
  html += 'BORDER-TOP: #000000 1px solid; ';
  html += 'BORDER-LEFT: #000000 1px solid; ';
  html += 'BORDER-BOTTOM: #000000 1px solid; ';
  html += 'BACKGROUND-COLOR: '+colorFondo+'" ';
  html += 'cellSpacing=0 ';
  html += 'cellPadding=0 ';
  html += 'width="100%" ';
  html += 'bgColor='+colorFondo+'>';
  html += '<TBODY>';
  html += '<TR>';

  switch(porcentaje)
  {
    case 0:
      html += '<TD width="100%" bgColor='+colorFondo+'></TD>';
      break;
    case 100:
      html += '<TD width="100%" bgColor='+colorBarra+'></TD>';
      break;
    default:
      html += '<TD width="'+porcentaje+'%" bgColor="'+colorBarra+'"></TD>';
      html += '<TD width="'+(100-porcentaje)+'"></TD></TR></TBODY>';
      break;
  }

  html += '</TABLE>';
  html += '</DIV>';

  return html;
}


function restaFecha( f0, f1 ){
	
	var fecha0 = f0;

	var matchArray0 = fecha0.split('/');
	month0 = matchArray0[1];
	day0 = matchArray0[0];
	year0 = matchArray0[2];

	var fecha1 = trim(f1);
	var matchArray1 = fecha1.split('/');
	month1 = matchArray1[1];
	day1 = matchArray1[0];
	year1 = matchArray1[2];

	var fechaIni = new Date();
	fechaIni.setFullYear(year0, month0, day0)

	var fechaFin = new Date();
	fechaFin.setFullYear(year1, month1, day1)

	var resta = fechaFin - fechaIni
	resta = parseInt(resta/86400000);
	return(resta);

}


function sumaDias(fecha, dias){ 
	// Se recibe la fecha DD/MM/YYYY y se convierte a YYYY/MM/DD
	if( typeof dias === 'string' ) dias = parseFloat(dias);
	
	var tmp = fecha.split('/');
	fecha = tmp[2] + '/' + tmp[1] + '/' + tmp[0];
	const d = new Date( fecha );
	d.setDate(d.getDate() + dias);

	let day   = d.getDate()
	let month = d.getMonth() + 1
	let year  = d.getFullYear()
	
	if(day   < 10) day   = '0' + day;
	if(month < 10) month = '0' + month;
	
	return ( day + '/' + month + '/' + year );

}

function isValidDate(v,t){
	
	return true;
    
	if (v == _nofecha || !v) return true;
    
	if(v.length<=9) return false;
    
	var patron = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/
    var aComp  = v.match(patron);
    var msg='';

    if (aComp==null) msg = v+': no es una fecha valida';
    dia = aComp[1];
    mes = aComp[3];
    ano = aComp[4];

    if (mes<1||mes>12||dia<1||dia>31) msg = v+': no es una fecha valida';
    if (!msg) if (dia>maxDays(parseFloat(mes)-1,parseFloat(ano))) msg = v+': no es una fecha valida';

    if (msg){
      if(!t) alert(msg);
      return false;
    }
    return true;
}

function MsgError(xml)
{
    var registro = valida_xml(xml);
    if(registro)
    {
        var xerror = trim(registro[0]['ERROR']);
        if(xerror.length)
        {
        	var xMsg = trim(registro[0]['ERROR']);
        	var xE   = trim(xMsg.substring(0,10));
            if(xE=='exception')
        	{
                var t    = registro[0]['ERROR'].split('<')[1].split('>');
                alert(xE +  ':'+ t[1] + '\n'+t[0]);
        	}
            return false;
    	}
    }
    return true;
}

function chr (codePt)
{
    // Converts a codepoint number to a character
    //
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/chr    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: chr(75);
    // *     returns 1: 'K'
    // *     example 1: chr(65536) === '\uD800\uDC00';    // *     returns 1: true
    if (codePt > 0xFFFF) { // Create a four-byte string (length 2) since this code point is high
        //   enough for the UTF-16 encoding (JavaScript internal use), to
        //   require representation with two surrogates (reserved non-characters
        //   used for building other characters; the first is "high" and the next "low")        codePt -= 0x10000;
        return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
    }
    return String.fromCharCode(codePt);
}

function guardar(param)
{
    var url = server_path + 'herramientas/utiles/actualizar_registro.php';
    var x = enviar(url,param,'GET');
	return x;
}

function mask_Hora(hora)
{
	var m = '';
	var tmp_hora = explode(':', hora);

	if(tmp_hora[0]==0){m=" AM";tmp_hora[0]=12}
	else if(tmp_hora[0] <= 11){m=" AM"}
	else if(tmp_hora[0] == 12){m=" PM";tmp_hora[0]=12}
	else if(tmp_hora[0] >= 13){m=" PM";tmp_hora[0]-=12}

	if(tmp_hora[0] <= 9){tmp_hora[0]="0"+tmp_hora[0]}
	var resp = tmp_hora[0]+":"+tmp_hora[1]+":"+tmp_hora[2]+" "+m;
    return resp;
}

function controlError(xml)
{
	
	var registro = valida_xml(xml);
    if(registro && registro[0] && registro[0]['ERROR'])
    {
       //var p = ^(.+\@.+\..+)$
        var xMsg = trim(registro[0]['ERROR']);
        var xE   = trim(xMsg.substring(0,10));
        if(xE=='exception')
        {
                var t    = registro[0]['ERROR'].split('<')[1].split('>');
                alert(t[0]);
        }    
        return false;
    }
	return true;
	
	
}

function server_info()
{
	var url 	= server_path + 'herramientas/utiles/server_info.php';	
	var params 	='';
	var resp   	= enviar(url, params, 'POST');
	return JSON.parse(resp);
}

jQuery.fn.extend({
	moveme: function( obj ) {
		console.log( $(this) );
		dragElement( $(this)[0] ); 
		
	}
});
 
function dragElement(elmnt) {
  
  
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  
  if (document.getElementById(elmnt.id + "header")) {
    // if present, the header is where you move the DIV from:
    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
  } else {
    // otherwise, move the DIV from anywhere inside the DIV:
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    // stop moving when mouse button is released:
    document.onmouseup = null;
    document.onmousemove = null;
  }
}












/******* EVALUAR PARA ELIMINAR DE ACA PARA ABAJO ***********/


function Busca_Documento(id_x_m_documentos)
{

	 var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
	 var params = 'tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS&operador==&busca=ID_X_M_DOCUMENTOS&xbusca=' + id_x_m_documentos;
	 var x      = enviar(url, params, 'POST');
	 var registro = valida_xml(x,'ID_M_DOCUMENTOS');
     if(!registro) return false;
	 return registro[0]['ID_M_DOCUMENTOS'];

}

/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.1 Copyright (C) Paul Johnston 1999 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/*
 * Configurable variables. You may need to tweak these to be compatible with
 * the server-side, but the defaults work in most cases.
 */
var hexcase = 0;  /* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad  = ""; /* base-64 pad character. "=" for strict RFC compliance   */
var chrsz   = 8;  /* bits per input character. 8 - ASCII; 16 - Unicode      */

/*
 * These are the functions you'll usually want to call
 * They take string arguments and return either hex or base-64 encoded strings
 */
function hex_md5(s){ return binl2hex(core_md5(str2binl(s), s.length * chrsz));}
function b64_md5(s){ return binl2b64(core_md5(str2binl(s), s.length * chrsz));}
function str_md5(s){ return binl2str(core_md5(str2binl(s), s.length * chrsz));}
function hex_hmac_md5(key, data) { return binl2hex(core_hmac_md5(key, data)); }
function b64_hmac_md5(key, data) { return binl2b64(core_hmac_md5(key, data)); }
function str_hmac_md5(key, data) { return binl2str(core_hmac_md5(key, data)); }

/*
 * Perform a simple self-test to see if the VM is working
 */
function md5_vm_test()
{
  return hex_md5("abc") == "900150983cd24fb0d6963f7d28e17f72";
}

/*
 * Calculate the MD5 of an array of little-endian words, and a bit length
 */
function core_md5(x, len)
{
  /* append padding */
  x[len >> 5] |= 0x80 << ((len) % 32);
  x[(((len + 64) >>> 9) << 4) + 14] = len;

  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;

    a = md5_ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = md5_ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = md5_ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = md5_ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = md5_ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = md5_ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = md5_ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = md5_ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = md5_ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = md5_ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = md5_ff(c, d, a, b, x[i+10], 17, -42063);
    b = md5_ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = md5_ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = md5_ff(d, a, b, c, x[i+13], 12, -40341101);
    c = md5_ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = md5_ff(b, c, d, a, x[i+15], 22,  1236535329);

    a = md5_gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = md5_gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = md5_gg(c, d, a, b, x[i+11], 14,  643717713);
    b = md5_gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = md5_gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = md5_gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = md5_gg(c, d, a, b, x[i+15], 14, -660478335);
    b = md5_gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = md5_gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = md5_gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = md5_gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = md5_gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = md5_gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = md5_gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = md5_gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = md5_gg(b, c, d, a, x[i+12], 20, -1926607734);

    a = md5_hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = md5_hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = md5_hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = md5_hh(b, c, d, a, x[i+14], 23, -35309556);
    a = md5_hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = md5_hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = md5_hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = md5_hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = md5_hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = md5_hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = md5_hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = md5_hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = md5_hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = md5_hh(d, a, b, c, x[i+12], 11, -421815835);
    c = md5_hh(c, d, a, b, x[i+15], 16,  530742520);
    b = md5_hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = md5_ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = md5_ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = md5_ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = md5_ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = md5_ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = md5_ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = md5_ii(c, d, a, b, x[i+10], 15, -1051523);
    b = md5_ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = md5_ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = md5_ii(d, a, b, c, x[i+15], 10, -30611744);
    c = md5_ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = md5_ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = md5_ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = md5_ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = md5_ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = md5_ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
  }
  return Array(a, b, c, d);

}

/*
 * These functions implement the four basic operations the algorithm uses.
 */
function md5_cmn(q, a, b, x, s, t)
{
  return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s),b);
}
function md5_ff(a, b, c, d, x, s, t)
{
  return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function md5_gg(a, b, c, d, x, s, t)
{
  return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function md5_hh(a, b, c, d, x, s, t)
{
  return md5_cmn(b ^ c ^ d, a, b, x, s, t);
}
function md5_ii(a, b, c, d, x, s, t)
{
  return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Calculate the HMAC-MD5, of a key and some data
 */
function core_hmac_md5(key, data)
{
  var bkey = str2binl(key);
  if(bkey.length > 16) bkey = core_md5(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++)
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_md5(ipad.concat(str2binl(data)), 512 + data.length * chrsz);
  return core_md5(opad.concat(hash), 512 + 128);
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left.
 */
function bit_rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * Convert a string to an array of little-endian words
 * If chrsz is ASCII, characters >255 have their hi-byte silently ignored.
 */
function str2binl(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (i%32);
  return bin;
}

/*
 * Convert an array of little-endian words to a string
 */
function binl2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (i % 32)) & mask);
  return str;
}

/*
 * Convert an array of little-endian words to a hex string.
 */
function binl2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((i%4)*8+4)) & 0xF) +
           hex_tab.charAt((binarray[i>>2] >> ((i%4)*8  )) & 0xF);
  }
  return str;
}

/*
 * Convert an array of little-endian words to a base-64 string
 */
function binl2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * ( i   %4)) & 0xFF) << 16)
                | (((binarray[i+1 >> 2] >> 8 * ((i+1)%4)) & 0xFF) << 8 )
                |  ((binarray[i+2 >> 2] >> 8 * ((i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}




/**
 * Code below taken from - http://www.evolt.org/article/document_body_doctype_switching_and_more/17/30655/
 *
 * Modified 4/22/04 to work with Opera/Moz (by webmaster at subimage dot com)
 *
 * Gets the full width/height because it's different for most browsers.
 */
function getViewportHeight() {
        if (window.innerHeight!=window.undefined) return window.innerHeight;
        if (document.compatMode=='CSS1Compat') return document.documentElement.clientHeight;
        if (document.body) return document.body.clientHeight;
        return window.undefined;
}
function getViewportWidth() {
        if (window.innerWidth!=window.undefined) return window.innerWidth;
        if (document.compatMode=='CSS1Compat') return document.documentElement.clientWidth;
        if (document.body) return document.body.clientWidth;
        return window.undefined;
}








if (parent.proceso && !parent.proceso.location) {
        class _location{
                set href(val){
                        if (parent.menu) parent.menu.setFocus();
                        parent.proceso.src=val;
                }
        }
        parent.proceso.location=new _location;
}




// ===================================================================
// Author: Matt Kruse <matt@ajaxtoolbox.com>
// WWW: http://www.AjaxToolbox.com/

function AjaxRequest() {

        var req = new Object();

        // -------------------
        // Instance properties
        // -------------------

        /**
         * Timeout period (in ms) until an async request will be aborted, and
         * the onTimeout function will be called
         */
        req.timeout = null;

        /**
         *        Since some browsers cache GET requests via XMLHttpRequest, an
         * additional parameter called AjaxRequestUniqueId will be added to
         * the request URI with a unique numeric value appended so that the requested
         * URL will not be cached.
         */
        req.generateUniqueUrl = true;

        /**
         * The url that the request will be made to, which defaults to the current
         * url of the window
         */
        req.url = window.location.href;

        /**
         * The method of the request, either GET (default), POST, or HEAD
         */
        req.method = "GET";

        /**
         * Whether or not the request will be asynchronous. In general, synchronous
         * requests should not be used so this should rarely be changed from true
         */
        req.async = true;

        /**
         * The username used to access the URL
         */
        req.username = null;

        /**
         * The password used to access the URL
         */
        req.password = null;

        /**
         * The parameters is an object holding name/value pairs which will be
         * added to the url for a GET request or the request content for a POST request
         */
        req.parameters = new Object();

        /**
         * The sequential index number of this request, updated internally
         */
        req.requestIndex = AjaxRequest.numAjaxRequests++;

        /**
         * Indicates whether a response has been received yet from the server
         */
        req.responseReceived = false;

        /**
         * The name of the group that this request belongs to, for activity
         * monitoring purposes
         */
        req.groupName = null;

        /**
         * The query string to be added to the end of a GET request, in proper
         * URIEncoded format
         */
        req.queryString = "";

        /**
         * After a response has been received, this will hold the text contents of
         * the response - even in case of error
         */
        req.responseText = null;

        /**
         * After a response has been received, this will hold the XML content
         */
        req.responseXML = null;

        /**
         * After a response has been received, this will hold the status code of
         * the response as returned by the server.
         */
        req.status = null;

        /**
         * After a response has been received, this will hold the text description
         * of the response code
         */
        req.statusText = null;

        /**
         * An internal flag to indicate whether the request has been aborted
         */
        req.aborted = false;

        /**
         * The XMLHttpRequest object used internally
         */
        req.xmlHttpRequest = null;

        // --------------
        // Event handlers
        // --------------

        /**
         * If a timeout period is set, and it is reached before a response is
         * received, a function reference assigned to onTimeout will be called
         */
        req.onTimeout = null;

        /**
         * A function reference assigned will be called when readyState=1
         */
        req.onLoading = null;

        /**
         * A function reference assigned will be called when readyState=2
         */
        req.onLoaded = null;

        /**
         * A function reference assigned will be called when readyState=3
         */
        req.onInteractive = null;

        /**
         * A function reference assigned will be called when readyState=4
         */
        req.onComplete = null;

        /**
         * A function reference assigned will be called after onComplete, if
         * the statusCode=200
         */
        req.onSuccess = null;

        /**
         * A function reference assigned will be called after onComplete, if
         * the statusCode != 200
         */
        req.onError = null;

        /**
         * If this request has a group name, this function reference will be called
         * and passed the group name if this is the first request in the group to
         * become active
         */
        req.onGroupBegin = null;

        /**
         * If this request has a group name, and this request is the last request
         * in the group to complete, this function reference will be called
         */
        req.onGroupEnd = null;

        // Get the XMLHttpRequest object itself
        req.xmlHttpRequest = AjaxRequest.getXmlHttpRequest();
        if (req.xmlHttpRequest==null) { return null; }

        // -------------------------------------------------------
        // Attach the event handlers for the XMLHttpRequest object
        // -------------------------------------------------------
        req.xmlHttpRequest.onreadystatechange =
        function() {
                if (req==null || req.xmlHttpRequest==null) { return; }
                if (req.xmlHttpRequest.readyState==1) { req.onLoadingInternal(req); }
                if (req.xmlHttpRequest.readyState==2) { req.onLoadedInternal(req); }
                if (req.xmlHttpRequest.readyState==3) { req.onInteractiveInternal(req); }
                if (req.xmlHttpRequest.readyState==4) { req.onCompleteInternal(req); }
        };

        // ---------------------------------------------------------------------------
        // Internal event handlers that fire, and in turn fire the user event handlers
        // ---------------------------------------------------------------------------
        // Flags to keep track if each event has been handled, in case of
        // multiple calls (some browsers may call the onreadystatechange
        // multiple times for the same state)
        req.onLoadingInternalHandled = false;
        req.onLoadedInternalHandled = false;
        req.onInteractiveInternalHandled = false;
        req.onCompleteInternalHandled = false;
        req.onLoadingInternal =
                function() {
                        if (req.onLoadingInternalHandled) { return; }
                        AjaxRequest.numActiveAjaxRequests++;
                        if (AjaxRequest.numActiveAjaxRequests==1 && typeof(window['AjaxRequestBegin'])=="function") {
                                AjaxRequestBegin();
                        }
                        if (req.groupName!=null) {
                                if (typeof(AjaxRequest.numActiveAjaxGroupRequests[req.groupName])=="undefined") {
                                        AjaxRequest.numActiveAjaxGroupRequests[req.groupName] = 0;
                                }
                                AjaxRequest.numActiveAjaxGroupRequests[req.groupName]++;
                                if (AjaxRequest.numActiveAjaxGroupRequests[req.groupName]==1 && typeof(req.onGroupBegin)=="function") {
                                        req.onGroupBegin(req.groupName);
                                }
                        }
                        if (typeof(req.onLoading)=="function") {
                                req.onLoading(req);
                        }
                        req.onLoadingInternalHandled = true;
                };
        req.onLoadedInternal =
                function() {
                        if (req.onLoadedInternalHandled) { return; }
                        if (typeof(req.onLoaded)=="function") {
                                req.onLoaded(req);
                        }
                        req.onLoadedInternalHandled = true;
                };
        req.onInteractiveInternal =
                function() {
                        if (req.onInteractiveInternalHandled) { return; }
                        if (typeof(req.onInteractive)=="function") {
                                req.onInteractive(req);
                        }
                        req.onInteractiveInternalHandled = true;
                };
        req.abort = function() // introducido por marco 20/07/2008
        {
          req.xmlHttpRequest.abort();
        };
        req.onCompleteInternal =
                function() {
                        if (req.onCompleteInternalHandled || req.aborted) { return; }
                        req.onCompleteInternalHandled = true;
                        AjaxRequest.numActiveAjaxRequests--;
                        if (AjaxRequest.numActiveAjaxRequests==0 && typeof(window['AjaxRequestEnd'])=="function") {
                                AjaxRequestEnd(req.groupName);
                        }
                        if (req.groupName!=null) {
                                AjaxRequest.numActiveAjaxGroupRequests[req.groupName]--;
                                if (AjaxRequest.numActiveAjaxGroupRequests[req.groupName]==0 && typeof(req.onGroupEnd)=="function") {
                                        req.onGroupEnd(req.groupName);
                                }
                        }
                        req.responseReceived = true;
                        req.status = req.xmlHttpRequest.status;
                        req.statusText = req.xmlHttpRequest.statusText;
                        req.responseText = req.xmlHttpRequest.responseText;
                        req.responseXML = req.xmlHttpRequest.responseXML;
                        if (typeof(req.onComplete)=="function") {
                                req.onComplete(req);
                        }
                        if (req.xmlHttpRequest.status==200 && typeof(req.onSuccess)=="function") {
                                req.onSuccess(req);
                        }
                        else if (typeof(req.onError)=="function") {
                                req.onError(req);
                        }

                        // Clean up so IE doesn't leak memory
                        delete req.xmlHttpRequest['onreadystatechange'];
                        req.xmlHttpRequest = null;
                };
        req.onTimeoutInternal =
                function() {
                        if (req!=null && req.xmlHttpRequest!=null && !req.onCompleteInternalHandled) {
                                req.aborted = true;
                                req.xmlHttpRequest.abort();
                                AjaxRequest.numActiveAjaxRequests--;
                                if (AjaxRequest.numActiveAjaxRequests==0 && typeof(window['AjaxRequestEnd'])=="function") {
                                        AjaxRequestEnd(req.groupName);
                                }
                                if (req.groupName!=null) {
                                        AjaxRequest.numActiveAjaxGroupRequests[req.groupName]--;
                                        if (AjaxRequest.numActiveAjaxGroupRequests[req.groupName]==0 && typeof(req.onGroupEnd)=="function") {
                                                req.onGroupEnd(req.groupName);
                                        }
                                }
                                if (typeof(req.onTimeout)=="function") {
                                        req.onTimeout(req);
                                }
                        // Opera won't fire onreadystatechange after abort, but other browsers do.
                        // So we can't rely on the onreadystate function getting called. Clean up here!
                        delete req.xmlHttpRequest['onreadystatechange'];
                        req.xmlHttpRequest = null;
                        }
                };

        // ----------------
        // Instance methods
        // ----------------
        /**
         * The process method is called to actually make the request. It builds the
         * querystring for GET requests (the content for POST requests), sets the
         * appropriate headers if necessary, and calls the
         * XMLHttpRequest.send() method
        */
        req.process =
                function() {
                        if (req.xmlHttpRequest!=null) {
                                // Some logic to get the real request URL
                                if (req.generateUniqueUrl && req.method=="GET") {
                                        req.parameters["AjaxRequestUniqueId"] = new Date().getTime() + "" + req.requestIndex;
                                }
                                var content = null; // For POST requests, to hold query string
                                for (var i in req.parameters) {
                                        if (req.queryString.length>0) { req.queryString += "&"; }
                                        req.queryString += encodeURIComponent(i) + "=" + encodeURIComponent(req.parameters[i]);
                                }
                                if (req.method=="GET") {
                                        if (req.queryString.length>0) {
                                                req.url += ((req.url.indexOf("?")>-1)?"&":"?") + req.queryString;
                                        }
                                }
                                req.xmlHttpRequest.open(req.method,req.url,req.async,req.username,req.password);
                                if (req.method=="POST") {
                                        if (typeof(req.xmlHttpRequest.setRequestHeader)!="undefined") {
                                                req.xmlHttpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                        }
                                        content = req.queryString;
                                }
                                if (req.timeout>0) {
                                        setTimeout(req.onTimeoutInternal,req.timeout);
                                }
                                req.xmlHttpRequest.send(content);
                        }
                };

        /**
         * An internal function to handle an Object argument, which may contain
         * either AjaxRequest field values or parameter name/values
         */
        req.handleArguments =
                function(args) {
                        for (var i in args) {
                                // If the AjaxRequest object doesn't have a property which was passed, treat it as a url parameter
                                if (typeof(req[i])=="undefined") {
                                        req.parameters[i] = args[i];
                                }
                                else {
                                        req[i] = args[i];
                                }
                        }
                };

        /**
         * Returns the results of XMLHttpRequest.getAllResponseHeaders().
         * Only available after a response has been returned
         */
        req.getAllResponseHeaders =
                function() {
                        if (req.xmlHttpRequest!=null) {
                                if (req.responseReceived) {
                                        return req.xmlHttpRequest.getAllResponseHeaders();
                                }
                                alert("Cannot getAllResponseHeaders because a response has not yet been received");
                        }
                };

        /**
         * Returns the the value of a response header as returned by
         * XMLHttpRequest,getResponseHeader().
         * Only available after a response has been returned
         */
        req.getResponseHeader =
                function(headerName) {
                        if (req.xmlHttpRequest!=null) {
                                if (req.responseReceived) {
                                        return req.xmlHttpRequest.getResponseHeader(headerName);
                                }
                                alert("Cannot getResponseHeader because a response has not yet been received");
                        }
                };

        return req;
}

// ---------------------------------------
// Static methods of the AjaxRequest class
// ---------------------------------------

/**
 * Returns an XMLHttpRequest object, either as a core object or an ActiveX
 * implementation. If an object cannot be instantiated, it will return null;
 */
AjaxRequest.getXmlHttpRequest = function() {
        if (window.XMLHttpRequest) {
                return new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
                // Based on http://jibbering.com/2002/4/httprequest.html
                /*@cc_on @*/
                /*@if (@_jscript_version >= 5)
                try {
                        return new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                        try {
                                return new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (E) {
                                return null;
                        }
                }
                @end @*/
        }
        else {
                return null;
        }
};

/**
 * See if any request is active in the background
 */
AjaxRequest.isActive = function() {
        return (AjaxRequest.numActiveAjaxRequests>0);
};

/**
 * Make a GET request. Pass an object containing parameters and arguments as
 * the second argument.
 * These areguments may be either AjaxRequest properties to set on the request
 * object or name/values to set in the request querystring.
 */
AjaxRequest.get = function(args) {
        return AjaxRequest.doRequest("GET",args);
};

/**
 * Make a POST request. Pass an object containing parameters and arguments as
 * the second argument.
 * These areguments may be either AjaxRequest properties to set on the request
 * object or name/values to set in the request querystring.
 */
AjaxRequest.post = function(args) {
        return AjaxRequest.doRequest("POST",args);
};

/**
 * The internal method used by the .get() and .post() methods
 */
AjaxRequest.doRequest = function(method,args) {
        if (typeof(args)!="undefined" && args!=null) {
                var myRequest = new AjaxRequest();
                myRequest.method = method;
                myRequest.handleArguments(args);
                myRequest.process();
                return myRequest;
        }
}        ;

AjaxRequest.submit = function(theform, args) {
        var myRequest = new AjaxRequest();
        if (myRequest==null) { return false; }
        var serializedForm = AjaxRequest.serializeForm(theform);
        myRequest.method = theform.method.toUpperCase();
        myRequest.url = theform.action;
        myRequest.handleArguments(args);
        myRequest.queryString = serializedForm;
        myRequest.process();
        return true;
};

AjaxRequest.serializeForm = function(theform) {
        var els = theform.elements;
        var len = els.length;
        var queryString = "";
        this.addField =
                function(name,value) {
                        if (queryString.length>0) {
                                queryString += "&";
                        }
                        queryString += encodeURIComponent(name) + "=" + encodeURIComponent(value);
                };
        for (var i=0; i<len; i++) {
                var el = els[i];
                if (!el.disabled) {
                        switch(el.type) {
                                case 'text': case 'password': case 'hidden': case 'textarea':
                                        this.addField(el.name,el.value);
                                        break;
                                case 'select-one':
                                        if (el.selectedIndex>=0) {
                                                this.addField(el.name,el.options[el.selectedIndex].value);
                                        }
                                        break;
                                case 'select-multiple':
                                        for (var j=0; j<el.options.length; j++) {
                                                if (el.options[j].selected) {
                                                        this.addField(el.name,el.options[j].value);
                                                }
                                        }
                                        break;
                                case 'checkbox': case 'radio':
                                        if (el.checked) {
                                                this.addField(el.name,el.value);
                                        }
                                        break;
                        }
                }
        }
        return queryString;
};

AjaxRequest.numActiveAjaxRequests = 0;
AjaxRequest.numActiveAjaxGroupRequests = new Object();
AjaxRequest.numAjaxRequests = 0;
  
