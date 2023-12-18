<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
$xfecha     = date("d/m/Y");

$onClose = 'Salir();';

$xid_m_usuarios = getsession('M_USUARIOS_ID_M_USUARIO');
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario2,forma,tabpane,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table border="0">
	<tr>
		<td id="GRUPO1" ></td>
		<td id="GRUPO2" ></td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">
var t                                  = null;
var f_activo                 = null;
var xestatus_cheque = null;
var xtotal                        = null;
var xid_pagos                = null;
var xmonto                        = null;
var xid_m_usuarios  = '{$xid_m_usuarios}';
var xfecha                        = '{$xfecha}';

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = '   ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal            = false;

m_cheques_manuales                 = new lista("pagos_manuales/m_cheques_manuales")
m_cheques_manuales.nombre               = "m_cheques_manuales";
m_cheques_manuales.padre                = "GRUPO1";
m_cheques_manuales.buscador        = true;
m_cheques_manuales.url             = server_path + "herramientas/genera_xml/genera_xml.php";
m_cheques_manuales.funcion              = t_m_cheques_manuales;
m_cheques_manuales.onSelect        = actualizar_detalle;
m_cheques_manuales.onFocus         = m_cheques_focus;

d_cheques                 = new lista("pagos_manuales/d_cheques")
d_cheques.nombre          = "d_cheques";
d_cheques.padre           = "GRUPO2";
d_cheques.url             = server_path + "herramientas/genera_xml/genera_xml.php";
d_cheques.funcion         = t_d_cheques;
d_cheques.buscador        = false;
d_cheques.noOcultarCombos = true;
d_cheques.filtro          = 'ID_D_PAGOS';
//d_cheques.onFocus         = d_cheques_focus;
d_cheques.onSelect        = actualizar_pie;

f_cheques_manuales                       = new formulario2('pagos_manuales/f_cheques_manuales');
f_cheques_manuales.nombre       = 'f_cheques_manuales';
f_cheques_manuales.padre        = 'contenedor_cuadro';
f_cheques_manuales.funcion      = t_f_cheques_manuales;

f_cheques              = new formulario2('pagos_manuales/f_cheques');
f_cheques.nombre       = 'f_cheques';
f_cheques.padre        = 'contenedor_cuadro';
f_cheques.funcion      = t_f_cheques;

f_pagos_conceptos                       = new formulario2('pagos_manuales/f_pagos_conceptos');
f_pagos_conceptos.nombre        = 'f_pagos_conceptos';
f_pagos_conceptos.padre         = 'contenedor_cuadro';
f_pagos_conceptos.funcion       = t_f_pagos_conceptos;

var impresora     = new printer();

var resp = iniciar();
if(resp)
{
        inicio();
}else
{
        Salir();
}

function m_cheques_focus()
{
        mostrarLeyenda(1);
}

function d_cheques_focus(xml)
{
        mostrarLeyenda(2);
}

function actualizar_detalle (obj,xml)
{
    if (t) window.clearTimeout(t);
          dxml = xml;
          t = window.setTimeout('act_timer(dxml)',500);
}
function act_timer(xml)
{
    var registro = XML2Array(xml);
    if(!registro[0] || !registro[0]['ID_D_PAGOS'])return;

    xid_pagos         = registro[0]['ID_D_PAGOS'];
    xdebitos          = unformat(registro[0]['DEBITOS']);
    d_cheques.xfiltro = xid_pagos;
    d_cheques.buscar('*');
    return;
}

function actualizar_pie()
{
    var url   = server_path + "herramientas/genera_xml/genera_xml.php";
    var param = "origen=pagos_manuales/d_cheques&procedimiento=DETALLE&ID_D_PAGOS=" + xid_pagos;
    var x     = enviar(url,param,'POST');
    var registro = valida_xml(x,'CREDITOS');
    xmonto = unformat(registro[0]['CREDITOS']);
    actualizaTag('PIEXTOTAL',xmonto);
   return;
}

function t_m_cheques_manuales(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
		cancelaTecla(evt);
			f_cheques_manuales.limpiar();
			f_cheques_manuales.setValue('FECHA_PAGO',xfecha);
			f_cheques_manuales.setValue('TIPO','MAN');
			f_cheques_manuales.setValue('ID_M_TIPO_PAGOS','0012');
			mostrar_formulario(f_cheques_manuales);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = XML2Array(xml);
			if(!registro[0]['ID_D_PAGOS'])return false;
			mostrarLeyenda(2);
			xid_d_pagos       = registro[0]['ID_D_PAGOS'];
			xestatus_cheque   = registro[0]['ESTATUS'];
			d_cheques.xfiltro = registro[0]['ID_D_PAGOS'];
			xdebitos          = unformat(registro[0]['DEBITOS']);
			d_cheques.setFocus();
		break;
		case _supr:
		cancelaTecla(evt);
		break;
		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f6:
		cancelaTecla(evt);
		break;
		case _f7:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(!registro) return false;
			if(registro[0]['SALDO'] != 0){
				alert('El monto monto del pago no coincide con el detalle del pago');
			return;
			}
			xid_d_pagos = registro[0]['ID_D_PAGOS'];
			imprimir();
		break;
		case _f8:
		case _f9:
		case _f10:
		case _f11:
		case _f12:

		case _esc:
		cancelaTecla(evt);
		Salir();
		break;
	}
}

