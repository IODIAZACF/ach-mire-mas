<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,forma,tabpane,formulario2,impresora,submodal,jquery');


echo <<<EOT


{$modulo->inicio}

<table class="contenido_modulo" border="0">
	<tr>
		<td id="GRUPO1" colspan="2"></td>
	</tr>
	<tr>
		<td id="GRUPO2"></td>
    	<td id="GRUPO3"></td>
    </tr>
</table>

{$modulo->fin}
 
<script type="text/javascript" >

var gridActivo	     = 'kardex';
var historialt	     = null;
var xproducto1	     = null;
var xkardex 	     = null;
var kardext		     = null;
var dxml			 = null;
var t		         = null;
var xid_m_producto   = null;
var xprod_alma    	 = null;
var xtipo            = null;
var id_m_documentos  = null;
var xid_m_documentos = null;

var impresora = new printer();

d_productos             = new lista("maestro_inventarios/d_productos")
d_productos.nombre      = "d_productos";
d_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
d_productos.funcion     = t_d_productos;
d_productos.padre       = "GRUPO1";
d_productos.buscador    = true;
d_productos.limite      = 50;
d_productos.onSelect    = actualizar;
d_productos.enter       = 0;
d_productos.botonCerrar = true;

m_almacen           = new lista("maestro_inventarios/m_almacen")
m_almacen.nombre    = "m_almacen";
m_almacen.padre     = "GRUPO2";
m_almacen.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_almacen.onSelect  = verAlmacen;
m_almacen.onFocus    = almacenFocus;
m_almacen.funcion   = t_m_almacen;

d_apartados             = new lista("maestro_inventarios/d_apartados")
d_apartados.nombre      = "d_apartados";
d_apartados.url         = server_path + "herramientas/genera_xml/genera_xml.php";
d_apartados.funcion     = t_d_apartados;
d_apartados.buscador    = true;
d_apartados.modal       = true;
d_apartados.botonCerrar = true;
d_apartados.enter       = 1;
d_apartados.filtro		='TIPO;ID_M_PRODUCTOS;ID_D_I_PROD_ALMA';
d_apartados.xoperadores = 'IN';


kardex           	   = new lista("maestro_inventarios/kardex")
kardex.nombre    	   = "kardex";
kardex.padre     	   = "GRUPO3";
kardex.url       	   = server_path + "herramientas/genera_xml/genera_xml.php";
kardex.onSelect  	   = kardex_focus;
kardex.funcion   	   = t_kardex;
kardex.noOcultarCombos = true;

historial           	  = new lista("maestro_inventarios/historial")
historial.nombre    	  = "historial";
historial.padre     	  = "GRUPO3";
historial.url       	  = server_path + "herramientas/genera_xml/genera_xml.php";
historial.onSelect  	  = kardex_focus;
historial.funcion   	  = t_historial;
historial.noOcultarCombos = true;

function actualizar(obj,xml)
{
  if (t) window.clearTimeout(t);
  dxml = xml;
  t = window.setTimeout('act_timer(dxml)',600);
  if(gridActivo=='kardex')
  {
  	mostrarLeyenda(0);
    $('#D_PRODUCTOS_F5').css('display','none');
  }
  else
  {
   	mostrarLeyenda(0);
   	$('#D_PRODUCTOS_F6').css('display','none');
  }
}

function Imprimir()
{
    impresora.showDialog=false;
    impresora.setParam('ID_M_DOCUMENTOS',id_m_documentos);
    if(xtipo=='FAC') xtipo='FACT';
    if(xtipo=='FACT' || xtipo=='DEV')
    {
        var reporte='CARTA_GRA';
        impresora.origin = 'reportes/r_documento_'+xtipo.toLowerCase() +'_'+ reporte.toLowerCase();

    }else{  impresora.origin = 'reportes/r_documento_'+xtipo.toLowerCase();}
    impresora.preview();
}

function Imprimir_prev()
{
	impresora.showDialog=false;
	if(xtipo=='FAC') xtipo='FACT';
	impresora.setParam('ID_X_M_DOCUMENTOS',xid_m_documentos);
	impresora.origin = 'reportes/r_documento_'+xtipo.toLowerCase() +'_prev';
	impresora.preview();
}

function almacenFocus()
{
       mostrarLeyenda(1);
}

function act_timer(xml)
{
    var registro = XML2Array(xml);
    if (!registro[0])
    {
        m_almacen.limpiar();
        historial.limpiar();
        kardex.limpiar();
     	return false;
    }
    xproducto = registro[0]['ID_D_PRODUCTOS'];
    var xml = m_almacen.leeUrl(m_almacen.url, 'origen=maestro_inventarios/m_almacen&procedimiento=ALMACEN&ID_D_PRODUCTOS=' +xproducto);
    xid_m_producto = registro [0]['ID_M_PRODUCTOS'];
    verAlmacen('',xml);

   if(!m_almacen.rows.length) kardex.limpiar();
}

