<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$fecha   = date("d/m/Y");
$id_m_usuario = getsession('M_USUARIOS_ID_M_USUARIO');
$my_ini 	= new ini('modulo');
encabezado($titulo);

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();
javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora');

echo <<<EOT

{$modulo->inicio}
<div id="contenido">
<table>
    <tr>
    <td id="GRUPO1"></td>
    </tr>
</table>
<table width="100%" align="center">
    <tr>
    <td class="radio" onclick="Switch('TODOS')"><input id="item1" name="correo" type="radio"> Mostrar Todos</td>
    <td class="radio" onclick="Switch('EXISTENCIA')"><input id="item2" name="correo" type="radio" checked> Mostrar En Existencia</td>
    <td class="radio" onclick="Switch('EN OFERTA')"><input id="item3" name="correo" type="radio"> Mostrar En Oferta</td>
    </tr>
</table>
</div>
{$modulo->fin}
<script type="text/javascript">

var xalmacen 		   = null;
var xdocumento		   = null;
var xproducto		   = null;
var t                  = null;
var impresora		   = new printer();

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 400;
contenedor.alto        = 200;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal  	   = true;

d_i_prod_alma              = new lista('maestro_productosxalmacen/d_i_prod_alma');
d_i_prod_alma.nombre       = 'd_i_prod_alma';
d_i_prod_alma.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_i_prod_alma.usaCookie    = false;
d_i_prod_alma.buscador     = true;
d_i_prod_alma.filtro       = 'ID_M_ALMACENES';
d_i_prod_alma.funcion      = t_d_i_prod_alma;
d_i_prod_alma.padre        = "GRUPO1";
d_i_prod_alma.panel        = 120;
d_i_prod_alma.onSelect     = actualizarImagen;

m_almacenes		        = new lista("maestros/m_almacenes")
m_almacenes.nombre      = "m_almacenes";
m_almacenes.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_almacenes.funcion     = t_m_almacenes;
m_almacenes.buscador    = true;
m_almacenes.modal       = true;
m_almacenes.botonCerrar = true;
m_almacenes.enter       = 1;
m_almacenes.filtro		= 'ESTATUS';
m_almacenes.xfiltro		= 'ACT';

d_kardex		     = new lista("maestro_productosxalmacen/d_kardex")
d_kardex.nombre      = "d_kardex";
d_kardex.url         = server_path + "herramientas/genera_xml/genera_xml.php";
d_kardex.funcion     = t_d_kardex;
d_kardex.modal       = true;
d_kardex.botonCerrar = true;
d_kardex.filtro      = 'ID_D_I_PROD_ALMA';

historial_compras		      = new lista("maestro_productosxalmacen/historial_compras")
historial_compras.nombre      = "historial_compras";
historial_compras.url         = server_path + "herramientas/genera_xml/genera_xml.php";
historial_compras.funcion     = t_historial_compras;
historial_compras.buscador    = true;
historial_compras.modal       = true;
historial_compras.botonCerrar = true;

m_productos		        = new lista("maestro_productosxalmacen/m_productos")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.buscador    = true;
m_productos.modal       = true;
m_productos.botonCerrar = true;
m_productos.enter       = 1;

