<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$fecha   = date("d/m/Y");

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$query = new sql();
$query->sql = "SELECT ID_M_CONCEPTOS,NOMBRES FROM M_CONCEPTOS WHERE CAMPO1='*'";
$query->ejecuta_query();
$query->next_record();
$xid_m_conceptos     = $query->Record['ID_M_CONCEPTOS'];
$xnombre_concepto    = $query->Record['NOMBRES'];


$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,tabpane,formulario2,forma,submodal,impresora,jquery,popup');

echo <<<EOT

{$modulo->inicio}

<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 30px;
    color: #000000;
-->
</style>

<div id="contenido">

<table border="0">
<tr>
<td id="GRUPO1" </td>
<td id="GRUPO2" </td>
</tr>
</table>

</div>

{$modulo->fin}

<script type="text/javascript">

var xproveedor  = 0;
var xsaldo    = 0;
var xcodigo1  = null;
var xcodigo2  = null;
var xdireccion  = null;
var xtelefono  = null;
var xnombre_proveedor = null;
var f_activo=null;
var idcxccxp =null;
var xdocumento;
var xpadre;
var xreferencia;
var xid_reverso;
var dxml;
var t;
var xrazon_proveedor
var fecha = '{$fecha}';

var xid_m_conceptos  = '{$xid_m_conceptos}';
var xnombre_concepto = '{$xnombre_concepto}';
var impresora  = new printer();
var xid_cxp = null;

//Se Crea El Contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.ayuda	   = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'titulo';
contenedor.botonCerrar = true;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.leyenda     ='  ';
contenedor.onClose     = function ()
{
	f_activo.ocultar();
    contenedor.ocultar();
}

cxp           = new lista("auditoria_cxp/d_cxp_documentos")
cxp.nombre    = "cxp";
cxp.padre     = "GRUPO1";
cxp.funcion   = t_cxp;
cxp.url       = server_path + "herramientas/genera_xml/genera_xml.php";
cxp.buscador  = true;
cxp.onSelect  = Actualiza_detalle;
cxp.filtro    = 'IDX';
cxp.onFocus   = focus_cxp;

d_cxp           = new lista("auditoria_cxp/d_cxp_detalles")
d_cxp.nombre    = "d_cxp";
d_cxp.padre     = "GRUPO2";
d_cxp.funcion   = t_d_cxp;
d_cxp.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_cxp.filtro    = 'ID_PADRE';
d_cxp.onFocus   = focus_d_cxp;

m_proveedores             = new lista("maestros/m_proveedores")
m_proveedores.nombre      = "m_proveedores";
m_proveedores.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_proveedores.funcion     = t_m_proveedores;
m_proveedores.buscador    = true;
m_proveedores.x           = 1;
m_proveedores.y           = 1;
m_proveedores.modal       = true;
m_proveedores.botonCerrar = true;
m_proveedores.enter       = 1;

f_cxp          = new formulario2('auditoria_cxp/f_cxp');
f_cxp.nombre   = 'f_cxp';
f_cxp.funcion  = t_f_cxp;
f_cxp.padre    = 'contenedor_cuadro';
f_cxp.tipo     = 'formulario';

f_d_cxp          = new formulario2('auditoria_cxp/f_d_cxp');
f_d_cxp.nombre   = 'f_d_cxp';
f_d_cxp.funcion  = t_f_d_cxp;
f_d_cxp.padre    = 'contenedor_cuadro';
f_d_cxp.tipo     = 'formulario';

f_d_cxp_rim          = new formulario2('auditoria_cxp/f_d_cxp_rim');
f_d_cxp_rim.nombre   = 'f_d_cxp_rim';
f_d_cxp_rim.funcion  = t_f_d_cxp_rim;
f_d_cxp_rim.padre    = 'contenedor_cuadro';
f_d_cxp_rim.tipo     = 'formulario';

function focus_cxp()
{
	mostrarLeyenda(0);
}
function focus_d_cxp(obj)
{
    if(GridAct.nombre==obj.nombre) mostrarLeyenda(1);
}

