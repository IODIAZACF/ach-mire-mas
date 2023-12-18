var docEditorAct = null;
function onlyoffice()
{
	this.docEditor = null;
	/*
	var FileName	= 'documentos/usuarios/' + xID_PROPIETARIO + '/' + xID_D_ARCHIVOS + '.' + fileType;
	var url 		= 'http://192.168.1.150/protecseguros/' + FileName;
	var callbackUrl = 'http://192.168.1.150/protecseguros/' + 'herramientas/onlyoffice/save.php';
	*/
	
	this.UrlStorage		= server_addr;
	this.FileName		= '';
	this.UrlFile 		= '';
	this.fileType 		= '';
	this.callbackUrl 	= '';
	this.UsuarioId 		=null;
	this.NombreUsuario 	= null;
	this.Titulo 		= null;
	this.Edit			= true;
	this.Download		= true;
	this.Print			= true;
	this.Comment		= true;
	
	this.onClose 				= null;
	this.onDownloadAs 			= null;
	this.onDocumentReady		= null;
	this.onDocumentStateChange 	= null;
	this.onError				= null;
	this.onRequestClose			= null;
	this.onPluginsReady			= null;
	
	
	this.s24Onlyofficecontenedor             = new submodal();
	this.s24Onlyofficecontenedor.nombre      = 's24Onlyofficecontenedor';
	this.s24Onlyofficecontenedor.ancho       = 800;
	this.s24Onlyofficecontenedor.alto        = 600;
	this.s24Onlyofficecontenedor.titulo      = ' ';
	this.s24Onlyofficecontenedor.x           = 100;
	this.s24Onlyofficecontenedor.y           = 100;
	this.s24Onlyofficecontenedor.botonCerrar = true;
	this.s24Onlyofficecontenedor.leyenda     = '  ';
	this.s24Onlyofficecontenedor.ayuda       = 1;
	this.s24Onlyofficecontenedor.usaFrame    = false;
	this.s24Onlyofficecontenedor.interpretar = false;
	this.s24Onlyofficecontenedor.inicializa();
	$('#sub_container_s24Onlyofficecontenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar').prop("onclick", null).off("click");
	$("#sub_container_s24Onlyofficecontenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar").click(function(e) { 
	    e.preventDefault();
		e.stopPropagation();
		
		
		docEditorAct.s24Onlyofficecontenedor.ocultar();
		docEditorAct.docEditor.destroyEditor();
		$("#s24OnlyofficePreview").remove();
		
		if( docEditorAct.onClose ){
			docEditorAct.onClose();
		}
		
		docEditorAct.docEditor = null;

	});
	
	$.getScript( "/onlyoffice/web-apps/apps/api/documents/api.js", function( data, textStatus, jqxhr ) {
	});	
	
}


onlyoffice.prototype.mostrar=function()
{
	this.UrlFile = this.UrlStorage + this.FileName;

	if(this.callbackUrl==''){
		this.callbackUrl = this.UrlStorage + 'herramientas/onlyoffice/save.php';		
	}
	
	console.log( 	this.UrlFile );
	var documentType;
	var fileType;
	
	fileType = this.fileType;
	
	if(fileType==''){		
		var fileType = this.FileName.split(".").pop();
	} 
	
	var mode = this.Edit ? 'edit' : 'view';

		
	switch(fileType){
		case "doc":
		case "docx":
		case "pdf":
		case "rtf":
			documentType = "word";
			break;
		case "xls":
		case "xlsx":
			documentType = "cell";
			break;
		case "ppt":
		case "pptx":
			documentType = "slide";
			break;
	}
	

	var config = {
		"documentType": documentType,
		"document": {
			"fileType": fileType,
			"key": 		btoa ( this.FileName  ) + '-' + makeid() ,
			"title": 	this.Titulo,
			"url": 		this.UrlFile,
			"permissions": {
				"download": this.Download,
				"print": 	this.Print,
				"comment": 	this.Comment,
				"edit": 	this.Edit,
				"review": 	true
			}
		},
		"editorConfig": {
			"lang":			"es",
			//"region":		"es_ES",			
			//"location": 	"es",
			"mode": 		mode,			
			"callbackUrl": 	this.callbackUrl, 
			"user": {
				"id":  		this.UsuarioId,
				"name": 	this.NombreUsuario
			},
			"coEditing": {
				"mode": 	"fast",
				"change": 	true
			},			
			"customization" : {
				"forcesave":true,
				"features": {
					"spellcheck": {
						"mode": true,
						"change": false
					}
				},
				 
			},
		},
	  "events": {
			"onDownloadAs": _onDownloadAs,
			"onDocumentReady": _onDocumentReady,
			"onDocumentStateChange": _onDocumentStateChange,
			"onError": _onError,
			"onRequestClose": _onRequestClose,
			"onPluginsReady": _onPluginsReady,
		}	
	}

	//console.log(this.s24Onlyofficecontenedor);
	$("<div class='s24OnlyofficePreview' id='s24OnlyofficePreview'>").appendTo(this.s24Onlyofficecontenedor.cuadro);
	
	this.docEditor = new DocsAPI.DocEditor("s24OnlyofficePreview", config);
	this.s24Onlyofficecontenedor.mostrar();
	docEditorAct = this;
}

function _onDownloadAs (e)
{
	if(docEditorAct.onDownloadAs){
		docEditorAct.onDownloadAs(e);
	}
}

function _onDocumentReady (e)
{
	if(docEditorAct.onDocumentReady){
		docEditorAct.onDocumentReady(e);
	}
}

function _onDocumentStateChange (e)
{
	if(docEditorAct.onDocumentStateChange){
		docEditorAct.onDocumentStateChange(e);
	}
}

function _onError(e)
{
	if(docEditorAct.onError){
		docEditorAct.onError(e);
	}
}

function _onRequestClose(e)
{
	if(docEditorAct.onRequestClose){
		docEditorAct.onRequestClose(e);
	}
}

function _onPluginsReady(e)
{
	if(docEditorAct.onPluginsReady){
		docEditorAct.onPluginsReady(e);
	}
}