f_edicion 			   = new formulario2('maestro_productosxalmacen/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';

f_configuracion 			   = new formulario2('maestro_productosxalmacen/f_configuracion');
f_configuracion.nombre       = 'f_configuracion';
f_configuracion.funcion      = t_f_configuracion;
f_configuracion.padre        = 'contenedor_cuadro';
f_configuracion.tipo		 = 'formulario';
//f_configuracion.filtro	 = 'ID_M_ALMACENES';

function actualizarImagen()
{
   if (t) clearTimeout(t);
   t = setTimeout('ver_imagen();',200);
}

function sin_imagen(img)
{
  img.src = server_path + 'imagenes/productos/0.jpg';
}

function ver_imagen()
{
   var xml = d_i_prod_alma.elementoXml();
   var HTML = '';
   var registro = XML2Array(xml);
   if(!registro[0]['ID_M_PRODUCTOS']) return false;
   if(!registro[0]) return false;
   var rand_no = Math.ceil(100000000*Math.random())

   var url = server_path + 'imagenes/productos/'+ registro[0]['ID_M_PRODUCTOS'] +'.jpg?rndid='+rand_no ;
       HTML  ='<table width="120px" height="100%" style="border: solid 1px #BCBCBC">';
       HTML +='<tr>';
       HTML +=' <td  align="center" colspan="2" height="100%"><img name="foto" src="' + url + '" onerror="sin_imagen(this)" width="100"></td>';
       HTML +='</tr>';
       HTML +='</table>';
   d_i_prod_alma.divPanel.innerHTML = HTML;
}

function Switch(estatus)
{
    switch (estatus)
    {
        case 'TODOS':
	        //xestatus = '';
	//            alert(xestatus);
	        d_i_prod_alma.filtro  ='ID_M_ALMACENES';
	        d_i_prod_alma.xfiltro =xalmacen;
	        d_i_prod_alma.buscar('*');
	        var obj=document.getElementById('item1');
	        obj.checked=true;
        break;

        case 'EXISTENCIA':
            //xestatus = 'A';
            alert('en existencia');
            d_i_prod_alma.filtro  ='DISPONIBLES';
            d_i_prod_alma.xfiltro =0;
            d_i_prod_alma.xoperadores='>';
            d_i_prod_alma.buscar('*');
            var obj=document.getElementById('item2');
            obj.checked=true;
        break;

         case 'EN OFERTA':
            //xestatus = 'EP';
            d_i_prod_alma.filtro  ='ESTATUS;ID_M_CLASE_FALLAS';
            d_i_prod_alma.xfiltro ='EP;'+xid_clase_falla;
            d_i_prod_alma.buscar('*');
            var obj=document.getElementById('item3');
            obj.checked=true;
        break;
    }
    d_i_prod_alma.setFocus();
}

function t_m_almacenes(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter://enter
      	cancelaTecla(evt);
      	var registro = valida_xml(xml,'ID_M_ALMACENES');
        if(!registro)return;
      	xalmacen = registro[0]['ID_M_ALMACENES'];
      	actualizaHTML(xml,'ENCABEZADO');
      	m_almacenes.ocultar();
      	d_i_prod_alma.xfiltro=xalmacen;
      	d_i_prod_alma.buscar('*');
      	d_i_prod_alma.setFocus();
      	break;

    case _esc://esc
    	if(!xalmacen)
     	{
      	Salir();
      	}
      	else
        {
        	m_almacenes.ocultar();
        	d_i_prod_alma.setFocus();
        }
      break;
  }
}

function t_d_i_prod_alma(objeto, tecla, xml)
{
  var evt = window.event || e;
 switch (tecla)
  {
  	case _insert:
        cancelaTecla(evt);
        m_productos.buscar('*');
        m_productos.mostrar();
        m_productos.setFocus();
        break;

  	case _enter:
    	cancelaTecla(evt);
      	var registro = valida_xml(xml,'ID_D_I_PROD_ALMA');
        if(!registro) return;
        var xprodalma= registro[0]['ID_D_I_PROD_ALMA'];
      	xproducto = registro[0]['ID_M_PRODUCTOS'];
      	f_edicion.limpiar();
      	f_edicion.buscar(xprodalma);
        f_edicion.setValue('ID_M_PRODUCTOS' , xproducto);
        f_edicion.setValue('DESCRIPCION' , registro[0]['DESCRIPCION']);
        f_edicion.setValue('PRESENTACION' , registro[0]['PRESENTACION']);
	  	mostrar_formulario(f_edicion);
      	break;

  	case _f3: // F3
    	cancelaTecla(evt);
    	m_almacenes.mostrar();
        m_almacenes.setFocus();
      	break;

    case _f4:
    	cancelaTecla(evt);
    	var registro = valida_xml(xml,'ID_M_PRODUCTOS');
        if(!registro)return;
        xproducto = registro[0]['ID_M_PRODUCTOS'];
        var x = historial_compras.leeUrl(server_path + "herramientas/genera_xml/genera_xml.php", "origen=maestro_productosxalmacen/historial_compras&procedimiento=HISTORIAL&ID_M_PRODUCTOS=" + xproducto);
        waitExec('{$t_realizando_proceso}', 'historial_compras.mostrar()', 5, 250, 283);
  		historial_compras.setFocus();
    	break;

    case _f5:
    	cancelaTecla(evt);
        var registro = XML2Array(xml);
       	xproducto = registro[0]['ID_M_PRODUCTOS'];
       	d_kardex.xfiltro = registro[0]['ID_D_I_PROD_ALMA'];
       	d_kardex.buscar();
       	d_kardex.mostrar();
       	d_kardex.setFocus();
       	break;

    case _f7:
    	cancelaTecla(evt);
         var registro = XML2Array(xml);
      	//var registro = valida_xml(xml,'ID_CONFIGURACION');
        //if(!registro) return;
      	//f_configuracion.limpiar();
        f_configuracion.buscar('0011');
        mostrar_formulario(f_configuracion);
        f_configuracion.setValue('ID_M_ALMACENES' ,xalmacen);
	  	mostrar_formulario(f_configuracion);
      	break;

    case _esc:
    	Salir();
        break;
  }
}

