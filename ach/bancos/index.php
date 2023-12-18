<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$xfecha   = date("d/m/Y");

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,tabpane,formulario2,forma,submodal,jquery');

$query = new sql();
$query->sql = "select id_m_cajas from m_cajas where ip='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas = $query->Record['ID_M_CAJAS'];

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="contenido"> </td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var fecha	   = '{$xfecha}';
var t;
var xid_m_cuentas_bancarias;
var xtipos	   =null;

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
contenedor.modal 	   = true;
contenedor.ayuda		=1;

m_cuentas_bancarias		         = new lista("maestros/m_cuentas_bancarias")
m_cuentas_bancarias.nombre       = "m_cuentas_bancarias";
m_cuentas_bancarias.url          = server_path + "herramientas/genera_xml/genera_xml.php";
m_cuentas_bancarias.funcion      = t_m_cuentas_bancarias;
m_cuentas_bancarias.buscador     = true;
m_cuentas_bancarias.enter        = 1;
m_cuentas_bancarias.x            = 1;
m_cuentas_bancarias.y            = 1;
m_cuentas_bancarias.modal        = true;
m_cuentas_bancarias.botonCerrar  = true;

d_cuentas_bancarias           = new lista("bancos/d_cuentas_bancarias")
d_cuentas_bancarias.nombre    = "d_cuentas_bancarias";
d_cuentas_bancarias.padre     = "contenido";
d_cuentas_bancarias.funcion   = t_d_cuentas_bancarias;
d_cuentas_bancarias.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_cuentas_bancarias.filtro    ='ID_M_CUENTAS_BANCARIAS';
d_cuentas_bancarias.buscador  = true;

f_nd 				= new formulario2('bancos/f_nd');
f_nd.nombre       	= 'f_nd';
f_nd.funcion      	= t_f_nd;
f_nd.padre        	= 'contenedor_cuadro';
f_nd.tipo		   	= 'formulario';

f_nc 				= new formulario2('bancos/f_nc');
f_nc.nombre       	= 'f_nc';
f_nc.funcion      	= t_f_nc;
f_nc.padre        	= 'contenedor_cuadro';
f_nc.tipo		   	= 'formulario';



function t_f_nd(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
		cancelaTecla(evt);
			ocultar_formulario(f_nd,d_cuentas_bancarias);
		break;
		case _f12:
		cancelaTecla(evt);
			var registro = f_nd.submit();
			if(!registro)return;
			d_cuentas_bancarias.actualizar(registro[0]['ID_D_CUENTAS_BANCARIAS']);
			ocultar_formulario(f_nd,d_cuentas_bancarias);
		break;
	}
}

function t_f_nc(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
		cancelaTecla(evt);
			ocultar_formulario(f_nc,d_cuentas_bancarias);
		break;
		case _f12:
		cancelaTecla(evt);
			var registro = f_nc.submit();
			if(!registro)return;
			d_cuentas_bancarias.actualizar(registro[0]['ID_D_CUENTAS_BANCARIAS']);
			ocultar_formulario(f_nc,d_cuentas_bancarias);
		break;
	}
}

function t_m_cuentas_bancarias(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _enter:
			var registro = valida_xml(xml,'ID_M_CUENTAS_BANCARIAS');
			if (!registro) return false;
			xid_m_cuentas_bancarias= registro[0]['ID_M_CUENTAS_BANCARIAS'];
			actualizaHTML(xml,'ENCABEZADO');
			m_cuentas_bancarias.ocultar();
			d_cuentas_bancarias.xfiltro=registro[0]['ID_M_CUENTAS_BANCARIAS'];
			d_cuentas_bancarias.buscar('*');
			d_cuentas_bancarias.setFocus();
		break;

		case _esc:
			cancelaTecla(evt);
			if(!xid_m_cuentas_bancarias)
			{
			Salir();
			}
			m_cuentas_bancarias.ocultar();
			d_cuentas_bancarias.setFocus();
		break;
	}
}

