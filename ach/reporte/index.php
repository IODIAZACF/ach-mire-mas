<?php

include('../config.php');

include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');


$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$xtipo    = getvar('tipo');
$x_config = $_REQUEST['xconfig'];

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);
$fecha   = date("d/m/Y");
echo '<body id="proceso" onload="ocultaCarga();">' . "\n";

cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora');

echo <<<EOT

<script type="text/javascript">
var origen       = '';
var indice       = '';
var opcion	     = '';
var obj          = '';
var xtitulo      = '';
var xfecha       = '{$fecha}';
var xtipo        = '{$xtipo}';
var mtitulo		 = null;
var xcarpeta     = '';
var xexportar    = null;


</script>

EOT;


?>

<script type="text/javascript">

impresora = new printer();
impresora.showDialog        = true;
impresora.setParam('letras',1);
var inis;


// Se Crea el Grid de Maestro de Pacientes
m_reporte             = new lista("reporte/m_reportes")
m_reporte.nombre      = "m_reporte";
m_reporte.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_reporte.funcion     = t_m_reporte;
m_reporte.buscador    = true;
m_reporte.botonCerrar = true;
m_reporte.enter		  = 1;
m_reporte.x           = 1;
m_reporte.y           = 1;
//m_reporte.modal       = true;
m_reporte.filtro      ='TIPO;ESTATUS';
m_reporte.xfiltro     = xtipo.toUpperCase()+';ACT';/*eduardo para filtrar los reportes*/


m_reporte.inicializa();
centrarObj(m_reporte.contenedor);
m_reporte.mostrar();
m_reporte.setFocus();

mtitulo = m_reporte.titulo;
m_reporte.setTitle(mtitulo +' - '+ xtipo.toUpperCase());


contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 400;
contenedor.alto        = 200;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = 'SUBMODAL 2';
contenedor.botonCerrar = true;
contenedor.leyenda     = creaLeyenda();
contenedor.usaFrame    = false;
contenedor.onClose     = onclose;
contenedor.interpretar = false;
//contenedor.modal = true;
contenedor.inicializa();
centrarObj(contenedor.contenedor);

f_edicion              = new formulario2();
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';
f_edicion.accion 	   = procesa;
//f_edicion.xconfig      = '{$x_config}';

function t_m_reporte(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			break;
		case _enter:
			cancelaTecla(evt);
			var registro = XML2Array(xml);

			if((!registro[0]) || (!registro[0]['ORIGEN'])) return false;

			origen   = registro[0]['ORIGEN'].toLowerCase();
			xcarpeta = registro[0]['CAMPO1'].toLowerCase();
			xtitulo  = registro[0]['TITULO'];
			
			var xurl = 'ini2json.php';
			var xparams = 'origen=' + origen; 

			enviar2(  xurl , xparams , 'POST', function( xdata ){
				inis = xdata;
			});
						
			m_reporte.ocultar();

			if(f_edicion.cuadro)
			{
				if (f_edicion.cuadro.childNodes.length > 0)
				{
					borraHijos(f_edicion.cuadro);
					f_edicion.ancho = null;
					f_edicion.alto  = null;
				}
			}
			f_edicion.origen = xcarpeta +'/f_'+origen;
			indice = f_edicion.indice;
			f_edicion.inicializa();
			contenedor.setTitle(xtitulo.charAt(0).toUpperCase() + xtitulo.slice(1).toLowerCase());
			contenedor.setWidth(f_edicion.ancho-25);
			contenedor.setHeight(f_edicion.alto);
			contenedor.mostrar();
			f_edicion.setValue("FECHA_DESDE",xfecha);
			f_edicion.setValue("FECHA_HASTA",xfecha);
			f_edicion.mostrar();
			f_edicion.setFocus();
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
		case _f8:
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
			break;
  }
}

function t_edicion(objeto,tecla)
{
	obj = objeto;
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			break;
		case _enter:
			cancelaTecla(evt);
			break;
		case _esc:
			cancelaTecla(evt);
			contenedor.ocultar();
			m_reporte.mostrar();
			m_reporte.setFocus();
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
			cancelaTecla(evt);
			break;
		case _f11:
			cancelaTecla(evt);
			xexportar=true;
			f_edicion.setFocus();
			f_edicion.submit();
			break;
		case _f12:
			cancelaTecla(evt);
			xexportar=false;
			f_edicion.setFocus();
			f_edicion.submit();
			break;
	}
}


function procesa(objForm)
{
    var params ='';
	impresora.origin = xcarpeta +'/r_' + origen;
    for (var i=0; i<objForm.length; i++)
	{
	    var campo = objForm[i].name;
        if(campo.substring(0,2)=='c_')
	    {

			var xtipo = campo.substring(campo.length-3);
            var tipo = xtipo.substring(0,1)
            if(tipo=='D')
            {
            	if(objForm[i].value !='' )
                {
                    //var arrFecha = objForm[i].value.split('/');
	        		//var xvalor_buscar = arrFecha[1] +'/'+arrFecha[0]+'/'+arrFecha[2];
                    var arrFecha = objForm[i].value;
	        		var xvalor_buscar = arrFecha;
                }
            }
            else
            {
	            var xvalor           = objForm[i].value.toUpperCase();
	            var xvalor_buscar    = escape(xvalor.replace(/,/gi, '%')) ;
            	//params += '&' + objForm[i].id + '=' + xvalor_buscar;
            }
            impresora.setValue("SERVERPATH", server_path);

            if(tipo=='L') impresora.setValue(objForm[i].id,objForm[i].value);
            else  impresora.setValue(objForm[i].id,objForm[i].value.toUpperCase());

            impresora.setValue("TITULO",xtitulo);
            impresora.setParam(objForm[i].id,xvalor_buscar);
	    }
	}
    impresora.setParam('xconfig','{$x_config}');
    impresora.exportar=xexportar;
    impresora.preview();
    f_edicion.setFocus();
	return false;
}

function Cerrar(){
	onclose();
	
}
function onclose()
{
	contenedor.ocultar();
    m_reporte.mostrar();
	m_reporte.setFocus();
}

function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}


function creaLeyenda()
{
  
  var l = '';

  l += '<div class="tabla_leyenda">';
  l += '<td>';
  l += etiqLeyenda('F12', 'Generar'		, 80, 't_edicion(obj,123)' , 'fa-solid fa-print fa-2x'  );
  l += etiqLeyenda('F11', 'Exportar CSV', 80, 't_edicion(obj,122)' , 'fa-solid fa-file-excel fa-2x'  );
  l += etiqLeyenda('ESC', 'Salir'		, 80, 'Cerrar();' );
  l += '</td>';
  l += '</div>';
  
  return l;
}

document.onclick=function() { if (parent.menu) parent.menu.reset(); }

//addEvent(LEYENDA1, "click",   function() { tecla_doc('', 114, m_reporte.elementoXml()) } )
//addEvent(LEYENDA2, "click",   function() { tecla_doc('', 113, m_reporte.elementoXml()) } )
//addEvent(LEYENDA3, "click",   function() { tecla_doc('', 27, m_reporte.elementoXml()) } )

addEvent(m_reporte_LEYENDA5, "click",   function() { t_m_reporte('', 13, m_reporte.elementoXml()) } )
addEvent(m_reporte_LEYENDA6, "click",   function() { t_m_reporte('', 27, m_reporte.elementoXml()) } )

</script>




