//var rsm=document.createElement("script");
//rsm.type="text/javascript";
//rsm.src="../herramientas/uploader/javascript/plupload-2.3.7/js/plupload.full.min.js";
//document.head.appendChild(rsm);

var uploadTarget = '../herramientas/uploader/php/receive.php';
var chunk_size = '32kb';
var maxSize = '30mb';

/*
  uso: var uploader = new Uploader({
    filelistId: // ID del elemento que contiene los archivos
    browseId: // ID del elemento que al dar click navega en los archivos locales
    dropId: // ID del elemenbto en donde se sueltan los arcchivos,
    onAdded: function(up, files){} // funcion cuando se agrega un archivo
    onProgress: function(up, file) // funcion cuando se ha subido un porcentaje
    onComplete: function(up, files) // funcion cuando se ha terminado de subir todo
    onError: function(up, err) // funcion que se llama cuando se presentó un error
  })
*/
class Uploader{
	this.autoload = true;
	
	
    constructor(o){
        var self=this;
		
		this.autoload = true;
		
        o=o||{};
        if (typeof o.onComplete==="function") self.onComplete = o.onComplete;
        var bid=o.browseId||'pickfiles';
        console.log("button uploader: "+bid);
        o.fileListId=o.fileListId||"files";
        if (o.query) this.query=o.query;

        var xuploader = new plupload.Uploader({
          browse_button: bid,
          container: document.getElementById(o.dropId||'container'),
          url: uploadTarget,
          chunk_size: chunk_size,
          max_retries: 2,
          filters: {
            max_file_size: maxSize,
            mime_types: o.types || [
              {title: "PDF", extensions: "pdf"},
              {title: "DOCX", extensions: "docx"},
              {title: "XLSX", extensions: "xlsx"},
              {title: "PPTX", extensions: "pptx"},
              {title: "DOC", extensions: "doc"},
              {title: "XLS", extensions: "xls"},
              {title: "PPT", extensions: "ppt"}
            ]
          },
          init: {
            PostInit: function () {
              //document.getElementById(o.fileListId).innerHTML = '';
            },
            FilesAdded: function (up, files) {
              plupload.each(files, function (file) {
                if (typeof o.onAdded==="function") {
                  o.onAdded(file);
                  self.up.start();
                  return;
                }
                /*document.getElementById(o.fileListId).innerHTML += '<div class="uploaded-file" id="' + file.id + '">' +
                  '<div class="ico"></div><div class="name">' + file.name + '</div><div class="size">' + plupload.formatSize(file.size) + '</div>'+
                  '<div class="percent"></div></div>';*/
              });
              xuploader.start();
            },
            UploadProgress: function (up, file) {
              if (typeof o.onProgress==="function") {
                o.onProgress(up, file);
                return;
              }
              document.getElementById(file.id).getElementsByClassName ('percent')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },
            UploadComplete: function(up, files) {
              if (self.onComplete) self.onComplete(files);
            },
            StateChanged: function(up){
              if (up.state === plupload.STARTED && typeof o.onStarted === "function") o.onStarted(up);
            }, 
            Error: function (up, err) {
              // DO YOUR ERROR HANDLING!
              if (typeof o.onError==="function") o.onError(up, err);
              console.log(err);
            }
          }
        });
        xuploader.init();
        self.up = xuploader;
    }
	

    addFile() {
		var el = '<input id="s24FileUpload" name="s24FileUpload" type="file" style="display:none;">';
		$("body").append(el);
		
      var self = this;
      
      var fi = $('#s24FileUpload');
      if (self.autoload) fi.click();

      var params="";
      
      if (self.query) {
        for(var i in self.query) {
          if (params) params+="&";
          params+=i+"="+escape(this.query[i]);
        }
        if (params) self.up.settings.url=uploadTarget+"?"+params;
      }
	  console.log('fi.value', fi.value );
      if (fi.value) {
        self.up.addFile(el);
      }
    }

    start() {
      var self=this;
      self.up.start();
    }
}