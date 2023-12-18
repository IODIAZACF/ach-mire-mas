<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,forma,tabpane,formulario2,impresora,submodal,jquery');


echo <<<EOT

{$modulo->inicio}

<table class="contenido_modulo" border="0">
	<tr>
		<td id="GRUPO1" ></td>
	</tr>
	<tr>
		<td id="GRUPO2"></td>
	</tr>
</table>


{$modulo->fin}

<script type="text/javascript">

var f_activo;
var xlineas   	  ='';
var dxml;
var t;
var xnombrelineas = null;

// Se crea el contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.titulo      = '   ';
contenedor.ancho       = 100;
contenedor.alto        = 100;
contenedor.ayuda       = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal 	   = true;

m_lineas             	  = new lista("maestro_lineas_inventarios/m_lineas")
m_lineas.nombre      	  = "m_lineas";
m_lineas.url         	  = server_path + "herramientas/genera_xml/genera_xml.php";
m_lineas.funcion     	  = t_m_lineas;
m_lineas.buscador    	  = true;
m_lineas.padre       	  = "GRUPO1";
m_lineas.onSelect    	  = actualizarDetalles;
m_lineas.enter       	  = 0;
m_lineas.usaFiltroEstatus = true;
m_lineas.botonCerrar 	  = false;
m_lineas.filtro			  = 'TIPO';
m_lineas.xfiltro		  = 'P';

m_sublineas               	 = new lista("maestro_lineas_inventarios/m_sublineas")
m_sublineas.nombre        	 = "m_sublineas";
m_sublineas.padre         	 = "GRUPO2";
//m_sublineas.onSelect       = actualizar_detalle;
m_sublineas.url           	 = server_path + "herramientas/genera_xml/genera_xml.php";
m_sublineas.funcion       	 = t_m_sublineas;
m_sublineas.filtro        	 = 'ID_M_LINEAS';
m_sublineas.botonCerrar   	 = false;
m_sublineas.enter		  	 = 0;
m_sublineas.noOcultarCombos	 = true;

f_linea              = new formulario2('maestro_lineas_inventarios/f_linea');
f_linea.nombre       = 'f_linea';
f_linea.funcion      = t_f_linea;
f_linea.padre        = 'contenedor_cuadro';
f_linea.tipo		 = 'formulario';

f_sublinea   		   = new formulario2('maestro_lineas_inventarios/f_sublinea');
f_sublinea.nombre      = 'f_sublinea';
f_sublinea.funcion     = t_f_sublinea;
f_sublinea.padre       = 'contenedor_cuadro';
f_sublinea.tipo		   = 'formulario';

function actualizarDetalles()
{
   if (t) clearTimeout(t);
   t = setTimeout('Detalles();',500);
}

function Detalles()
{
   var xml = m_lineas.elementoXml();
   var registro = XML2Array(xml);
   if(!registro[0]['ID_M_LINEAS']) return false;
   m_sublineas.xfiltro = registro[0]['ID_M_LINEAS'];
   m_sublineas.buscar('*');
   mostrarLeyenda(0);
}

function t_m_lineas(objeto, tecla, xml, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _enter: // tabulador
       	cancelaTecla(evt);
       	var registro = XML2Array(xml);
       	if(!registro[0]['ID_M_LINEAS']) return false;
       	xlineas = registro[0]['ID_M_LINEAS'];
        xnombrelineas = registro[0]['NOMBRES'];
       	mostrarLeyenda(1);
       	m_sublineas.setFocus();
	break;
        case _insert: // Insertar
       	cancelaTecla(evt);
       	f_linea.limpiar();
       	mostrar_formulario(f_linea);
    break;

    case _esc: //Escape
       cancelaTecla(evt);
       Salir();
    break;
    case _f6:  //enter
    cancelaTecla(evt);
       	var registro = XML2Array(xml);
       	if(!registro[0]['ID_M_LINEAS']) return false;
       	xlineas = registro[0]['ID_M_LINEAS'];
        mostrar_formulario(f_linea);
        f_linea.buscar(xlineas);
    break;
    case _supr: // Supr
    cancelaTecla(evt);
       var registro = XML2Array(xml);
       if(!registro[0]['ID_M_LINEAS']) return false;
  	   var eliminar = confirm('{$t_eliminar_registro}');
       if(eliminar)
	   {
	        var url      = server_path + 'herramientas/utiles/actualizar_registro.php';
			var params   = "origen=maestro_lineas_inventarios/m_lineas&procedimiento=ELIMINAR&ID_M_LINEAS=" + registro[0]['ID_M_LINEAS'];
	        var x       = enviar(url, params, 'GET');
            m_lineas.actualizar();
       }
       m_lineas.setFocus();
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
    case _f12:  //F12
    cancelaTecla(evt);
    break;
  }
}