function Actualiza_detalle(objeto, xml)
{
    var registro = XML2Array(xml);
    if (!registro[0]||!registro[0]['ID_D_CXCCXP'])
    {
       d_cxp.limpiar();
       xid_cxp =null;
       limpiarElementos('PIED_');
       return;
    }
    dxml = xml;
	setTimeout('act_timer(dxml)',500);
}
function act_timer(xml)
{
    if (t) window.clearTimeout(t);
    dxml = xml;
    t = window.setTimeout("muestra_detalle(dxml)",300);
    actualizaHTML(xml,'PIED_');
}

function muestra_detalle(xml)
{
    var registro = XML2Array(xml);
    xid_cxp   = registro[0]['ID_D_CXCCXP'];
    d_cxp.xfiltro = xid_cxp;
    d_cxp.buscar('*');
}

function t_m_proveedores(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _enter: // Enter
      var registro = valida_xml(xml,'ID_M_PROVEEDORES');
      if (!registro)return;
      m_proveedores.ocultar();

      actualizaHTML(xml,'ENCABEZADO');
      xproveedor = registro[0]['ID_M_PROVEEDORES'];
      xcodigo1  = registro[0]['CODIGO1'];
      xcodigo2  = registro[0]['CODIGO2'];
      xnombre_proveedor =registro[0]['NOMBRES'];
      xrazon_proveedor = registro[0]['RAZON'];
      xdireccion      = registro[0]['DIRECCION'];
      xtelefono       = registro[0]['TELEFONOS'];
      xpadre=null;
      Calcula_saldo(xproveedor);
      cxp.xfiltro=xproveedor + ',M_PROVEEDORES';
      cxp.buscar('*');
      cxp.setFocus();
    break;

    case _esc: // Escape
      if (xproveedor)
      {
        m_proveedores.ocultar();
        cxp.setFocus();
      }
      else Salir();
    break;
  }
}

function t_cxp(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // Enter
  	  	cancelaTecla(evt);
        var registro = valida_xml(xml,'DOCUMENTO');
      	if (!registro)return;
      	d_cxp.setFocus();
      	mostrarLeyenda(1);
    break;

    case _f3:
      	cancelaTecla(evt);
      	m_proveedores.mostrar();
      	m_proveedores.setFocus();
    break;

	case _f4:
        cancelaTecla(evt);
        var registro = XML2Array(xml);
        xdocumento = registro[0]['DOCUMENTO'];
        var xtipo = registro[0]['TIPO'];
        if (!xdocumento) return false;
        if(xtipo =='COM' || xtipo =='NCP') VerPreview(xdocumento,xtipo);

    break;

    case _f6:
      cancelaTecla(evt);
	  var registro = valida_xml(xml,'ID_D_CXCCXP');
      if(!registro)return;
      f_cxp.limpiar();
      f_cxp.buscar(registro[0]['ID_D_CXCCXP']);
      mostrar_formulario(f_cxp);
    break;

    case _f8:
    case _f9:
    case _f10:
    case _f11:
      cancelaTecla(evt);
    break;

    case _supr: // Del
  	  cancelaTecla(evt);
      var registro = valida_xml(xml,'DOCUMENTO');
      if (!registro)return;
      var registro = valida_xml(xml,'ID_D_CXCCXP');
      if(!registro)
      {
      	alert('{$t_operacion_no_permitida}');
      	return;
      }
      xid_reverso = registro[0]['ID_D_CXCCXP'];
      var xtipo   = registro[0]['TIPO'];
      var eliminar = confirm('{$t_reversar_documento}');
      if(eliminar)
	  {
      	if(xtipo == 'COM')
      	{
			waitExec('{$t_realizando_proceso}', 'Reverso_nd()', 5, 10, 283);
      	}
       	if(xtipo=='NDP' || xtipo=='ADP' || xtipo=='ACP' || xtipo=='NCP')
       	{
        	waitExec('{$t_realizando_proceso}', 'eliminaRegistro()', 5, 10, 283);
       	}
      }
	break;

    case _esc:
      Salir();
    break;

    case _insert: // Insertar
    case _supr: // Del
    case _f1:
    case _f2:
    case _f5:
    case _f7:
    case _f12:
  	  cancelaTecla(evt);
	break;
    default:
    break;
  }
}

