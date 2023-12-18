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

var fecha         	 = '{$xfecha}';

var xproveedor    	  = null;
var xsaldo            = 0;
var xcodigo1          = null;
var xcodigo2      	  = null;
var xdireccion    	  = null;
var xtelefono         = null;
var xnombre_proveedor = null;
var f_activo          = null;
var idcxccxp          = null;
var xdias_credito 	  = null;
var xdocumento        = null;
var xid_cxp    = null;
var xpadre;
var xreferencia;
var xid_reverso;
var xid_reversar;

var xvalida 	  	 = null;
var dxml         	 = null;
var t                = null;
var xid_m_documentos = null;
var fs               = new forma_simple();
var impresora		 = new printer();

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

f_control              = new formulario2('auditoria_retenciones_cxp/f_control');
f_control.nombre       = 'f_control';
f_control.funcion      = t_f_control;
f_control.padre        = 'contenedor_cuadro';
f_control.tipo         = 'formulario';

f_insertar              = new formulario2('auditoria_retenciones_cxp/f_insertar');
f_insertar.nombre       = 'f_insertar';
f_insertar.funcion      = t_f_insertar;
f_insertar.padre        = 'contenedor_cuadro';
f_insertar.tipo         = 'formulario';

m_documentos              = new lista('auditoria_retenciones_cxp/m_documentos');
m_documentos.nombre       = 'm_documentos';
m_documentos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_documentos.usaCookie    = false;
m_documentos.funcion      = t_m_documentos;
m_documentos.padre        = "GRUPO1";
m_documentos.buscador     = true;
m_documentos.onSelect     = Actualiza_detalle;
m_documentos.filtro       = 'TIPO';
m_documentos.xfiltro      = 'COM';

d_documentos              = new lista('auditoria_retenciones_cxp/d_documentos');
d_documentos.nombre       = 'd_documentos';
d_documentos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_documentos.usaCookie    = false;
d_documentos.funcion      = t_d_documentos;
d_documentos.padre        = "GRUPO2";
d_documentos.buscador     = false;
d_documentos.onFocus      = d_documentosFocus;
d_documentos.filtro       = 'ID_PADRE;TIPO';

d_pagos              = new lista('auditoria_retenciones_cxp/d_pagos');
d_pagos.nombre       = 'd_pagos';
d_pagos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_pagos.funcion      = t_d_pagos;
d_pagos.filtro       = 'IDX;TABLA';
d_pagos.buscador    = true;
d_pagos.x           = 1;
d_pagos.y           = 1;
d_pagos.modal       = true;
d_pagos.botonCerrar = true;
d_pagos.enter       = 1;

f_retenciones          = new formulario2('auditoria_retenciones_cxp/f_retenciones');
f_retenciones.nombre   = 'f_retenciones';
f_retenciones.funcion  = t_f_retenciones;
f_retenciones.padre    = 'contenedor_cuadro';

function t_d_pagos(objeto, tecla, xml, e)
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
			d_pagos.ocultar();
			m_documentos.setFocus();
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

function d_documentosFocus()
{
	mostrarLeyenda(1);
}

function Actualiza_detalle(objeto, xml)
{
    var registro = XML2Array(xml);
    if (!registro[0]||!registro[0]['ID_D_CXCCXP'])
    {
		d_documentos.limpiar();
		xid_cxp =null;
		return;
    }
		xid_cxp   = registro[0]['ID_D_CXCCXP'];
		xdocumento   = registro[0]['DOCUMENTO'];
		xdoc_final = registro[0]['ID_M_DOC_FINAL'];
		xmonto    = registro[0]['MONTO'];
		xsaldo = registro[0]['SALDO'];
		xproveedor = registro[0]['ID_M_PROVEEDORES'];
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
	d_documentos.xfiltro = xid_cxp+';REM';
	d_documentos.buscar('*');
}

function t_m_documentos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			mostrar_formulario(f_insertar);
			f_insertar.limpiar();
			f_insertar.setValue('FECHA_DOCUMENTO',fecha);
		break;

		case _enter: // Enter
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_CXCCXP');
			if(!registro)return;
			d_documentos.setFocus();
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
			var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
			if(!registro)return;
			xid_m_documentos = registro [0]['ID_M_DOCUMENTOS'];
			d_pagos.xfiltro = xid_m_documentos+';M_DOCUMENTOS';
			d_pagos.limpiar();
			d_pagos.buscar();
			d_pagos.mostrar();
			d_pagos.setFocus();
		break;

		case _f3:
			cancelaTecla(evt);
		break;
		
		case _f4:
			cancelaTecla(evt);
			var registro = XML2Array(xml);
			xdocumento = registro[0]['DOCUMENTO'];
			var xtipo = registro[0]['TIPO'];
			if (!xdocumento) return false;
			if(xtipo =='COM' || xtipo =='NCP' || xtipo =='ACP') VerPreview(xdocumento,xtipo);
		break;

		case _f5:
			cancelaTecla(evt);
		break;

		case _f6:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
			if(!registro)return;
			f_control.buscar(registro[0]['ID_M_DOCUMENTOS']);
			mostrar_formulario(f_control);
		break;

		case _f8:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
			if(!registro)return;
			fs.armar("auditoria_retenciones_cxp/fs_actualizar");
			fs.xbusca=registro[0]['ID_M_DOCUMENTOS'];
			fs.setValue('CONDICION_ACTUALIZACION','*');
			var registro = fs.submit();
		break;

		case _f7:
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}
function t_d_documentos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			xpadre = xid_cxp;
			f_retenciones.limpiar();
			f_retenciones.setValue("ID_M_PROVEEDORES",xproveedor);
			// f_retenciones.setValue("NOMBRE_VENDEDOR",xnombre_concepto);
			//f_retenciones.setValue("DOCUMENTO",xdocumento);
			//f_retenciones.setValue("REFERENCIA",xdoc_final);
			f_retenciones.setValue("CAMPO3",xpadre);
			f_retenciones.setValue("XNETO",xmonto);
			f_retenciones.setValue("ID_PADRE",xpadre);
			f_retenciones.setValue('FECHA_DOCUMENTO',fecha);
			mostrar_formulario(f_retenciones);
		break;

		case _enter: // Enter
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_DOCUMENTOS');
			if(!registro)return;
			f_centro_costos.buscar(registro[0]['ID_D_DOCUMENTOS']);
			mostrar_formulario(f_centro_costos);
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			m_documentos.setFocus();
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

