<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha         = date("d/m/Y");
$ventana        = getvar('ventana','modulo');
$id             = getvar('id');

$id_m_usuario   = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini         = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery,forma_simple,wait');

echo <<<EOT


{$modulo->inicio}
<table>
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO_IMAGEN" valign="top"  class="grid_cuadro_activo" style="overflow-y: auto; overflow-x: none; text-align: center; width: 340px;">
			<table width="100%" border="0">
				<tr>
					<td  height="320px">
						<center><img id="IMAGEN" name="foto" src="" onerror="sin_imagen(this)" width="330px" ></center>
					</td>
				</tr>
				<tr>
					<td height="190px">
						&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var xID_M_PRODUCTOS;
var fecha = '{$xfecha}';
var fs    = new forma_simple();
var t                = null;
var t2               = null;
//*************************************//

//se crea el contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 800;
contenedor.alto        = 400;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.ayuda       = 1;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.modalResult = true;
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

d_productos              = new lista('web_productos/d_productos');
d_productos.nombre       = 'd_productos';
d_productos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_productos.usaCookie    = false;
d_productos.funcion      = t_d_productos;
d_productos.padre        = "GRUPO1";
d_productos.buscador     = true;
d_productos.onSelect    = actualizarDetalles;

f_edicion              = new formulario2('web_productos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo         = 'formulario';

var resp = iniciar();

if(!resp)
{
     Salir();
}
else
{
     inicio();
}

function t_d_productos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
	case _insert:
		cancelaTecla(evt);
	break;

	case _enter:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
		if(!registro)return;
		f_edicion.buscar(registro[0]['ID_M_PRODUCTOS']);

		f_edicion.setValue('PRESENTACION',registro[0]['PRESENTACION']);
		f_edicion.setValue('ECOMMERCE_WEB',registro[0]['ECOMMERCE_WEB']);
		f_edicion.setValue('ECOMMERCE_MOVIL',registro[0]['ECOMMERCE_MOVIL']);
		f_edicion.setValue('PRECIO1',registro[0]['PRECIO1']);
		f_edicion.setValue('ID_D_PRODUCTOS',registro[0]['ID_D_PRODUCTOS']);

		mostrar_formulario(f_edicion);


	break;

	case _supr:
		cancelaTecla(evt);
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
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
		if(!registro) return;
		var rand_no = Math.ceil(100000000*Math.random())
		contenedorImg.destruir();
		contenedorImg.url = '../maestro_productos/upload_imagen_producto.php?ID_M_PRODUCTOS='+ registro[0]['ID_M_PRODUCTOS'] +'&rndid='+rand_no ;
		contenedorImg.inicializa();
		centrarObj(contenedorImg.contenedor);
		contenedorImg.mostrar();
		contenedorImg.setFocus();
	break;
	case _f9:
		cancelaTecla(evt);
	break;
	case _f10:
		cancelaTecla(evt);
		ActualizarProductos();
		d_productos.buscar('*');
		return;
	break;
	case _f11:
	case _f12:
		cancelaTecla(evt);
	break;
  }
}

function t_f_edicion(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion,d_productos);
		break;

		case _f12:
			cancelaTecla(evt);
			var accion = f_edicion.getValue('xbusca');

			fs.armar('web_productos/fs_d_productos');
			fs.xbusca = f_edicion.getValue('ID_D_PRODUCTOS');
			fs.setValue('ECOMMERCE_WEB',f_edicion.getValue('ECOMMERCE_WEB'));
			fs.setValue('ECOMMERCE_MOVIL',f_edicion.getValue('ECOMMERCE_MOVIL'));
			fs.setValue('PRECIO1',f_edicion.getValue('PRECIO1'));

			var registro = fs.submit();
			if(!registro)
			{
					alert('Error al guardar datos de la presentación');
					return;
			}

			var registro = f_edicion.submit();
			if(!registro)return;
			if(accion!='-1')
			{
				d_productos.actualizar(accion);
			}
			else d_productos.buscar();
			ocultar_formulario(f_edicion,d_productos);
		break;
	}
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
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

