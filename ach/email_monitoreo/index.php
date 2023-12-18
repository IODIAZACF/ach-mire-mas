<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$hora       = date("H:i");

$xempresa   = getsession('CONFIGURACION_NOMBRES');
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

{$modulo->inicio}

<table border="0">
<tr>
	<td id="contenido" style="vertical-align: text-top;"></td>
</tr>
<tr>
	<td class="grid_contenedor" style="height: unset !important;">
		<iframe id="mensaje" width="100%" height="100%" src=""></iframe>
	</td>
</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var opcion	   = '';
var usuario    = '{$nombre_usuario}';
var id_usuario = '{$id_usuario}';
var xempresa   = '{$xempresa}';
var fecha      = '{$xfecha}';
var hora       = '{$hora}';
var tiempo1=0;
var t1;
var pXML,tXML;
var filtroAct;


</script>


EOT;

?>

<script type="text/javascript">

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

contenedor_upload             = new submodal();
contenedor_upload.nombre      = 'contenedor_upload';
contenedor_upload.ancho       = 1200;
contenedor_upload.alto        = 600;
contenedor_upload.titulo      = ' ';
contenedor_upload.x           = 1;
contenedor_upload.y           = 1;
contenedor_upload.botonCerrar = false;
contenedor_upload.titulo      = 'Cargar Archivo';
contenedor_upload.leyenda     = creaLeyenda();
contenedor_upload.ayuda       = 503;
contenedor_upload.usaFrame    = true;
contenedor_upload.interpretar = false;
contenedor_upload.modal       = true;

d_correo           = new lista("email_monitoreo/d_correo")
d_correo.nombre    = "d_correo";
d_correo.padre     = "contenido";
d_correo.funcion   = t_d_correo;
d_correo.onSelect  = mostrar_mail;
d_correo.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_correo.buscador  = true;
d_correo.enter     = 0;
d_correo.botonCerrar = true;

