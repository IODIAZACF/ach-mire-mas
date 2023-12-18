<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha         = date("d/m/Y");
$ventana         = getvar('ventana','modulo');
$id                 = getvar('id');

$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini         = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,forma_simple,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table border="0">
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var t			= null;
var xvalida 	= null;
var fecha       = '{$xfecha}';
var impresora 	= new printer();
var fs          = new forma_simple();
//***********************************************//

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = '    ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.ayuda           = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

m_pagos_recibidos              = new lista('control_pagos_recibidos/m_pagos_recibidos');
m_pagos_recibidos.nombre       = 'm_pagos_recibidos';
m_pagos_recibidos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_pagos_recibidos.usaCookie    = false;
m_pagos_recibidos.funcion      = t_m_pagos_recibidos;
m_pagos_recibidos.padre        = "GRUPO1";
m_pagos_recibidos.buscador     = true;
m_pagos_recibidos.onSelect     = actualizaDetalles;

d_pagos_recibidos              = new lista('control_pagos_recibidos/d_pagos_recibidos');
d_pagos_recibidos.nombre       = 'd_pagos_recibidos';
d_pagos_recibidos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_pagos_recibidos.funcion      = t_d_pagos_recibidos;
d_pagos_recibidos.usaCookie    = false;
d_pagos_recibidos.padre        = "GRUPO2";
d_pagos_recibidos.buscador     = true;
d_pagos_recibidos.onSelect     = d_pagosFocus;
d_pagos_recibidos.filtro       = 'ID_D_PAGOS';

f_cheque_devuelto	   		   = new formulario2('control_pagos_recibidos/f_cheque_devuelto');
f_cheque_devuelto.nombre       = 'f_cheque_devuelto';
f_cheque_devuelto.funcion      = t_f_cheque_devuelto;
f_cheque_devuelto.padre        = 'contenedor_cuadro';
f_cheque_devuelto.tipo         = 'formulario';

function d_pagosFocus()
{
	if (t) window.clearTimeout(t);
	t = window.setTimeout('mostrarLeyenda(1)',300);
}

function t_m_pagos_recibidos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert: // Insertar
			cancelaTecla(evt);
		break;

		case _enter: // Enter
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			d_pagos_recibidos.setFocus();
			mostrarLeyenda(1);
		break;

		case _supr:
			cancelaTecla(evt);
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
		break;

		case _f3:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			var xid_d_pagos = registro[0]['ID_D_PAGOS'];
			f_cheque_devuelto.buscar(xid_d_pagos);
			f_cheque_devuelto.setValue('ESTATUS','DEV');
			mostrar_formulario(f_cheque_devuelto);
		break;

		case _f4:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			var xid_d_pagos = registro[0]['ID_D_PAGOS'];
			fs.armar('control_pagos_recibidos/fs_estatus_pagos');
			fs.xbusca = xid_d_pagos
			fs.setValue('ESTATUS','REP');
			var registro = fs.submit();
			if(!registro)
			{
				alert('{$t_error_general}');
				return;
			}
			m_pagos_recibidos.actualizar(xid_d_pagos);
		break;

		case _f5: // F5
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			var xid_d_pagos = registro[0]['ID_D_PAGOS'];
			fs.armar('control_pagos_recibidos/fs_estatus_pagos');
			fs.xbusca = xid_d_pagos;
			fs.setValue('ESTATUS','REC');
			var registro = fs.submit();
			if(!registro)
			{
				alert('{$t_error_general}');
				return;
			}
			m_pagos_recibidos.actualizar(xid_d_pagos);
		break;

		case _f6: // F6
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			var xid_d_pagos = registro[0]['ID_D_PAGOS'];
			fs.armar('control_pagos_recibidos/fs_estatus_pagos');
			fs.xbusca = xid_d_pagos;
			fs.setValue('ESTATUS','D');
			var registro = fs.submit();
			if(!registro)
			{
				alert('{$t_error_general}');
				return;
			}
			m_pagos_recibidos.actualizar(xid_d_pagos);
		break;

		case _f7: // F7
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro)return;
			var xid_d_pagos = registro[0]['ID_D_PAGOS'];
			fs.armar('control_pagos_recibidos/fs_estatus_pagos');
			fs.xbusca = xid_d_pagos;
			fs.setValue('ESTATUS','CNF');
			var registro = fs.submit();
			if(!registro)
			{
				alert('{$t_error_general}');
				return;
			}
			m_pagos_recibidos.actualizar(xid_d_pagos);
		break;

		case _f7: // F7
			cancelaTecla(evt);
		break;

		case _f8: // F8
			cancelaTecla(evt);
		break;

		case _f9: // F9
			cancelaTecla(evt);
		break;

		case _f10: // F10
			cancelaTecla(evt);
		break;

		case _f11: // F10
			cancelaTecla(evt);
		break;

		case _f12: // F10
			cancelaTecla(evt);
		break;
	}
}

