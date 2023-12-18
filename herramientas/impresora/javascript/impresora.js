var _conteo = false;
var url=new URL("", location);
var reportServer = url.origin + "/herramientas/reporte/";
var s24OReportAct;

class printer{
    constructor(){
		
        var me	=	this;		
		this.dockerIP	= '172.17.0.1';
		this.url 		= this.dockerIP  + '/' + sistema + '/herramientas/impresora/impresora_new.php';
        this.urlCSV		= server_path + '/herramientas/impresora/impresora_csv.php';
		this.Url_Path	= server_path + '/herramientas/impresora/impresora.php';

        this.values={};
        this.params={};
        this.showDialog="";
        this.origin="";
        this.web=false;
        this.contenedor=null;
        this.focusObj=null;
        this.exportar=false;
		this.compress=1;
		this.viewer=1;
		this.onClose 	= null;
		
		this.s24OReportcontenedor             = new submodal();
		this.s24OReportcontenedor.nombre      = 's24OReportcontenedor';
		this.s24OReportcontenedor.ancho       = 800;
		this.s24OReportcontenedor.alto        = 600;
		this.s24OReportcontenedor.titulo      = ' ';
		this.s24OReportcontenedor.x           = 100;
		this.s24OReportcontenedor.y           = 100;
		this.s24OReportcontenedor.botonCerrar = true;
		this.s24OReportcontenedor.leyenda     = '  ';
		this.s24OReportcontenedor.ayuda       = 1;
		this.s24OReportcontenedor.usaFrame    = true;
		this.s24OReportcontenedor.interpretar = false;
		this.s24OReportcontenedor.inicializa();
		
		s24OReportAct = this;
		
		$('#sub_container_s24OReportcontenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar').prop("onclick", null).off("click");
		$("#sub_container_s24OReportcontenedor > div.grid_title > div.grid_barra_titulo > div.grid_controles > span.grid_cerrar").click(function(e) { 
			e.preventDefault();
			e.stopPropagation();		
			
			s24OReportAct.s24OReportcontenedor.iframe.src="about:blank";
			s24OReportAct.s24OReportcontenedor.ocultar();
			
			if( s24OReportAct.onClose ){
				s24OReportAct.onClose();
			}			

		});

        fetch(reportServer)
            .then(response => response.text())
            .then(data => {
                me.web = true;
            })
    }

    setParam(name, value){
        if ((typeof value==="undefined" || value === null) && this.params[name]) {
            delete this.params[name];
        }
        else this.params[name]=value;		
    }

    getParam(name){
        return this.params[name]||null;
    }

    setValue(name, value){
        if ((typeof value==="undefined" || value === null) && this.values[name]) {
            delete this.values[name];
        }
        else this.values[name]=value;
    }

    getValue(name){
        return this.values[name]||null;
    }

    clearValues(){
        this.values={};
    }

    preview = function(noPrint){
		
        if (!this.origin) return;
        if (this.doPrint) this.values["print"]=1;
        else {
            if (this.values["print"]) delete this.values["print"];
        }	
		
		var params=['origen=' + this.origin, 'db=' + db , 'SERVERPATH=http://' + this.dockerIP + '/' + sistema + '/'];
		
        if (!this.copies) this.copies=1;

        for (var i in this.params){
            params.push(i+'='+this.params[i]);
        }

        for (var i in this.values){
            params.push(i+'='+this.values[i]);
        }
		
		params.push('compress=' + this.compress );
		params.push('id_m_usuario=' + id_m_usuario );
		
		params.push('log_USER_ID=' + id_m_usuario );
		params.push('log_USER_NAME=' + nombre_usuario.replaceAll(' ', '+' ) );
		params.push('log_SISTEMA=' + sistema );
		params.push('log_SISTEMA_DB=' + db );		
		
        if (!!noPrint) params.push["np=1"];

        if (_conteo)
        {
            try{
                var xorigin = this.origin.replace('reportes/r_','');
                var xurl=server_path + 'herramientas/utiles/actualizar_registro.php';
                var xparams='tabla=M_REPORTES&busca=ORIGEN&xbusca='+xorigin+'&c_CONSULTAS_NSS=CONSULTAS%2B1';
                var xxml = enviar(xurl, xparams,'POST');
            }
            catch(e){
                alert(e.description);
            }
        }
		

        if (this.web && !this.exportar) {
			if(this.viewer==0){
				params.push('viewer=0');
				this.viewer = 1;
				this.previewPdf(params);
			}else{
				params.push('viewer=1');
				this.previewWeb(params);				
			}
            return;
        }
		
        var url='o24report://' + this.url + '?' + params.join('&');
		
        if (this.exportar){
			url	= this.urlCSV + '?' + params.join('&').replaceAll(' ', '+');
			$.fileDownload(url);
			return;
		} 	
    }

    previewWeb(params) {
        if (!this.origin) return;
        var tit="Reporte";
        if (this.values["TITULO"]) {
            tit = this.values.TITULO;
            params.push("title="+tit);
        }

        this.focusObj=$(":focus");
        var _rp=this;

        var url = reportServer + '?url=http://' + this.url + '?' + params.join('&');
		
		
		if (debug()){
			var url_debug = this.Url_Path + "?impresora_debug=1&" + params.join("&").replaceAll(' ', '+');
			_prompt("impresora.js", "Reporte Debug:", url_debug);
		} 
		this.s24OReportcontenedor.iframe.src = url ;
		this.s24OReportcontenedor.mostrar();
		this.s24OReportcontenedor.setTitle(tit);

	}

    previewPdf(params) {
        if (!this.origin) return;
        var tit="Reporte";
        if (this.values["TITULO"]) {
            tit = this.values.TITULO;
            params.push("title="+tit);
        }

        this.focusObj=$(":focus");
        var _rp=this;

        var url=reportServer + "?url=http://"+this.url + "?" + params.join("&");
		
		if (debug()){
			var url_debug = this.Url_Path + "?impresora_debug=1&" + params.join("&").replaceAll(' ', '+');
			_prompt("impresora.js", "Reporte Debug:", url_debug);			
		} 

		this.s24OReportcontenedor.iframe.src = url ;
		this.s24OReportcontenedor.mostrar();
		this.s24OReportcontenedor.setTitle(tit);

    }

    print() {
        this.doPrint=1;
        this.preview();
        this.doPrint=null;
    }

}
