<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');
$xfecha         = date("d/m/Y");
$xhora             = date("H:i:s");
$id_m_ordenes_medicas = getvar('id_m_ordenes_medicas','');

//-- leo variables por cgi:
$onClose = 'Salir();';
$retcgi     = getvar('retornando','false');

//-- armar url de retorno
$retornando = '';
if (!$onClose) $onClose    = 'Salir();';
else if (!strpos($onClose,'&retornando=')) $retornando = '&retornando=true';
//--

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

$s_id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');

javascript('auto_tabla,utiles,tabpane,formulario2,forma,submodal,impresora,jquery,popup');

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

var xiddocumentofinal      ='';
var xdocumento             ='';
var xidusuario             ='{$s_id_usuario}';
var xfecha                 ='{$xfecha}';
var xhora                  ='{$xhora}';
var dxml;
var t;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;

m_depositos               = new lista("control_depositos_bancarios/m_depositos")
m_depositos.nombre        = "m_depositos";
m_depositos.padre         = "GRUPO1";
m_depositos.onSelect      = actualizar;
m_depositos.buscador      = true;
m_depositos.url           = server_path + "herramientas/genera_xml/genera_xml.php";
m_depositos.funcion       = t_m_depositos;
m_depositos.onFocus       = focus_deposito;

d_pagos               = new lista("control_depositos_bancarios/d_pagos")
d_pagos.nombre        = "d_pagos";
d_pagos.padre         = "GRUPO2";
d_pagos.url           = server_path + "herramientas/genera_xml/genera_xml.php";
d_pagos.filtro        ='ID_M_DOCUMENTOS';
d_pagos.funcion       = t_d_pagos;
d_pagos.onFocus       = focus_d_pagos;

f_cierre              = new formulario2('control_depositos_bancarios/f_cierre');
f_cierre.nombre       = 'f_cierre';
f_cierre.funcion      = t_f_cierre;
f_cierre.padre        = 'contenedor_cuadro';
f_cierre.tipo         = 'formulario';
f_cierre.onClose      = function (){m_depositos.setFocus();}

function focus_deposito()
{
	mostrarLeyenda(0);
}
function focus_d_pagos(obj)
{
    if(GridAct.nombre==obj.nombre) mostrarLeyenda(1);
}

function actualizar(obj,xml)
{
	if (t) window.clearTimeout(t);
	dxml = xml;
	t = window.setTimeout('act_timer(dxml)',300);
}

function act_timer(xml)
{
    var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
    if(!registro)
    {
        d_pagos.limpiar();
        return;
    }

    xdocumento     = registro[0]['ID_M_DOCUMENTOS'];
	d_pagos.xfiltro= xdocumento;
	d_pagos.buscar();
}

function actualizar_detalle(obj,xml)
{
   // CambiaLeyenda(2);
}

function t_f_cierre(objeto, tecla, xml)
{
 var evt = window.event || e;
    switch (tecla)
    {
		case _f12: // F12 Guardar
			cancelaTecla(evt);
			var registro = f_cierre.submit();
			if (!registro)return;
			ocultar_formulario(f_cierre);
			m_depositos.actualizar(registro[0]['ID_M_DOCUMENTOS']);
			m_depositos.setFocus();
		break;

		case _esc: // ESC Cerrar Formulario
			ocultar_formulario(f_cierre);
			m_depositos.setFocus();
		break;
    }
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_cierre':
			t_f_cierre('',_f12);
		break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_cierre':
			t_f_cierre('',_esc);
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
function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
    m_depositos.setFocus();
}