f_edicion 			   = new formulario2('email_monitoreo/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_insertar 			   = new formulario2('email_monitoreo/f_insertar');
f_insertar.nombre       = 'f_insertar';
f_insertar.funcion      = t_f_insertar;
f_insertar.padre        = 'contenedor_cuadro';
f_insertar.tipo		   = 'formulario';


function creaLeyenda()
{
  var l = '';
  l += '<center><table class="tabla_leyenda">';
  l += '<tr>';
  l += etiqLeyenda('ESC', '{$t_salir}', '90', 'Cerrar()');
  l += '</tr>';
  l += '</table></center>';
  return l;
}

function Cerrar()
{
    contenedor_upload.ocultar();
	if(xConte=='d_correo'){
		d_correo.buscar('*');
		d_correo.setFocus();
	}else{
		d_correo.setFocus();
	}
}

function t_d_correo(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
	case _insert:
		cancelaTecla(evt);
		f_insertar.limpiar();
		f_insertar.setValue('FECHA_ENVIO',fecha);
		f_insertar.setValue('HORA_ENVIO',hora);
		mostrar_formulario(f_insertar);
	break;

    case _esc:
    	cancelaTecla(evt);
        Salir();
        break;


    case _f1:
    	cancelaTecla(evt);
        break;
	case _f2:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_D_CORREO');
		if(!registro){
			d_correo.setFocus();
			return;
		};
		var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
		var params = 'tabla=D_ARCHIVOS&busca=ID_D_ARCHIVOS&xbusca=-1&c_RUTA_LSS=/opt/lampp/htdocs/tmp/Presentacion-Sistemas24.pptx&c_TABLA_CSS=D_CORREO&c_IDX_CSS=' + registro[0]['ID_D_CORREO'];
		var x      = enviar(url, params, 'POST');

		break;
	case _f3:
    	cancelaTecla(evt);
        break;
	case _f4:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_D_CORREO');
		if(!registro){
			d_correo.setFocus();
			return;
		};
		xConte = 'd_correo';
		contenedor_upload.destruir();
		contenedor_upload.titulo = 'Correo';
		contenedor_upload.url = server_path  + 'email_monitoreo/correo_visualizar.php?ID_D_CORREO=' + registro[0]['ID_D_CORREO'];
		contenedor_upload.inicializa();
		centrarObj(contenedor_upload.contenedor);
		contenedor_upload.mostrar();
		contenedor_upload.setFocus();
		break;
	case _f5:
    	cancelaTecla(evt);
        break;
    case _f6:
    cancelaTecla(evt);
      var registro = valida_xml(xml,'DESTINATARIO');
      if(!registro)return;
      {
		f_edicion.setValue('DESTINATARIO',registro[0]['DESTINATARIO']);
      	f_edicion.setValue('FECHA_ENVIO',fecha);
      	f_edicion.setValue('FECHA_ENVIO',hora);
        mostrar_formulario(f_edicion);
      }
      break;
    case _f7:
    case _f8:
	case _f9:
    	cancelaTecla(evt);
        break;

	case _f10:
    	cancelaTecla(evt);
			if(confirm('Desea enviar el correo?')){
				var registro = valida_xml(xml,'ID_D_CORREO');
				if(!registro){
					d_correo.setFocus();
					return;
				};
				var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
				var params = 'tabla=D_CORREO&busca=ID_D_CORREO&xbusca='+ registro[0]['ID_D_CORREO'] +'&c_ESTATUS_CSS=CONFIRMADO';
				var x      = enviar(url, params, 'POST');
				d_correo.buscar(registro[0]['ID_D_CORREO'], '=');
				d_correo.setFocus();
			}
			else{
				d_correo.setFocus();
			}

        break;
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
		ocultar_formulario(f_edicion,d_correo);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function t_f_insertar(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	cancelaTecla(evt);
		ocultar_formulario(f_insertar,d_correo);
      	break;

    case _f12:
    	cancelaTecla(evt);

		var registro = f_insertar.submit();
		ocultar_formulario(f_insertar);
		d_correo.buscar();
		d_correo.setFocus();


      	break;
  }
}



function mostrar_mail(obj,xml){

	var registro = valida_xml(xml,'ID_D_CORREO');
	if(!registro) return;

	var rand_no = Math.ceil(100000000*Math.random());
	var url = server_path  + 'email_monitoreo/correo_visualizar.php?rndid='+rand_no+'&ID_D_CORREO=' + registro[0]['ID_D_CORREO'];

	//prompt('', url);
	_prompt('', url );
	
	$('#mensaje').attr('src',url);
	/*
	$('#mensaje').html('');
	$.get( url, function( data ) {
		$('#mensaje').html(data );
	});
	*/
}


function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
          case 'f_edicion':
          t_f_edicion('',_esc);
          break;

          case 'f_insertar':
          t_f_insertar('',_esc);
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

          case 'f_insertar':
          t_f_insertar('',_f12);
          break;
    }
}



function Cerrar_contenedor()
{
          f_activo.ocultar();
          contenedor.ocultar();
}


function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}


function iniciar()
{
    contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	contenedor_upload.inicializa();

	f_edicion.inicializa();
	f_insertar.inicializa();

    d_correo.inicializa();
    d_correo.mostrar();
    d_correo.setFocus();
    d_correo.buscar('*');


	addEvent(INS,  "click",  function() { t_d_correo  ('', _insert, d_correo.elementoXml()) } );
    addEvent(F4,   "click",  function() { t_d_correo  ('', _f4,     d_correo.elementoXml()) } );
    addEvent(F6,   "click",  function() { t_d_correo  ('', _f6,     d_correo.elementoXml()) } );
	addEvent(F10,   "click",  function() { t_d_correo  ('', _f10,     d_correo.elementoXml()) } );
    addEvent(ESC,  "click",  function() { t_d_correo  ('', _esc,    d_correo.elementoXml()) } );

	document.onclick=function() {if (parent.menu) parent.menu.reset();}

}
iniciar();

</script>


</body>
</html>