function t_m_productos(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc:
    	m_productos.ocultar();
        d_i_prod_alma.setFocus();
    	break;
    case _enter:
    	var registro= valida_xml(xml,'ID_M_PRODUCTOS');
        if(!registro) return;
        xproducto = registro[0]["ID_M_PRODUCTOS"];
      	var url = server_path + 'herramientas/genera_xml/genera_xml.php';
      	var params = 'tabla=I_PROD_ALMA&campos=ID_I_PROD_ALMA&filtro=ID_M_ALMACENES;ID_M_PRODUCTOS&xfiltro='+xalmacen+';'+xproducto;
      	var x= enviar(url, params, 'POST');
      	var registro2 = XML2Array(x);
      	if (!registro2[0])
      	{
        	url = server_path + 'herramientas/utiles/actualizar_registro.php';
          	params = 'tabla=I_PROD_ALMA&busca=ID_I_PROD_ALMA&xbusca=-1&c_ID_M_PRODUCTOS_RSS=' + xproducto + '&c_ID_M_ALMACENES_RSS=' + xalmacen;
          	x = enviar(url, params, 'POST');
          	registro2 = XML2Array(x);
            m_productos.ocultar();
      	}
        else{
            alert('{$t_almacen_asignado}');
            return;
        }
        d_i_prod_alma.buscar('*');
        d_i_prod_alma.localiza(registro2[0]['I_PROD_ALMA']);
        d_i_prod_alma.setFocus();
    	break;
  }
}

function t_d_kardex(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc://esc
	  	d_kardex.ocultar();
      	d_i_prod_alma.localiza("ID_M_PRODUCTOS",xproducto);
      	d_i_prod_alma.setFocus();
      	break;

    case _f4://F4
      	cancelaTecla(evt);
       	var registro =valida_xml(xml,'ID_M_DOCUMENTOS');
       	if (!registro)
        {
            alert('{$t_operacion_no_permitida}');
         	return;
        }
       	xdocumento =registro[0]['ID_M_DOCUMENTOS'];
       	ver_documento(xdocumento,registro[0]['TIPO']);

        break;
  }
}

function t_historial_compras(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
  	case _esc://esc
		historial_compras.ocultar();
      	d_i_prod_alma.setFocus();
      	break;
  }
}

function t_f_edicion(elemento, tecla)
{
  switch (tecla)
  {
    case _esc: //Escape
      	ocultar_formulario(f_edicion);
        d_i_prod_alma.setFocus();
      	break;

    case _f12: // F12 Guardar_item
    	var registro = f_edicion.submit();
    	if(registro)
   		{
    		var indice = registro[0]['UNICO'];
            ocultar_formulario(f_edicion);
    		d_i_prod_alma.buscar(registro[0]['ID_M_PRODUCTOS']);
    		d_i_prod_alma.localiza('ID_D_I_PROD_ALMA',registro[0]['ID_D_I_PROD_ALMA']);
    		d_i_prod_alma.setFocus();
   		}
      	break;
  }
}

function t_f_configuracion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_configuracion,d_i_prod_alma);
      break;

    case _f12:
      cancelaTecla(evt);
      var accion = f_configuracion.getValue('xbusca');

      var registro = f_configuracion.submit();
      if(!registro)return;
      if(accion!='-1')
      {
       d_i_prod_alma.actualizar(accion);
      }
      else d_i_prod_alma.buscar();
      ocultar_formulario(f_configuracion,d_i_prod_alma);
      break;
  }
}