function ocultar_formulario(xformulario,xobjecto_destino)
{
	contenedor.ocultar();
	xformulario.ocultar();
	if(xobjecto_destino) xobjecto_destino.setFocus();
}

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
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

function iniciar()
{
	console.clear();

	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	contenedorImg.inicializa();

	d_productos.inicializa(false);
	d_productos.mostrar();

	f_edicion.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(ENTER, "click",   function() { t_d_productos('', _enter,	d_productos.elementoXml()) } );
	addEvent(F10,   "click",   function() { t_d_productos('', _f10,     d_productos.elementoXml()) } );
	addEvent(F8,    "click",   function() { t_d_productos('', _f8,      d_productos.elementoXml()) } );
	addEvent(ESC,   "click",   function() { t_d_productos('', _esc,     d_productos.elementoXml()) } );

	var extra = '<center>';
    extra +='<input name="FILTRO" id="oTODOS" type="radio" checked="checked" onclick="Switch(\'TODOS\')">Mostrar <b><u>T</u></b>odos';
    extra +='<input name="FILTRO" id="oWEB"   type="radio" onclick="Switch(\'WEB\')"> Mostrar <b><u>W</u></b>eb';
	extra +='<input name="FILTRO" id="oMOVIL" type="radio" onclick="Switch(\'MOVIL\')"> Mostrar <b><u>M</u></b>óvil';
    extra +='</center>';

	d_productos.extra(extra);


	return true;
}

function inicio()
{
	d_productos.buscar('*');
	d_productos.setFocus();
}

function ActualizarProductos(){
	if (!!window.EventSource) {
		var source = new EventSource(server_path + 'woocommerce/actualizar_productos.php');

		source.addEventListener('message', function(respuesta) {
			console.log(respuesta.data);
			if(respuesta.data=='--fin--'){
				alerta.ocultarMensaje();
				console.log('******fin conexion*****');
				source.close();
				d_productos.buscar('*');
			}
			alerta.mostrarMensaje(respuesta.data);
		}, false);

		source.addEventListener('open', function(respuesta) {
			console.log('******inicio conexion******');
		}, false);

		source.addEventListener('error', function(respuesta) {
			if (e.readyState == EventSource.CLOSED) {
				alerta.ocultarMensaje();
				console.log('******errro en conexion*****');
			}
		}, false);
	} else {
	}
}

function sin_imagen(img)
{
	img.src = server_path + 'imagenes/productos/0.jpg';
}

function actualizarDetalles()
{
	if (t) clearTimeout(t);
	t = setTimeout('mostrarImagen();', 200);
}

function mostrarImagen()
{
	var xml = d_productos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	if(!registro[0]) return false;
	xID_M_PRODUCTOS = registro[0]['ID_M_PRODUCTOS'];

	if (t2) clearTimeout(t2);
	t2 = setTimeout('ver_imagen();', 200);
}

function ver_imagen()
{
	if(!xID_M_PRODUCTOS) return false;
	var rand_no = Math.ceil(100000000*Math.random())

	var url = server_path + 'imagenes/productos/'+ xID_M_PRODUCTOS +'.jpg?rndid='+rand_no ;
	$("#IMAGEN").attr('src', url);
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
	d_productos.actualizar(xID_M_PRODUCTOS);
	window.setTimeout("d_productos.setFocus()", 100);

}

function Switch(opcion)
{
    switch(opcion)
    {
		case 'TODOS':
            d_productos.filtro  = null;
        break;
        case 'WEB':
            d_productos.filtro  = 'ECOMMERCE_WEB';
            d_productos.xfiltro = 'SI';
        break
        case 'MOVIL':
            d_productos.filtro  = 'ECOMMERCE_MOVIL';
            d_productos.xfiltro = 'SI';
        break
    }
    d_productos.buscar('*');
    d_productos.setFocus();
}


</script>

</body>
</html>

EOT;

?>