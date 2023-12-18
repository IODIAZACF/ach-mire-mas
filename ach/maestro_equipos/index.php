<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
$xfecha     = date("d/m/Y");

if (!$onClose) $onClose    = Server_Path .'main/inicio.php';
else if (!strpos($onClose,'&retornando=')) $retornando = '&retornando=true';
//--

$xid_m_usuarios = getsession('M_USUARIOS_ID_M_USUARIO');
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario2,forma,tabpane,submodal,jquery');

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

var xformulario        = null;
var t                  = null;
var xtipo          	   =null;
var xd_tipos	   	   =null;
var xestaciones           =null;


contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 350;
contenedor.alto        = 270;
contenedor.x           = 100;
contenedor.y           = 50;
contenedor.titulo      = 'Edicion';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal 	   = true;

m_tipos              = new lista("maestro_equipos/m_tipos")
m_tipos.nombre    	 = "m_tipos";
m_tipos.padre     	 = "GRUPO1";
m_tipos.buscador     = true;
m_tipos.url          = server_path + "herramientas/genera_xml/genera_xml.php";
m_tipos.funcion   	 = t_m_tipos;
m_tipos.onSelect     = actualizar_detalle;
m_tipos.onFocus      = m_tipos_focus;
m_tipos.filtro		 = "ESTATUS";
m_tipos.xfiltro		 = "ACT";

m_equipos                 = new lista("maestro_equipos/m_equipos")
m_equipos.nombre          = "m_equipos";
m_equipos.padre           = "GRUPO2";
m_equipos.url             = server_path + "herramientas/genera_xml/genera_xml.php";
m_equipos.funcion         = t_m_equipos;
m_equipos.buscador        = true;
m_equipos.noOcultarCombos = true;
m_equipos.filtro          = 'ID_M_TIPO_ESTACIONES';
m_equipos.onFocus		  = m_equipos_focus;

f_edicion   		    = new formulario2('maestro_equipos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.funcion      = t_f_edicion;

function m_tipos_focus()
{
	mostrarLeyenda(0);
}

function m_equipos_focus()
{
	mostrarLeyenda(1);
}

function actualizar_detalle (obj,xml)
{
    if (t) window.clearTimeout(t);
  	t = window.setTimeout('act_timer()',500);
}

function act_timer()
{
    var xml = m_tipos.elementoXml();
    var registro = XML2Array(xml);
 	if(!registro[0] || !registro[0]['ID_M_TIPO_ESTACIONES'])
    {
    	m_equipos.xfiltro = '';
        m_equipos.limpiar();
    }
    else
    {
    	xtipos=registro[0]['ID_M_TIPO_ESTACIONES'];
	    m_equipos.xfiltro = xtipos;
        m_equipos.buscar('*');
    }
    m_tipos_focus();
}

function t_m_tipos(objeto, tecla, xml,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter:
    	cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_TIPO_ESTACIONES');
        if (!registro)return;
        mostrarLeyenda(1);
    	m_equipos.setFocus();
    	break;

    case _esc:
    	cancelaTecla(evt);
    	Salir();
    	break;

    case _f3:
    case _f5:
    case _f11:
    case _f12:
    	cancelaTecla(evt);
    	break;
  }
}