function verAlmacen(at, xml)
{
  var registro = XML2Array(xml);
  if(!registro[0]) return;
   xprod_alma = registro[0]['ID_D_I_PROD_ALMA'];

  if (gridActivo=='kardex')
  {
  	xkardex = xprod_alma;
 	if (kardext) window.clearTimeout(kardext);
  	kardext = window.setTimeout('verKardex()', 100);
  }
  else
  {
    var registro2 = valida_xml(d_productos.elementoXml(),'ID_M_PRODUCTOS')
    xproducto1 = registro2[0]['ID_M_PRODUCTOS']

    if (historialt) window.clearTimeout(historialt);
  	historialt = window.setTimeout('verHistorial(xproducto1)', 100);
  }
}

function verKardex()
{
    var xml = kardex.leeUrl(kardex.url, 'origen=maestro_inventarios/kardex&procedimiento=KARDEX&limite=150&ID_D_I_PROD_ALMA=' +xkardex);
}

function verHistorial(xproducto1)
{
    var xml = historial.leeUrl(historial.url, 'origen=maestro_inventarios/historial&procedimiento=HISTORIAL&ID_M_PRODUCTOS=' + xproducto1);
}

function kardex_focus()
{
	mostrarLeyenda(2);
}

function t_d_productos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _insert: // Insertar
      cancelaTecla(evt);
      break;

    case _enter: // Enter
  	  cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_PRODUCTOS');
      if(!registro)return;
      m_almacen.setFocus();
      mostrarLeyenda(1);
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
        cancelaTecla(evt);
        Salir();
      break;

    case _f1: // F1
	  cancelaTecla(evt);
      break;

    case _f2: // F2
	  cancelaTecla(evt);
      break;

    case _f3: // F3
    	cancelaTecla(evt);
        break;

    case _f4: // F4
	  cancelaTecla(evt);     
      break;

    case _f5: // F5
	  cancelaTecla(evt);
      gridActivo='kardex';
      verKardex();
      historial.ocultar();
      kardex.mostrar();

      $('#D_PRODUCTOS_F5').css('display','none');
      $('#D_PRODUCTOS_F6').css('display','inline');
      break;

    case _f6: // F6
	  cancelaTecla(evt);
      var registro = XML2Array(xml);
      xproducto1 = registro[0]['ID_M_PRODUCTOS'];

      gridActivo='historial';

      verHistorial(xproducto1);

      historial.mostrar();
      kardex.ocultar();

      $('#D_PRODUCTOS_F6').css('display','none');
      $('#D_PRODUCTOS_F5').css('display','inline');
      break;

    case _f7: // F7
	  cancelaTecla(evt);
      break;

    case _f8: // F8
	  cancelaTecla(evt);
      break;

    case _f9: // F9
	  cancelaTecla(evt);
      break;

    case _f10: // F10
	  cancelaTecla(evt);
      break;

    case _f11: // F10
	  cancelaTecla(evt);
      break;

    case _f12: // F10
	  cancelaTecla(evt);
      break;
  }
}

function t_m_almacen(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _insert: // Insertar
      cancelaTecla(evt);
      break;

    case _enter: // Enter
  	  cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_I_PROD_ALMA');
      if(!registro)return;
      if(gridActivo=='kardex')
      {
      	kardex.setFocus();
        return;
      }
      else
      {
      	historial.setFocus();
        return;
      }
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
      cancelaTecla(evt);
      inicio(1);
      break;

    case _f1: // F1
	  cancelaTecla(evt);
      break;

    case _f2: // F2
	     cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
		if(!registro)return;
		
		var xcantidad = prompt('Cantidad de Etiquetas ', parseInt(registro[0]['CANTIDAD']));
		if(!xcantidad) return;
		var xresp = confirm('Desea Imprimir ' + parseInt(xcantidad) + ' de etiquetas');
		if(!xresp) return;
	
		impresora.origin = 'reportes/r_etiqueta_producto';
		impresora.setParam('ID_M_PRODUCTOS',registro[0]['ID_M_PRODUCTOS']);		
		impresora.copies = parseInt(xcantidad);
		impresora.print();
		m_almacen.setFocus();
      break;

    case _f3: // F3
    	cancelaTecla(evt);
        break;

		
    case _f4: // F4
	  cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_I_PROD_ALMA');
      if(!registro)return;
      xalma = registro[0]['ID_D_I_PROD_ALMA'];
      d_apartados.xfiltro ='FAC,PED,NEN;'+xid_m_producto+';'+xalma;
      d_apartados.limpiar();
      d_apartados.mostrar();
      d_apartados.setFocus();
      break;

    case _f5: // F5
	  cancelaTecla(evt);
      break;

    case _f6: // F6
	  cancelaTecla(evt);
      break;

    case _f7: // F7
	  cancelaTecla(evt);
      break;

    case _f8: // F8
	  cancelaTecla(evt);
      break;

    case _f9: // F9
	  cancelaTecla(evt);
      break;

    case _f10: // F10
	  cancelaTecla(evt);
      break;

    case _f11: // F10
	  cancelaTecla(evt);
      break;

    case _f12: // F10
	  cancelaTecla(evt);
      break;
  }
}

