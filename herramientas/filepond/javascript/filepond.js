var me_uploader24;


class uploader24{
    constructor(){
		me_uploader24 = this;
		
		this.ini;
		
		this.padre;
		this.ancho;
		this.alto;
		this.isReady = false;
		this.FilePond;
		this.onload;
		this.onprocessfiles;
		this.onerror;
		this.ondata;
		this.maxFiles;
		this.maxFilesSize = 3;
		this.values={};
        this.params={};
		this.camara = true;
		this.onclose;
		this.mensaje;
		
		this.FilePond_contenedor             = new submodal();
		this.FilePond_contenedor.nombre      = 'FilePond_contenedor';
		this.FilePond_contenedor.titulo      = ' ';
		this.FilePond_contenedor.x           = 100;
		this.FilePond_contenedor.y           = 100;
		this.FilePond_contenedor.botonCerrar = true;
		this.FilePond_contenedor.ayuda       = 0;
		this.FilePond_contenedor.usaFrame    = false;
		this.FilePond_contenedor.interpretar = false;
		this.FilePond_contenedor.modal 		 = true;
		this.FilePond_contenedor.leyenda     = '<center><table class="tabla_leyenda"><tr><td>' + etiqLeyenda('ESC', 'Cerrar', '90', 'me_uploader24.ocultar();') + '</td></tr></table></center>';
		this.url_Server = server_path + '/herramientas/filepond/class/';

		this.FilePond_contenedor.onClose	 = function(){
			if(me_uploader24.camara) {
				me_uploader24.cameraPhoto.stopCamera()
				.then(() => {
				  console.log('Camera stoped!');
				})
				.catch((error) => {
				  console.log('No camera to stop!:', error);
				});
			}
		};
		
		this.FilePond_contenedor.inicializa();
		

		$("#FilePond_contenedor_cuadro, #FilePond_contenedor_popupMask").on('dragover drop',function(e){
			e = e || event;
		    e.preventDefault();	
		});

		$("#FilePond_contenedor_cuadro").css('background-color','var(--color-blanco)');
		$("#FilePond_contenedor_cuadro").css('padding','2px');
		//$("#sub_container_FilePond_contenedor").css('border-bottom','solid 1px var(--color-azul-oscuro)');
		$("#sub_container_FilePond_contenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar").prop("onclick", null).off("click");
		$("#sub_container_FilePond_contenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar").click(function(e) { 
			e.preventDefault();
			e.stopPropagation();
			
			me_uploader24.ocultar();
			
		});
		

		if( !this.maxFiles ){
			this.maxFiles = 1;
		}
		

		const head = document.getElementsByTagName('head')[0]
		const style1 = document.createElement('link') 
		style1.href = server_path + 'herramientas/filepond/external/filepond.min.css'
		style1.type = 'text/css'
		style1.rel = 'stylesheet'
		head.append(style1);

		const style2 = document.createElement('link') 
		style2.href = server_path + 'herramientas/filepond/external/filepond.min.css'
		style2.type = 'text/css'
		style2.rel = 'stylesheet'

		style2.href = server_path + '/herramientas/filepond/external/filepond-plugin-image-preview.css'
		head.append(style2);

		$.when(

			$.getScript( server_path + "herramientas/filepond/external/filepond.min.js" ),
			//$.getScript( server_path + "herramientas/filepond/external/filepond-plugin-pdf-preview.min.js" ),
			
			$.getScript( server_path + "herramientas/filepond/external/filepond-plugin-file-encode.js" ),
			$.getScript( server_path + "herramientas/filepond/external/filepond-plugin-image-preview.js" ),
			$.getScript( server_path + "herramientas/filepond/external/filepond-plugin-file-validate-size.js" ),
			$.getScript( server_path + "herramientas/filepond/external/filepond-plugin-file-validate-type.js" ),
			$.getScript( server_path + "herramientas/filepond/external/jslib-html5-camera-photo.min.js" ),
			$.Deferred(function( deferred ){
				$( deferred.resolve );
			})
		).done(function(){
			
			FilePond.registerPlugin(
				FilePondPluginFileEncode,
				FilePondPluginImagePreview,
				FilePondPluginFileValidateSize,
				FilePondPluginFileValidateType,
				//FilePondPluginPdfPreview
			);
			
			const contenedor = $("#FilePond_contenedor_cuadro" );
			const table   = $('<table style="width: 100%; height: 100%;">').appendTo( contenedor );
			const tr1	  = $('<tr>').appendTo( table );
			
			if (me_uploader24.camara) {
				const td1	  = $('<td width="330px" class="has-text-centered p-1" style="border-radius: 8px; border: solid 1px var(--color-gris-claro);" class="has-text-centered" >').appendTo( tr1 );
				const webcam  = $('<video id="webcam" autoplay="true" playsInline></video>').appendTo( td1 );			
				const webbot  = $('<button class="button is-small mt-2" onclick="me_uploader24.tomarFoto();"><span class="icon is-small"><i class="fa-solid fa-camera"></i></span><span>Tomar Foto</span></button>' ).appendTo( td1 );
			}

			const td2	  = $('<td class="has-text-centered p-0" style="">').appendTo( tr1 );
			const input   = $('<input type="file" id="FilePond24" class="filepond" name="filepond[]" multiple data-allow-reorder="true" data-max-file-size="' + me_uploader24.maxFilesSize + 'MB" data-max-files="' + me_uploader24.maxFiles + '" style="display: none;">').appendTo( td2 );

			const tr2	  = $('<tr>').appendTo( table );
			
			me_uploader24.mensaje  = $('<td colspan="2" class="has-text-centered p-0" style="height: 23px; padding-top: 3px !important; border-radius: 8px 8px 8px 8px; border: solid 1px var(--color-gris-claro);">').appendTo( tr2 );
			
			var videoElement = document.getElementById('webcam');
			me_uploader24.cameraPhoto = new JslibHtml5CameraPhoto.default(videoElement);
			
			me_uploader24.isReady = true;
		});	
		
		
	}
	
	
	inicializa(){
		
		if( !me_uploader24.isReady ) {
			setTimeout( function(){ 
				me_uploader24.inicializa();
			} ,50);
			return;
		}
		
		
		if( inis[this.origen] ){
			this.ini = inis[this.origen];
		} else {
			console.error('No hay ini con este origen --> [' + this.origen + ']');
			return;
		}

		me_uploader24.labelIdle = this.ini['VENTANA'].MENSAJE;
		
		if( !this.ancho ) this.ancho = this.ini['VENTANA'].ANCHO;
		if( !this.alto )  this.alto = this.ini['VENTANA'].ALTO;
		
		me_uploader24.FilePond_contenedor.setWidth( this.ini['VENTANA'].ANCHO );
		me_uploader24.FilePond_contenedor.setHeight( this.ini['VENTANA'].ALTO );
		me_uploader24.FilePond_contenedor.setTitle( this.ini['VENTANA'].TITULO );

		var typeFiles = [
			'text/plain', 
			'image/*', 
			'application/pdf', 
			'application/msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.ms-excel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.ms-powerpoint',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		];

		var options = {
			labelIdle: me_uploader24.labelIdle,
			imagePreviewHeight: 50,
			name: 'filepond',
			allowBrowse: true,
			acceptedFileTypes: typeFiles,
			fileValidateTypeDetectType: (source, type) =>
				new Promise((resolve, reject) => {
				// Do custom type detection here and return with promise

				resolve(type);
			}),			
			styleLoadIndicatorPosition: 'center bottom',
			styleProgressIndicatorPosition: 'right bottom',
			styleButtonRemoveItemPosition: 'left bottom',
			styleButtonProcessItemPosition: 'right bottom',
			
			// server
			server: {
				load: ( uniqueFileId, load, error, progress, abort, headers) => {
						console.log('attempting to load', uniqueFileId);
						let controller = new AbortController();
						let signal = controller.signal;

						fetch( me_uploader24.url_Server + 'load.php?&origen=' + me_uploader24.origen + '&key=' + uniqueFileId , {
						method: "GET",
						signal,
					})
					.then(res => {
					  window.c = res
					  console.log(res)
					  return res.blob()
					})
					.then(blob => {
					  console.log(blob)
					  load(blob);
					})
					.catch(err => {
					  console.log(err)
					  error(err.message);
					})
					return {
						abort: () => {
						  controller.abort();
						  abort();
						}
					};
				},
			},
		};
		
		me_uploader24.FilePond = FilePond.create(
			document.querySelector( "#FilePond24" ),
			options,
		);

		me_uploader24.FilePond.on('warning', (error, file) => {
			console.log('Warning', error, file);
		});

		FilePond.setOptions({
			server: {
				// url: "/",
				process: {
				url: me_uploader24.url_Server + 'process.php',
				method: 'POST',
				headers: {
				  'x-customheader': 'Processing File'
				},
				
				
				onprocessfiles: (response) => {
				  response = JSON.parse(response);

				  if( me_uploader24.onprocessfiles ){
					  me_uploader24.onprocessfiles( response );
				  }

				  return response.key;
				},

				onload: (response) => {
				  response = JSON.parse(response);

				  if( me_uploader24.onload ){
					  me_uploader24.onload( response );
				  }

				  return response.key;
				},

				onerror: (response) => {
					response = JSON.parse(response);
					if( me_uploader24.onerror ){
						me_uploader24.onerror( response );
					}
					return response.msg
				},

				ondata: (formData) => {
						window.h = formData;
						  
						for (var i in me_uploader24.params){
							formData.append( i , me_uploader24.params[i] ); 
						}
						
						formData.append( 'origen', me_uploader24.origen ); 
						formData.append( 'maxsize', me_uploader24.maxFilesSize ); 
						formData.append( 'db', db );
						
						var modulo = window.location.pathname;
						formData.append( 'url_modulo', modulo );
		  
						if( me_uploader24.ondata ){
						  me_uploader24.ondata( formData );
						}
						return formData;
					}
				},
			
				revert: (uniqueFileId, load, error) => {
					const formData = new FormData();
					formData.append("key", uniqueFileId);

					for (var i in me_uploader24.params){
						formData.append( i , me_uploader24.params[i] ); 
					}
					formData.append( 'origen', me_uploader24.origen ); 
					formData.append( 'db', db );
					var modulo = window.location.pathname;
					formData.append( 'url_modulo', modulo );			

					//fetch( me_uploader24.url_Server + 'revert.php?origen=' + me_uploader24.origen + '&key=' + uniqueFileId, {
					fetch( me_uploader24.url_Server + 'revert.php', {				
					  method: "POST",
					  body: formData,
					})
					.then(res => res.json())
					.then(json => {
					if (json.status == "success") {
					  // Should call the load method when done, no parameters required
					  load();
					} else {
					  // Can call the error method if something is wrong, should exit after
					  error(err.msg);
					}
					})
					.catch(err => {
						console.log(err)
						// Can call the error method if something is wrong, should exit after
						error(err.message);
					})
				},
			  
				remove: (uniqueFileId, load, error) => {
					const formData = new FormData();
					formData.append("key", uniqueFileId);

					fetch( me_uploader24.url_Server + 'revert.php?key=' + uniqueFileId, {
					  method: "DELETE",
					  body: formData,
					})
					.then(res => res.json())
					.then(json => {
						console.log(json);
						if (json.status == "success") {
							// Should call the load method when done, no parameters required
							load();
						} else {
							// Can call the error method if something is wrong, should exit after
							error(err.msg);
						}
					})
					.catch(err => {
					console.log(err)
						// Can call the error method if something is wrong, should exit after
						error(err.message);
					})
				},
			  
				restore: (uniqueFileId, load, error, progress, abort, headers) => {
					let controller = new AbortController();
					let signal = controller.signal;

					fetch( me_uploader24.url_Server + 'load.php?key=' + uniqueFileId , {
						method: "GET",
						signal,
					})
					.then(res => {
						window.c = res
						console.log(res)
						const headers = res.headers;
						const contentLength = +headers.get("content-length");
						const contentDisposition = headers.get("content-disposition");
						let fileName = contentDisposition.split("filename=")[1];
						fileName = fileName.slice(1, fileName.length - 1)
						progress(true, contentLength, contentLength);
						return {
							blob: res.blob(),
							size: contentLength,
						}
					})
					.then(({ blob, size }) => {
						console.log(blob)
						// headersString = 'Content-Disposition: inline; filename="my-file.jpg"'
						// headers(headersString);

						const imageFileObj = new File([blob], `${uniqueFileId}.${blob.type.split('/')[1]}`, {
							type: blob.type
						})
					
						console.log(imageFileObj)
						progress(true, size, size);
						load(imageFileObj);
					})
					.catch(err => {
						console.log(err)
						error(err.message);
					})

					return {
						abort: () => {
							// User tapped cancel, abort our ongoing actions here
							controller.abort();
							// Let FilePond know the request has been cancelled
							abort();
						}
					};
				},
			},
		})
	  
		$("#FilePond24").css('height', (this.alto) - 100  + 'px');
		$("#FilePond24").css('border', 'solid 1 px var(--color-gris-oscuro)');
		$("#FilePond24").css('margin-bottom', '0px');

		document.addEventListener('FilePond:processfiles', (e) => {
			console.log('Todos los archivos procesados', e.detail);

			
			// get create method reference
			//const { create } = e.detail;
		});
	  
	}
	
	
	setMessage( texto ){
		$(this.mensaje).html( texto );
	}
	
