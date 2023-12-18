<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');

$ID_M_PROVEEDORES    = getsession('M_USUARIOS_ID_M_PROVEEDORES');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var t		= null;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 1;
contenedor.ancho	   = 1;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal 	   = true;
contenedor.ayuda	   = 1;
contenedor.onClose     = Cerrar_contenedor;

m_anticipos            = new lista('auditoria_anticipo_cxp/m_anticipos');
m_anticipos.nombre     = 'm_anticipos';
m_anticipos.url        = server_path + 'herramientas/genera_xml/genera_xml.php';
m_anticipos.usaCookie  = false;
m_anticipos.funcion    = t_m_anticipos;
m_anticipos.padre      = "GRUPO1";
m_anticipos.asyncload	= true;
m_anticipos.buscador   = true;
m_anticipos.onSelect   = actualizaPie;

d_documentos              = new lista('auditoria_anticipo_cxp/d_documentos');
d_documentos.nombre       = 'd_documentos';
d_documentos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_documentos.usaCookie    = false;
d_documentos.funcion      = t_d_documentos;
d_documentos.padre        = "GRUPO2";
d_documentos.buscador     = false;
d_documentos.onSelect     = d_documentosFocus;
d_documentos.filtro	      = 'ID_M_ANTICIPOS';

f_edicion 			   = new formulario2('auditoria_anticipo_cxp/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

function d_documentosFocus()
{
	if (t) window.clearTimeout(t);
	t = window.setTimeout('mostrarLeyenda(1)',300);
}

function actualizaPie(obj,xml)
{
	dxml = xml;
	if (t) window.clearTimeout(t);
	t = window.setTimeout('act_timer(dxml)',300);
}

function act_timer(xml)
{
	var registro = valida_xml(xml,'ID_M_ANTICIPOS');
	if(!registro)return;
	d_documentos.xfiltro = registro[0]['ID_M_ANTICIPOS'];
	d_documentos.buscar();

	mostrarLeyenda(0);
	actualizaHTML(xml,'PIE');
}

function t_m_anticipos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert: // Insertar
			cancelaTecla(evt);
		break;

		case _enter: // Enter
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_ANTICIPOS');
			if(!registro)return;
			d_documentos.setFocus();
			mostrarLeyenda(1);
		break;

		case _supr:
			cancelaTecla(evt);
			alert('En proceso');
		break;

		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1: // F1
		case _f2: // F2
		case _f3: // F3
		case _f4: // F4
		case _f5: // F5
		case _f6: // F6
		case _f7: // F7
		case _f8: // F8
		case _f9: // F9
		case _f10: // F10
		case _f11: // F10
		case _f12: // F10
			cancelaTecla(evt);
		break;
	}
}

function m_anticipos_supr(id)
{
       var url   = server_path + "herramientas/utiles/actualizar_registro.php";
       var param = "origen=auditoria_anticipo_cxp/m_anticipos&procedimiento=ELIMINAR_ITEM&ID_D_CXCCXP=" + id;
       var x     = enviar(url,param,'POST');
       m_anticipos.buscar('*');
}

function t_d_documentos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert: // Insertar
			cancelaTecla(evt);
			mostrar_formulario(f_edicion);
		break;

		case _enter: // Enter
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_TEST');
			if(!registro)return;
			f_edicion.buscar(registro[0]['ID_D_TEST']);
			mostrar_formulario(f_edicion);
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			m_anticipos.setFocus();
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

function t_f_edicion(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc: //Escape
			cancelaTecla(evt);
			ocultar_formulario(f_edicion,m_anticipos);
		break;

		case _f12: // F12 Guardar_item
			cancelaTecla(evt);
			var registro = f_edicion.submit();
			if(!registro)return;
			ocultar_formulario(f_edicion,m_anticipos);
			m_anticipos.buscar();
		break;
	}
}

function actualizaDetalles()
{
	actualizaHTML(m_proveedores.elementoXml(),'ENCABEZADO');
}

function Salir()
{
	parent.proceso.location.href = server_path + 'main/inicio.php';
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

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
			t_f_edicion('',_f12);
		break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
			t_f_edicion('',_esc);
		break;
    }
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_anticipos.inicializa(false);
	m_anticipos.mostrar();

	d_documentos.inicializa(false);
	d_documentos.mostrar();

	f_edicion.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(GRID_ENTER, 	"click",   function() { t_m_anticipos('', _enter, m_anticipos.elementoXml()) } )        //enter
	addEvent(GRID_SUPR, 	"click",   function() { t_m_anticipos('', _supr, 	m_anticipos.elementoXml()) } )        //supr
	addEvent(GRID_ESC, 	"click",   function() { t_m_anticipos('', _esc, 	m_anticipos.elementoXml()) } )        //esc

	addEvent(DETA_ESC, 	"click",   function() { t_d_documentos('', _esc, 	d_documentos.elementoXml()) } )        //esc

	return true;
}

function inicio()
{
	m_anticipos.mostrar();
	m_anticipos.setFocus();
    m_anticipos.buscar();
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