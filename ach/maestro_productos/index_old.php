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

javascript('utiles,auto_tabla,forma,tabpane,formulario2,impresora,submodal,jquery,clave');


echo <<<EOT

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.0/dist/jBox.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.0/dist/jBox.all.min.css" rel="stylesheet">

 <script src="./tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<style>

.jBox-Tooltip .jBox-title {
  font-family: sans-serif;
  background: #ccf;
  border-bottom: 1px solid #66f;
  padding: 10px 10px;
}

.jBox-Tooltip .jBox-content {
  font-family: sans-serif;
  padding: 50px 20px;
}

.jBox-Modal .jBox-title {
  font-family: sans-serif;
  background: #ccf;
  border-bottom: 1px solid #66f;
  padding: 10px 10px;
}

.jBox-Modal .jBox-content {
  font-family: sans-serif;
  padding: 0px 0px;
}

</style>

{$modulo->inicio}
<table class="contenido_modulo" border="0">
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO_IMAGEN" class="grid_cuadro_activo" style="overflow-y: auto; overflow-x: none; text-align: center; width: 290px; ">
			<img id="IMAGEN" name="foto" src="" onerror="sin_imagen(this)" width="200px">
		</td>
	</tr>
	<tr>
		<td id="GRUPO2" colspan="2"></td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

xclave               = new clave('xclave');
var f_activo;
var xid_m_producto   ='';
var dxml;
var t                = null;
var t2               = null;
var xalmacen		 = null;
var xalmacen_nuevo	 = null;
var xi_prod_alma	 = null;
var xproducto;
var xdescripcion;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.titulo      = 'XX';
contenedor.ancho       = 100;
contenedor.alto        = 100;
contenedor.ayuda       = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.botonCerrar = true;
contenedor.leyenda     = ' ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal 	   = true;
contenedor.onClose     = Cerrar_contenedor;

contenedorImg             = new submodal();
contenedorImg.nombre      = 'contenedorImg';
contenedorImg.titulo      = 'Actualizar Foto';
contenedorImg.ancho       = 750;
contenedorImg.alto        = 450;
contenedorImg.ayuda       = 1;
contenedorImg.x           = 1;
contenedorImg.y           = 1;
contenedorImg.botonCerrar = true;
contenedorImg.leyenda     = creaLeyenda();
contenedorImg.usaFrame    = true;
contenedorImg.interpretar = false;
contenedorImg.modal 	   = true;

m_productos             = new lista("maestro_productos/m_productos")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.buscador    = true;
m_productos.padre       = "GRUPO1";
m_productos.onSelect    = actualizarDetalles;
m_productos.enter       = 0;
m_productos.botonCerrar = false;

d_productos               = new lista("maestro_productos/d_productos")
d_productos.nombre        = "d_productos";
d_productos.padre         = "GRUPO2";
d_productos.url           = server_path + "herramientas/genera_xml/genera_xml.php";
d_productos.enter         = 1;
d_productos.funcion       = t_d_productos;
d_productos.xfiltro       ='';
d_productos.filtro        ='ID_M_PRODUCTOS';
d_productos.onFocus       = focus_d_productos;