	addFile( image ){
		
		if( !me_uploader24.isReady ) {
			setTimeout( function(){ 
				me_uploader24.addFile( image );
			} ,50);
			return;
		}
		
		me_uploader24.FilePond.addFile( image.src );
		
	}
	
	
	limpiar(){
		me_uploader24.FilePond.removeFiles();		
		
	}

	tomarFoto(){

		var sizeFactor = 1;
		var imageType = 'jpg';
		var imageCompression = 1;

		var config = {
			sizeFactor,
			imageType,
			imageCompression
		};
		
		var data_uri = me_uploader24.cameraPhoto.getDataUri(config);
		var image = me_uploader24.dataURLtoFile(data_uri, 'capture.jpg');
		me_uploader24.FilePond.addFile( image )
	}
	
    setParam(name, value){
        if ((typeof value==="undefined" || value === null) && this.params[name]) {
            delete this.params[name];
        }
        else this.params[name]=value;
		
    }
	
	dataURLtoFile(dataurl, filename) {
        var arr = dataurl.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), 
            n = bstr.length, 
            u8arr = new Uint8Array(n);
            
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        
        return new File([u8arr], filename, {type:mime});
    }
	
	mostrar(){
		if(me_uploader24.camara) {
			me_uploader24.cameraPhoto.startCamera()
			.then(() => {
			  var log = `Camera started with default All`;
			  console.log(log);
			})
			.catch((error) => {
			  console.error('Camera not started!', error);
			});
			
		}	
		me_uploader24.FilePond_contenedor.mostrar();
	}

	ocultar(){
		
		me_uploader24.FilePond_contenedor.ocultar();
		if( me_uploader24.onclose ) {
			me_uploader24.onclose();
		}
	}
		
	startCamera() {
		me_uploader24.cameraPhoto.startCamera()
		.then(() => {
		  var log = `Camera started with default All`;
		  console.log(log);
		})
		.catch((error) => {
		  console.error('Camera not started!', error);
		});
	}
	
	stopCamera () {
		me_uploader24.cameraPhoto.stopCamera()
		.then(() => {
		  console.log('Camera stoped!');
		})
		.catch((error) => {
		  console.log('No camera to stop!:', error);
		});
	}
	
	onFinish( ){
		
		
	}

}


 



