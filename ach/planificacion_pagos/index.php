<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$ventana 		= getvar('ventana','modulo');
$id 			= getvar('id');

$xfecha     = date("d/m/y");

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
		<td id="contenido"></td>
	<tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var t;
var f_activo  		   = null;
var xproveedor 		   = null;
var xproveedor_razon   = null;
var xproveedor_codigo1 = null;
var impresora 		   = new printer();

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.titulo      = ' ';
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = '     ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;

d_cxp             = new lista('planificacion_pagos/d_cxp');
d_cxp.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
d_cxp.nombre      = 'd_cxp';
d_cxp.funcion     = t_d_cxp;
d_cxp.padre       = "contenido";
d_cxp.buscador    = true;
d_cxp.onSelect    = actualizarPie;

f_edicion 			= new formulario2('planificacion_pagos/f_edicion');
f_edicion.nombre    = 'f_edicion';
f_edicion.funcion   = t_f_edicion;
f_edicion.padre     = 'contenedor_cuadro';
f_edicion.tipo		= 'formulario';

function actualizarPie(obj,xml)
{
	if (t) window.clearTimeout(t);
	dxml = xml;
	t = window.setTimeout('act_timer(dxml)',1000);
}

function act_timer(xml)
{
    var registro = valida_xml(xml,'IDX');
    if(!registro) return;
    xproveedor=registro[0]['IDX'];
    var url = server_path + "herramientas/genera_xml/genera_xml.php";
    var params = "origen=planificacion_pagos/d_cxp&procedimiento=TOTALES&ID_M_PROVEEDORES="+xproveedor;
	var x = enviar(url,params,'POST');
 	actualizaHTML(x, 'PIED_');
}

function t_d_cxp(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
  	{
    	case 107:
        case 187:
		    //if(event.shiftKey && tecla==187||event.shiftKey && tecla==107) return;
        	cancelaTecla(evt);
      		var registro = XML2Array(xml);
      		if(!registro[0]['ID_D_CXCCXP']) return;
      		var xid = registro[0]['ID_D_CXCCXP'];
	  		var xsaldo = unformat(registro[0]['SALDO']);
	  		var xcond = registro[0]['CONDICION3'];
      		var url = server_path + 'herramientas/utiles/actualizar_registro.php';
      		xcond =unformat(xcond);
      		if (xcond == 0)
      		{
	      		params = 'tabla=D_CXCCXP&busca=ID_D_CXCCXP&xbusca='+xid+'&c_CONDICION3_NSS='+xsaldo;
	  		}
      		else
      		{
	      		params = 'tabla=D_CXCCXP&busca=ID_D_CXCCXP&xbusca='+xid+'&c_CONDICION3_NSS=0';
      		}
      		var x = enviar(url,params,'POST');
  	  		d_cxp.actualizar(xid);
      		Refrescar();
        	break;

		case _f4:
            cancelaTecla(evt);
            var registro = valida_xml(xml,'DOCUMENTO');
            if(!registro)return;
            ver_doc(registro[0]['DOCUMENTO'],registro[0]['TIPO']);
        	break;
			
       	case _f5:
        	cancelaTecla(evt);

            Imprimir();
        	break;

    	case _f6:
        	cancelaTecla(evt);
            var registro = XML2Array(xml);
      		if(!registro[0]['ID_D_CXCCXP']) return;
      		var xid = registro[0]['ID_D_CXCCXP'];
      		var xmonto = registro[0]['CONDICION3'];
      		var xsaldo =registro[0]['SALDO'];
      		if(unformat(xmonto)!=0) xsaldo = xmonto;
      		f_edicion.limpiar();
      		f_edicion.setValue('xbusca',xid);
      		f_edicion.setValue('CONDICION3',xsaldo);
      		f_edicion.setValue('NOMBRE_PROVEEDOR',registro[0]['NOMBRES']);
      		f_edicion.setValue('ID_M_DOC_FINAL',registro[0]['ID_M_DOC_FINAL']);

      		mostrar_formulario(f_edicion);
        	break;			
			
       	case _f9:
        	cancelaTecla(evt);
            var crear = confirm('{$t_documento_anular}');
            if(crear)
            {
                var registro = valida_xml(xml,'ID_D_CXCCXP');
                if(registro) var xid=registro[0]['ID_D_CXCCXP'];
                var url = server_path + 'herramientas/utiles/actualizar_registro.php';
                var params = 'origen=planificacion_pagos/d_cxp&procedimiento=LIMPIAR';
                var x = enviar(url,params,'POST');
                d_cxp.actualizar(xid);
			}
            else return;

        	break;

    	case _f10:
        	cancelaTecla(evt);
        	var crear = confirm('{$t_documento_cerrar}');
        	if(crear)
        	{
          		url = server_path + 'herramientas/utiles/actualizar_registro.php';
          		params = 'tabla=H_PLANIFICACION_PAGOS&c_FECHA_RSS=&busca=ID_H_PLANIFICACION_PAGOS&xbusca=-1';
          		xml = enviar(url, params, 'POST');
          		var registro = XML2Array(xml);
          		if(!registro[0]) return;
          		//Imprimir(registro[0]['ID_H_PLANIFICACION_PAGOS']);
		  		d_cxp.buscar('*');
          		d_cxp.setFocus();
		  		Refrescar();
	    	}
        	else
        		return;
        	break;

    	case _esc:
        	cancelaTecla(evt);
            Salir();
        	break;

    }
}

