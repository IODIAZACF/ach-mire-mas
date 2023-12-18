// Dom7
var $$ = Dom7;

 const app = new Framework7({
      el: '#app',
      theme: 'aurora',
      routes: [
        {
          path: '/',
          master: true,
          url: './index.html',
        },
      ],
    });
	
	
var xauth;
	

var tituloAlert = 'Acción Contra el Hambre';
var str = document.URL;
var url = str.split("/");

var db = "ach";

var server_path = url[0] + "//" + url[2] + "/" + db + "/";

function tecla_grid(e,id, metodo){
	
	if(e.which != 13) return;
    
	var arr = id.split('-');
    var grid = arr[0];
    var xbusca = $('#' + id ).val().toUpperCase();
	
	buscar_grid(grid, xbusca);
	
	$("#"+id).focus();
    $("#"+id).val('');

	if(app.device.os=='ios' || app.device.os=='android') $("#"+id).blur();

}


function buscar_grid(grid, xbusca){
	
	
	if(xbusca == '') xbusca = '*';
	
	var url = server_path + "herramientas/genera_json/genera_json.php";
	var rnd  = Math.floor((Math.random() * 100000000) + 1);
	
	
	var params = '';
	params += 'tabla=M_GRAFICOS';
	params += '&campos=*';
	params += '&filtro=ESTATUS';
	params += '&xfiltro=ACT';
	params += '&busca=DESCRIPCION';
	params += '&xbusca=' + xbusca;
	params += '&rnd=' +  rnd;
	
	
	//prompt('',url + '?' + params);
    console.log(url + '?' + params);


	$( "#GRAFICOS" ).html( '' );
	
	var xhtml = '';	


	enviar2(url, params, 'post', function(data){

		
		$.each(data.tabla.registro, function(xtabla, xregistro) {

			xhtml += '		<li>';
			xhtml += '			<a href="#" class="item-link item-content">';
			xhtml += '				<div class="item-inner">';
			xhtml += '					<div class="item-title-row">';
			xhtml += '						<div class="item-title">Facebook</div>';
			xhtml += '						<div class="item-after">' + xregistro['FECHA_REGISTRO_H'] + '</div>';
			xhtml += '					</div>';
			xhtml += '					<div class="item-subtitle">' + xregistro['DESCRIPCION'] + '</div>';
			xhtml += '					<div class="item-text">'   + xregistro['TIPO'] +   '</div>';
			xhtml += '				</div>';
			xhtml += '			</a>';
			xhtml += '		</li>';
					
			$( "#GRAFICOS" ).append( xhtml );
			
		});			
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



	
	