<?php

include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$hora       = date("H:i");

$xempresa  		= getsession('CONFIGURACION_NOMBRES');
$nombre_usuario = getsession('M_USUARIOS_NOMBRES');
$id_m_usuario   = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal,password');


echo <<<EOT
<script>

window.alert = function( xtext ){
	var myModal = new parent.jBox('Modal', {
		content: xtext
	});
	
	console.log(xtext);	
	myModal.open();

};

</script>
<script src="js/canvasjs.js"></script>


{$modulo->inicio}


<table border="0" cellpadding=0 cellspacing=2>
<tr>
	<td id="GRUPO1" width="35%" style="vertical-align: text-top;"></td>
	<td id="GRUPO2" width="65%" class="grid_contenedor">
		<div id="titulo"  class="grid_encab" 		 style="width: 100%;">Grafico</div>
		<div id="grafico" class="grid_cuadro_activo" style="display: block;width: 100%; height: 95% !important; "></div>
	</td>
</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var chart;
var dps = []; //dataPoints.

var opcion	   = '';
var usuario    = '{$nombre_usuario}';
var id_usuario = '{$id_usuario}';
var xempresa   = '{$xempresa}';
var fecha      = '{$xfecha}';
var hora       = '{$hora}';
var filtroAct;
var f_activo = null;;

</script>

EOT;

?>



<script type="text/javascript">

//se crea el contenedor

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 1;
contenedor.ancho       = 1;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 1;
contenedor.onClose     = Cerrar_contenedor;

// Se Crea el Grid de las Detalle De Los Mensajes
m_graficos             = new lista("modulo_graficos/m_graficos")
m_graficos.nombre      = "m_graficos";
m_graficos.padre       = "GRUPO1";
m_graficos.funcion     = tecla_doc;
m_graficos.onSelect    = crear_grafico;
m_graficos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_graficos.buscador    = true;
m_graficos.enter       = 0;
m_graficos.botonCerrar = true;