function t_d_cxp(objeto, tecla, xml, e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case _esc:
       cancelaTecla(evt);
       cxp.setFocus();
       mostrarLeyenda(0);
    break;

    case _f4:
       cancelaTecla(evt);
       var registro = valida_xml(xml,'ID_D_CXCCXP');
       if(!registro) return;
       var xtipo = registro[0]['TIPO'];
       if(xtipo!='RRP' && xtipo!='RIP') return;
       xpadre = registro[0]['ID_D_CXCCXP'];
       var xdocumento = registro[0]['DOCUMENTO'];
       VerPreview(xdocumento,xtipo);
    break;

    case _supr:
       cancelaTecla(evt);
       var registro = valida_xml(xml,'ID_D_CXCCXP');
	   if(!registro) return;
       xid_reverso = registro[0]['ID_D_CXCCXP'];
       xpadre = registro[0]['ID_D_PADRE'];
       var xtipo = registro[0]['TIPO'];

       var borrar = confirm("$t_eliminar_registro");
       if(borrar)
       {
       	if(xtipo=='NDP' || xtipo=='ADP' || xtipo=='ACP' || xtipo=='NCP' || xtipo=='RRP' || xtipo=='RIP')
       	{
        	waitExec('{$t_realizando_proceso}', 'eliminaRegistro(1)', 5, 10, 283);
       	}
        else
        {
			waitExec('{$t_realizando_proceso}', 'Reverso_nd(1)', 5, 10, 283);
        }


	   }
       else return;
    break;

	case _enter:
    	cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_D_CXCCXP');
        if(!registro)return;
        var xtipo = registro[0]['TIPO'];
        if(xtipo=='RRP' || xtipo=='RIP' )
        {
        	f_d_cxp.buscar(registro[0]['DOCUMENTO']);
        	mostrar_formulario(f_d_cxp);
        }

        if(xtipo=='REM')
        {
        	f_d_cxp_rim.buscar(registro[0]['ID_D_CXCCXP']);
        	mostrar_formulario(f_d_cxp_rim);
        }
        else
        {
         alert('{$t_operacion_no_permitida_doc}');
        }
        break;
  }
}

function Reverso_nd(xid)
{
	var url = server_path + 'herramientas/utiles/actualizar_registro.php';
    var params = 'tabla=D_CXCCXP&busca=ID_D_CXCCXP&xbusca='+xid_reverso+"&c_CAMPO5_CSS=REV";
    x = enviar(url, params, 'POST');
    var registro = valida_xml(x,'ID_D_CXCCXP');
    if(xid)
    {
    	cxp.actualizar(xpadre);
        d_cxp.buscar('*');
    }
    else
    {
      cxp.buscar('*');
      cxp.setFocus();
      Calcula_saldo(xproveedor);
      mostrarLeyenda(0);
    }
    xid_reverso=null;
}

function eliminaRegistro(reg)
{
    var url   = server_path + "herramientas/utiles/actualizar_registro.php";
    var param = "origen=auditoria_cxp/d_cxp_detalles&procedimiento=DELETE&ID_D_CXCCXP=" + xid_reverso;
    var x     = enviar(url,param,'POST');
    if(reg)
    {
        cxp.actualizar(xid_cxp);
        d_cxp.buscar('*');
    }
    else
    {
      cxp.buscar('*');
      cxp.setFocus();
      Calcula_saldo(xproveedor);
      mostrarLeyenda(0);
    }
    xid_reverso=null;
}

function t_f_cxp(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
        var registro = f_cxp.submit();
		if(!registro)return;
        ocultar_formulario(f_cxp,cxp);
        cxp.actualizar(registro[0]['ID_CXCCXP']);
        break;

    case _esc: // ESC Cerrar Formulario
    	ocultar_formulario(f_cxp,cxp);
      	break;
  }
}

function t_f_d_cxp(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
        var registro = f_d_cxp.submit();
        if(!registro)return;
        ocultar_formulario(f_d_cxp,d_cxp);
        d_cxp.actualizar(registro[0]['ID_CXCCXP']);
        break;

    case _esc: // ESC Cerrar Formulario
        ocultar_formulario(f_d_cxp,d_cxp);
        break;
  }
}

