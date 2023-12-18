<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha    = date("d/m/Y");
$ventana   = getvar('ventana','modulo');
$id        = getvar('id');

$id_m_usuario  = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini        = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr valign="top">
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var xID_M_USUARIO = null;
var xID_M_TEST    = null ;
var fecha         = '{$xfecha}';
var dxml   		  = null;
var t             = null;
//*************************************//

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 100;
contenedor.alto        = 100;
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

m_test              = new lista('modulo_base_2_grid/m_test');
m_test.nombre       = 'm_test';
m_test.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_test.usaCookie    = false;
m_test.funcion      = t_m_test;
m_test.padre        = "GRUPO1";
m_test.asyncload    = true;
m_test.buscador     = true;
//m_test.onSelect     = actualizaPie;
m_test.onFocus      = m_testFocus;
m_test.filtro       = 'ID_M_USUARIO';

d_test            = new lista('modulo_base_2_grid/d_test');
d_test.nombre     = 'd_test';
d_test.url        = server_path + 'herramientas/genera_xml/genera_xml.php';
d_test.usaCookie  = false;
d_test.funcion    = t_d_test;
d_test.padre      = "GRUPO2";
d_test.buscador   = true;
d_test.onFocus    = d_testFocus;
d_test.filtro     = 'ID_M_TEST';

m_usuarios            = new lista("maestros/m_usuarios")
m_usuarios.nombre     = "m_usuarios";
m_usuarios.url        = server_path + "herramientas/genera_xml/genera_xml.php";
m_usuarios.funcion    = t_m_usuarios;
m_usuarios.buscador   = true;
m_usuarios.modal      = true;
m_usuarios.botonCerrar= true;
m_usuarios.enter      = 1;

f_m_test 			   = new formulario2('modulo_base_2_grid/f_m_test');
f_m_test.nombre       = 'f_m_test';
f_m_test.funcion      = t_f_m_test;
f_m_test.padre        = 'contenedor_cuadro';
f_m_test.tipo         = 'formulario';

f_d_test 			  = new formulario2('modulo_base_2_grid/f_d_test');
f_d_test.nombre       = 'f_d_test';
f_d_test.funcion      = t_f_d_test;
f_d_test.padre        = 'contenedor_cuadro';
f_d_test.tipo         = 'formulario';

var resp = iniciar();

if(!resp)
{
	Salir();
}
else
{
	inicio();
}

function t_m_usuarios(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_USUARIO');
			if(!registro)return;
			actualizaHTML(xml,'ENCABEZADO');
			m_usuarios.ocultar();
			xID_M_USUARIO = registro[0]['ID_M_USUARIO'];
			m_test.xfiltro = xID_M_USUARIO;
			m_test.buscar();
			m_test.setFocus();
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			if(xID_M_USUARIO==null)
			{
				Salir();
				return;
			}
			m_usuarios.ocultar();
			m_test.setFocus();
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


function t_m_test(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			mostrar_formulario(f_m_test);
			f_m_test.limpiar();
			f_m_test.setValue('FECHA_TEST',fecha);
			f_m_test.setValue('ID_M_USUARIO',xID_M_USUARIO);
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro)return;
			d_test.setFocus();
		break;

		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro) return;

			if(confirm('{$t_eliminar_registro}'))
			{
				var url   = server_path + "herramientas/utiles/actualizar_registro.php";
				var param = "origen=modulo_base_2_grid/m_test&procedimiento=ELIMINAR_ITEM&ID_M_TEST=" + registro[0]['ID_M_TEST'];
				var x     = enviar(url,param,'POST');
			}
			m_test.buscar('*');
		break;

		case _esc:
			cancelaTecla(evt);
			Salir();
		break;

		case _f1:
		case _f2:
			cancelaTecla(evt);
		break;

		case _f3:
			cancelaTecla(evt);
			m_usuarios.mostrar();
			m_usuarios.setFocus();
		break;

		case _f4:
		case _f5:
			cancelaTecla(evt);
		break;

		case _f6:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro)return;
			f_m_test.buscar(registro[0]['ID_M_TEST']);
			mostrar_formulario(f_m_test);
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
function t_d_test(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			f_d_test.limpiar();
			f_d_test.setValue('ID_M_TEST',xID_M_TEST);
			mostrar_formulario(f_d_test);
		break;

	case _enter:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_D_TEST');
		if(!registro)return;
		f_d_test.buscar(registro[0]['ID_D_TEST']);
		mostrar_formulario(f_d_test);
	break;

	case _supr:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_D_TEST');
		if(!registro) return;

		if(confirm('{$t_eliminar_registro}'))
		{
			var url   = server_path + "herramientas/utiles/actualizar_registro.php";
			var param = "origen=modulo_base_2_grid/d_test&procedimiento=ELIMINAR_ITEM&ID_D_TEST=" + registro[0]['ID_D_TEST'];
			var x     = enviar(url,param,'POST');
		}
		d_test.buscar('*');
	break;

	case _esc:
		cancelaTecla(evt);
		m_test.setFocus();
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

function t_f_m_test(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_test,m_test);
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
			cancelaTecla(evt);
		 break;

		case _f12:
			cancelaTecla(evt);
			var registro = f_m_test.submit();
			if(!registro)return;
			ocultar_formulario(f_m_test,m_test);
			m_test.buscar();
		break;
	}
}