f_edicion 			   = new formulario2('maestro_productos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';

f_edicion2              = new formulario2('maestro_productos/f_XXXXMPDES0011');
f_edicion2.nombre       = 'f_edicion2';
f_edicion2.funcion      = t_f_edicion2;
f_edicion2.padre        = 'f_edicion_grupo2';
f_edicion2.modal        = true;
f_edicion2.noOcultarCombos = true;

f_d_productos              = new formulario2('maestro_productos/f_d_productos');
f_d_productos.nombre       = 'f_d_productos';
f_d_productos.funcion      = t_f_d_productos;
f_d_productos.padre        = 'contenedor_cuadro';
f_d_productos.modal        = true;

f_costos              = new formulario2('maestro_productos/f_costos');
f_costos.nombre       = 'f_costos';
f_costos.funcion      = t_f_costos;
f_costos.padre        = 'contenedor_cuadro';
f_costos.tipo         = 'formulario';

function t_m_productos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert: 
			cancelaTecla(evt);
			xproducto = '-1';
			f_edicion.limpiar();
			armarPlantilla();
			mostrar_formulario(f_edicion);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro) return;
			xproducto = registro[0]['ID_M_PRODUCTOS'];
			waitExec("{$t_realizando_proceso}","Pasar_detalles()",5,250,250);
			if(!registro) return;
			xproducto = registro[0]['ID_M_PRODUCTOS'];
			xdescripcion= registro[0]['DESCRIPCION'];
			d_productos.setFocus();
			mostrarLeyenda(1);
		break;
		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro) return;
			xproducto = registro[0]['ID_M_PRODUCTOS'];
			var eliminar = confirm('{$t_eliminar_registro}');
			if(eliminar)
			{
				var url = server_path + 'herramientas/utiles/actualizar_registro.php';
				var param = 'tabla=M_PRODUCTOS&c_ESTATUS_CSS=INA&busca=ID_M_PRODUCTOS&xbusca='+ xproducto ;
				var x = enviar(url,param,'POST');
			}
			m_productos.actualizar(xproducto);
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
		cancelaTecla(evt);
		break;
		case _f6:
		cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro) return;
			xproducto = registro[0]['ID_M_PRODUCTOS'];
			mostrar_formulario(f_edicion);
			f_edicion.setValue('xID_M_P_DESCRIPCION', registro[0]['ID_M_P_DESCRIPCION']);
			f_activo = f_edicion;
			armarPlantilla();

			var url       = server_path + "herramientas/genera_xml/genera_xml.php";
			var param = "tabla=V_I_PROD_ALMA&campos=ID_I_PROD_ALMA,ALMACENES,ID_M_ALMACENES&filtro=CONDICION1;ID_M_PRODUCTOS&xfiltro=*;"+ xproducto;
			var x         = enviar(url,param,'POST');
			var registro = valida_xml(x,'ID_I_PROD_ALMA');
			if(!registro)return;

			xalmacen 	   = registro[0]['ID_M_ALMACENES'];
			xi_prod_alma   = registro[0]['ID_I_PROD_ALMA'];
			xalmacen_nuevo = null;

			f_edicion.setValue('r_ID_M_ALMACENES',registro[0]['ALMACENES']);
			f_edicion.setValue('ID_M_ALMACENES',registro[0]['ID_M_ALMACENES']);
			setTimeout('f_edicion.setFocus();',10);
		break;
		case _f7:
			cancelaTecla(evt);
		break;
		case _f8:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro) return;
			var rand_no = Math.ceil(100000000*Math.random())
			contenedorImg.destruir();
			contenedorImg.url = 'upload_imagen_producto.php?ID_M_PRODUCTOS='+ registro[0]['ID_M_PRODUCTOS'] +'&rndid='+rand_no ;
			contenedorImg.inicializa();
			centrarObj(contenedorImg.contenedor);
			contenedorImg.mostrar();
			contenedorImg.setFocus();
		break;
		case _f9:
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_d_productos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		
		case _insert:
			cancelaTecla(evt);
			var registro = XML2Array(xml);
			f_d_productos.limpiar();
			f_d_productos.setValue('ID_M_PRODUCTOS', xproducto);
			f_d_productos.setValue('DESCRIPCION',xdescripcion);
			pedirPrecio();
			mostrar_formulario(f_d_productos);
			f_d_productos.setFocus();
		break;		
		
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PRODUCTOS');
			if(!registro)return;
			xdproductos  = registro[0]['ID_D_PRODUCTOS'];

			f_d_productos.buscar(xdproductos);
			f_d_productos.setValue('DESCRIPCION',registro[0]['DESCRIPCION']);
			pedirPrecio();

			mostrar_formulario(f_d_productos);
			f_d_productos.setFocus();
		break;	
		case _supr:
		break;		
		case _esc:
			cancelaTecla(evt);
			m_productos.setFocus();
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
		case _f10:
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;

		case 80://P
			if (evt.ctrlKey)
			{
				cancelaTecla(evt);
				pideClave('CambiaCosto',8,true);
			}
		break;
	}
}