function t_f_d_cxp_rim(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
        var registro = f_d_cxp_rim.submit();
        if(!registro)return;
        ocultar_formulario(f_d_cxp_rim,d_cxp);
        d_cxp.actualizar(registro[0]['ID_CXCCXP']);
        break;

    case _esc: // ESC Cerrar Formulario
        ocultar_formulario(f_d_cxp_rim,d_cxp);
        break;
  }
}

function VerPreview(doc,tipo)
{
   if(tipo !='ABO' && tipo !='ANP')
   {
    var xsufijo = tipo.toLowerCase();
    impresora.origin = 'reportes/r_documento_'+xsufijo;
    impresora.setParam('ID_M_DOCUMENTOS',doc);
   }
    impresora.showDialog=1;
    impresora.preview();
    if(GridAct.nombre=='cxp') cxp.setFocus();
    else d_cxp.setFocus();
    return;
}


function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}

function irMenu()
{
  if (parent.menu) parent.menu.reset();
}


function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_cxp':
          t_f_cxp('',_f12);
          break;

       case 'f_d_cxp':
          t_f_d_cxp('',_f12);
       break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_cxp':
          t_f_cxp('',_esc);
       break;

       case 'f_d_cxp':
          t_f_d_cxp('',_esc);
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
    window.setTimeout("f_activo.setFocus()", 100);
}

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
}

function Calcula_saldo(xproveedor)
{
   var url = server_path + 'herramientas/genera_xml/genera_xml.php';
   var params = "origen=auditoria_cxp/d_cxp_documentos&procedimiento=SALDO&IDX=" + xproveedor;
   var xml= enviar(url, params, 'GET');
   actualizaHTML(xml,'PIE','totales');
}

function iniciar()
{
  contenedor.inicializa(false);
  centrarObj(contenedor.contenedor);

  cxp.inicializa(false);
  cxp.mostrar();
  cxp.limpiar();

  d_cxp.inicializa(false);
  d_cxp.mostrar();

  m_proveedores.inicializa(false);
  centrarObj(m_proveedores.contenedor);

  f_cxp.inicializa(false);
  f_d_cxp.inicializa(false);
  f_d_cxp_rim.inicializa(false);

  addEvent(CXP_F3, 	  "click",   function()   { t_cxp('', _f3,  	cxp.elementoXml()) } )
  addEvent(CXP_ENTER, "click",   function()   { t_cxp('', _enter,  	cxp.elementoXml()) } )
  addEvent(CXP_F4,	  "click",   function()   { t_cxp('', _f4, 		cxp.elementoXml()) } )
  addEvent(CXP_F6,	  "click",   function()   { t_cxp('', _f6, 		cxp.elementoXml()) } )
  addEvent(CXP_ESC,   "click",   function()   { t_cxp('', _esc, 	cxp.elementoXml()) } )
  addEvent(CXP_SUPR,  "click",   function()   { t_cxp('', _supr, 	cxp.elementoXml()) } )

  addEvent(D_CXP_ESC, "click",   function() { t_d_cxp('', _esc, 	d_cxp.elementoXml()) } )
  addEvent(D_CXP_F4,  "click",   function() { t_d_cxp('', _f5, 		d_cxp.elementoXml()) } )
  addEvent(D_CXP_SUPR,"click",   function() { t_d_cxp('', _supr, 	d_cxp.elementoXml()) } )

  addEvent(m_proveedores_ENTER, "click",   function() { t_m_proveedores('', _enter, m_proveedores.elementoXml()) } )
  addEvent(m_proveedores_ESC, 	"click",   function() { t_m_proveedores('', _esc,   m_proveedores.elementoXml()) } )

  document.onclick=irMenu;

  return true;

}

function inicio()
{
	mostrarLeyenda(0);
    m_proveedores.mostrar();
  	m_proveedores.setFocus();
}

var resp = iniciar();
if(resp)
{
    inicio();
}
else
{
	Salir();
}
</script>


</body>
</html>

EOT;

?>