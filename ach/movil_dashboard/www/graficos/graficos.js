var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;
var registro;

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params.filtro );
	
	
}

	

$(".TITULO_GRAFICOS").html("Gráficos " + params.filtro );
//$(".progressbar").data( "progress", "50" );

console.log( $("#LISTA").width() );


//app.panel.open('left');

/*** RECORRIDO DE LOS GRAFICOS ****/

var url = server_path + 'herramientas/genera_json/genera_json.php';
var params   = 'tabla=V_M_GRAFICOS&campos=*&busca=CLASE&xbusca=' + params.filtro + '&filtro=REGISTROS&xfiltro=0&xoperadores=>&limite=1000&orden=ORDEN';

enviar2(url , params,'POST', function(data) {
	
	if(!data.tabla.registro) return;

	var y=0;
	
	var cantidad = data.tabla.registro.length;

	var tot_tr = cantidad / 2;
	
	var xx = '';
	
	var ancho = $("#LISTA").width();
	
	yy = 0;
	for (x=0; x< tot_tr; x++ ) {
		xx += '<div class="row">';
		
		for (y=0; y<2; y++ ) {
			yy++;
			xx += '<div class="col-100 medium-50" style="margin-bottom: 60px;">';
			xx += '		<div id="GRAFICO' + yy + '" style="width: 370px; height: 300px; overflow: visible;"></div>';
			xx += '</div>';
			//xx += '		<td ><button id="BOTON_DATA' + y + '" class="col button button-fill" data-funcion="t_M_DATA" data-opcion="' + xregistro.ID_M_GRAFICOS + '" >Data</button></td>';
		}
		
		xx += '</div>';
		
	}
	
	
	$("#LISTA").append(xx);
	
	y = 0;
	
	$.each(data.tabla.registro, function(xtabla, xregistro) {

		y++;

		var porcentaje = y * 100 / cantidad;
		
		app.progressbar.set(".progressbar", porcentaje, 1);

		console.log('PORCENTAJEX: ' + porcentaje);
		$(".XXX").html( porcentaje );
		
		if(xregistro.FORMA=='pie'){ 
			crear_grafico1(xregistro, 'GRAFICO' + y);
		} else {
			crear_grafico2(xregistro, 'GRAFICO' + y);
		}
		
	

	});
	

});



function t_M_GRAFICOS(obj, tecla){
	
	//console.log(obj);
	//console.log(tecla);
	
	
	if( $(obj).data("registro") ) {
		registro =  $(obj).data("registro") ;
		r_M_GUIAS = registro;
	}

	switch(tecla){
		case 'enter':
		break;

		case 'cerrar_detalle':
			app.popup.close("#form-popup");
		break;

		case 'esc':
			cargar_pagina('/menu_principal/');
		break;
		
		
		default:
		app.dialog.alert('Opción no programada..!', tituloAlert);
		return false;
		

	}
}


function t_M_DATA(obj, ID_M_GRAFICOS){

	app.popup.close('#form-popup');	

	app.popup.open('#form-popup');	
	
	var rnd  = Math.floor((Math.random() * 100000000) + 1);
		
	app.request.get('graficos/graficos_datos.html?'+rnd, function (data) {

	$('#form-popup').html(data);

	var url = server_path + 'herramientas/genera_json/genera_json.php';
	var params   = "tabla=X_GRAFICOS&campos=*&filtro=ID_M_GRAFICOS&xfiltro=" + ID_M_GRAFICOS;
	//console.log('', url + '?' + params);
	
	enviar2(url , params,'POST', function(data) {
		
		if(!data.tabla.registro) return;

		var cantidad = data.tabla.registro.length;
		
		$("#DATOS").html('');
		
		$.each(data.tabla.registro, function(xtabla, xregistro) {
			var xhtml = '';
			xhtml += '	<tr>';
			xhtml += '		<td class="label-cell"  >' + xregistro.ROTULO  + '</td>';
			xhtml += '		<td class="numeric-cell">' + xregistro.VALOR1  + '</td>';
			xhtml += '		<td class="numeric-cell">' + xregistro.VALOR2  + '</td>';
			xhtml += '		<td class="numeric-cell">' + xregistro.VALOR3  + '</td>';
			xhtml += '		<td class="numeric-cell">' + xregistro.VALOR4  + '</td>';
			xhtml += '		<td class="numeric-cell">' + xregistro.VALOR5  + '</td>';
			xhtml += '	</tr>';
			$("#DATOS").append(xhtml);

		});
		
	});

		
		
		
	});			

	return;	
	


}