function t_f_d_test(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_d_test,d_test);
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
			cancelaTecla(evt);
		break;

		case _f12:
			cancelaTecla(evt);
			var registro = f_d_test.submit();
			if(!registro)return;
			ocultar_formulario(f_d_test,d_test);
			d_test.buscar();
		break;
	}
}

function m_testFocus()
{
       mostrarLeyenda(0)
}

function d_testFocus()
{
	mostrarLeyenda(1)
}

function actualizaDetalles()
{
	actualizaHTML(m_usuarios.elementoXml(),'ENCABEZADO');
}

function actualizar_d_test(obj,xml)
{
	if (t) window.clearTimeout(t);
	t = window.setTimeout('ver_detalles_test()',300);
}

function ver_detalles_test()
{
	var xml = m_test.elementoXml();
	var registro = valida_xml(xml,'ID_M_TEST');
	if(!registro)return;
	xID_M_TEST = registro[0]['ID_M_TEST'];
	d_test.xfiltro = xID_M_TEST;
	d_test.buscar();
	actualizaHTML(xml,'PIE');
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
        case 'f_m_test':
			t_f_m_test('',_f12);
        break;

		case 'f_d_test':
			t_f_d_test('',_f12);
        break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
        case 'f_m_test':
			t_f_m_test('',_esc);
        break;

		case 'f_d_test':
			t_f_d_test('',_esc);
        break;
    }
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	m_test.inicializa(false);
	m_test.mostrar();

	d_test.inicializa(false);
	d_test.mostrar();

	m_usuarios.inicializa(true);
	centrarObj(m_usuarios.contenedor);

	f_m_test.inicializa();
	f_d_test.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(GRID_INS,     "click",   function() { t_m_test('', _insert,m_test.elementoXml()) } );
	addEvent(GRID_ENTER,   "click",   function() { t_m_test('', _enter, m_test.elementoXml()) } );
	addEvent(GRID_F3,      "click",   function() { t_m_test('', _f3,    m_test.elementoXml()) } );
	addEvent(GRID_F6,      "click",   function() { t_m_test('', _f6,    m_test.elementoXml()) } );
	addEvent(GRID_SUPR,    "click",   function() { t_m_test('', _supr,  m_test.elementoXml()) } );
	addEvent(GRID_ESC,     "click",   function() { t_m_test('', _esc,   m_test.elementoXml()) } );

	addEvent(DETA_INS,     "click",   function() { t_d_test('', _insert, d_test.elementoXml()) } );
	addEvent(DETA_ENTER,   "click",   function() { t_d_test('', _enter,  d_test.elementoXml()) } );
	addEvent(DETA_SUPR,    "click",   function() { t_d_test('', _supr,   d_test.elementoXml()) } );
	addEvent(DETA_ESC,     "click",   function() { t_d_test('', _esc,    d_test.elementoXml()) } );

	addEvent(m_usuarios_ENTER, "click",   function() { t_m_usuarios('', _enter, m_usuarios.elementoXml()) } );
	addEvent(m_usuarios_ESC,   "click",   function() { t_m_usuarios('', _esc,   m_usuarios.elementoXml()) } );

	return true;

}

function inicio()
{
	m_usuarios.mostrar();
	m_usuarios.setFocus();
}



</script>

</body>
</html>

EOT;

?>