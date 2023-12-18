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
//var xid_d_pagos      = null;
//*************************************//
//se crea el contenedor
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

// Se Crea el Grid Base
m_pedidos              = new lista('auditoria_pedidos/m_pedidos');
m_pedidos.nombre       = 'm_pedidos';
m_pedidos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_pedidos.usaCookie    = false;
m_pedidos.funcion      = t_m_pedidos;
m_pedidos.padre        = "GRUPO1";
m_pedidos.buscador     = true;
m_pedidos.onSelect     = act_timer;
//m_pedidos.filtro       = 'TIPO';
//m_pedidos.xfiltro      = 'FAC';

// Se Crea el Grid del Detalle Base
d_pedidos              = new lista('auditoria_pedidos/d_pedidos');
d_pedidos.nombre       = 'd_pedidos';
d_pedidos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_pedidos.usaCookie    = false;
d_pedidos.funcion      = t_d_pedidos;
d_pedidos.padre        = "GRUPO2";
d_pedidos.buscador     = false;
d_pedidos.onFocus     = d_pedidosFocus;
d_pedidos.filtro       = 'ID_M_PEDIDOS';

function d_pedidosFocus()
{
   mostrarLeyenda(1);
}

function act_timer(obj,xml)
{
    if (t) window.clearTimeout(t);
    dxml = xml;
    t = window.setTimeout("actualizaDetalle(dxml)",500);
}

function actualizaDetalle(xml)
{
    var registro = valida_xml(xml,'ID_M_PEDIDOS');
    if(!registro)
    {
        d_pedidos.limpiar();
        return;
    }
    xID_M_PEDIDOS = registro[0]['ID_M_PEDIDOS'];
	d_pedidos.xfiltro=xID_M_PEDIDOS;
    d_pedidos.buscar('*');

    mostrarLeyenda(0);
}

function t_m_pedidos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert: // Insertar
      cancelaTecla(evt);
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _enter: // Enter
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_PEDIDOS');
      if(!registro)return;
      d_pedidos.setFocus();
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
    case _f3:
      cancelaTecla(evt);
	  break;
    case _f4:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_PEDIDOS');
      if(!registro){
		m_pedidos.setFocus();
	  }
		Imprimir(registro[0]['ID_M_PEDIDOS']);	
	  break;
	
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

function t_d_pedidos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert: // Insertar
      cancelaTecla(evt);
      break;

    case _enter: // Enter
      cancelaTecla(evt);
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
      cancelaTecla(evt);
      m_pedidos.setFocus();
      mostrarLeyenda(0);
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
       case 'f_control':
          t_f_control('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_control':
          t_f_control('',_esc);
          break;
    }
}

function iniciar()
{
      contenedor.inicializa(false);
      centrarObj(contenedor.contenedor);

      m_pedidos.inicializa(false);
      m_pedidos.mostrar();

      d_pedidos.inicializa(false);
      d_pedidos.mostrar();

      document.onclick=function() { if (parent.menu) parent.menu.reset(); }


      addEvent(ENTER, "click",   function() { t_m_pedidos('', _enter,   m_pedidos.elementoXml()) } )
      addEvent(ESC,   "click",   function() { t_m_pedidos('', _esc,     m_pedidos.elementoXml()) } );

      addEvent(D_DOCUMENTOS_ESC,   "click",   function() { t_d_pedidos('', _esc,    d_pedidos.elementoXml()) } );

     return true;
}

function inicio()
{
    m_pedidos.setFocus();
    m_pedidos.buscar();
    mostrarLeyenda(0);
}


function Imprimir(xid)
{
	console.log('Imprimir');
	impresora.origin = 'reportes/r_pedido';
	impresora.setParam('ID_M_PEDIDOS',xid);
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
