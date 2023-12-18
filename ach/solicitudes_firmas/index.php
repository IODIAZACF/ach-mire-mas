<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha        = date("d/m/Y");
$ventana       = getvar('ventana','modulo');
$id            = getvar('id');

$id_m_usuario  = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini        = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery,forma_simple');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr>
		<td id="GRUPO1"></td>
	</tr>
	<tr>
		<td id="GRUPO2"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">


var impresora          	  = new printer();

var fecha         	 = '{$xfecha}';
var xvalida 	  	 = null;
var dxml         	 = null;
var t                = null;
var xdocumento 		 = null;
var xid_x_documentos = null;
var fs               = new forma_simple();

var xID_M_SOLICITUDES;
//var xid_d_pagos      = null;
//*************************************//
contenedor_upload      = new submodal();
contenedor_upload.nombre      = 'contenedor_upload';
contenedor_upload.ancho       = 800;
contenedor_upload.alto        = 400;
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



contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 100;
contenedor.ancho       = 100;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 1;
contenedor.onClose     = Cerrar_contenedor;

m_solicitudes              = new lista('solicitudes_firmas/m_solicitudes');
m_solicitudes.nombre       = 'm_solicitudes';
m_solicitudes.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_solicitudes.usaCookie    = false;
m_solicitudes.funcion      = t_m_solicitudes;
m_solicitudes.padre        = "GRUPO1";
m_solicitudes.buscador     = true;
m_solicitudes.noOcultarCombos     = true;
m_solicitudes.onSelect     = ver_firmantes;

m_solicitudes_firmas              = new lista('solicitudes_firmas/m_solicitudes_firmas');
m_solicitudes_firmas.nombre       = 'm_solicitudes_firmas';
m_solicitudes_firmas.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_solicitudes_firmas.usaCookie    = false;
m_solicitudes_firmas.funcion      = t_m_solicitudes_firmas;
m_solicitudes_firmas.padre        = "GRUPO2";
m_solicitudes_firmas.buscador     = true;
m_solicitudes_firmas.onFocus     = m_solicitudes_firmasFocus;
m_solicitudes_firmas.filtro       = 'ID_M_SOLICITUDES';



f_m_solicitudes 			  = new formulario2('solicitudes_firmas/f_m_solicitudes');
f_m_solicitudes.nombre       = 'f_m_solicitudes';
f_m_solicitudes.funcion      = t_f_m_solicitudes;
f_m_solicitudes.padre        = 'contenedor_cuadro';
f_m_solicitudes.tipo         = 'formulario';

f_m_solicitudes_firmantes 			  = new formulario2('solicitudes_firmas/f_m_solicitudes_firmantes');
f_m_solicitudes_firmantes.nombre       = 'f_m_solicitudes_firmantes';
f_m_solicitudes_firmantes.funcion      = t_f_m_solicitudes_firmantes;
f_m_solicitudes_firmantes.padre        = 'contenedor_cuadro';
f_m_solicitudes_firmantes.tipo         = 'formulario';

function creaLeyenda(){
	var l = '';
	l += '<center><table class="tabla_leyenda">';
	l += '<tr>';
	l += etiqLeyenda('ESC', '{$t_salir}', '90', 'Cerrar()');
	l += '</tr>';
	l += '</table></center>';
	return l;
}

function Cerrar(){
	contenedor_upload.ocultar();
	m_solicitudes.setFocus();
}


function etiqLeyenda(tecla, texto, ancho, accion){
	var e = ' <td onselectstart="return false;" style="width:'+ancho+'px"; class="td_leyenda_inactiva" onmouseup="this.className=\\'td_leyenda_activa\\'" onmouseover="this.className=\\'td_leyenda_activa\\'" onmousedown="this.className=\\'td_leyenda_click\\'" onmouseout="this.className=\\'td_leyenda_inactiva\\'" onclick="'+accion+'">[<B>'+tecla+'</B>]<br>'+texto+'</td>';
	return e;
}


function t_f_m_solicitudes_firmantes(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_solicitudes_firmantes);
			m_solicitudes_firmas.setFocus();
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
			var registro = f_m_solicitudes_firmantes.submit();
			ocultar_formulario(f_m_solicitudes_firmantes);
			m_solicitudes_firmas.actualizar(registro[0]['ID_M_SOLICITUDES_FIRMANTES'], '=');
			m_solicitudes_firmas.setFocus();
		break;
	}
}