function t_m_equipos(objeto, tecla, xml,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
    	cancelaTecla(evt);
       	f_edicion.limpiar();
    	f_edicion.setValue('ID_M_TIPO_ESTACIONES',xtipos);
        armarPlantilla();
		mostrar_formulario(f_edicion);
   		break;

    case _supr: // Eliminar
		cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_ESTACIONES');
        if(!registro) return;
        xd_tipos = registro[0]['ID_M_ESTACIONES'];
  		var eliminar = confirm('{$t_eliminar_registro}');
	   	if(eliminar)
	   	{
	        var url = server_path + 'herramientas/utiles/actualizar_registro.php';
	        var param = 'tabla=M_ESTACIONES&c_ESTATUS_CSS=INA&busca=ID_M_ESTACIONES&xbusca='+ xd_tipos ;
	        var x = enviar(url,param,'POST');
        }
		m_equipos.buscar('*');
    	break;

	case _enter: // Modificar
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_ESTACIONES');
        if(!registro) return;
        xd_tipos= registro[0]['ID_M_ESTACIONES'];
        mostrar_formulario(f_edicion);
        armarPlantilla();
        setTimeout('f_edicion.setFocus();',10);
		break;

    case _esc: //Tab volver a tipo de estaciones
    	cancelaTecla(evt);
        mostrarLeyenda(0);
    	m_tipos.setFocus();
    	break;
  }

}

function t_f_edicion(obj, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _f12: // F12
    	cancelaTecla(evt);
	    var registro =f_edicion.submit();
	    if(!registro) return false;
        ocultar_formulario(f_edicion);
        xd_tipos= null;
        m_equipos.buscar(xtipos);
	    m_equipos.setFocus();

		var url = server_path  + 'maestro_equipos/guacamole.php';
		var param = 'x=0' ;

		var x = enviar(url, param, 'POST');
		
    	break;

    case _esc://Salir
        ocultar_formulario(f_edicion);
        xd_tipos= null;
        m_equipos.setFocus();
      	break;
  }
}

function Guardar_formulario()
{
          t_f_edicion('',_f12);
}

function Cerrar_formulario()
{
          t_f_edicion('',_esc);
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

function armarPlantilla()
{
      if (f_edicion.cuadro.childNodes.length > 0)
      {
      	borraHijos(f_edicion.cuadro);
	  }
      if(!xtipos) return;
      f_edicion.destruir();
      f_edicion.origen = "maestro_equipos/f_" + xtipos;
      f_edicion.inicializa();
      f_edicion.limpiar();
      if (!xd_tipos){ +
           	f_edicion.setValue('ID_M_TIPO_ESTACIONES', xtipos);
            f_edicion.mostrar();
      }
      else{
      	f_edicion.buscar(xd_tipos);
      	f_edicion.setValue('ID_M_ESTACIONES' , xd_tipos);
        f_edicion.setValue('ID_M_TIPO_ESTACIONES', xtipos);
      	f_edicion.mostrar();
      }
}

function Salir()
{
   parent.proceso.location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
    contenedor.inicializa();

	m_tipos.inicializa(false);
	m_tipos.mostrar();
    m_tipos.buscar('*');

	m_equipos.inicializa(false);
	m_equipos.mostrar();

    f_edicion.inicializa();

    addEvent(M_TIPOS_ENTER, "click",   function() { t_m_tipos('', _enter,  m_tipos.elementoXml()) } )		//Tab
    addEvent(M_TIPOS_ESC, "click",   function() { t_m_tipos('', _esc,  m_tipos.elementoXml()) } )       //Esc

  	addEvent(M_ESTACIONES_INS, "click",   function() { t_m_equipos('', _insert,  m_equipos.elementoXml()) } )      //Ins
  	addEvent(M_ESTACIONES_ENTER, "click",   function() { t_m_equipos('', _enter,  m_equipos.elementoXml()) } )		//Enter
	addEvent(M_ESTACIONES_SUP, "click",   function() { t_m_equipos('', _sup,   m_equipos.elementoXml()) } )       //Del
    addEvent(M_ESTACIONES_ESC, "click",   function() { t_m_equipos('', _esc,  m_equipos.elementoXml()) } )      //Esc

   // document.onclick=function() { if (parent.menu) parent.menu.reset(); }
    return true;
}
function inicio(recargar)
{
    mostrarLeyenda(0);
    m_tipos.setFocus();
}

var resp = iniciar();
if(resp)
{
	inicio(0);
}else
{
	Salir();
}

</script>

EOT;

?>