function crear_grafico1(registro, obj_grafico){
	
	var xurl = server_path + 'herramientas/genera_json/genera_json.php';
	params   = "tabla=X_GRAFICOS&campos=*&filtro=ID_M_GRAFICOS&xfiltro=" + registro['ID_M_GRAFICOS'];
	
	var data = enviar(xurl , params,'POST');
	

	if(data.tabla.registro){
		
		dps = [];
		var r = 0;
		$.each(data.tabla.registro, function(xtabla, xregistro) 
		{
				r++;
				var xvalor = xregistro['VALOR1'];
				xvalor = xvalor.replaceAll(',','');
				xvalor = parseFloat(xvalor);
				
				dps.push({indexLabel: xregistro['ROTULO'], y: xvalor  });
		});

		

	} else {
		
		alert('No hay datos para este Gráfico');
		return;
	}
 	

	var chart = new CanvasJS.Chart( obj_grafico ,{ 
		title:     { text: registro['DESCRIPCION'], fontSize: 16 },
		subtitles: [{ text: registro['FECHA_DESDE'] + ' - ' + registro['FECHA_HASTA'] , fontSize: 12}],

		data: [
		{
			type: "pie",
			indexLabel: "#percent%",
			
			toolTipContent: "{y} (#percent%)",
			dataPoints: dps
		}
		]
	});	
	
	
	chart.render();	
	
	
}


function crear_grafico2(registro, obj_grafico)
{
    if(!registro) return;
  
    chart = new CanvasJS.Chart(obj_grafico ,{
		title:     { text: registro['DESCRIPCION'], fontSize: 16 },
		subtitles: [{ text: registro['FECHA_DESDE'] + ' - ' + registro['FECHA_HASTA'] , fontSize: 12}],
		//toolTip:   { content: "{name} {label} {y}" },
		toolTip:   { content: "{name} {y}" },
        legend:    {
            fontSize: 10,
			cursor: "pointer",
            itemclick: function (e) {
                //console.log("legend click: " + e.dataPointIndex);
                //console.log(e);
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }

                e.chart.render();
            }
        },

		axisX:  { title: registro['TITULOX'], titleFontSize: 12, labelFontSize: 10, interval: 1, labelAngle: -20, labelWrap: true },
		axisY:  { title: registro['TITULOY'], titleFontSize: 12, labelFontSize: 10 },
		data: []
		
	});
 //edson

	var xurl = server_path + 'herramientas/genera_json/genera_json.php';
	var params   = "tabla=X_GRAFICOS&campos=*&filtro=ID_M_GRAFICOS&xfiltro=" + registro['ID_M_GRAFICOS'];
	
	//prompt('', url + '?' + params);
	
	var data = enviar(xurl , params,'POST');
	
	
	if(data.tabla.registro)
	{
		
		for (var i = 1; i < 6; i++) { 
				
			dps = [];
			var xtotal = 0;
			var r = 0;
			$.each(data.tabla.registro, function(xtabla, xregistro) 
			{
					r++;
					var xvalor = xregistro['VALOR' + i];
					xvalor = xvalor.replaceAll(',','');
					xvalor = parseFloat(xvalor);
					
					xtotal = xtotal + xvalor;
					
					dps.push({ name: registro['SERIE'+i], label: xregistro['ROTULO'], x: r, y: xvalor  });
			});

			if( xtotal>0 ){
				var newSerie = {
					type: registro['FORMA'],
					showInLegend: true,           
					name: registro['SERIE'+i],
					legendText: registro['SERIE'+i],
					dataPoints: dps
				};
				
				chart.options.data.push(newSerie);  
				
			}
			
		}
		
		chart.render();		
		

	} else {
		
		alert('No hay datos para este Gráfico');
		return;

	}
	

}