function t_d_apartados(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  case _esc:
    cancelaTecla();
      d_apartados.ocultar();
      m_almacen.setFocus();
      break;
    }
}

function t_kardex(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _insert: // Insertar
    case _enter: // Enter
    case _supr:
      cancelaTecla(evt);
    break;

    case _esc:
      cancelaTecla(evt);
      m_almacen.setFocus();
      mostrarLeyenda(1);
    break;

    case _f4: // F4
	  cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_I_PROD_ALMA');
      if(!registro) return;
      id_m_documentos = registro[0]['ID_M_DOCUMENTOS'];
      xid_m_documentos=registro[0]['ID_X_M_DOCUMENTOS'];
      xtipo      = registro[0]['TIPO'];
      if(!id_m_documentos){Imprimir_prev();}else{ Imprimir();}
    break;

    case _f1: // F1
    case _f2: // F2
    case _f3: // F3
    case _f5: // F5
    case _f6: // F6
    case _f7: // F7
    case _f8: // F8
    case _f9: // F9
    case _f10: // F10
    case _f11: // F10
    case _f12: // F10
	  cancelaTecla(evt);
    break;
  }
}

function t_historial(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _insert: // Insertar
      cancelaTecla(evt);
      break;

    case _enter: // Enter
  	  cancelaTecla(evt);
      break;

    case _supr:
      cancelaTecla(evt);
      break;

    case _esc:
      cancelaTecla(evt);
      m_almacen.setFocus();
      mostrarLeyenda(1);
      break;

    case _f1: // F1
	  cancelaTecla(evt);
      break;

    case _f2: // F2
	  cancelaTecla(evt);
      break;

    case _f3: // F3
    	cancelaTecla(evt);
        break;

    case _f4: // F4
	  cancelaTecla(evt);
      break;

    case _f5: // F5
	  cancelaTecla(evt);
      break;

    case _f6: // F6
	  cancelaTecla(evt);
      break;

    case _f7: // F7
	  cancelaTecla(evt);
      break;

    case _f8: // F8
	  cancelaTecla(evt);
      break;

    case _f9: // F9
	  cancelaTecla(evt);
      break;

    case _f10: // F10
	  cancelaTecla(evt);
      break;

    case _f11: // F10
	  cancelaTecla(evt);
      break;

    case _f12: // F10
	  cancelaTecla(evt);
      break;
  }
}

function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function iniciar()
{

      kardex.inicializa(false);
      kardex.mostrar();

      m_almacen.inicializa(false);
      m_almacen.mostrar();

      historial.inicializa(false);

      d_apartados.inicializa(true);
      centrarObj(d_apartados.contenedor);

      d_productos.inicializa(false);
      d_productos.mostrar();

      document.onclick=function() { if (parent.menu) parent.menu.reset(); }

      addEvent(D_PRODUCTOS_ENTER, 	"click",   function() { t_d_productos('', _enter,  	d_productos.elementoXml()) } ) //ENTER
      addEvent(D_PRODUCTOS_F5, 		"click",   function() { t_d_productos('', _f5,  	d_productos.elementoXml()) } ) //F5
      addEvent(D_PRODUCTOS_F6, 		"click",   function() { t_d_productos('', _f6,  	d_productos.elementoXml()) } ) //F6
      addEvent(D_PRODUCTOS_ESC, 	"click",   function() { t_d_productos('', _esc,  	d_productos.elementoXml()) } ) //ESC

      addEvent(M_ALAMACEN_ENTER, 	"click",   function() { t_m_almacen('', _enter, m_almacen.elementoXml()) } ) //ENTER
	  addEvent(M_ALAMACEN_F2, 		"click",   function() { t_m_almacen('', _f2,  	m_almacen.elementoXml()) } )
      addEvent(M_ALAMACEN_F4, 		"click",   function() { t_m_almacen('', _f4,  	m_almacen.elementoXml()) } )
      addEvent(M_ALAMACEN_ESC, 		"click",   function() { t_m_almacen('', _esc,  	m_almacen.elementoXml()) } ) //ESC

  	  addEvent(KARDEX_F4, 		"click",   function() { t_kardex('', _f4,   	kardex.elementoXml()) } ) //ESC
      addEvent(KARDEX_ESC, 		"click",   function() { t_kardex('', _esc,  	kardex.elementoXml()) } ) //ESC

     addEvent(d_apartados_ESC,   "click",   function()    { t_d_apartados('', _esc,     d_apartados.elementoXml()) } );

      return true;

}

function inicio(registro)
{
	switch(registro)
    {
    	case 0:
        	mostrarLeyenda(0);
        	$('#D_PRODUCTOS_F5').css('display','none');
//            d_productos.buscar('*');
            d_productos.setFocus();
            break;

        case 1:
            d_productos.setFocus();
        	break;
     }
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
