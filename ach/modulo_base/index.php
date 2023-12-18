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

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="contenido"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var xvalida = null;
var fecha = '{$xfecha}';
//*************************************//

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.ayuda       = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
contenedor.onClose     = Cerrar_contenedor;

grid_base              = new lista('modulo_base/grid_base');
grid_base.nombre       = 'grid_base';
grid_base.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
grid_base.usaCookie    = false;
grid_base.funcion      = tecla_doc;
grid_base.padre        = "contenido";
grid_base.buscador     = true;
grid_base.onSelect     = actualizaPie;
grid_base.filtro       = 'ID_M_USUARIOS';

m_principal             = new lista("maestros/m_usuarios")
m_principal.nombre      = "m_principal";
m_principal.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_principal.funcion     = t_m_principal;
m_principal.buscador    = true;
m_principal.modal       = true;
m_principal.botonCerrar = true;
m_principal.enter       = 1;

f_edicion              = new formulario2('modulo_base/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo         = 'formulario';

var resp = iniciar();

if(!resp)
{
	Salir();
}
else
{
	inicio();
}

function t_m_principal (objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _enter:
			var registro = valida_xml(xml,'ID_M_USUARIO');
			if(!registro)return;
			m_principal.ocultar();
			xvalida = registro[0]['ID_M_USUARIO'];
			grid_base.xfiltro = xvalida;
			actualizaDetalles();
			grid_base.buscar();
			grid_base.setFocus();
		break;

		case _esc:
			cancelaTecla();
			if(xvalida==null)
			{
			Salir();
			return;
			}
			m_principal.ocultar();
			grid_base.setFocus();
		break;
  }
}

function tecla_doc(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			mostrar_formulario(f_edicion);
			f_edicion.limpiar();
			f_edicion.setValue('FECHA_TEST',fecha);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro)return;
			f_edicion.buscar(registro[0]['ID_M_TEST']);
			mostrar_formulario(f_edicion);
		break;

		case _f3:
			cancelaTecla(evt);
			m_principal.mostrar();
			m_principal.setFocus();
		break;

		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro) return;
			if(confirm('{$t_eliminar_registro}'))
			{
			grid_base_supr(registro[0]['ID_M_TEST']);
			}
		break;

		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1:
		case _f2:
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

function grid_base_supr(id)
{
		var url   = server_path + "herramientas/utiles/actualizar_registro.php";
		var param = "origen=modulo_base/grid_base&procedimiento=ELIMINAR_ITEM&ID_M_TEST=" + id;
		var x     = enviar(url,param,'POST');
		grid_base.buscar('*');
}


function t_f_edicion(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion,grid_base);
		break;

		case _f12:
			cancelaTecla(evt);
			var accion = f_edicion.getValue('xbusca');

			var registro = f_edicion.submit();
			if(!registro)return;
			if(accion!='-1')
			{
			grid_base.actualizar(accion);
			}
			else grid_base.buscar();
			ocultar_formulario(f_edicion,grid_base);
		break;
	}
}

function actualizaDetalles()
{
	actualizaHTML(m_principal.elementoXml(),'ENCABEZADO');
}
function actualizaPie()
{
	var xml = grid_base.elementoXml();
	var registro = valida_xml(xml,'ID_M_TEST');
	if (!registro)
	{
		limpiarElementos('PIE');
		return;
	}
	xid_test = registro[0]['ID_M_TEST'];
	actualizaHTML(xml,'PIE');
}
/*function actualizaPie()
{
	actualizaHTML(grid_base.elementoXml(),'PIE');
}
*/
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

	grid_base.inicializa(false);
	grid_base.mostrar();

	m_principal.inicializa(true);
	centrarObj(m_principal.contenedor);

	f_edicion.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(INS,  "click",   function() { tecla_doc('', _insert,  grid_base.elementoXml()) } );
	addEvent(ENTER,"click",   function() { tecla_doc('', _enter,   grid_base.elementoXml()) } );
	addEvent(F3,   "click",   function() { tecla_doc('', _f3,      grid_base.elementoXml()) } );
	addEvent(SUPR, "click",   function() { tecla_doc('', _supr,    grid_base.elementoXml()) } );
	addEvent(ESC,  "click",   function() { tecla_doc('', _esc,     grid_base.elementoXml()) } );

	addEvent(m_principal_ENTER, "click",   function() { t_m_principal('', _enter, m_principal.elementoXml()) } );
	addEvent(m_principal_ESC,   "click",   function() { t_m_principal('', _esc,   m_principal.elementoXml()) } );

	return true;
}

function inicio()
{
	m_principal.mostrar();
	m_principal.setFocus();
}

</script>

</body>
</html>

EOT;

?>