function t_f_d_productos(objeto,tecla)
{
	switch (tecla)
	{
		case _insert:
		break;
		case _enter:
		break
		case _supr:
		break;
		case _esc:
			ocultar_formulario(f_d_productos);
			d_productos.setFocus();
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
		case _f10:
		case _f11:
			cancelaTecla(evt);
		break;
		
		case _f12: // F12
			f_d_productos.setValue('ID_M_PRODUCTOS' , xproducto);
			var registro = f_d_productos.submit();
			if(!registro) return false;
			ocultar_formulario(f_d_productos);
			d_productos.buscar('*');
			d_productos.setFocus();
		break;
	}
}

function t_f_costos(elemento, tecla)
{
	switch (tecla)
	{
		case _insert:
		break;
		case _enter:
		break
		case _supr:
		break;
		case _esc:
			ocultar_formulario(f_costos);
			m_productos.setFocus();
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
		case _f10:
		case _f11:
			cancelaTecla(evt);
		break;
		case _f12:
			var registro = f_costos.submit();
			if(registro)
			{
				ocultar_formulario(f_costos);
				d_productos.localiza('ID_D_PRODUCTOS',registro[0]['ID_D_PRODUCTOS']);
				d_productos.buscar();
				d_productos.setFocus();
			}
			else return;
		break;
	}
}

function t_f_edicion(obj, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
	case _insert:
	break;
	case _enter:
	break
	case _supr:
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
	case _f10:
	case _f11:
		cancelaTecla(evt);
	break;
	case _esc://Salir
		ocultar_formulario(f_edicion);
		m_productos.setFocus();
	break;
  }
}

