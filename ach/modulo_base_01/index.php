<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$id_m_usuario = getsession('M_USUARIOS_ID_M_USUARIO');


$query = new sql();
$query->sql = "SELECT ID_M_VENDEDORES,ID_M_USUARIO FROM M_VENDEDORES WHERE ID_M_USUARIO='". $id_m_usuario ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_vendedores 	 = $query->Record['ID_M_VENDEDORES'];

$fecha           = date("d/m/Y");

$xid_m_cliente   = getsession("M_CLIENTES_ID_M_CLIENTES");
$xusuario        = getsession("M_USUARIOS_ID_M_USUARIO");


$ventana = getvar('ventana','modulo');
$id = getvar('id');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,tabpane,formulario2,forma,submodal,impresora,camara,popup,forma_simple,clave');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr>
		<td id="GRUPO1" colspan="2"></td>
		<td id="RESUMEN" rowspan="2" class="grid_cuadro_activo" style="padding: 2px; vertical-align: text-top;">
			<div class="grid_encab" style="width: 407px; height: 25px;">Resumen</div>
			<div style="overflow-y: scroll; height:300px;">
				<table border="0">
					<tr>
						<td colspan="2" style="width: 407px; background: #e6e6e6; font-weight: bold; text-align: center; border: solid 1px #e6e6e6; font-size: 10px;">Datos Generales</td>
					<tr>
				</table>
				<table>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 100px;">Campo1:</td>
						<td class="grid_contenido_pie" style="width: 93px;" id="RESUMEN_CAMPO1">&nbsp;</td>
						<td class="grid_rotulo_pie"    style="width: 100px;">Campo2:</td>
						<td class="grid_contenido_pie" style="width: 85px;" id="RESUMEN_CAMPO2">&nbsp;</td>
					</tr>
				</table>
				<hr>
				<table border="0">
					<tr>
						<td colspan="2" style="background: #e6e6e6; font-weight: bold; text-align: center; border: solid 1px #e6e6e6; font-size: 10px;">Datos Específicos</td>
					<tr>
					<tr>
						<td class="grid_rotulo_pie"    style="width: 100px;">Comentarios:</td>
						<td class="grid_contenido_pie" style="width: 295px;" id="RESUMEN_COMENTARIOS">&nbsp;</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>

{$modulo->fin}

<style>

	hr {
		padding: 2px;
		height : 1px;
		color  : #bfbfbf;
	}

</style>


<script type="text/javascript">

var xID_M_USUARIO = null;
var fecha = '{$xfecha}';
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

m_test              = new lista('modulo_base_01/m_test');
m_test.nombre       = 'm_test';
m_test.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_test.usaCookie    = false;
m_test.funcion      = t_m_test;
m_test.padre        = "GRUPO1";
m_test.buscador     = true;
m_test.onSelect     = actualizaPie;
m_test.filtro       = 'ID_M_USUARIO';

m_usuarios             = new lista("maestros/m_usuarios")
m_usuarios.nombre      = "m_usuarios";
m_usuarios.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_usuarios.funcion     = t_m_usuarios;
m_usuarios.buscador    = true;
m_usuarios.modal       = true;
m_usuarios.botonCerrar = true;
m_usuarios.enter       = 1;

f_m_test              = new formulario2('modulo_base_01/f_m_test');
f_m_test.nombre       = 'f_m_test';
f_m_test.funcion      = t_f_m_test;
f_m_test.padre        = 'contenedor_cuadro';
f_m_test.tipo         = 'formulario';

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
		f_m_test.buscar(registro[0]['ID_M_TEST']);
		mostrar_formulario(f_m_test);
	break;

    case _supr:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_TEST');
		if(!registro) return;
		if(confirm('{$t_eliminar_registro}'))
		{
			m_test_supr(registro[0]['ID_M_TEST']);
		}
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
			var accion = f_m_test.getValue('xbusca');
			var registro = f_m_test.submit();
			if(!registro)return;
			if(accion!='-1')
			{
			m_test.actualizar(accion);
			}
			else m_test.buscar();
			ocultar_formulario(f_m_test,m_test);
		break;
  }
}

function m_test_supr(id)
{
	var url   = server_path + "herramientas/utiles/actualizar_registro.php";
	var param = "origen=modulo_base_01/m_test&procedimiento=ELIMINAR_ITEM&ID_M_TEST=" + id;
	var x     = enviar(url,param,'POST');
	m_test.buscar('*');
}

function actualizaPie()
{
	var xml = m_test.elementoXml();
	var registro = valida_xml(xml,'ID_M_TEST');
	if (!registro)
	{
		limpiarElementos('PIE');
		return;
	}
	xid_test = registro[0]['ID_M_TEST'];
	actualizaHTML(xml,'PIE');
	actualizaHTML(xml,'RESUMEN_');

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
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_m_test':
			t_f_m_test('',_esc);
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

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
	m_test.inicializa(false);
	m_usuarios.inicializa(true);
	centrarObj(m_usuarios.contenedor);
	f_m_test.inicializa();

	addEvent(INS,  "click",   function() { t_m_test('', _insert,  m_test.elementoXml()) } );
	addEvent(ENTER,"click",   function() { t_m_test('', _enter,   m_test.elementoXml()) } );
	addEvent(F3,   "click",   function() { t_m_test('', _f3,      m_test.elementoXml()) } );
	addEvent(SUPR, "click",   function() { t_m_test('', _supr,    m_test.elementoXml()) } );
	addEvent(ESC,  "click",   function() { t_m_test('', _esc,     m_test.elementoXml()) } );

	addEvent(m_usuarios_ENTER, "click",   function() { t_m_usuarios('', _enter, m_usuarios.elementoXml()) } );
	addEvent(m_usuarios_ESC,   "click",   function() { t_m_usuarios('', _esc,   m_usuarios.elementoXml()) } );

	return true;
}

function inicio()
{
	m_test.mostrar();
	m_usuarios.mostrar();
	m_usuarios.setFocus();
}



</script>

</body>
</html>

EOT;

?>