function t_d_cheques(objeto, tecla, xml,e)
{
    var evt = window.event || e;
    switch (tecla)
    {
       case _insert:
       		cancelaTecla(evt);
            if(xestatus_cheque =='ANU')
            {
				 alert('{$t_proceso_incorrecto}');
				 return false;
            }
            var txml =  valida_xml(m_cheques_manuales.elementoXml(),'FECHA_PAGO');
            if(!txml) return false;
        	var xtotal = xdebitos - xmonto;
		    var xtotal = xtotal.toFixed(2);
            f_pagos_conceptos.limpiar();
            f_pagos_conceptos.setValue('ID_D_PAGOS', xid_d_pagos);
            f_pagos_conceptos.setValue('CREDITOS',xtotal);
            f_pagos_conceptos.setValue('FECHA_REGISTRO',txml[0]['FECHA_PAGO']);
            f_pagos_conceptos.setValue('COMENTARIOS',txml[0]['COMENTARIOS']);
            mostrar_formulario(f_pagos_conceptos);
        break;

       case _enter:
       		cancelaTecla(evt);
            var registro = valida_xml(xml,'ID_D_PAGOS_CONCEPTOS');
			if(!registro) return;
			
            f_pagos_conceptos.buscar(registro[0]['ID_D_PAGOS_CONCEPTOS']);
            mostrar_formulario(f_pagos_conceptos);
        break;

        case _supr:
	        var registro = valida_xml(xml,'ID_D_PAGOS_CONCEPTOS');
			if(!registro) return;
	        var eliminar = confirm('{$t_eliminar_registro}');
	        if(eliminar)
	        {
	            var url = server_path + 'herramientas/utiles/actualizar_registro.php';
	            var params   = "origen=pagos_manuales/d_cheques&procedimiento=ELIMINAR_ITEM&ID_D_PAGOS_CONCEPTOS=" + registro[0]['ID_D_PAGOS_CONCEPTOS'];
	            var x        = enviar(url, params, 'POST');
				m_cheques_manuales.actualizar(registro[0]['ID_D_PAGOS']);
	            d_cheques.buscar('*');
	            d_cheques.setFocus();
	        }
	        setTimeout('d_cheques.setFocus();',1);
	    break;

	    case _esc:
	        cancelaTecla(evt);
	        mostrarLeyenda(1);
	        m_cheques_manuales.setFocus();
        break;
    }
}

function t_f_cheques_manuales(objeto,tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			ocultar_formulario(f_cheques_manuales);
			m_cheques_manuales.setFocus();
		break;

		case _f12: // F12 Guardar
			var xconf=Valida_numero();
			if(!xconf)
			{
			alert("$t_documento_existe");
			return false;
			}
			var registro = f_cheques_manuales.submit();
			if(!registro)return;
			ocultar_formulario(f_cheques_manuales);
			m_cheques_manuales.setFocus();
			m_cheques_manuales.buscar(registro[0]['ID_D_PAGOS']);		
			xid_d_pagos = registro[0]['ID_D_PAGOS'];
			//imprimir();		  
		break;
  }
}

function t_f_cheques(objeto,tecla,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
		case _esc:
			ocultar_formulario(f_cheques);
			m_cheques_manuales.setFocus();
		break;

		case _f12:
			var xconf=Valida_numero();
			if(!xconf)
			{
			alert("$t_documento_existe");
			 return false;
			}

			var registro = f_cheques.submit();
			if(!registro)return;
			ocultar_formulario(f_cheques);
			m_cheques_manuales.setFocus();
			xid_d_pagos = registro[0]['ID_D_PAGOS'];
			//imprimir();		  
		break;

  }
}

function t_f_pagos_conceptos(objeto,tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			ocultar_formulario(f_pagos_conceptos);
			d_cheques.setFocus();
		break;

		case _f12: // F12 Guardar

			var xtotal = xdebitos - xmonto;
			var x_monto_form = unformat(f_pagos_conceptos.getValue('CREDITOS'));
			var registro = f_pagos_conceptos.submit();
			if(!registro)return;
			ocultar_formulario(f_pagos_conceptos);
			m_cheques_manuales.actualizar(xid_pagos);
			d_cheques.setFocus();
			d_cheques.buscar('*');
		break;
	}
}