function t_f_edicion2(obj, tecla, evt, e)
{
   var evt = window.event || e;
  switch (tecla)
  {
	case _insert:
		break;
		case _enter:
		break
		case _supr:
		break;
    case _esc: //Salir
    	cancelaTecla(evt);
		ocultar_formulario(f_edicion);
        m_productos.setFocus();
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
	case _f10:
	case _f11:
		cancelaTecla(evt);
	break;
	
    case _f12: // F12
    	cancelaTecla(evt);
        var accion = f_edicion2.getValue('xbusca');

	    var registro = f_edicion2.submit();
	    if(!registro) return false;

        xproducto = registro[0]['ID_M_PRODUCTOS'];

        ocultar_formulario(f_edicion);
        if(accion!='-1')
        {
        	m_productos.actualizar(accion);
        }
        else
        {
            m_productos.buscar(xproducto);
        }

	    m_productos.setFocus();
        break;
	
/*	
    case _f12: // F12
    	cancelaTecla(evt);

        xalmacen_nuevo = f_edicion2.getValue('ID_M_ALMACENES');

        var accion = f_edicion2.getValue('xbusca');

	    var registro = f_edicion2.submit();
	    if(!registro) return false;

        xproducto = registro[0]['ID_M_PRODUCTOS'];

        if(xalmacen != null && (xalmacen!=xalmacen_nuevo))
        {
	        var url       = server_path + "herramientas/genera_xml/genera_xml.php";
	        var param = "tabla=I_PROD_ALMA&campos=ID_I_PROD_ALMA,ID_M_ALMACENES,CONDICION1&filtro=ID_M_PRODUCTOS;ID_M_ALMACENES&xfiltro="+xproducto+";" + xalmacen_nuevo;
	        var x         = enviar(url,param,'POST');
	        var registro2 = valida_xml(x,'ID_I_PROD_ALMA');
	        if(!registro2)
	        {
	            var url       = server_path + "herramientas/utiles/actualizar_registro.php";
	            var param = "tabla=I_PROD_ALMA&busca=ID_I_PROD_ALMA&xbusca=-1&c_ID_M_PRODUCTOS_CSS="+ xproducto +"&c_CONDICION1_CSS=*&c_ID_M_ALMACENES_CSS=" + xalmacen_nuevo;
	            var y         = enviar(url,param,'POST');
	            var registro3 = valida_xml(y,'ID_I_PROD_ALMA');
	            if(!registro3)return;
	        }
            else
            {
             	var url       = server_path + "herramientas/utiles/actualizar_registro.php";
	            var param = "tabla=I_PROD_ALMA&c_CONDICION1_CSS=*&busca=ID_I_PROD_ALMA&xbusca="+registro2[0]['ID_I_PROD_ALMA'];
	            var y         = enviar(url,param,'POST');
	            var registro3 = valida_xml(y,'ID_I_PROD_ALMA');
	            if(!registro3)return;
            }

            var url       = server_path + "herramientas/utiles/actualizar_registro.php";
	        var param = "tabla=I_PROD_ALMA&c_CONDICION1_RSS=&busca=ID_I_PROD_ALMA&xbusca="+xi_prod_alma;
	        var y         = enviar(url,param,'POST');
	        var registro3 = valida_xml(y,'ID_I_PROD_ALMA');
	        if(!registro3)return;
        }
        ocultar_formulario(f_edicion);
        m_productos.buscar(xproducto);
        m_productos.setFocus();
        break;
*/		
  }
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function Pasar_detalles()
{
	d_productos.setFocus();
	mostrarLeyenda(1);
}

function focus_d_productos()
{
	if (t) clearTimeout(t);
	t = setTimeout('mostrarLeyenda(1);',200);
}


function etiqLeyenda(tecla, texto, ancho, accion)
{
	
	var e = ' <td onselectstart="return false;" style="width:'+ancho+'px;" class="td_leyenda_inactiva" onmouseup="this.className=\\'td_leyenda_activa\\'" onmouseover="this.className=\\'td_leyenda_activa\\'" onmousedown="this.className=\\'td_leyenda_click\\'" onmouseout="this.className=\\'td_leyenda_inactiva\\'" onclick="'+accion+'">[<B>'+tecla+'</B>]<br>'+texto+'</td>';
	return e;
}

function creaLeyenda()
{
	var l = '';
	l += '<center><table class="tabla_leyenda">';
	l += '<tr>';
	l += etiqLeyenda('ESC', 'Cerrar', '120', 'Ocultar_Contenedor();');
	l += '</tr>';
	l += '</table></center>';
	return l;
}

function Ocultar_Contenedor()
{
	contenedorImg.ocultar();
	m_productos.actualizar(xproducto);
	m_productos.setFocus();
}

function actualizarDetalles()
{
	if (t) clearTimeout(t);
	t = setTimeout('Detalles();',200);
}

function Detalles()
{
	var xml = m_productos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	if(!registro[0]) return false;
	xid_m_producto = registro[0]['ID_M_PRODUCTOS'];
	d_productos.xfiltro = xid_m_producto;
	d_productos.buscar('*');
	mostrarLeyenda(0);

	if (t2) clearTimeout(t2);
	t2 = setTimeout('ver_imagen();',200);
}

function sin_imagen(img)
{
	img.src = server_path + 'imagenes/productos/0.jpg';
}

function ver_imagen()
{
	var xml = m_productos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	if(!registro[0]) return false;
	var rand_no = Math.ceil(100000000*Math.random())

	var url = server_path + 'imagenes/productos/'+ registro[0]['ID_M_PRODUCTOS'] +'.jpg?rndid='+rand_no ;
	$("#IMAGEN").attr('src', url);
}

function mostrar_formulario(xformulario)
{
	f_activo=xformulario;
	contenedor.setTitle(xformulario.titulo);
	contenedor.setWidth(xformulario.ancho);
	contenedor.setHeight(xformulario.alto);
	centrarObj(contenedor.contenedor);
	contenedor.mostrar();
	xformulario.mostrar();

	setTimeout('f_activo.setFocus();',1);
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

function ocultar_formulario(xformulario)
{
	f_activo = xformulario;
	contenedor.ocultar();
	xformulario.ocultar();
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
		case 'f_edicion2':
			t_f_edicion2('',_esc);
		break;

		case 'f_d_productos':
			t_f_d_productos('',_esc);
		break;

		case 'f_costos':
			t_f_costos('',_esc);
		break;
    }
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion':
		case 'f_edicion2':
			t_f_edicion2('',_f12);
		break;

		case 'f_d_productos':
			t_f_d_productos('',_f12);
		break;

		case 'f_costos':
			t_f_costos('',_f12);
		break;
    }
}