function t_f_m_solicitudes(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
	case _insert:
		cancelaTecla(evt);
	break;
	case _enter:
		cancelaTecla(evt);
	break;

	case _supr:
		cancelaTecla(evt);
	break;

	case _esc:
		cancelaTecla(evt);
		ocultar_formulario(f_m_solicitudes);
		m_solicitudes.setFocus();
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
		var registro = f_m_solicitudes.submit();
		ocultar_formulario(f_m_solicitudes);
		m_solicitudes.actualizar(registro[0]['ID_M_SOLICITUDES'], '=');
		m_solicitudes.setFocus();
	break;
	}
}


function m_solicitudes_firmasFocus()
{
   mostrarLeyenda(1);
}

function ver_firmantes(obj,xml)
{
    if (t) window.clearTimeout(t);
    dxml = xml;
    t = window.setTimeout("actualizaDetalle(dxml)",500);
}

function actualizaDetalle(xml)
{
    var registro = valida_xml(xml,'ID_M_SOLICITUDES');
    if(!registro)
    {
        m_solicitudes_firmas.limpiar();
        return;
    }
    xID_M_SOLICITUDES = registro[0]['ID_M_SOLICITUDES'];
	m_solicitudes_firmas.xfiltro=xID_M_SOLICITUDES;
    m_solicitudes_firmas.buscar('*');

    mostrarLeyenda(0);
}

function t_m_solicitudes(objeto, tecla, xml, e){
  var evt = window.event || e;
  switch (tecla){
    case _insert:	
      cancelaTecla(evt);
	  f_m_solicitudes.limpiar();
	  mostrar_formulario(f_m_solicitudes);
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _enter: 
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_SOLICITUDES');
      if(!registro){
		m_solicitudes.setFocus();
	  }
      m_solicitudes_firmas.setFocus();
      mostrarLeyenda(1);
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
			  var registro = valida_xml(xml,'ID_M_SOLICITUDES');
			  if(!registro){
				m_solicitudes.setFocus();
			  }
			contenedor_upload.destruir();
			contenedor_upload.url = server_path  + 'solicitudes_firmas/upload.php?ID_M_SOLICITUDES=' + registro[0]['ID_M_SOLICITUDES'];
			contenedor_upload.inicializa();
			centrarObj(contenedor_upload.contenedor);
			contenedor_upload.mostrar();
			contenedor_upload.setFocus();
		
			break;
	
    case _f3:
    case _f4:
    case _f5:
		cancelaTecla(evt);
		break;
    case _f6:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_SOLICITUDES');
      if(!registro){
		m_solicitudes.setFocus();
	  }
	
      f_m_solicitudes.buscar(registro[0]['ID_M_SOLICITUDES']);
	  mostrar_formulario(f_m_solicitudes);
	  break;

	  
	case _f7:
    case _f8:
	case _f9:
      cancelaTecla(evt);
      break;
    case _f10:
		var registro = valida_xml(xml, 'ID_M_SOLICITUDES');
		var eliminar = confirm('Confirme que desea enviar la notificacion');
		if(eliminar){
			var url = server_path + 'herramientas/utiles/actualizar_registro.php';
			var params   = "tabla=M_SOLICITUDES&c_ESTATUS_CSS=CONFIRMADO&busca=ID_M_SOLICITUDES&xbusca=" + registro[0]['ID_M_SOLICITUDES'];
			var x        = enviar(url, params, 'POST');
			m_solicitudes.actualizar(registro[0]['ID_M_SOLICITUDES'], '=');
			m_solicitudes.setFocus();
		}
	
    case _f11:
    case _f12:
      cancelaTecla(evt);
      break;
  }
}

