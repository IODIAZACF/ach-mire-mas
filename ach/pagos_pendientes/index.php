<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$fecha   = date("d/m/Y");
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');
$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario2,impresora,forma,tabpane,submodal,jquery');

echo <<<EOT


{$modulo->inicio}

<div id="contenido">

<table border="0">
<tr>
<td id="GRUPO1"></td>
</tr>
<tr>
<td id="GRUPO2"></td>
</tr>
</table>

</div>

{$modulo->fin}

<script type        = "text/javascript">
var xusuarios       = '{$xusuarios}';
var xfecha          = '{$fecha}';
var t               = null;
var xidpagoslotes   = null;
var xpagos          = null;


contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = '    ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal       = true;

m_pagos_pendientes             = new lista("pagos_pendientes/m_pagos_pendientes")
m_pagos_pendientes.nombre      = "m_pagos_pendientes";
m_pagos_pendientes.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_pagos_pendientes.funcion     = t_m_pagos_pendientes;
m_pagos_pendientes.buscador    = true;
m_pagos_pendientes.padre       = "GRUPO1";
m_pagos_pendientes.onSelect    = actualizarDetalles;
m_pagos_pendientes.onFocus     = m_pagosFocus;
m_pagos_pendientes.enter       = 1;
m_pagos_pendientes.botonCerrar = true;

d_pagos_pendientes              	 = new lista("pagos_pendientes/d_pagos_pendientes")
d_pagos_pendientes.nombre       	 = "d_pagos_pendientes";
d_pagos_pendientes.padre             = "GRUPO2";
d_pagos_pendientes.url               = server_path + "herramientas/genera_xml/genera_xml.php";
d_pagos_pendientes.funcion       	 = t_d_pagos_pendientes;
d_pagos_pendientes.enter             = 1;
d_pagos_pendientes.filtro            = 'ID_M_PAGOS_LOTES';
d_pagos_pendientes.onSelect          = d_pagosFocus;
d_pagos_pendientes.noOcultarCombos   = true;