function Cerrar_contenedor()
{
    if(f_activo)f_activo.ocultar();
    contenedor.ocultar();
}


function armarPlantilla()
{
	if (f_edicion2.cuadro.childNodes.length > 0)
	{
		borraHijos(f_edicion2.cuadro);
	}
	origen = f_edicion.getValue('xID_M_P_DESCRIPCION');
	if(!origen) return;
	f_edicion2.destruir();
	f_edicion2.origen = "maestro_productos/f_" + origen;
	f_edicion2.inicializa();
	f_edicion2.limpiar();
	f_edicion2.mostrar();
	if(xproducto !='-1')
    {
        f_edicion2.buscar(xproducto);
    }

	f_edicion2.setValue('ID_M_PRODUCTOS' , xproducto);
	f_edicion.setValue('ID_M_P_DESCRIPCION', origen);
	f_edicion.setValue('CAMPO5', '*');
	f_edicion.setValue('TIPO', 'P');

}


function CambiaCosto(confirmado)
{
	if(!confirmado)
	{
		xclave.hide();
		m_productos.setFocus();
	}
	else
	{
		var xml=d_productos.elementoXml();
		var registro = XML2Array(xml);
		if(!registro[0]) return;
		var xid = registro[0]['ID_D_PRODUCTOS'];
		xclave.hide();
		mostrar_formulario(f_costos);
		f_costos.limpiar();
		f_costos.buscar(xid);
	}
}

function pedirPrecio()
{
    if(f_d_productos.getValue("CALCULO")=='M')
    {
		for(i=1;i<7;i++)
		{
			var xcampo = 'PRECIO'+i;
			f_d_productos.mostrarCampo(xcampo);
		}
			f_d_productos.ocultarCampo('ID_M_UTILIDADES');
    }
    else
    {
		for(i=1;i<7;i++)
		{
			var xcampo = 'PRECIO'+i;
			f_d_productos.ocultarCampo(xcampo);
		}
			f_d_productos.mostrarCampo('ID_M_UTILIDADES');
    }
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
    contenedorImg.inicializa();

	
	d_productos.inicializa(false);
	d_productos.mostrar();
    m_productos.inicializa(false);
	m_productos.mostrar();
	m_productos.setFocus();
	

    f_edicion.inicializa();
    f_edicion2.inicializa();
    f_costos.inicializa(false);
    f_d_productos.inicializa();

	addEvent(frm_f_d_productos.CALCULO, 'change', pedirPrecio);
	addEvent(frm_f_edicion.xID_M_P_DESCRIPCION, 'change', armarPlantilla);
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	document.onclick = function() { if (parent.menu) parent.menu.reset(); } 
	
	addEvent(M_PRODUCTOS_INS,   "click",   function() { t_m_productos('', _insert, m_productos.elementoXml()) } )
    addEvent(M_PRODUCTOS_F6,    "click",   function() { t_m_productos('', _f6, m_productos.elementoXml()) } )
    addEvent(M_PRODUCTOS_SUP,   "click",   function() { t_m_productos('', _supr, m_productos.elementoXml()) } )
    addEvent(M_PRODUCTOS_ENTER, "click",   function() { t_m_productos('', _enter, m_productos.elementoXml()) } )
    addEvent(M_PRODUCTOS_F8,    "click",   function() { t_m_productos('', _f8, m_productos.elementoXml()) } )
    addEvent(M_PRODUCTOS_ESC,   "click",   function() { t_m_productos('', _esc, m_productos.elementoXml()) } )

    addEvent(D_PRODUCTOS_INS,   "click",   function() { t_d_productos('', _insert, d_productos.elementoXml()) } )
	addEvent(D_PRODUCTOS_ENTER, "click",   function() { t_d_productos('', _enter,d_productos.elementoXml()) } )
	addEvent(D_PRODUCTOS_ESC,   "click",   function() { t_d_productos('', _esc, d_productos.elementoXml()) } )
	
    return true;
}

function inicio()
{
	mostrarLeyenda(0);
}

var resp = iniciar();
if(resp)
{
	inicio(0);
	
}
else
{
	Salir();
}