function t_d_cuentas_bancarias(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert: 
			cancelaTecla(evt);
		break;
		case _enter:
		cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_CUENTAS_BANCARIAS');
			if(!registro)return;
			if(registro[0]['NOMBRE_COND_ASIENTO']=='AUTOMATICO')
			{
				alert('Proceso Invalido');
				return;
			}
			if(registro[0]['TIPO']=='NC'){
				f_nc.buscar(registro[0]['ID_D_CUENTAS_BANCARIAS']);	
				mostrar_formulario(f_nc);
			}else{
				f_nd.buscar(registro[0]['ID_D_CUENTAS_BANCARIAS']);	
				mostrar_formulario(f_nd);				
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
			m_cuentas_bancarias.mostrar();
			m_cuentas_bancarias.buscar('*');
			m_cuentas_bancarias.setFocus();
		break;

		case _f4:
		case _f5:
			cancelaTecla(evt);
		break;
		case _f6: 
			cancelaTecla(evt);
			f_nc.limpiar();
			f_nc.setValue('FECHA1', fecha);
			f_nc.setValue('TIPO','NC');
			f_nc.setValue('ID_M_CUENTAS_BANCARIAS', xid_m_cuentas_bancarias);	  
			mostrar_formulario(f_nc);
		break;	 
		case _f7: 
			cancelaTecla(evt);
			f_nd.limpiar();
			f_nd.setValue('FECHA1', fecha);
			f_nd.setValue('TIPO','ND');
			f_nd.setValue('ID_M_CUENTAS_BANCARIAS', xid_m_cuentas_bancarias);	  
			mostrar_formulario(f_nd);
		break;	 
		case _f8:
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function armarPlantilla()
{

      if(!xtipos) return;
      f_edicion.titulo = '';
      f_edicion.destruir();
      f_edicion.origen = "bancos/f_" + xtipos;
      f_edicion.inicializa();
      f_edicion.limpiar();
      contenedor.setTitle(f_edicion.titulo);
      if (xtipos=='nd')
      {
		f_edicion.setValue('FECHA1', fecha);
      	f_edicion.setValue('TIPO','ND');
      	f_edicion.setValue('ID_M_CUENTAS_BANCARIAS', xid_m_cuentas_bancarias);
        f_edicion.mostrar();
        f_edicion.setFocus();
      }
      else{
        f_edicion.setValue('FECHA1', fecha);
        f_edicion.setValue('TIPO','NC');
        f_edicion.setValue('ID_M_CUENTAS_BANCARIAS', xid_m_cuentas_bancarias);
      	f_edicion.mostrar();
        f_edicion.setFocus();
      }
}



function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_nc':
			t_f_nc('',_f12);
		break;
		case 'f_nd':
			t_f_nd('',_f12);
		break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_nc':
			t_f_nc('',_esc);
		break;
		case 'f_nd':
			t_f_nd('',_esc);
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
    d_cuentas_bancarias.setFocus();
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	d_cuentas_bancarias.inicializa();
	d_cuentas_bancarias.mostrar();

	f_nc.inicializa(false);
	f_nd.inicializa(false);

	m_cuentas_bancarias.inicializa();
	centrarObj(m_cuentas_bancarias.contenedor);

	m_cuentas_bancarias.mostrar();
	m_cuentas_bancarias.setFocus();
	m_cuentas_bancarias.buscar('*');

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	addEvent(ENTER, "click", function() { t_d_cuentas_bancarias('', _enter, d_cuentas_bancarias.elementoXml()) } )
	addEvent(F3, 	"click", function() { t_d_cuentas_bancarias('', _f3,    d_cuentas_bancarias.elementoXml()) } )
	addEvent(F6, 	"click", function() { t_d_cuentas_bancarias('', _f6,    d_cuentas_bancarias.elementoXml()) } )
	addEvent(F7, 	"click", function() { t_d_cuentas_bancarias('', _f7,    d_cuentas_bancarias.elementoXml()) } )
	addEvent(ESC,	"click", function() { t_d_cuentas_bancarias('', _esc,   d_cuentas_bancarias.elementoXml()) } )

	addEvent(m_cuentas_bancarias_ENTER, "click", function() { t_m_cuentas_bancarias('', _enter, m_cuentas_bancarias.elementoXml()) } )
	addEvent(m_cuentas_bancarias_ESC, 	"click", function() { t_m_cuentas_bancarias('', _esc, m_cuentas_bancarias.elementoXml()) } )

	return true;
}

iniciar();
</script>

</body>
</html>

EOT;

?>