f_edicion              = new formulario2('pagos_pendientes/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';

var impresora=new printer();


function actualizarDetalles()
{
   if (t) clearTimeout(t);
   t = setTimeout('Detalles();',300);
}

function Detalles()
{
   var xml = m_pagos_pendientes.elementoXml();
   var registro = XML2Array(xml);
   if(!registro[0]||!registro[0]['ID_M_PAGOS_LOTES']) return false;
   d_pagos_pendientes.xfiltro = registro[0]['ID_M_PAGOS_LOTES'];
   d_pagos_pendientes.buscar('*');
   mostrarLeyenda(1);
}

function d_pagosFocus()
{
	if (t) clearTimeout(t);
	t = setTimeout('mostrarLeyenda(2);',200);
}

function m_pagosFocus()
{
	if(m_pagos_pendientes.rows.length == 0){
		d_pagos_pendientes.limpiar(); 
	}
}


function t_m_pagos_pendientes(objeto, tecla, xml, e)
{
   var evt = window.event || e;
   switch (tecla)
   {
        case _insert:
			cancelaTecla(evt);
			break;
        case _enter: 
		   cancelaTecla(evt);
	        var registro= XML2Array(xml);
	        if(m_pagos_pendientes.rows.length) {
	        d_pagos_pendientes.setFocus();
	        mostrarLeyenda(2);
	    }
	    else{
	    	return;
	    }
        break;
        case _esc:
		  cancelaTecla(evt);
           Salir();
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
        	cancelaTecla(evt);
        break;
        case _f10:
            cancelaTecla(evt);
            var registro= XML2Array(xml);
            if(!registro[0]['ID_M_PAGOS_LOTES']) return;
            xidpagoslotes   = registro[0]['ID_M_PAGOS_LOTES'];
            f_edicion.setValue('xbusca',xidpagoslotes);
            //f_edicion.setValue('FECHA_PAGO',xfecha);
			f_edicion.setValue('FECHA_PAGO',registro[0]['FECHA_PAGO']);
            f_edicion.setValue('DEBITOS',registro[0]['DEBITOS']);
            f_edicion.setValue('TITULAR',registro[0]['RAZON']);
			f_edicion.setValue('ID_M_TIPO_PAGOS','0012');
            mostrar_formulario(f_edicion);
        break;
        case _f11:  //F11
        case _f12:  //F12
        	cancelaTecla(evt);
        break;
   }
}

function t_d_pagos_pendientes(objeto, tecla, xml, e)
{
   var evt = window.event || e;
   switch (tecla)
   {
		case _supr:
	    var registro = XML2Array(xml);
	    if(!registro[0]['ID_D_PAGOS_LOTES']) return;
	    var eliminar = confirm('{$t_eliminar_registro}');
	    if(eliminar)
	    {
	      var url = server_path + 'herramientas/utiles/actualizar_registro.php';
	      var param = 'origen=pagos_pendientes/d_pagos_pendientes&procedimiento=ELIMINAR&ID_D_PAGOS_LOTES='+ registro[0]['ID_D_PAGOS_LOTES'];
	      var x = enviar(url,param,'POST');
	    }
	    m_pagos_pendientes.actualizar(xidpagoslotes);
	    if(m_pagos_pendientes.rows.length)
        {
	        d_pagos_pendientes.buscar();
	        d_pagos_pendientes.setFocus();
	        mostrarLeyenda(2);
	    }
	    else{
	        d_pagos_pendientes.limpiar();
	        m_pagos_pendientes.setFocus();
	        m_pagos_pendientes.buscar('*');
	        mostrarLeyenda(1);
		}
        break;


        case _esc:  //ESC
        m_pagos_pendientes.setFocus();
        mostrarLeyenda(1);
        break;

        case _f1:
        case _f2:
        case _f3:
		case _f4:  //F4
		cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS_LOTES');
			if(!registro)return;
			/*
			xidpagoslotes = registro[0]['ID_D_PAGOS_LOTES'];
			var url = server_path + "herramientas/genera_xml/genera_xml.php";
			var params = 'origen=pagos_pendientes/d_pagos_pendientes&procedimiento=IMPRIMIR_PLANIFICACION&ID_M_DOC_FINAL='+registro[0]['ID_M_DOC_FINAL']+'&DEBITOS='+unformat(registro[0]['MONTO']);
			var x= enviar(url,params,'POST');
			var registro = XML2Array(x);
			*/
			if(registro[0]['TIPO']=='COM'){
				impresora.origin = 'reportes/r_documento_com';
				impresora.setParam('ID_M_DOCUMENTOS',registro[0]['ID_M_DOCUMENTOS']);
				impresora.showDialog = true;
				impresora.preview();
			
			}
			break;
		
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

function t_f_edicion(objeto, tecla, xml, e)
{
   var evt = window.event || e;
   switch (tecla)
   {
	    case _f12:  //F12
	    var xconf=Valida_numero();
	    if(!xconf)
	    {
	       alert("$t_documento_existe");
	       return false;
	    }
	    Guardar();
	    Refrescar();
        break;

        case _esc:  //ESC
        ocultar_formulario(f_edicion);
        m_pagos_pendientes.setFocus();
        break;

	    case _f1:  //F1
	    case _f2:  //F2
	    case _f3:  //F3
	    case _f4:  //F4
	    case _f5:  //F5
	    case _f6:  //F6
	    case _f7:  //F7
	    case _f8:  //F8
	    case _f9:  //F9
	    case _f10:  //F10
	    case _f11:  //F11
	    	cancelaTecla(evt);
	    break;
   }
}

function Guardar()
{
	if (!f_edicion.validator[0].validar()) return false;
	var registro =f_edicion.submit();
	if(!registro) return false;
	ocultar_formulario(f_edicion);
	f_edicion.limpiar();
	xpagos = registro[0]['ID_D_PAGOS'];
	imprimir();
}

function Guardar_formulario()
{
    t_f_edicion('',_f12);
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

function mostrar_formulario(xformulario)
{
    f_activo = xformulario;
    contenedor.setTitle(xformulario.titulo);
    contenedor.setWidth(xformulario.ancho);
    contenedor.setHeight(xformulario.alto);
    centrarObj(contenedor.contenedor);
    contenedor.setLegend(xformulario.leyenda);
    contenedor.mostrar();
    xformulario.mostrar();
    setTimeout('f_activo.setFocus();',1);
}

function ocultar_formulario(xformulario)
{
	f_activo = xformulario;
	contenedor.ocultar();
	xformulario.ocultar();
}

function Valida_numero()
{
    var xcuenta =f_edicion.getValue('ID_M_CUENTAS_BANCARIAS');
    var xnumero =f_edicion.getValue('NUMERO');

    var url = server_path + "herramientas/genera_xml/genera_xml.php";
    var param =  "origen=pagos_pendientes/m_pagos_pendientes&procedimiento=VALIDA_NUMERO&NUMERO=" + xnumero+'&ID_M_CUENTAS_BANCARIAS='+xcuenta;
    var x = enviar(url,param,'POST');
    var registro = XML2Array(x);
    if(!registro[0]) return true;
    else return false;
}

function Refrescar()
{
    d_pagos_pendientes.limpiar();
    m_pagos_pendientes.buscar('*');
    m_pagos_pendientes.setFocus();
}

function imprimir()
{
    impresora.origin='reportes/r_cheque_pendiente';
	impresora.setParam('ID_D_PAGOS',xpagos);
    impresora.setParam('ID_M_USUARIOS',xusuarios);
    impresora.setParam('letras',1);
	
    // impresora.showDialog = true;
     impresora.preview();
    
    //impresora.print();
}

function verPreview(id_h_planificacion)
{
    impresora.origin = 'reportes/r_planificacion_pagos2';
    impresora.setParam('ID_H_PLANIFICACION_PAGOS',id_h_planificacion);
    impresora.setParam('ID_M_USUARIOS',xusuarios);
    impresora.showDialog = true;
    impresora.preview();
}

function onclose()
{
   m_pagos_pendientes.setFocus();
}

function Salir(){
	location.href = server_path + 'main/inicio.php';
}


function iniciar()
{
    contenedor.inicializa();
    centrarObj(contenedor.contenedor);

    m_pagos_pendientes.inicializa();
    d_pagos_pendientes.inicializa(false);
	m_pagos_pendientes.mostrar();
    m_pagos_pendientes.setFocus();
    d_pagos_pendientes.mostrar();

    f_edicion.inicializa(false);

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(M_PAGOS_PENDIENTES_F10,   "click",   function() { t_m_pagos_pendientes('', _f10,   m_pagos_pendientes.elementoXml()) } )
    addEvent(M_PAGOS_PENDIENTES_ENTER, "click",   function() { t_m_pagos_pendientes('', _enter, m_pagos_pendientes.elementoXml()) } )
    addEvent(M_PAGOS_PENDIENTES_ESC,   "click",   function() { t_m_pagos_pendientes('', _esc,   m_pagos_pendientes.elementoXml()) } )

    addEvent(D_PAGOS_PEDIENTES_F4,     "click",   function() { t_d_pagos_pendientes('', _f4,   d_pagos_pendientes.elementoXml()) } )
    addEvent(D_PAGOS_PEDIENTES_SUPR,   "click",   function() { t_d_pagos_pendientes('', _supr, d_pagos_pendientes.elementoXml()) } )
    addEvent(D_PAGOS_PEDIENTES_ESC,    "click",   function() { t_d_pagos_pendientes('', _esc,  d_pagos_pendientes.elementoXml()) } )
    return true;
}

function inicio()
{
    m_pagos_pendientes.buscar('*');
    mostrarLeyenda(1);
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