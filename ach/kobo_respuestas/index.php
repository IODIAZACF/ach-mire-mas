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

tmp_respuestas              = new lista('kobo_respuestas/tmp_respuestas');
tmp_respuestas.nombre       = 'tmp_respuestas';
tmp_respuestas.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
tmp_respuestas.usaCookie    = false;
tmp_respuestas.funcion      = t_tmp_respuestas;
tmp_respuestas.padre        = "contenido";
tmp_respuestas.buscador     = true;
//tmp_respuestas.onSelect     = actualizaPie;

f_edicion              = new formulario2('kobo_respuestas/f_edicion');
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

function t_tmp_respuestas(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
/* 			mostrar_formulario(f_edicion);
			f_edicion.limpiar();
			f_edicion.setValue('FECHA_TEST',fecha); */
		break;

		case _enter:
			cancelaTecla(evt);
/* 			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro)return;
			f_edicion.buscar(registro[0]['ID_M_TEST']);
			mostrar_formulario(f_edicion); */
		break;

		case _f3:
			cancelaTecla(evt);
/* 			m_principal.mostrar();
			m_principal.setFocus(); */
		break;

		case _supr:
			cancelaTecla(evt);
/* 			var registro = valida_xml(xml,'ID_M_TEST');
			if(!registro) return;
			if(confirm('{$t_eliminar_registro}'))
			{
			tmp_respuestas_supr(registro[0]['ID_M_TEST']);
			} */
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

function tmp_respuestas_supr(id)
{
		var url   = server_path + "herramientas/utiles/actualizar_registro.php";
		var param = "origen=kobo_respuestas/tmp_respuestas&procedimiento=ELIMINAR_ITEM&ID_M_TEST=" + id;
		var x     = enviar(url,param,'POST');
		tmp_respuestas.buscar('*');
}


function t_f_edicion(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion,tmp_respuestas);
		break;

		case _f12:
			cancelaTecla(evt);
			var accion = f_edicion.getValue('xbusca');

			var registro = f_edicion.submit();
			if(!registro)return;
			if(accion!='-1')
			{
			tmp_respuestas.actualizar(accion);
			}
			else tmp_respuestas.buscar();
			ocultar_formulario(f_edicion,tmp_respuestas);
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

	tmp_respuestas.inicializa(false);
	tmp_respuestas.mostrar();

	f_edicion.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	//addEvent(INS,  "click",   function() { t_tmp_respuestas('', _insert,  tmp_respuestas.elementoXml()) } );
	//addEvent(ENTER,"click",   function() { t_tmp_respuestas('', _enter,   tmp_respuestas.elementoXml()) } );
 	//addEvent(F3,   "click",   function() { t_tmp_respuestas('', _f3,      tmp_respuestas.elementoXml()) } ); */
	//addEvent(SUPR, "click",   function() { t_tmp_respuestas('', _supr,    tmp_respuestas.elementoXml()) } );
	addEvent(ESC,  "click",   function() { t_tmp_respuestas('', _esc,     tmp_respuestas.elementoXml()) } );
 /*
	addEvent(m_principal_ENTER, "click",   function() { t_m_principal('', _enter, m_principal.elementoXml()) } );
	addEvent(m_principal_ESC,   "click",   function() { t_m_principal('', _esc,   m_principal.elementoXml()) } );
 */
	return true;
}

function inicio()
{
	tmp_respuestas.buscar('*');
	tmp_respuestas.setFocus();
}

</script>

</body>
</html>

EOT;

?>