<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/ini/class/class_ini.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$id_m_usuario = getsession('M_USUARIOS_ID_M_USUARIO');


$fecha           = date("d/m/Y");

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

m_proyectos              = new lista('proyectos/m_proyectos');
m_proyectos.nombre       = 'm_proyectos';
m_proyectos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
m_proyectos.usaCookie    = false;
m_proyectos.funcion      = t_m_proyectos;
m_proyectos.padre        = "GRUPO1";
m_proyectos.buscador     = true;
m_proyectos.onSelect     = actualizaPie;
m_proyectos.filtro       = 'ID_M_USUARIO';

f_m_proyectos              = new formulario2('proyectos/f_m_proyectos');
f_m_proyectos.nombre       = 'f_m_proyectos';
f_m_proyectos.funcion      = t_f_m_proyectos;
f_m_proyectos.padre        = 'contenedor_cuadro';
f_m_proyectos.tipo         = 'formulario';

var resp = iniciar();

if(!resp)
{
	Salir();
}
else
{
	inicio();
}

function t_m_proyectos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
	case _insert:
		cancelaTecla(evt);
		mostrar_formulario(f_m_proyectos);
		f_m_proyectos.limpiar();
    break;

    case _enter:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_PROYECTOS');
		if(!registro)return;
		f_m_proyectos.buscar(registro[0]['ID_M_PROYECTOS']);
		mostrar_formulario(f_m_proyectos);
	break;

    case _supr:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_PROYECTOS');
		if(!registro) return;
		if(confirm('{$t_eliminar_registro}'))
		{
			m_proyectos_supr(registro[0]['ID_M_PROYECTOS']);
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

function t_f_m_proyectos(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_m_proyectos,m_proyectos);
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
			var accion = f_m_proyectos.getValue('xbusca');
			var registro = f_m_proyectos.submit();
			if(!registro)return;
			if(accion!='-1')
			{
			m_proyectos.actualizar(accion);
			}
			else m_proyectos.buscar();
			ocultar_formulario(f_m_proyectos,m_proyectos);
		break;
  }
}

function m_proyectos_supr(id)
{
	var url   = server_path + "herramientas/utiles/actualizar_registro.php";
	var param = "origen=proyectos/m_proyectos&procedimiento=ELIMINAR_ITEM&ID_M_PROYECTOS=" + id;
	var x     = enviar(url,param,'POST');
	m_proyectos.buscar('*');
}

function actualizaPie()
{
	var xml = m_proyectos.elementoXml();
	var registro = valida_xml(xml,'ID_M_PROYECTOS');
	if (!registro)
	{
		limpiarElementos('PIE');
		return;
	}
	xid_proyectos = registro[0]['ID_M_PROYECTOS'];
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
		case 'f_m_proyectos':
			t_f_m_proyectos('',_f12);
		break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_m_proyectos':
			t_f_m_proyectos('',_esc);
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
	console.clear();
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
	m_proyectos.inicializa(false);
	f_m_proyectos.inicializa();

	addEvent(INS,  "click",   function() { t_m_proyectos('', _insert,  m_proyectos.elementoXml()) } );
	addEvent(ENTER,"click",   function() { t_m_proyectos('', _enter,   m_proyectos.elementoXml()) } );
	addEvent(SUPR, "click",   function() { t_m_proyectos('', _supr,    m_proyectos.elementoXml()) } );
	addEvent(ESC,  "click",   function() { t_m_proyectos('', _esc,     m_proyectos.elementoXml()) } );

	return true;
}

function inicio()
{
	m_proyectos.buscar('*');
	m_proyectos.mostrar();
	m_proyectos.setFocus();
}



</script>

</body>
</html>

EOT;

?>