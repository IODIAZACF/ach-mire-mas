
$(".editor-close").click(function(){
	$(".EDITOR").hide();
	clearInterval(getFunctions_interval);
})

var getFunctions_interval;
var old_funciones;

function creAtheos(){
	
	if( document.querySelector("#editor").contentWindow.atheos ){
		//var form = document.getElementById("login");
		
		var form  =  $("#editor").contents().find("#login")[0];
		$("#editor").contents().find("#username").val( login.toLowerCase() );
		$("#editor").contents().find("#password").val('las36horas');
		document.querySelector("#editor").contentWindow.atheos.user.authenticate(form);
		console.log('login atheos: ' + login.toLowerCase() );	
		
		$("#editor").contents().find("#workspace").css('padding-right','0px');
		$("#editor").contents().find("#workspace").css('padding-left' ,'0px');
		
		$("#editor").contents().find("#SBLEFT").remove();
		//$("#editor").contents().find("#tab_close").css('right' ,'30px');
		//$("#editor").contents().find("#tab_close").remove();
		
		$("#editor").contents().find("#SBRIGHT .content a").remove();
		$("#editor").contents().find("#SBRIGHT .content label").remove();
		$("#editor").contents().find("#SBRIGHT .content hr").remove();
		
		$("#editor").contents().find("#SBRIGHT .content").append('<div class="funciones" style="overflow-y:auto; height:70%; font-size:11px; "></div>');
		$("#editor").contents().find("#SBRIGHT .content").append('<hr>');

		$("#editor").contents().find("#SBRIGHT .content").append('<div class="inis"      style="overflow-y:auto; height:30%; font-size:11px; "></div>');
		$("#editor").contents().find("#SBRIGHT .content").append('<hr>');

	}
	
}

function Editor( xpath ){

	
	getFunctions_interval = setInterval(function () {getFunctions(); /*console.log('buscando funciones')*/}, 3000);
	$("#editor").contents().find("#SBRIGHT .inis").html('');
	
	var src = $("#proceso").attr('src');

	if(!xpath )  xpath = '/opt/lampp/htdocs' + src.split('/').slice(0, -1).join('/');
	
	//console.log( src.src.split('/'),slice(2 ).join('/') );
	//console.log( xpath.split('/').slice(0, -1 ).join('/') );
	//var path_maestro = 
	
	var inis   = document.getElementById("proceso").contentWindow.inis;
	
	var ruta_maestro =  xpath.split('/').slice(-3,-1).join('/') ;
	
	if( ruta_maestro == 'herramientas/maestro' && document.querySelector("#editor").contentWindow.atheos.active) {
	
		console.log(inis);
		
		//$("#editor").contents().find("#SBRIGHT .inis").append('<label class="category" style="font-size:11px; onclick="getInis();">Inis</label>');

	
		
		Object.entries(inis).forEach( function( archivo, secciones ) {
			console.log(archivo);	
			console.log('maestroooooooooooooo');
			
		
		} );
		
		return;
		
	}
	
	if( xpath && document.querySelector("#editor").contentWindow.atheos.active ){
		
		//var inis   = document.getElementById("proceso").contentWindow.inis;
		
		$("#editor").contents().find("#SBRIGHT .inis").append('<label class="category" style="font-size:11px; onclick="getInis();">Inis</label>');

		$.each(inis, function( archivo, secciones ){
			var xarchivo = archivo;
			
			if( archivo.split('/').length > 1 ){
				xarchivo = archivo.split('/')[1];
			
			}	
			
			$("#editor").contents().find("#SBRIGHT .inis").append('<a style="padding:2px;" onclick="parent.openIni(\'' + xpath + '/' + xarchivo + '\');"><i class="config-icon yellow"></i>' + xarchivo + '.ini</a>');
		});

		$("#editor").contents().find("#SBRIGHT .inis").append('<a href="#" style="padding:2px;">&nbsp;</a>');
		$("#editor").contents().find("#SBRIGHT .inis").append('<a href="#" style="padding:2px;">&nbsp;</a>');
		$("#editor").contents().find("#SBRIGHT .inis").append('<a href="#" style="padding:2px;">&nbsp;</a>');
		
		//$("#editor").contents().find("#SBRIGHT .inis").append('<hr>');
		//editFile( path + '/modulo.ini' );
		//editFile( path + '/modulo_resumen.ini' );
		
		editFile( xpath + '/index.js' );
		editFile( xpath + '/index.php' );
	}
	
	
}