f_edicion 			   = new formulario2('modulo_graficos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_edicion2 			   = new formulario2('modulo_graficos/f_edicion');
f_edicion2.nombre      = 'f_edicion2';
f_edicion2.funcion     = t_f_edicion2;
f_edicion2.padre       = 'contenedor_cuadro';
f_edicion2.tipo		   = 'formulario';
f_edicion2.accion	   = procesa;

f_edicion3 			   = new formulario2('modulo_graficos/f_edicion3');
f_edicion3.nombre      = 'f_edicion3';
f_edicion3.funcion     = t_f_edicion3;
f_edicion3.padre       = 'contenedor_cuadro';
f_edicion3.tipo		   = 'formulario';

f_todos 			   = new formulario2('modulo_graficos/f_todos');
f_todos.nombre       = 'f_todos';
f_todos.funcion      = t_f_todos;
f_todos.padre        = 'contenedor_cuadro';
f_todos.tipo		   = 'formulario';

m_datos                = new lista("modulo_graficos/m_datos")
m_datos.nombre         = "m_datos";
m_datos.funcion        = t_m_datos;
m_datos.modal          = true;
m_datos.url            = server_path + "herramientas/genera_xml/genera_xml.php";
m_datos.buscador       = true;
m_datos.enter          = 0;
m_datos.botonCerrar    = true;
m_datos.filtro         = 'ID_M_GRAFICOS';


function crear_grafico(objeto,xml){

	var registro = valida_xml(xml,'ID_M_GRAFICOS');
	if (!registro) return;

	if(registro[0]['FORMA'] == 'pie') crear_grafico1(xml);	
	else crear_grafico2(xml);

	
}





function crear_grafico1(xml){
	
	var registro = valida_xml(xml,'ID_M_GRAFICOS');

	var xurl = server_path + 'herramientas/genera_json/genera_json.php';
	params   = "tabla=X_GRAFICOS&campos=*&filtro=ID_M_GRAFICOS&xfiltro=" + registro[0]['ID_M_GRAFICOS'];
	
	var x = enviar(xurl , params,'POST');
	
	var data = jQuery.parseJSON(x);

	
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
 	


	var chart = new CanvasJS.Chart("grafico",{ 
		title:     { text: registro[0]['DESCRIPCION'], fontSize: 16 },
		subtitles: [{ text: registro[0]['FECHA_DESDE'] + ' - ' + registro[0]['FECHA_HASTA'] , fontSize: 12}],

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


function crear_grafico2(xml)
{

	var registro = valida_xml(xml,'ID_M_GRAFICOS');
    if(!registro) return;
  
	
	
    chart = new CanvasJS.Chart("grafico",{
		title:     { text: registro[0]['DESCRIPCION'], fontSize: 16 },
		subtitles: [{ text: registro[0]['FECHA_DESDE'] + ' - ' + registro[0]['FECHA_HASTA'] , fontSize: 12}],
		toolTip:   { content: "{name} {label} {y}" },
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
 

	var xurl = server_path + 'herramientas/genera_json/genera_json.php';
	params   = "tabla=X_GRAFICOS&campos=*&filtro=ID_M_GRAFICOS&xfiltro=" + registro[0]['ID_M_GRAFICOS'];
	
	console.log('',xurl + params);
	
	var x = enviar(xurl , params,'POST');
	var data = jQuery.parseJSON(x);

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
					
					dps.push({ name: registro[0]['SERIE'+i], label: xregistro['ROTULO'], x: r, y: xvalor  });
			});

			if( xtotal>0 ){
				var newSerie = {
					type: registro[0]['FORMA'],
					showInLegend: true,           
					name: registro[0]['SERIE'+i],
					legendText: registro[0]['SERIE'+i],
					dataPoints: dps
				};
				
				chart.options.data.push(newSerie);  
				
			}
			
		}
		
		
		console.log(dps);
	} else {
		
		alert('No hay datos para este Gráfico');
		return;

	}
	
	chart.render();	


}


function tecla_doc(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {

	case _insert:
    	cancelaTecla(evt);
        f_edicion.setValue('FECHA_DESDE', fecha);
        f_edicion.setValue('FECHA_HASTA', fecha);
		f_edicion.limpiar();
		mostrar_formulario(f_edicion);
        break;

	case _enter: 
    	cancelaTecla(evt);
	    var registro = valida_xml(xml,'ID_M_GRAFICOS');
        f_edicion.buscar(registro[0]['ID_M_GRAFICOS']);
        mostrar_formulario(f_edicion);
        break;

	case _esc:
    	cancelaTecla(evt);
        Salir();
        break;

	


	case _f1:
	case _f2:
	case _f3:
	case _f4:
	case _f5:
	case _f6:
	case _f7:
    	cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_GRAFICOS');
		if(!registro) return;
		
		if( confirm('Este procesa genera la data de todos los graficos previamente vistos por algun usuario, seguro de correr el proceso?') ){
			f_todos.buscar(registro[0]['ID_M_GRAFICOS']);
			f_todos.setValue('CONDICION1','T');
			mostrar_formulario(f_todos);
		}
		return;
        break;	
	case _f8:
		cancelaTecla(evt);
		break;
	
	case _f9: 
    	cancelaTecla(evt);
	    var registro = valida_xml(xml,'ID_M_GRAFICOS');
        f_edicion3.buscar(registro[0]['ID_M_GRAFICOS']);
        mostrar_formulario(f_edicion3);
        break;

	case _f10: 
		cancelaTecla(evt);
		armarPlantilla(xml);
		break;

	case _f11:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_GRAFICOS');
		if(!registro)return;
			
		m_datos.xfiltro = registro[0]['ID_M_GRAFICOS'];
		m_datos.mostrar();
		m_datos.buscar("*");
		m_datos.setFocus();
		break;

	case _f12:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_GRAFICOS');
		if(!registro)return;

		
		if(registro[0]['FORMA'] == 'pie') crear_grafico1(xml);	
		else crear_grafico2(xml);
		break;

  }
}

function t_m_datos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {

    case _esc: // Esc
    	cancelaTecla(evt);
        m_datos.ocultar();
		m_graficos.setFocus();
        break;
		
	case _f1:
	case _f2:
	case _f3:
	case _f4:
	case _f5:
	case _f6:
	case _f7:
	case _f8:
	case _f9:
	case _f10:
	case _f11:
	case _f12:
    	cancelaTecla(evt);
        break;

  }
}


function t_f_edicion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion,m_graficos);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        
		var xquery = f_edicion.getValue('QUERY');		// lee el valor del formulario
		xquery = xquery.toLowerCase(); 					// a minusculas
		xquery = xquery.replaceAll('where','there'); 	//reemplaza todo
		xquery = xquery.toUpperCase(); 					// a mayusculas
		f_edicion.setValue('QUERY',xquery);				// guarda el valor en el formulario

		f_edicion.setValue('XQUERY','');
		f_edicion.setValue('CONDICION','');
		
		var registro = f_edicion.submit();
		if(!registro){
			alert('Error al guardar el registro');
			return;
		} 
		
		ocultar_formulario(f_edicion, m_graficos);
        m_graficos.actualizar(registro[0]['ID_M_GRAFICOS'])

      	break;
  }
}

