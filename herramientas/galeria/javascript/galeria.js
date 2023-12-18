var me_galeria;

class galeria{
    constructor(){
		me_galeria = this;
		
		this.ini;
		this.origen;
		this.padre;
		this.isReady = false;
		this.onload;
		this.filtro;
		this.xfiltro;
		this.values={};
        this.params={};
		this.nombre;
		this.funcion;
		this.visor;
		this.encab;
		this.image;
		this.thumbnails;
		this.controles;
		this.progress_container;
		this.progress;
		this.calidad = 60;
		this.modal;
		//console.log(this.constructor);

		me_galeria.isReady = true;
	
	}
	
	inicializa(){
		
		if( !me_galeria.isReady ) {
			setTimeout( function(){ 
				me_galeria.inicializa();
			} ,50);
			return;
		}

		
		//this.visor = $('<div class="grid_contenedor">').appendTo( "#" + this.padre );
		this.visor = $('<div class="grid_contenedor" style="height: 100%; inset:0; position:relative; padding: 2px 2px 2px 2px; display: flex; flex-direction: column;">').appendTo( "#" + this.padre );
		
		this.image = $('<div class="galeria24-image p-1" data-id="" style="flex: 1; display: flex; align-items: center; justify-content: center;"><img src="" style="border-radius: 4px;"></div>').appendTo( this.visor );
		
		$(this.image).click( function(){

			var src = $(me_galeria.image).find('img').attr('src');
			$(me_galeria.modal).find('p img').attr('src', src);
			$( me_galeria.modal ).addClass('is-active');
			
			
		});
		
	
		this.progress_container = $('<div class="loader-grid-container">').appendTo( this.visor );	
		
		this.progress = $( '<div class="loader-grid-stop">' ).appendTo( this.progress_container );
		
		var xHtml  = '';
			xHtml += '<div class="buttons has-addons is-centered mb-0 mt-1 is-fullwidth" xstyle="border: solid 1px var(--color-gris-oscuro);  border-radius: 4px;">';
			xHtml += '		<button data-calidad="30" class="button is-small">30%</button>';
			xHtml += '		<button data-calidad="40" class="button is-small has-text-weight-bold">40%</button>';
			xHtml += '		<button data-calidad="50" class="button is-small">50%</button>';
			xHtml += '		<button data-calidad="60" class="button is-small">60%</button>';
			xHtml += '		<button data-calidad="80" class="button is-small">80%</button>';
			xHtml += '		<button data-calidad="100" class="button is-small">100%</button>';
			xHtml += '</div>';			
		this.controles = $( xHtml ).appendTo( this.visor );

		$( me_galeria.controles ).find("button").click( function(){
			$( me_galeria.controles ).find("button").removeClass('has-text-weight-bold');
			$( this ).addClass('has-text-weight-bold');

			var id = $( me_galeria.image ).data( 'id' );
			
			me_galeria.calidad = parseFloat( $(this).data("calidad") );
			
			me_galeria.loadImage ( id );
			
		});
		
		this.thumbnails = $('<ul class="galeria24-list galeria24-transition" role="navigation" style="height: 90px; border: solid 1px var(--color-gris-oscuro); border-radius: 4px;">').appendTo( this.visor );
		
		var xHtml = '';
			xHtml += '<div class="modal">';
			xHtml += '	<div class="modal-background"></div>';
			xHtml += '	<div class="modal-content">';
			xHtml += '		<p class="image is-fullwidth">';
			xHtml += '			<img src="" alt="">';
			xHtml += '		</p>';
			xHtml += '	</div>';
			xHtml += '	<button class="modal-close is-large" aria-label="close"></button>';
			xHtml += '</div>';
		this.modal = $( xHtml ).appendTo( 'body' );
		
		$(this.modal).find('.modal-close').click( function() {
			$( me_galeria.modal ).removeClass('is-active');
		});
		
		
		$(this.modal).on('keydown', function() {
			$(this.modal).removeClass('is-active');
			
		});
		
	}
	
	
	mostrar(){
		me_galeria = this;
	}
	