var myTooltip;
var quill;

var ayuda_db = 'demo24'; 
var ayuda_tipo; 
var ayuda_titulo;
var ayuda_origen;
var ayuda_objeto;

var xHtml  = '';

xHtml  += '<div id="editor" style="height: 380px" data-tipo="" data-titulo="" data-origen="" data-objeto="">';
xHtml  += '</div>';
xHtml  += '<table align="center" class="tabla_leyenda">';
xHtml  += '<tbody><tr>';
xHtml  += '<td id="AYUDA_GUARDAR" onclick="ayuda_guardar();" onselectstart="return false;" style="width:80px;" class="td_leyenda_inactiva" onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="" onmouseout="this.className=\'td_leyenda_inactiva\'">Guardar</td>';
//xHtml  += '<td id="AYUDA_CANCELAR" onselectstart="return false;" style="width:80px;" class="td_leyenda_inactiva" onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="" onmouseout="this.className=\'td_leyenda_inactiva\'">Cancelar</td>';
xHtml  += '</tr>';
xHtml  += '</tbody></table>';

var myModal_ayuda = new jBox('Modal', {
	width: 1200,
	height: 500,
	title: 'My Modal Window',
	closeButton : 'title',
	content: xHtml,
	onCreated: function(){
		quill = new Quill('#editor', {
			theme: 'snow'
		});						

	}
});						

function ayuda_guardar(){

	var tipo      = $("#editor").data("tipo");
	var titulo    = $("#editor").data("titulo");
	var origen    = $("#editor").data("origen");
	var objeto    = $("#editor").data("objeto");
	var contenido = $("#editor .ql-editor").html(); 
	
	console.log(quill.getText());
	var contenido = $("#editor .ql-editor").html(); 

	var url = server_path + "maestro_productos/ayuda.php";
	var params = "db=demo24&tipo=" + tipo + "&origen=" + origen + "&objeto=" + objeto + "&contenido=" + encodeURIComponent(contenido) ;

	enviar(url, params, 'POST', function(data){
		var data = jQuery.parseJSON(data);
		
		if(data.tabla.registro){
			console.log(data);
		}
		
	} );

	
}



function ayuda_editar( tipo, titulo, origen, objeto  ){

	myModal_ayuda.open();

	var url = server_path + "maestro_productos/ayuda.php";
	var params = "db=demo24&tipo=" + tipo + "&origen=" + origen + "&objeto=" + objeto;	

	enviar(url, params, 'POST', function(data){
		var data = jQuery.parseJSON(data);
		console.log(data);
		
		if(data.tabla.registro){
			$("#editor").data("tipo"   , tipo);
			$("#editor").data("titulo" , titulo);
			$("#editor").data("origen" , origen);
			$("#editor").data("objeto" , objeto);
			quill.setText( data.tabla.registro.CONTENIDO , 'user');
		}
		
	} );

}

$.fn.disableClick = function (disable){
    this.each(function() {
        if(disable){
            if(this.onclick)
                $(this).data('onclick', this.onclick).removeAttr('onclick');
            if($._data(this, 'events') && $._data(this, 'events').click)
                $(this).data('click', $.extend(true, {}, $._data(this, 'events').click)).off('click');
        }
        else{
            if($(this).data('onclick'))
                this.onclick = $(this).data('onclick');
            if($(this).data('click'))
                for(var i in $(this).data('click'))
                    $(this).on('click', $(this).data('click')[i].handler);
        }
    });
    return this;
};