function t_f_edicion2(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion2,m_graficos);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
		f_edicion2.submit();
		//m_graficos.actualizar(registro[0]['ID_M_GRAFICOS']);
      	break;
  }
}

function t_f_edicion3(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion3,m_graficos);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
		f_edicion3.submit();
		ocultar_formulario(f_edicion3,m_graficos);
      	break;
  }
}

function t_f_todos(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc: //Escape
    	cancelaTecla(evt);
		ocultar_formulario(f_todos,m_graficos);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
		var registro = f_todos.submit();
		if(!registro){
			alert('Error al guardar el registro');
			return;
		} 
		
		ocultar_formulario(f_todos, m_graficos);
        m_graficos.buscar('')
      	break;
  }
}

function armarPlantilla(xml)
{

	var registro = valida_xml(xml,'ID_M_GRAFICOS');
	if(!registro) return;
	
	origen = registro[0]['ID_M_GRAFICOS'];
	if(!origen) return;

	f_edicion2.destruir();
	f_edicion2.origen = "modulo_graficos/formularios/f_" + origen;
	f_edicion2.inicializa();
	f_edicion2.limpiar();
	f_edicion2.setValue("FECHA_DESDE",registro[0]['FECHA_DESDE']);
	f_edicion2.setValue("FECHA_HASTA",registro[0]['FECHA_HASTA']);
	mostrar_formulario(f_edicion2);

	
}



function procesa(objForm)
{

	var xml = m_graficos.elementoXml();
	
	var registro = valida_xml(xml,'ID_M_GRAFICOS')

	f_edicion.limpiar();
	f_edicion.buscar(registro[0]['ID_M_GRAFICOS']);

	var xquery = f_edicion.getValue('QUERY');		// lee el valor del formulario

	xquery = xquery.toLowerCase(); 					// a minusculas
	xquery = xquery.replaceAll('where','there'); 	//reemplaza todo
		
	var params ='';
    for (var i=0; i<objForm.length; i++)
	{
	    var campo = objForm[i].name;
		if(campo.substring(0,2)=='c_')
	    {
			var xcampo = objForm[i].id;
			var xtipo = campo.substring(campo.length-3);
            var tipo = xtipo.substring(0,1)
            if(tipo=='D')
            {
            	if(objForm[i].value !='' )
                {
					if(xcampo=='FECHA_DESDE') f_edicion.setValue('FECHA_DESDE', objForm[i].value);
					if(xcampo=='FECHA_HASTA') f_edicion.setValue('FECHA_HASTA', objForm[i].value);
					
					var arrFecha = objForm[i].value.split('/');
	        		var xvalor = arrFecha[1] +'/'+arrFecha[0]+'/'+arrFecha[2];
                }
            }
            else
            {
	            var xvalor1    = objForm[i].value.toUpperCase();
	            var xvalor     = escape(xvalor1.replace(/,/gi, '%')) ;
				xvalor = xvalor.toLowerCase();
            }

			
			xcampo = xcampo.toLowerCase();
			xquery = xquery.replaceAll( '{' + xcampo + '}' , xvalor ); 
			
	    }
		
	}
	
	xquery = xquery.toUpperCase(); 	// a mayusculas
	f_edicion.setValue('XQUERY',xquery);	// guarda el valor en el formulario
	f_edicion.setValue('CONDICION1','*');
	
	ocultar_formulario(f_edicion2);
	var registro = f_edicion.submit();
	if(!registro){
		alert('Error al guardar el registro');
		return;
	} 
	m_graficos.actualizar(registro[0]['ID_M_GRAFICOS']);
	m_graficos.setFocus();	
	
	//buscar_data(xml);
	
}