function t_f_edicion(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
    	case _f12:
        	cancelaTecla(evt);
            var registro = f_edicion.submit();
            if(!registro)return;
            f_edicion.limpiar();
            ocultar_formulario(f_edicion);
            xid = f_edicion.getValue('xbusca');
            d_cxp.actualizar(xid);
        	break;
		case _esc:
        	cancelaTecla(evt);
            f_edicion.limpiar();
            ocultar_formulario(f_edicion);
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
	d_cxp.setFocus();
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

function Refrescar()
{
	act_timer(null);
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function Imprimir(xplanificacion)
{
    if(xplanificacion)
    {
    	impresora.origin = 'reportes/r_planificacion_pagos2';
   		impresora.setParam('ID_H_PLANIFICACION_PAGOS',xplanificacion);
   		impresora.showDialog=true;
   		impresora.preview();
	}
    else
    {
        impresora.origin = 'reportes/r_planificacion_pagos';
		impresora.showDialog = true;
    	impresora.preview();
		d_cxp.setFocus();
    }
}

function ver_doc(reg,tipo)
{
        if (tipo=='COM'){
        impresora.origin = 'reportes/r_documento_fact_carta_gra';
        } else{
        impresora.origin = 'reportes/r_documento_ncc_carta_gra';
        }
   		impresora.setParam('ID_M_DOCUMENTOS',reg);
   		impresora.showDialog=true;
   		impresora.preview();
		d_cxp.setFocus();
}

function iniciar()
{
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	d_cxp.inicializa();

	f_edicion.inicializa(false);

	document.onclick		=	function() { if (parent.menu) parent.menu.reset(); }

	addEvent(MAS, 	"click",   function() { t_d_cxp('', 107,  d_cxp.elementoXml()) } )        //+
	addEvent(F10, 	"click",   function() { t_d_cxp('', _f10, d_cxp.elementoXml()) } )        //F10
	addEvent(ESC, 	"click",   function() { t_d_cxp('', _esc, d_cxp.elementoXml()) } )        //Esc
	addEvent(F4, 	"click",   function() { t_d_cxp('', _f4,  d_cxp.elementoXml()) } )        //F5
	addEvent(F5, 	"click",   function() { t_d_cxp('', _f5,  d_cxp.elementoXml()) } )        //F4
	addEvent(F6, 	"click",   function() { t_d_cxp('', _f6,  d_cxp.elementoXml()) } )        //F6
	addEvent(F9, 	"click",   function() { t_d_cxp('', _f9,  d_cxp.elementoXml()) } )        //F6

	inicio (0);
	return true;
}

function inicio(registro)
{
	switch(registro)
    {
    	case 0:
             d_cxp.mostrar();
             d_cxp.setFocus();
    		 d_cxp.buscar('*');
             break;
    }
}

var resp = iniciar();
if(!resp)
{
   Salir();
}

</script>

</body>
</html>

EOT;

?>