function  t_m_depositos(objeto, tecla, xml, e)
{
  var evt = window.event || e;

  switch (tecla)
  {

    case _supr:// enter
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro)
        {
			return false;
        }
        xdocumetos = registro[0]['ID_M_DOCUMENTOS'];
        var tabla  = 'M_DOCUMENTOS';
        var campos = new Array;
        var busca  = 'ID_M_DOCUMENTOS';
        var xbusca = registro[0]['ID_M_DOCUMENTOS'];
            var xestatus=registro[0]['ESTATUS'];
        if(xestatus=='C'  )
        {
			var eliminar = confirm('{$t_eliminar_registro}');
			if(eliminar)
			{
				campos['ESTATUS'] = new Campo('X');
				var xml = actualizar_registro(tabla, campos, busca, xbusca);
				var registro     = XML2Array(xml);
			}
			else
			{
				return;
			}
        }
        m_depositos.actualizar(xdocumetos);

        break;

    case _enter:// enter
		cancelaTecla(evt);
	break
    case _esc:// enter
        cancelaTecla(evt);
                Salir();
        break;
    case _f5:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro) return false;
        xdocumetos = registro[0]['ID_M_DOCUMENTOS'];
        var tabla  = 'M_DOCUMENTOS';
        var campos = new Array;
        var busca  = 'ID_M_DOCUMENTOS';
        var xbusca = registro[0]['ID_M_DOCUMENTOS'];
            var xestatus=registro[0]['ESTATUS'];
        if(xestatus=='T'  )
        {
			campos['ESTATUS'] = new Campo('C');
			var xml = actualizar_registro(tabla, campos, busca, xbusca);
			var registro     = XML2Array(xml);
		}
        m_depositos.actualizar(xdocumetos);
        break;

    case _f6:
        cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
			if(!registro) return false;
			xdocumetos = registro[0]['ID_M_DOCUMENTOS'];
			var tabla  = 'M_DOCUMENTOS';
			var campos = new Array;
			var busca  = 'ID_M_DOCUMENTOS';
			var xbusca = registro[0]['ID_M_DOCUMENTOS'];
			var xestatus=registro[0]['ESTATUS'];
			if(xestatus=='C' || xestatus=='D')
			{
				campos['ESTATUS'] = new Campo('T');
					var xml = actualizar_registro(tabla, campos, busca, xbusca);
				var registro     = XML2Array(xml);
			}
			m_depositos.actualizar(xdocumetos);
        break;


    case _f7:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro) return false;
        xdocumetos = registro[0]['ID_M_DOCUMENTOS'];
        var tabla  = 'M_DOCUMENTOS';
        var campos = new Array;
        var busca  = 'ID_M_DOCUMENTOS';
        var xbusca = registro[0]['ID_M_DOCUMENTOS'];
		var xestatus=registro[0]['ESTATUS'];
        if(xestatus=='T'||xestatus=='O')
        {
            f_cierre.limpiar();
            f_cierre.buscar(xbusca);
            f_cierre.setValue('ESTATUS','D');
            mostrar_formulario(f_cierre);
        }
        break;



    case _f8:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro) return false;
        xdocumetos = registro[0]['ID_M_DOCUMENTOS'];
        var tabla  = 'M_DOCUMENTOS';
        var campos = new Array;
        var busca  = 'ID_M_DOCUMENTOS';
        var xbusca = registro[0]['ID_M_DOCUMENTOS'];
		var xestatus=registro[0]['ESTATUS'];
        if(xestatus=='D')
        {
			campos['ESTATUS'] = new Campo('O');
			var xml = actualizar_registro(tabla, campos, busca, xbusca);
			var registro     = XML2Array(xml);
		}
        m_depositos.actualizar(xdocumetos);
	break;

  }
}
function  t_d_pagos(objeto, tecla, xml, e)
{
	var evt = window.event || e;

	switch (tecla)
	{
		case _enter:// enter
			cancelaTecla(evt);
		break;
		case _esc:
			cancelaTecla(evt);
			m_depositos.setFocus();
			mostrarLeyenda(0);
		break;
	}
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function irMenu()
{
	if (parent.menu) parent.menu.reset();
}

function Iniciar()
{

    contenedor.inicializa();
    centrarObj(contenedor.contenedor);

    m_depositos.inicializa(false);
    m_depositos.mostrar();

    d_pagos.inicializa(false);
    d_pagos.mostrar();
    m_depositos.setFocus();

    f_cierre.inicializa(false);

	addEvent(M_DEPOSITO_F5,  "click",   function() { t_m_depositos       ('', _f5, m_depositos.elementoXml())} );
	addEvent(M_DEPOSITO_F6,  "click",   function() {  t_m_depositos      ('', _f6, m_depositos.elementoXml())} );
	addEvent(M_DEPOSITO_F7,  "click",   function() {  t_m_depositos      ('', _f7, m_depositos.elementoXml())} );
	addEvent(M_DEPOSITO_F8,  "click",   function() {  t_m_depositos      ('', _f8, m_depositos.elementoXml())} );
	addEvent(M_DEPOSITO_ESC, "click",   function() {  t_m_depositos      ('', _esc, m_depositos.elementoXml())} );
	addEvent(D_PAGOS_ESC,    "click",   function() {  t_m_depositos      ('', _esc, d_pagos.elementoXml())} );

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	mostrarLeyenda(0);
	return;
}

var res =Iniciar();
</script>

EOT;

?>