function Refrescar()
{
    m_graficos.buscar('*');
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
          case 'f_edicion':
          t_f_edicion('',_esc);
          break;
		  
          case 'f_edicion2':
          t_f_edicion('',_esc);
          break;
		  
          case 'f_edicion3':
          t_f_edicion('',_esc);
          break;
		  
          case 'f_todos':
          t_f_todos('',_esc);
          break;		  
    }
}

function mostrar_formulario(xformulario)
{
    contenedor.setTitle(xformulario.titulo);
    contenedor.setWidth(xformulario.ancho);
    contenedor.setHeight(xformulario.alto);
    centrarObj(contenedor.contenedor);
    contenedor.setLegend(xformulario.leyenda);
    contenedor.mostrar();
    xformulario.mostrar();
    f_activo = xformulario;
    window.setTimeout("f_activo.setFocus()", 1);
}

function ocultar_formulario(xformulario,xobjecto_destino)
{
    contenedor.ocultar();
	xformulario.ocultar();
	if(xobjecto_destino) xobjecto_destino.setFocus();
}


function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
			t_f_edicion('',_f12);
			break;

		case 'f_edicion2':
			t_f_edicion2('',_f12);
			break;

		case 'f_edicion3':
			t_f_edicion3('',_f12);
			break;

		case 'f_todos':
			t_f_todos('',_f12);
			break;			

    }
}


function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};


function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}


function iniciar(){

/*	
if(id_m_usuario == '0013' ){
	alert('hola');
	return false;	
}
*/	

    contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	f_edicion.inicializa();
	f_edicion3.inicializa();
	f_todos.inicializa();

    m_graficos.inicializa();
    m_graficos.mostrar();
    m_graficos.setFocus();
    m_graficos.buscar('*');

    m_datos.inicializa();
	centrarObj(m_datos.contenedor);

    addEvent(INSERT,"click",  function() { tecla_doc  ('', _insert, m_graficos.elementoXml()) } )
    addEvent(ENTER, "click",  function() { tecla_doc  ('', _enter, m_graficos.elementoXml()) } )
    addEvent(F7,   "click",  function() { tecla_doc  ('', _f7,   m_graficos.elementoXml()) } )
	addEvent(F9,   "click",  function() { tecla_doc  ('', _f9,   m_graficos.elementoXml()) } )
    addEvent(F10,   "click",  function() { tecla_doc  ('', _f10,   m_graficos.elementoXml()) } )
    addEvent(F11,   "click",  function() { tecla_doc  ('', _f11,   m_graficos.elementoXml()) } )
    addEvent(F12,   "click",  function() { tecla_doc  ('', _f12,   m_graficos.elementoXml()) } )
    addEvent(ESC,   "click",  function() { tecla_doc  ('', _esc,   m_graficos.elementoXml()) } )

    addEvent(m_datos_ESC,   "click",  function() { t_m_datos  ('', _esc,   m_datos.elementoXml()) } )

	document.onclick=function() {if (parent.menu) parent.menu.reset();}
	return true;
}

if( !iniciar() ) Salir();



</script>


</body>
</html>