function ver_documento (xdocumento,xtipo)
{
 impresora.origin = 'reportes/r_documento';
  if (xtipo == 'FAC' ) impresora.origin = 'reportes/r_documento_fact';
  if (xtipo == 'DEV')  impresora.origin = 'reportes/r_documento_dev';
  if (xtipo == 'PRE')  impresora.origin = 'reportes/r_documento_pre';
  if (xtipo == 'AUD' ) impresora.origin = 'reportes/r_documento_aud';
  if (xtipo == 'COM' ) impresora.origin = 'reportes/r_documento_com';
  impresora.setParam('ID_M_DOCUMENTOS',xdocumento);
  impresora.showDialog=true;
  impresora.preview();
  d_kardex.localiza("ID_M_DOCUMENTOS",xdocumento)
  d_kardex.setFocus();
}

function mostrar_formulario(xformulario)
{
    f_activo=xformulario;
    contenedor.setTitle(xformulario.titulo);
	contenedor.setWidth(xformulario.ancho);
	contenedor.setHeight(xformulario.alto);
	centrarObj(contenedor.contenedor);
    contenedor.setLegend(xformulario.leyenda);
	contenedor.mostrar();
	xformulario.mostrar();
	xformulario.setFocus();
}

function ocultar_formulario(xformulario)
{

    contenedor.ocultar();
	xformulario.ocultar();
	d_i_prod_alma.setFocus();
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_edicion':
          t_f_edicion('',_f12);
          break;

       case 'f_configuracion':
       t_f_configuracion('',_f12);
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

       case 'f_configuracion':
       t_f_configuracion('',_esc);
       break;
    }
}

function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
     contenedor.inicializa();
     centrarObj(contenedor.contenedor);

     d_i_prod_alma.inicializa();
     d_i_prod_alma.mostrar();

     m_almacenes.inicializa(false);
     centrarObj(m_almacenes.contenedor);

     d_kardex.inicializa();
     centrarObj(d_kardex.contenedor);

     historial_compras.inicializa();
     centrarObj(historial_compras.contenedor);

     m_productos.inicializa();
     centrarObj(m_productos.contenedor);

     f_edicion.inicializa();

     f_configuracion.inicializa();

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(F3, 	"click",   function() { t_d_i_prod_alma('', _f3,  d_i_prod_alma.elementoXml()) } )        //114 f3
    addEvent(INS, 	"click",   function() { t_d_i_prod_alma('', _insert,  d_i_prod_alma.elementoXml()) } )        //ENTER Ins
    addEvent(ENTER, "click",   function() { t_d_i_prod_alma('', _enter,   d_i_prod_alma.elementoXml()) } )        //Enter
    addEvent(F4, 	"click",   function() { t_d_i_prod_alma('', _f4,   d_i_prod_alma.elementoXml()) } )        //f4
    addEvent(F5, 	"click",   function() { t_d_i_prod_alma('', _f5,   d_i_prod_alma.elementoXml()) } )
    addEvent(F7, 	"click",   function() { t_d_i_prod_alma('', _f7,   d_i_prod_alma.elementoXml()) } )
    addEvent(ESC, 	"click",   function() { t_d_i_prod_alma('', _esc,   d_i_prod_alma.elementoXml()) } )        //Esc

    addEvent(m_almacenes_ENTER, "click",   function() { t_m_almacenes('', _enter, m_almacenes.elementoXml()) } )
    addEvent(m_almacenes_ESC, "click",   function() { t_m_almacenes('', _esc, m_almacenes.elementoXml()) } )

    addEvent(d_kardex_ESC, "click",   function() { t_d_kardex('', _esc, d_kardex.elementoXml()) } ) //Esc
    addEvent(d_kardex_F4, "click",   function() { t_d_kardex('', _f4, d_kardex.elementoXml()) } )

    addEvent(m_productos_ENTER, "click",   function() { t_m_productos('', _enter, m_productos.elementoXml()) } )
    addEvent(m_productos_ESC, "click",   function() { t_m_productos('', _esc, m_productos.elementoXml()) } )

    addEvent(historial_compras_ESC, "click",   function() { t_historial_compras('', _esc, historial_compras.elementoXml()) } ) //Esc

  return true;
}

function inicio()
{
	m_almacenes.mostrar();
	m_almacenes.setFocus();
}

var resp = iniciar();
if(resp)
{
    inicio();
}else
{
	Salir();
}

</script>

</body>
</html>

EOT;

?>