	setFocus(){
		me_galeria = this;
//		this.d_archivos.setFocus();
	}
	
	buscar( xbusca ){
		me_galeria = this;
		
		var url = server_path + 'herramientas/genera_xml/genera_xml.php';
		var params = '';
			params += 'tabla=D_ARCHIVOS';
			params += '&campos=*';
			params += '&filtro='  + this.filtro;
			params += '&xfiltro=' + this.xfiltro ;
		
		var xml = enviar(url, params, 'GET');
		var registro = valida_xml( xml, 'ID_D_ARCHIVOS');
		
		if(!registro) {
			var src = server_path + 'imagenes/productos/0.jpg';
			$( me_galeria.image ).find("img").attr( 'src', src );
			$( me_galeria.thumbnails ).empty(); 
			return;
		}
		
		$(me_galeria.thumbnails).empty();
		
		registro.forEach( function( registro, recno ) {

			var id = registro['ID_D_ARCHIVOS'];
			var ancho   = 80;
			var alto    = ancho; 
			var calidad = 50;
			
			if( recno == 0 ) me_galeria.loadImage( id );

			var url = server_path + 'herramientas/galeria/genera_imagen.php?ID_D_ARCHIVOS=' + id + '&ancho=' + ancho + '&alto=' + alto + '&calidad=' + calidad + '';			
			
			var xHtml = "";
				xHtml +='<li data-index="0" data-viewer-action="view" role="button" tabindex="0" class="">';
				xHtml +='<img src="' + url + '" alt="" data-id="' + id + '" style="width: ' + ancho + 'px; height: ' + alto + 'px; border-radius: 4px;">';
				xHtml +='</li>';
			
			$(xHtml).appendTo( me_galeria.thumbnails );
			
		});
			
		$(this.thumbnails).find('img').on('click', function(){
			var id =  $(this).data('id');
			me_galeria.loadImage( id );	
		});
		
		//this.d_archivos.xfiltro = this.xfiltro;
		//this.d_archivos.buscar( xbusca );
	
	}

	loadImage( id )	{

		//$( me_galeria.image ).find("img").attr( 'src', "");

		var ancho   = $(me_galeria.image)[0].getBoundingClientRect().width  - 10 ;
		var alto    = $(me_galeria.image)[0].getBoundingClientRect().height - 10 ; 

		var calidad = this.calidad;
		
		var url = server_path + 'herramientas/galeria/genera_imagen.php?ID_D_ARCHIVOS=' + id + '&ancho=' + ancho + '&alto=' + alto + '&calidad=' + calidad + '&tipo=base64';			
		
		var request;		
		
		request = new XMLHttpRequest();
		
		request.onloadstart = function(){
			$(me_galeria.progress).removeClass('loader-grid-stop');
			$(me_galeria.progress).addClass('loader-grid-start');
		}
		
		request.onprogress = function( e ){
			if (e.lengthComputable){
				console.log( e.loaded / e.total * 100 );
				var porcentaje = ( e.loaded / e.total * 100 );
				//$(me_galeria.progress).html( porcentaje );
			}
			else
				$(me_galeria.progress).removeClass('loader-grid-start');
				$(me_galeria.progress).addClass('loader-grid-stop');
		}
		
		request.onload = function( ){
			$( me_galeria.image ).data('id', id );
			$( me_galeria.image ).find("img").attr( 'src', "data:image/jpeg;base64," + request.responseText );
			$( me_galeria.progress).toggleClass('loader-grid-stop');
			
		};
		
		request.onloadend = function(){
			//document.body.removeChild(progressBar);
			$(me_galeria.progress).removeClass('loader-grid-start');
			$(me_galeria.progress).addClass('loader-grid-stop');
			
		}
		
		request.open("GET", url, true);
		request.overrideMimeType('text/plain; charset=x-user-defined'); 
		request.send(null);
		
	}
	
	
    setParam(name, value){
        if ((typeof value==="undefined" || value === null) && this.params[name]) {
            delete this.params[name];
        }
        else this.params[name]=value;
    }

}