function t_d_pagos_recibidos(objeto, tecla, xml, e)
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
		m_pagos_recibidos.setFocus();
		mostrarLeyenda(0);
	break;

	case _f4: // F4
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_D_PAGOS');
		var xanticipo=registro[0]['ID_M_ANTICIPOS'];
		//if(!registro)return;
		//alert(registro[0]['TIPO']);
		if(registro[0]['TIPO']=='FAC' && registro[0]['TABLA']=='M_DOCUMENTOS')
		{
			Imprimir(registro[0]['REFERENCIA']);
		}
		else if(registro[0]['TIPO']=='FAC' && registro[0]['TABLA']=='X_M_DOCUMENTOS')
		{
			Imprimir(registro[0]['REFERENCIA'],1);
		}
		else
		{
			Imprimir_anticipo(xanticipo);
		}
		d_pagos_recibidos.setFocus();
	break;

	case _f1:
	case _f2:
	case _f3:
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

function t_f_cheque_devuelto(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc: 
		cancelaTecla(evt);
		ocultar_formulario(f_cheque_devuelto,m_pagos_recibidos);
	break;

	case _f12:
		cancelaTecla(evt);
		var accion = f_cheque_devuelto.getValue('xbusca');
		var registro = f_cheque_devuelto.submit();
		m_pagos_recibidos.actualizar(accion);
		ocultar_formulario(f_cheque_devuelto);
	break;
	}
}

function Imprimir(reg,estatus)
{
	if(!estatus)
	{
		impresora.origin="reportes/r_documento_fact_carta_gra";
		impresora.setParam('ID_M_DOCUMENTOS',reg);
	}
	else
	{
		impresora.origin="reportes/r_documento_fact_prev";
		impresora.setParam('ID_X_M_DOCUMENTOS',reg);
	}
	impresora.preview();
}

function Imprimir_anticipo(anticipo)
{
    impresora.origin = 'reportes/r_anticipo';
    impresora.setParam('ID_M_ANTICIPOS',anticipo);
    impresora.setParam('letras',1);
    impresora.showDialog = true;
    impresora.preview();
}

function actualizaDetalles(obj,xml)
{
    if (t) window.clearTimeout(t);
    t = window.setTimeout('act_timer();',500);
}

function act_timer()
{
    var registro = valida_xml(m_pagos_recibidos.elementoXml(),'ID_D_PAGOS');
    if(!registro)
    {
        d_pagos_recibidos.limpiar();
        return;
    }

    d_pagos_recibidos.xfiltro = registro[0]['ID_D_PAGOS'];
    d_pagos_recibidos.buscar();
    mostrarLeyenda(0);
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
		case 'f_cheque_devuelto':
			t_f_cheque_devuelto('',_f12);
		break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_cheque_devuelto':
			t_f_cheque_devuelto('',_esc);
		break;
    }
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_pagos_recibidos.inicializa(false);
	m_pagos_recibidos.mostrar();

	d_pagos_recibidos.inicializa(false);
	d_pagos_recibidos.mostrar();

	f_cheque_devuelto.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(ESC,         "click",    function() {  t_m_pagos_recibidos   ('', _esc,  m_pagos_recibidos.elementoXml())} );
	addEvent(ENTER,       "click",    function() {  t_m_pagos_recibidos   ('', _enter,m_pagos_recibidos.elementoXml())} );
	addEvent(F3,          "click",    function() {  t_m_pagos_recibidos   ('', _f3,   m_pagos_recibidos.elementoXml())} );
	addEvent(F4,          "click",    function() {  t_m_pagos_recibidos   ('', _f4,   m_pagos_recibidos.elementoXml())} );
	addEvent(F5,          "click",    function() {  t_m_pagos_recibidos   ('', _f5,   m_pagos_recibidos.elementoXml())} );
	addEvent(F6,          "click",    function() {  t_m_pagos_recibidos   ('', _f6,   m_pagos_recibidos.elementoXml())} );
	addEvent(F7,          "click",    function() {  t_m_pagos_recibidos   ('', _f7,   m_pagos_recibidos.elementoXml())} );

	addEvent(DOC_F4,      "click",    function() {  t_d_pagos_recibidos   ('', _f4, d_pagos_recibidos.elementoXml())} );
	addEvent(DOC_ESC,     "click",    function() {  t_d_pagos_recibidos   ('', _esc,d_pagos_recibidos.elementoXml()) } );

	return true;

}

function inicio()
{
        m_pagos_recibidos.setFocus();
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