function cond_centro_costo()
{
	var xid_concepto_comp = f_pagos_conceptos.getValue('ID_M_CONCEPTOS_COMPRAS');
	var url   = server_path + "herramientas/genera_xml/genera_xml.php";
	var param = "tabla=V_M_CONCEPTOS_COMPRAS&campos=CONDICION_CENTRO_COSTOS,ID_M_CENTRO_COSTOS,NOMBRE_CENTRO_COSTOS&filtro=ID_M_CONCEPTOS_COMPRAS&xfiltro="+ xid_concepto_comp;
	var x     = enviar(url,param,'POST');
	var registro = valida_xml(x,'CONDICION_CENTRO_COSTOS');
	if(!registro)
	{
		f_pagos_conceptos.ocultarCampo('ID_M_CENTRO_COSTOS');
	}
	else
	{
		f_pagos_conceptos.mostrarCampo('ID_M_CENTRO_COSTOS');
		f_pagos_conceptos.setValue('ID_M_CENTRO_COSTOS',registro[0]['ID_M_CENTRO_COSTOS']);
		f_pagos_conceptos.setValue('r_ID_M_CENTRO_COSTOS',registro[0]['NOMBRE_CENTRO_COSTOS']);
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
    xformulario.ocultar();
    contenedor.ocultar();
    f_activo=null;
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_pagos_conceptos':
			t_f_pagos_conceptos('',_f12);
		break;
		case 'f_cheques_manuales':
			t_f_cheques_manuales('',_f12);
		break;
		case 'f_cheques':
			t_f_cheques('',_f12);
		break;

    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_pagos_conceptos':
			t_f_pagos_conceptos('',_esc);
		break;
		case 'f_cheques_manuales':
			t_f_cheques_manuales('',_esc);
		break;
		case 'f_cheques':
			t_f_cheques_manuales('',_esc);
		break;
    }
}

function imprimir()
{
    if(xid_d_pagos)
	{
		
		impresora.origin  = 'reportes/r_cheque_manual';
	    impresora.setParam('ID_D_PAGOS'   ,xid_d_pagos);
	    impresora.setParam('ID_M_USUARIOS',xid_m_usuarios);
	    impresora.setParam('letras',1);
	    do
	    {
			impresora.showDialog = true;
            impresora.preview(); 
            //impresora.print();
	    }
	    while (confirm("{$t_reimprimir_documento}")==1);		
        

	    var url = server_path + 'herramientas/utiles/actualizar_registro.php';
	    var params = 'tabla=D_PAGOS&c_ESTATUS_CSS=REA&busca=ID_D_PAGOS&xbusca='+xid_d_pagos;
	    var x = enviar(url, params, 'POST');
	    m_cheques_manuales.actualizar(xid_d_pagos);
	    inicio(0);
  }
}


function Salir()
{
	location.href = server_path + 'main/inicio.php';
}
 

function Valida_numero()
{
    var xcuenta =f_cheques_manuales.getValue('ID_M_CUENTAS_BANCARIAS');
    var xnumero =f_cheques_manuales.getValue('NUMERO');

    var url = server_path + "herramientas/genera_xml/genera_xml.php";
    var param =  "origen=pagos_manuales/m_cheques_manuales&procedimiento=VALIDA_NUMERO&NUMERO=" + xnumero+'&ID_M_CUENTAS_BANCARIAS='+xcuenta;
    var x = enviar(url,param,'POST');
    var registro = XML2Array(x);
    if(!registro[0]) return true
    else return false;
}

function Buscar_proveedor()
{
	var xproveedor = f_cheques_manuales.getValue('r_ID_M_PROVEEDORES');
	if(xproveedor &&xproveedor!=''  )
	{
		f_cheques_manuales.setValue('TITULAR',xproveedor);
	}
}

function iniciar()
{
	console.clear();
	contenedor.inicializa();
	f_cheques_manuales.inicializa();
	f_cheques.inicializa();
	f_pagos_conceptos.inicializa();

	m_cheques_manuales.inicializa(false);
	m_cheques_manuales.mostrar();
	m_cheques_manuales.buscar('*');

	d_cheques.inicializa(false);
	d_cheques.mostrar();

	addEvent(M_CHEQUES_INS,  "click",   function() { t_m_cheques_manuales('', _insert,  m_cheques_manuales.elementoXml()) } )      //ins
	addEvent(ENTER,          "click",   function() { t_m_cheques_manuales('', _enter,  m_cheques_manuales.elementoXml()) } )      //enter
	addEvent(M_CHEQUES_ESC,  "click",   function() { t_m_cheques_manuales('', _esc,  m_cheques_manuales.elementoXml()) } )       //es
	addEvent(F7,             "click",   function() { t_m_cheques_manuales('', _f7,  m_cheques_manuales.elementoXml()) } )       //esc

	addEvent(D_CHEQUES_INS,  "click",   function() { t_d_cheques('', _insert,   d_cheques.elementoXml()) } )       //INS
	addEvent(D_CHEQUES_ENTER, "click",   function() { t_d_cheques('', _enter,   d_cheques.elementoXml()) } )       //SUPR
	addEvent(D_CHEQUES_SUPR, "click",   function() { t_d_cheques('', _supr,   d_cheques.elementoXml()) } )       //SUPR
	addEvent(D_CHEQUES_ESC,  "click",   function() { t_d_cheques('', _esc,   d_cheques.elementoXml()) } )       //ESC

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	
    return true;

}

function inicio()
{
    mostrarLeyenda(1);
	m_cheques_manuales.buscar('*');
    m_cheques_manuales.setFocus();
}
</script>
EOT;

?>