function t_f_retenciones(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_retenciones,d_documentos);
		break;

		case _f12:
			cancelaTecla(evt);
			var accion = f_retenciones.getValue('xbusca');

			var registro = f_retenciones.submit();
			if(!registro)return;
			if(accion!='-1')
			{
				d_documentos.actualizar(accion);
			}
			else d_documentos.buscar();
			ocultar_formulario(f_retenciones,d_documentos);
		break;
	}
}

function t_f_control(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc: //Escape
			cancelaTecla(evt);
			ocultar_formulario(f_control,m_documentos);
		break;

		case _f12: // F12 Guardar_item
			cancelaTecla(evt);
			var xid = f_control.getValue('xbusca');
			{
				var registro = f_control.submit();
				if(!registro)return;
				ocultar_formulario(f_control,m_documentos);
				m_documentos.actualizar();
			}
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
			ocultar_formulario(f_insertar,m_documentos);
		break;

		case _f12:
			cancelaTecla(evt);
			var accion = f_insertar.getValue('xbusca');

			var registro = f_insertar.submit();
			if(!registro)return;
			if(accion!='-1')
			{
				m_documentos.actualizar(accion);
			}
			else m_documentos.buscar();
			ocultar_formulario(f_insertar,m_documentos);
		break;
	}
}


function VerPreview(doc,tipo)
{
	if(tipo !='ABO' && tipo !='ANP')
	{
		if (tipo =='NCP' || tipo =='ACP') tipo='NDC_GRAF';
		if (tipo =='REM') tipo='REM_MEDIA_GRA';
		var xsufijo = tipo.toLowerCase();
		impresora.origin = 'reportes/r_documento_'+xsufijo;
		impresora.setParam('ID_M_DOCUMENTOS',doc);
	}
	impresora.showDialog=1;
	impresora.preview();
	if(GridAct.nombre=='m_documentos') m_documentos.setFocus();
	else m_documentos.setFocus();
	return;
}

function imprimir(tipo)
{
        switch(tipo)
    {
     case 1:
	    var xml      = m_documentos.elementoXml();
	    var registro = valida_xml(xml,'ID_M_DOCUMENTOS');

	    impresora.origin = 'reportes/r_documento_fact_carta_gra';
	    impresora.setParam('ID_M_DOCUMENTOS', registro[0]['ID_M_DOCUMENTOS']);
	    impresora.showDialog = true;
	    impresora.preview();
		m_documentos.setFocus();
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

		case 'f_insertar':
			t_f_insertar('',_f12);
		break;

		case 'f_retenciones':
			t_f_retenciones('',_f12);
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

		case 'f_insertar':
			t_f_insertar('',_esc);
		break;

		case 'f_retenciones':
			t_f_retenciones('',_esc);
		break;
    }
}

function iniciar()
{
	contenedor.inicializa(false);
	centrarObj(contenedor.contenedor);

	f_control.inicializa(false);
	f_insertar.inicializa(false);
	f_retenciones.inicializa();

	m_documentos.inicializa(false);
	m_documentos.mostrar();

	d_documentos.inicializa(false);
	d_documentos.mostrar();
	d_pagos.inicializa(false);

	centrarObj(d_pagos.contenedor);
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(M_DOCUMENTOS_ENTER, "click",   function() { t_m_documentos('', _enter,   m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_F4,    "click",   function() { t_m_documentos('', _f4,      m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_ESC,   "click",   function() { t_m_documentos('', _esc,     m_documentos.elementoXml()) } )

	addEvent(D_DOCUMENTOS_INSERT,	"click",   function() { t_d_documentos('', _insert,    d_documentos.elementoXml()) } )
	addEvent(D_DOCUMENTOS_ESC,   	"click",   function() { t_d_documentos('', _esc,    d_documentos.elementoXml()) } )

	addEvent(d_pagos_ESC,        	"click",   function() { t_d_pagos('', _esc,         d_documentos.elementoXml()) } )

	return true;
}

function inicio()
{
	m_documentos.setFocus();
	mostrarLeyenda(0);
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