function openIni( file ){
	console.log(file);

	//var src = $("#proceso").attr('src');
	//var path = '/opt/lampp/htdocs' + src.split('/').slice(0, -1).join('/');
	editFile( file + '.ini' );
	
}

function editFile( file ){

	var folderFile = file.split('/').slice(-2)[0];
	
	var open = false;
	var tabs = document.querySelector("#editor").contentWindow.atheos.active.tabList.element.childNodes;
	
	for ( var i = 0; i < tabs.length; i++ ){
		
		/*Compara las carpetas de los archivos abiertos y cierra las que no coinciden*/
		var Tab = '/' + tabs[i].innerText;
		var folderTab = tabs[i].innerText.split('/').slice(-2)[0];
		
		if(folderFile != folderTab) {
			//console.log(folderFile + ' ---> ' + folderTab);
			document.querySelector("#editor").contentWindow.atheos.active.close( Tab );
		}
		
		/*Compara el archivo con los archivos abiertos y si existe no lo abre*/
		if ( file == Tab) {
			//console.log( 'el archivo ya esta abierto..!' );
			open = true;
		} else {
			//console.log('abriendo en editor ' + file);
			
		}
		
	}	
	
	if(!open) document.querySelector("#editor").contentWindow.atheos.filemanager.openFile( file );

	document.querySelector("#editor").contentWindow.atheos.editor.setFontSize(14);
	document.querySelector("#editor").contentWindow.atheos.editor.focus();
		
	
	//getFunctions();
	
	
	$(".EDITOR").show();	
	
}

function getFunctions(){
	
	if( !document.querySelector("#editor").contentWindow.atheos.editor.getActive() ) return;	
	$("#editor").contents().find("#last_login").remove();
	
	$("#editor").contents().find("toaster").remove();
	
	var extension = document.querySelector("#editor").contentWindow.atheos.editor.getActive().session.path.split('.').slice(-1)[0];
	if(!extension) return;
	//console.log( extension );

	extension = extension.toLowerCase();
	
	var xmatch = [];
	var xTitulo;
	
	switch(extension){
		case 'js':
		case 'php':
		case 'html':
			xTitulo = 'Funciones';
			xmatch[0] = /function \w.*\(/g;
		break;
		
		case 'ini':
			xTitulo = 'Secciones';
			xmatch[0] = /\ROTULO\w*\W.\w*.*/g;
			xmatch[1] = /\PIE\w*\W.\w*.*/g;
			xmatch[2] = /\[SQL\w*]/g;
		break;
	}
	//console.log(extension);
	
	$("#editor").contents().find("#SBRIGHT .funciones").html('');
	$("#editor").contents().find("#SBRIGHT .funciones").append('<label class="category" style="font-size:11px; cursor: pointer;" onclick="parent.getFunctions();">' +  xTitulo + '</label>');
	$("#editor").contents().find("#SBRIGHT .funciones").append

	var contenido = document.querySelector("#editor").contentWindow.atheos.editor.getContent();
	if(!contenido) return;
	
	var funciones = [];
	
	$.each( xmatch, function( reg, exp ){
		//console.log( contenido.match( exp ) );
		funciones = funciones.concat ( contenido.match( exp ) );
	} );	
	
	//console.log(funciones);
	
	//if( !funciones.length ) return;
	
	
	if( extension != 'ini'){
		funciones = funciones.map(element => {
			return element.toLowerCase();
		});
		funciones = funciones.sort();
	}

	$.each(funciones, function( reg, func ){
		
		//console.log(func);
		var nombre = func;
		if( extension != 'ini'){
			 nombre = func.replace('function ', '').replace('(','');
		}
		
		$("#editor").contents().find("#SBRIGHT .funciones").append('<a style="padding: 2px;" onclick="parent.buscar(\'' +  func + '\');">' + nombre + '</a>');		
		
	});
	
}

function buscar( texto ){
	
	document.querySelector("#editor").contentWindow.atheos.editor.activeInstance.find(texto);
	
}