function t_m_sublineas(objeto, tecla, xml, e)
{
   var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // Enter Seleccionar
         cancelaTecla(evt);
       var registro = valida_xml(xml,'ID_M_SUBLINEAS');
       if(!registro) return;
       f_sublinea.buscar(registro[0]['ID_M_SUBLINEAS']);
       f_sublinea.setValue('LINEA',xnombrelineas);
       mostrar_formulario(f_sublinea);
    break;

    case _insert: // Insertar
        cancelaTecla(evt);
        f_sublinea.limpiar();
        f_sublinea.setValue('LINEA',xnombrelineas);
        f_sublinea.setValue('ID_M_LINEAS', xlineas );
        mostrar_formulario(f_sublinea);
    break;

    case _supr: // Supr
       cancelaTecla(evt);
       var registro = XML2Array(xml);
       if(!registro[0]['ID_M_SUBLINEAS']) return;
  	   var eliminar = confirm('{$t_eliminar_registro}');
       if(eliminar)
	   {
	        var url      = server_path + 'herramientas/utiles/actualizar_registro.php';
			var params   = "origen=maestro_lineas_inventarios/m_sublineas&procedimiento=ELIMINAR&ID_M_SUBLINEAS=" + registro[0]['ID_M_SUBLINEAS'];
	        var x        = enviar(url, params, 'GET');
            Refrescar();
       }
    break;

    case _esc:
        m_lineas.setFocus();
    break;


  }
}

function mostrar_formulario(xformulario)
{
  	f_activo = xformulario;

    contenedor.setTitle(xformulario.titulo);
	contenedor.setWidth(xformulario.ancho);
 	contenedor.setHeight(xformulario.alto);
    contenedor.setLegend(xformulario.leyenda);
    contenedor.mostrar();
    xformulario.mostrar();
    setTimeout('f_activo.setFocus();',1);

 }

function t_f_linea(objeto,tecla,e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _esc:
       cancelaTecla(evt);
 	   ocultar_formulario(f_linea);
       m_lineas.setFocus();
    break;

    case _f12: // F12 Guardar
    	 var accion = f_linea.getValue('xbusca');
         var registro = f_linea.submit();
	     if(!registro[0]) return false;
         if(accion!='-1')
         {
         	m_lineas.actualizar(accion);
         }
         else
         {
         	m_lineas.buscar(registro[0]['ID_M_LINEAS'],'=');
         }
         ocultar_formulario(f_linea);
         m_lineas.setFocus();
    break;
  }
}

function t_f_sublinea(objeto,tecla,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case _esc:
       cancelaTecla(evt);
       ocultar_formulario(f_sublinea);
       m_sublineas.setFocus();
    break;

    case _f12: // F12 Guardar
    cancelaTecla(evt);
    	 var accion   = f_sublinea.getValue('xbusca');
         var registro = f_sublinea.submit();
	     if(!registro[0]) return false;
                  if(accion!='-1')
         {
         	m_sublineas.actualizar(accion);
         }
         else
         {
         	m_sublineas.buscar(registro[0]['ID_M_SUBLINEAS'],'=');
         }
         ocultar_formulario(f_sublinea);

         m_sublineas.setFocus();
    break;
  }
}

function Guardar_formulario()
{
   switch (f_activo.nombre)
   {
     case 'f_linea':

        t_f_linea ('',_f12);

     break;

     case 'f_sublinea':

         t_f_sublinea ('',_f12);

     break;
   }
}

function Refrescar()
{
    var xml = m_sublineas.leeUrl(m_sublineas.url , "origen=maestro_lineas_inventarios/m_sublineas&procedimiento=DETALLE&ID_M_LINEAS=" + xlineas);
    m_sublineas.setFocus();
}

function Refrescar2()
{
    m_lineas.buscar('*');
    m_lineas.setFocus();
}

function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function ocultar_formulario(xformulario)
{
	f_activo = xformulario;
    contenedor.ocultar();
	xformulario.ocultar();
}

function VerPreview()
{
  impresora.setParam('ID_M_LINEAS', xidcirugias);
  impresora.showDialog = true;
  impresora.preview();

  m_lineas.setFocus();
}

function onclose()
{
   m_lineas.setFocus();
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_linea':
          t_f_linea('',_esc);
          break;

       case 'f_sublinea':
          t_f_sublinea('',_esc);
          break;
       }
}


function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

    m_lineas.inicializa(false);
    m_lineas.mostrar();
	m_lineas.setFocus();

	m_sublineas.inicializa(false);
	m_sublineas.mostrar();

   	f_linea.inicializa();
	f_sublinea.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(M_LINEAS_INS   , "click",   function() { t_m_lineas('', _insert, m_lineas.elementoXml()) } )
	addEvent(M_LINEAS_F6 , "click",   function() { t_m_lineas('', _f6, m_lineas.elementoXml()) } )
    addEvent(M_LINEAS_SUPR  , "click",   function() { t_m_lineas('', _supr, m_lineas.elementoXml()) } )
    addEvent(M_LINEAS_ENTER   , "click",   function() { t_m_lineas('', _enter, m_lineas.elementoXml()) } )
	addEvent(M_LINEAS_ESC   , "click",   function() { t_m_lineas('', _esc, m_lineas.elementoXml()) } )

    addEvent(M_SUBLINEAS_INS   , "click",   function() { t_m_sublineas('', _insert, m_sublineas.elementoXml()) } )
	addEvent(M_SUBLINEAS_ENTER , "click",   function() { t_m_sublineas('', _enter,m_sublineas.elementoXml()) } )
    addEvent(M_SUBLINEAS_SUPR  , "click",   function() { t_m_sublineas('', _supr, m_sublineas.elementoXml()) } )
	addEvent(M_SUBLINEAS_ESC   , "click",   function() { t_m_sublineas('', _esc, m_sublineas.elementoXml()) } )

    return true;
}



function inicio()

{
    //m_lineas.buscar('*');
    mostrarLeyenda(0);
}

var resp = iniciar();
if(!resp)
{
	Salir();
}
else
{
	inicio(0);
}
</script>


</body>
</html>

EOT;

?>