function t_m_solicitudes_firmas(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
      cancelaTecla(evt);
	  txml = m_solicitudes.elementoXml();
	  tregistro =  valida_xml(txml, 'ID_M_SOLICITUDES');
	  if(!tregistro){
		  alert('Debe Seleccionar una solicitud');
		  m_solicitudes.buscar('*');
		  m_solicitudes.setFocus();
		  return;
	  }
	  
	  f_m_solicitudes_firmantes.limpiar();
	  f_m_solicitudes_firmantes.setValue('ID_M_SOLICITUDES', tregistro[0]['ID_M_SOLICITUDES']);
	  mostrar_formulario(f_m_solicitudes_firmantes);

	  
      break;

    case _enter:
      cancelaTecla(evt);
      break;

    case _supr:	  
		cancelaTecla(evt);
		var registro = valida_xml(xml, 'ID_M_SOLICITUDES_FIRMANTES');
		var eliminar = confirm('{$t_eliminar_registro}');
		if(eliminar){
			var url = server_path + 'herramientas/utiles/actualizar_registro.php';
			var params   = "origen=solicitudes_firmas/m_solicitudes_firmas&procedimiento=ELIMINAR&ID_M_SOLICITUDES_FIRMANTES=" + registro[0]['ID_M_SOLICITUDES_FIRMANTES'];
			var x        = enviar(url, params, 'POST');
			m_solicitudes_firmas.buscar('*');
			m_solicitudes_firmas.setFocus();
		}
      break;

    case _esc:
      cancelaTecla(evt);
      m_solicitudes.setFocus();
      mostrarLeyenda(0);
      break;

    case _f1:
    case _f2:
    case _f3:
    case _f4:
	case _f5:
		cancelaTecla(evt);
		break;
    case _f6:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_SOLICITUDES_FIRMANTES');
      if(!registro){
		m_solicitudes_firmantes.setFocus();
	  }
	
      f_m_solicitudes_firmantes.buscar(registro[0]['ID_M_SOLICITUDES_FIRMANTES']);
	  mostrar_formulario(f_m_solicitudes_firmantes);
	  break;
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

function Salir()
{
	location.href = server_path + 'main/inicio.php';
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
    xformulario.setFocus();
    f_activo = xformulario;
    window.setTimeout("f_activo.setFocus()", 1);
}

function ocultar_formulario(xformulario,xobjecto_destino)
{
    contenedor.ocultar();
    xformulario.ocultar();
    if(xobjecto_destino) xobjecto_destino.setFocus();
}

function Cerrar_contenedor()
{
    f_activo.ocultar();
    contenedor.ocultar();
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_m_solicitudes':
          t_f_m_solicitudes('',_f12);
          break;
       case 'f_m_solicitudes_firmantes':
          t_f_m_solicitudes_firmantes('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_m_solicitudes':
          t_f_m_solicitudes('',_esc);
          break;
       case 'f_m_solicitudes_firmantes':
          t_f_m_solicitudes_firmantes('',_esc);
          break;
    }
}

function iniciar()
{
	  contenedor_upload.inicializa();
	  centrarObj(contenedor_upload.contenedor);
      contenedor.inicializa(false);
      centrarObj(contenedor.contenedor);

      m_solicitudes.inicializa(false);
      m_solicitudes.mostrar();

      m_solicitudes_firmas.inicializa(false);
      m_solicitudes_firmas.mostrar();
	  
	  f_m_solicitudes.inicializa();
	  f_m_solicitudes_firmantes.inicializa();

      document.onclick=function() { if (parent.menu) parent.menu.reset(); }


      addEvent(INSERT, "click",   function() { t_m_solicitudes('', _insert,   m_solicitudes.elementoXml()) } )
	  addEvent(ENTER, "click",   function() { t_m_solicitudes('', _enter,   m_solicitudes.elementoXml()) } )
	  addEvent(F2, "click",   function() { t_m_solicitudes('', _f2,   m_solicitudes.elementoXml()) } )
	  addEvent(F6, "click",   function() { t_m_solicitudes('', _f6,   m_solicitudes.elementoXml()) } )
	  addEvent(F10, "click",   function() { t_m_solicitudes('', _f10,   m_solicitudes.elementoXml()) } )
      addEvent(ESC,   "click",   function() { t_m_solicitudes('', _esc,     m_solicitudes.elementoXml()) } );
	  
	  

      addEvent(FIRMANTE_INSERT,   "click",   function() { t_m_solicitudes_firmas('', _insert,    m_solicitudes_firmas.elementoXml()) } );
	  addEvent(FIRMANTE_F6,   "click",   function() { t_m_solicitudes_firmas('', _f6,    m_solicitudes_firmas.elementoXml()) } );
	  addEvent(FIRMANTE_SUPR,   "click",   function() { t_m_solicitudes_firmas('', _supr,    m_solicitudes_firmas.elementoXml()) } );
	  addEvent(FIRMANTE_ESC,   "click",   function() { t_m_solicitudes_firmas('', _esc,    m_solicitudes_firmas.elementoXml()) } );
	  

     return true;
}

function inicio()
{
    m_solicitudes.setFocus();
    m_solicitudes.buscar();
    mostrarLeyenda(0);
}


function Imprimir(xid)
{
	console.log('Imprimir');
	impresora.origin = 'reportes/r_pedido';
	impresora.setParam('ID_m_solicitudes',xid);
	impresora.print();
}


var resp = iniciar();

if(!resp)
{
        Salir();
}
else
{
        inicio();
}

</script>

</body>
</html>

EOT;

?>