function ayuda(tipo, titulo, origen){

	console.log('tipo   : ' + tipo);
	console.log('titulo : ' + titulo);
	console.log('origen : ' + origen);

	ayuda_tipo   = tipo; 
	ayuda_titulo = titulo;
	ayuda_origen = origen;

	switch (modo_ayuda)
	{
		case 0: // si esta apagado se enciende
			modo_ayuda = 1;
			//$("." + tipo + " .td_leyenda_inactiva").off('click');

			
			$("." + tipo + " .td_leyenda_inactiva").addClass( "td_leyenda_ayuda");
			$("." + tipo + " .td_leyenda_ayuda").removeClass( "td_leyenda_inactiva");

			$("." + tipo + " .td_leyenda_ayuda").mouseup( function(){
				$(this).addClass("td_leyenda_ayuda");
			});
			
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseup"   , "" );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseover" , "" );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmousedown" , "" );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseout"  , "" );

			$("." + tipo + " .grid_estatus").addClass( "grid_estatus_ayuda");
			$("." + tipo + " .grid_estatus").removeClass( "grid_estatus");

			
			myTooltip = new jBox('Tooltip', {
				width: 600,
				height: 400,
				attach: '.grid_estatus_ayuda, .td_leyenda_ayuda',
				//trigger: 'click',
				delayOpen: 500, 
				closeOnMouseleave:true,
				preventDefault: false,
				closeOnEsc : true,
				closeButton : true,
				title: '',
				content: '',
				onPosition: function(){
					this.setContent('');
				},
				onOpen: function(){
					/*
					if( !this.source.data("on-click") ){
						this.source.data("on-click", this.source.on("click") );
						this.source.off('click');
					}
					*/
					/*
					if( !this.source.data("onclick") ){
						this.source.data("onclick", this.source.attr("onclick") );
						this.source.attr("onclick", ""); 			
					}
					*/
					
					$("." + tipo + " .td_leyenda_inactiva").addClass( "td_leyenda_ayuda");
					$("." + tipo + " .td_leyenda_inactiva").removeClass( "td_leyenda_inactiva");
					
					$("." + tipo + " .grid_estatus").addClass( "grid_estatus_ayuda");
					$("." + tipo + " .grid_estatus").removeClass( "grid_estatus");
				
					if( this.source[0].className == 'td_leyenda_activa'){
						$("#" + this.source[0].id ).removeClass('td_leyenda_activa');
						$("#" + this.source[0].id ).addClass('td_leyenda_ayuda');
					}
					
					if(this.source[0].id){
						ayuda_objeto = this.source[0].id;
						var xfunc = "ayuda_editar('" + ayuda_tipo + "','" + ayuda_titulo + "','" + ayuda_origen + "','" + ayuda_objeto + "')";
						this.setTitle( 'Ayuda ' + this.source[0].id + '&nbsp; <button onclick="' + xfunc + '">editar</button>' );

						var url = server_path + 'maestro_productos/ayuda.php';
						var params = 'db=' + ayuda_db + "&tipo=" + ayuda_tipo + "&origen=" + ayuda_origen + "&objeto=" + ayuda_objeto;
						
						enviar(url, params, 'POST', function(data){
							var data = jQuery.parseJSON(data);
							//console.log('jBox AJAX response', data);
							myTooltip.setContent('' + data.tabla.registro.CONTENIDO);
						});
					}
					
				},
				onClose: function(){
					
					this.source.on("click");
					/*
					if( this.source.data("onclick") ){
						this.source.attr("onclick", this.source.data("onclick") ); 			
						//this.source.data("onclick", "" );
						//console.log( this.source.data("onclick") );
							
					}
					*/
				},
			});
			
		break;
	
		case 1: // si esta encendido se apaga
			modo_ayuda = 0;
			
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseup"   , "this.className='td_leyenda_activa'"   );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseover" , "this.className='td_leyenda_activa'"   );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmousedown" , "this.className='td_leyenda_click'"    );
			$("." + tipo + " .td_leyenda_ayuda").attr("onmouseout"  , "this.className='td_leyenda_inactiva'" );

			$("." + tipo + " .td_leyenda_ayuda").addClass( "td_leyenda_inactiva");
			$("." + tipo + " .td_leyenda_ayuda").removeClass( "td_leyenda_ayuda");

			$("." + tipo + " .grid_estatus_ayuda").addClass( "grid_estatus");
			$("." + tipo + " .grid_estatus_ayuda").removeClass( "grid_estatus_ayuda");

			$("." + tipo + " .td_leyenda_ayuda").disableClick(false);
			
			if( myTooltip.isOpen ){
				myTooltip.close();
			}
			myTooltip.destroy();
		break;
	}

}


</script>
</body>
</html>

EOT;

?>