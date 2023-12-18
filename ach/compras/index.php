<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$ventana         = getvar('ventana','modulo');
$id              = getvar('id');
$xcondicion_pago = strtoupper(getvar('condicion_pago','CRE'));

$xID_M_ALMACENES = getsession('M_ESTACIONES_ID_M_ALMACENES_COMPRAS');
$xestatus   	 = 'C';
$xFecha_Documento= date("d/m/Y");
$my_ini          = new ini('modulo');

$xtitulo = $xcondicion_pago == 'CRE'? $my_ini->seccion('VENTANA','TITULO') : $my_ini->seccion('VENTANA','TITULO2');

encabezado($titulo);

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table border="0">
	<tr>
		<td id="CONTENIDO"></td>
	</tr>
</table>

{$modulo->fin}
<script type="text/javascript">
var f_activo              = null;
var impresora          	  = new printer();

var xID_M_PROVEEDORES       = '';
var xID_D_DOCUMENTOS		= '';
var xID_M_DOCUMENTOS      	= null;
var xID_M_ALMACENES       	= '{$xID_M_ALMACENES}'; /*variable para definir el almacen principal*/
var xTIPO                 	= 'COM';
var xID_M_PRODUCTOS         = '';
var xNOMBRE_ALMACEN         = null;

var xProveedor_Nombre     = '';
var xProveedor_Codigo1    = '';
var xProveedor_Codigo2    = '';
var xProveedor_Direccion  = '';
var xProveedor_Telefono   = '';
var fecha                 = '{$xFecha_Documento}';
var xFecha_Documento      = '{$xFecha_Documento}';
var xFecha_Recepcion      = '{$xFecha_Documento}';
var xDias                 = 0;
var xid_m_almacen_estacion= '{$xID_M_ALMACENES}'; /*variable para definir el almacen principal*/
var t1                    = null;
var xbandera              = null;
var id                    = null;
var xcondicion_pago       = '{$xcondicion_pago}';
var xrango                = 0.5; //PREGUNTAR !!!1

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 720;
contenedor.alto        = 650;
contenedor.titulo      = ' ';
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = 'XXXXXX';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 100;
contenedor.onClose     = Cerrar_contenedor;

d_documento             = new lista('compras/d_documento');
d_documento.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
d_documento.nombre      = 'd_documento';
d_documento.funcion     = t_d_documento;
d_documento.padre       = 'CONTENIDO';
d_documento.buscador    = true;
d_documento.filtro      = 'ID_X_M_DOCUMENTOS';
d_documento.onSelect    = actualizaPie;
d_documento.onLoad      = d_documentoLoad;

m_proveedores         = new lista("compras/m_proveedores");
m_proveedores.nombre  = 'm_proveedores';
m_proveedores.url     = server_path + "herramientas/genera_xml/genera_xml.php";
m_proveedores.funcion = t_m_proveedores;
m_proveedores.buscador= true;
m_proveedores.x       = 1;
m_proveedores.y       = 1;
m_proveedores.enter   = 1;
m_proveedores.modal   = true;
m_proveedores.botonCerrar  = true;

m_productos           = new lista("compras/d_i_prod_almacen");
m_productos.nombre    = 'm_productos';
m_productos.url       = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion   = t_m_productos;
m_productos.usaCookie = false;
m_productos.buscador  = true;
m_productos.filtro    ='ID_M_ALMACENES';
m_productos.x         = 1;
m_productos.y         = 1;
m_productos.enter     = 1;
m_productos.modal     = true;
m_productos.botonCerra= true;

m_ordenes_compras             = new lista("compras/m_ordenes_compras");
m_ordenes_compras.nombre   	  = 'm_ordenes_compras';
m_ordenes_compras.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_ordenes_compras.funcion     = t_m_ordenes_compras;
m_ordenes_compras.buscador    = true;
m_ordenes_compras.x           = 1;
m_ordenes_compras.y           = 1;
m_ordenes_compras.enter       = 1;
m_ordenes_compras.modal       = true;
m_ordenes_compras.botonCerrar = true;
m_ordenes_compras.filtro      = 'ID_M_PROVEEDORES';


f_edicion             = new formulario2('compras/f_edicion');
f_edicion.nombre      = 'f_edicion';
f_edicion.funcion     = t_f_edicion;
f_edicion.padre       = 'contenedor_cuadro';
f_edicion.tipo        = 'formulario';

f_servicio            = new formulario2('compras/f_servicio');
f_servicio.nombre     = 'f_servicio';
f_servicio.funcion    = t_f_servicio;
f_servicio.padre      = 'contenedor_cuadro';
f_servicio.tipo       = 'formulario';

f_compra 			  = new formulario2('compras/f_compra');
f_compra.nombre       = 'f_compra';
f_compra.funcion      = t_f_compra;
f_compra.padre        = 'contenedor_cuadro';
f_compra.tipo         = 'formulario';

f_cierre 			  = new formulario2('compras/f_cierre');
f_cierre.nombre       = 'f_cierre';
f_cierre.funcion      = t_f_cierre;
f_cierre.padre        = 'contenedor_cuadro';
f_cierre.tipo         = 'formulario';

f_descuento           = new formulario2('compras/f_descuento');
f_descuento.nombre    = 'f_descuento';
f_descuento.funcion   = t_f_descuento;
f_descuento.padre     = 'contenedor_cuadro';
f_descuento.tipo      = 'formulario';

f_proveedores         = new formulario2('compras/f_proveedores');
f_proveedores.nombre  = 'f_proveedores';
f_proveedores.funcion = t_f_proveedores;
f_proveedores.padre   = 'contenedor_cuadro';
f_proveedores.tipo    = 'formulario';
f_proveedores.onclose = "function{ alert('cerró'); }"

f_productos           = new formulario2('maestro_productos/f_edicion');
f_productos.nombre    = 'f_productos';
f_productos.funcion   = t_f_productos;
f_productos.padre     = 'contenedor_cuadro';

f_productos2          = new formulario2('maestro_productos/f_XXXXMPDES0011');
f_productos2.nombre   = 'f_productos2';
f_productos2.funcion  = t_f_productos2;
f_productos2.padre    = 'f_productos_grupo2';
f_productos2.modal    = true;
f_productos2.noOcultarCombos = true;

f_producto_precio         = new formulario2('compras/f_producto_precio');
f_producto_precio.nombre  = 'f_producto_precio';
f_producto_precio.funcion = t_f_producto_precio;
f_producto_precio.padre   = 'contenedor_cuadro';
f_producto_precio.tipo    = 'formulario';

function d_documentoLoad(){
	if(d_documento.rows.length==0){
		limpiarElementos('PIE');

	}
	
}

function t_m_proveedores(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			f_proveedores.limpiar();
			m_proveedores.ocultar();
			mostrar_formulario(f_proveedores);
		break;
		
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PROVEEDORES');
			if(!registro)
			{
				inicio(0);
				return;
			}
			xDias                 = registro[0]['DIAS_CREDITO'];
			xID_M_PROVEEDORES     = registro[0]['ID_M_PROVEEDORES'];
			xProveedor_Nombre     = registro[0]['RAZON'];
			xProveedor_Codigo1    = registro[0]['CODIGO1'];
			xProveedor_Codigo2    = registro[0]['CODIGO2'];
			xProveedor_Direccion  = registro[0]['DIRECCION'];
			xProveedor_Telefono   = registro[0]['TELEFONO'];
			if (!xDias) xDias = 0;
			
			actualizaHTML(xml, 'ENCABEZADO');
			m_proveedores.ocultar();
			CrearLista();
			m_ordenes_compras.xfiltro     = xID_M_PROVEEDORES; 
		break;
		case _supr:
			cancelaTecla(evt);
		break;		

		case _esc:
			cancelaTecla(evt);
			if(!xbandera)
			{
				Salir();
			}
			else
			{
				m_proveedores.ocultar();
				d_documento.setFocus();
			}
			xbandera=null;
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
	}
}

function t_f_proveedores(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
		cancelaTecla(evt);
			ocultar_formulario(f_proveedores);
			m_proveedores.mostrar();
			m_proveedores.setFocus();
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
			cancelaTecla(evt);
			var registro =f_proveedores.submit();
			if(!registro) return;
			var xID_M_PROVEEDORES = registro[0]['ID_M_PROVEEDORES'];
			ocultar_formulario(f_proveedores);
			m_proveedores.buscar(xID_M_PROVEEDORES,'=');
			m_proveedores.mostrar();
			m_proveedores.setFocus();
		break;			
	}
}

function t_f_compra(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _enter:
			cancelaTecla(evt);
		break;
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_compra);
			m_proveedores.mostrar();
			m_proveedores.setFocus();
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
			cancelaTecla(evt);
			xID_M_ALMACENES = f_compra.getValue('ID_M_ALMACENES');
			xFECHA_DOCUMENTO = f_compra.getValue('FECHA_DOCUMENTO');
			xFECHA_RECEPCION = f_compra.getValue('FECHA_RECEPCION');
			if(xFECHA_DOCUMENTO > fecha){
				alert('La fecha del documento es mayor a la fecha actual');
				return;
			}
			if(xFECHA_RECEPCION > fecha){
				alert('La fecha de recepción es mayor a la fecha actual');
				return;
			}
			
			if(xFECHA_RECEPCION < xFECHA_DOCUMENTO){
				alert('La fecha de recepción es menor a la fecha del documento');
				return;
			}			
			
			var val_com=ValidarCompra();
			if(val_com)
			{
				alert('{$t_documento_existe}');
				return false;
			}

			var registro= f_compra.submit(); 
			if(!registro) return;
			xID_M_DOCUMENTOS = registro[0]['ID_X_M_DOCUMENTOS'];
			ocultar_formulario(f_compra);
			CrearLista();
		break;
	}
}

function t_d_documento(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			if(!xID_M_ALMACENES)
			{
				alert('Debe seleccionar un almacén para registrar productos');
				d_documento.setFocus();
				return;
			}
			m_productos.mostrar();
			m_productos.setFocus();
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_D_DOCUMENTOS');
			if(!registro){
				d_documento.setFocus();
				return;
			} 
			xID_D_DOCUMENTOS = registro[0]['ID_D_DOCUMENTOS'];
			if(registro[0]['TIPO_PRODUCTO']=='S')
			{
				f_servicio.limpiar();
				f_servicio.buscar(registro[0]['ID']);
				f_servicio.setValue('CONDICION_CALCULO',null);
				mostrar_formulario(f_servicio);
			}else{
				f_edicion.limpiar();
				f_edicion.buscar(registro[0]['ID']);
				f_edicion.setValue('CONDICION_CALCULO',null);
				mostrar_formulario(f_edicion);
			}
		break;

		
		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml, 'ID_D_DOCUMENTOS');
			if(!registro){
				d_documento.setFocus();
				return;
			}
			var borrar = confirm('{$t_eliminar_registro}');
			if(borrar)
			{
				var url 	= server_path + 'herramientas/utiles/actualizar_registro.php';
				var param 	= 'origen=compras/d_documento&procedimiento=ELIMINAR_ITEM&ID=' + unformat(registro[0]['ID']);
				enviar(url,param,'GET');
				Refrescar();
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
			inicio(1);
			xbandera=true;
		break;
		case _f4:
			cancelaTecla(evt);
		break;
		case _f5:
			cancelaTecla(evt);
			f_descuento.buscar(xID_M_DOCUMENTOS);
			mostrar_formulario(f_descuento);
		break;
		case _f6:
			cancelaTecla(evt);
		break;
		case _f7:
			cancelaTecla(evt);
			f_servicio.limpiar();
			
			f_servicio.setValue('ID_X_M_DOCUMENTOS',xID_M_DOCUMENTOS);
			f_servicio.setValue('TIPO', xTIPO);
			mostrar_formulario(f_servicio);
		break;
		case _f8:
			cancelaTecla(evt);
			m_ordenes_compras.buscar('*');
			m_ordenes_compras.mostrar();
			m_ordenes_compras.setFocus();
		break;
		case _f9:
			cancelaTecla(evt);
			var anular = confirm('{$t_documento_anular}');
			if(anular)
			{
				var url 	= server_path + 'herramientas/utiles/actualizar_registro.php';
				var param 	= 'origen=compras/d_documento&procedimiento=ELIMINAR_DOCUMENTO&ID_X_M_DOCUMENTOS=' + xID_M_DOCUMENTOS;
				enviar(url,param,'GET');
				inicio(0);
			}
		break;
		case _f10:
			cancelaTecla(evt);
			if(d_documento.rows.length)
			{
				f_cierre.limpiar();
				f_cierre.buscar(xID_M_DOCUMENTOS);
				mostrar_formulario(f_cierre);
				if(xcondicion_pago=='CRE')
				{
					f_cierre.setValue('REEMBOLSABLE', xID_M_PROVEEDORES);
					f_cierre.setValue('r_REEMBOLSABLE',xProveedor_Nombre);
				}else{
					f_cierre.ocultarCampo('REEMBOLSABLE');
				}
				f_cierre.setValue('MONTO_DOCUMENTO',f_cierre.getValue('NETO'));
				f_cierre.setValue('NETO',0);
				f_cierre.setValue('ESTATUS','C');
				Calcular_vencimiento();
				f_cierre.setFocus();
			}else{
				alert('{$t_documento_noregistro}');
			}
		break;
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_m_productos(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			xID_M_PRODUCTOS    = '-1';
			f_productos.limpiar();
			armarPlantilla();
			mostrar_formulario(f_productos);
			m_productos.ocultar();
		break;
		
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro){
				m_productos.setFocus();
				return;
			} 
			xID_M_PRODUCTOS = registro[0]['ID_M_PRODUCTOS'];
			m_productos.ocultar();
			f_edicion.limpiar();
			mostrar_formulario(f_edicion);
			f_edicion.setValue('xbusca','-1');
			f_edicion.setValue('ID_M_PRODUCTOS' ,xID_M_PRODUCTOS);
			f_edicion.setValue('DESCRIPCION'    ,registro[0]['DESCRIPCION']);
			f_edicion.setValue('PRESENTACION'   ,registro[0]['PRESENTACION']);
			f_edicion.setValue('COSTO'          ,registro[0]['COSTO']);
			f_edicion.setValue('TIPO'           ,'COM');
			f_edicion.setValue('ID_X_M_DOCUMENTOS',xID_M_DOCUMENTOS);
			f_edicion.setValue('ID_M_IMPUESTOS' ,registro[0]['ID_M_IMPUESTOS2']);
			f_edicion.setValue('ID_M_ALMACENES' ,registro[0]['ID_M_ALMACENES']);
			f_edicion.setValue('ID_I_PROD_ALMA' ,registro[0]['ID_I_PROD_ALMA']);
			f_edicion.setValue('ID_D_I_PROD_ALMA',registro[0]['ID_D_I_PROD_ALMA']);
			f_edicion.setValue('ID_D_PRODUCTOS' ,registro[0]['ID_D_PRODUCTOS']);
			f_edicion.setValue('SERIAL' ,registro[0]['SERIALIZABLE']);
		break;
		case _supr:
			cancelaTecla(evt);
		break;
		
		case _esc:
			cancelaTecla(evt);
			m_productos.ocultar();
			d_documento.setFocus();
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
			var registro = valida_xml(xml,'ID_D_PRODUCTOS');
			if(!registro){
				m_productos.setFocus();
				return;
			}
			f_producto_precio.buscar(registro[0]['ID_D_PRODUCTOS']);
			mostrar_formulario(f_producto_precio);
			m_productos.ocultar();
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

function t_m_ordenes_compras(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
			if(!registro){
				m_ordenes_compras.setFocus();
				return;
			}
			var xref = confirm('¿Desea cargar el documento '+registro[0]['ID_M_DOC_FINAL']+'?');
			if(xref)
			{
				var url = server_path + 'herramientas/utiles/actualizar_registro.php';
				var params = 'tabla=X_M_DOCUMENTOS&busca=ID_X_M_DOCUMENTOS&xbusca='+xID_M_DOCUMENTOS+'&c_DOCUMENTO_CSS=' + registro[0]['ID_M_DOCUMENTOS'] + '&c_CONDICION1_CSS=*';
				var x = enviar(url,params,'POST');
			}
			m_ordenes_compras.ocultar();
			Refrescar();
			d_documento.setFocus();
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			m_ordenes_compras.ocultar();
			d_documento.setFocus();
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
	}
}

function t_f_cierre(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_cierre);
			d_documento.setFocus();
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
		cancelaTecla(evt);
			var val_com=ValidarCompra();
			if(val_com)
			{
				alert('{$t_documento_existe}');
				f_cierre.setFocus();
				return false;
			}		
			
			var xmonto_doc = unformat(f_cierre.getValue('MONTO_DOCUMENTO'));
			var xmonto_fac = unformat(f_cierre.getValue('NETO'));
			var dif = xmonto_doc - xmonto_fac;
			dif = Math.abs(dif);
			if(dif > xrango)
			{
				alert('{$t_error_monto_compras}');
				f_cierre.setFocus();
				return;
			}
			var registro = f_cierre.submit();
			if(!registro){
				alert('Error al cerrar documento');
				return;
			}
			waitExec('{$t_realizando_proceso}', 'CerrarDocumento();', 5, 250, 283);						
			ocultar_formulario(f_cierre);
		break;
	}
}


function t_f_edicion(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;

		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion);
			d_documento.setFocus();
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
			cancelaTecla(evt);
			var xcantidad  = unformat(f_edicion.getValue('CANTIDAD'));
			var xprecio = unformat(f_edicion.getValue('PRECIO'));
			var xmonto = xcantidad*xprecio;
			var registro = f_edicion.submit();
			if(!registro) return;
			xID_D_DOCUMENTOS    = registro[0]['ID_D_DOCUMENTOS'];
			ocultar_formulario(f_edicion);
			waitExec('{$t_realizando_proceso}', 'Refrescar();', 5, 250, 283);
			d_documento.setFocus();
		break;
	}
}

function t_f_servicio(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_servicio);
			d_documento.setFocus();
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
			cancelaTecla(evt);
			var registro = f_servicio.submit();
			if(!registro) return;
			xID_D_DOCUMENTOS = registro[0]['ID_D_DOCUMENTOS'];
			ocultar_formulario(f_servicio);
			waitExec('{$t_realizando_proceso}', 'Refrescar();', 5, 250, 283);
			d_documento.setFocus();
		break;
	}
}

function t_f_descuento(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;
		
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_descuento);
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
			cancelaTecla(evt);
			var registro = f_descuento.submit();
			if(!registro){
				return;
			} 
			ocultar_formulario(f_descuento);
			waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
			d_documento.setFocus();
		break;
	}
}

function t_f_productos(obj, tecla, evt, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;	
		
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_productos);
			
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
			cancelaTecla(evt);
			t_f_productos2('',_f12);
		break;
	}
}

function t_f_productos2(obj, tecla, evt, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;	
		
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_productos);
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
			cancelaTecla(evt);
			var registro = f_productos2.submit();
			if(!registro) return false;
			xID_M_PRODUCTOS = registro[0]['ID_M_PRODUCTOS'];
			ocultar_formulario(f_productos);
			m_productos.mostrar();
			m_productos.setFocus();
			m_productos.buscar(xID_M_PRODUCTOS);
		break;
	}
}

function t_f_producto_precio(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
		break;
		
		case _supr:
			cancelaTecla(evt);
		break;	
		
		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_producto_precio,m_productos);
			m_productos.mostrar();
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
			cancelaTecla(evt);
			var accion = f_producto_precio.getValue('xbusca');
			var registro = f_producto_precio.submit();
			if(!registro)return;
			if(accion!='-1')
			{
				m_productos.actualizar(accion);
			}else{
				m_productos.buscar();
			} 
			ocultar_formulario(f_producto_precio,m_productos);
			m_productos.mostrar();
			m_productos.setFocus();
		break;
	}
}

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
	Cerrar_formulario();
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{	 
	console.clear();
	contenedor.inicializa(false);
	centrarObj(contenedor.contenedor);

	d_documento.inicializa(false);
	d_documento.mostrar();

	m_ordenes_compras.inicializa(false);
	centrarObj(m_ordenes_compras.contenedor);

	m_proveedores.inicializa(false);
	centrarObj(m_proveedores.contenedor);

	m_productos.inicializa(false);
	centrarObj(m_productos.contenedor);

	f_productos.inicializa();
	addEvent(frm_f_productos.xID_M_P_DESCRIPCION, 'change', armarPlantilla);
	f_productos2.inicializa();

	f_edicion.inicializa(false);
	f_servicio.inicializa(false);
	f_compra.inicializa(false);
	f_cierre.inicializa(false);
	f_descuento.inicializa(false);
	f_proveedores.inicializa(false);
	f_producto_precio.inicializa(false);

	addEvent(m_proveedores_ENTER,  "click",   function() { t_m_proveedores('', _enter, m_proveedores.elementoXml()) } );
	addEvent(m_proveedores_ESC,    "click",   function() { t_m_proveedores('', _esc,   m_proveedores.elementoXml()) } );
	addEvent(m_proveedores_INS,    "click",   function() { t_m_proveedores('', _insert,m_proveedores.elementoXml()) } );

	addEvent(ENTER,  "click",   function() { t_d_documento('', _enter,  d_documento.elementoXml()) } );
	addEvent(SUP,    "click",   function() { t_d_documento('', _supr,   d_documento.elementoXml()) } );
	addEvent(ESC,    "click",   function() { t_d_documento('', _esc,    d_documento.elementoXml()) } );
	addEvent(F3,     "click",   function() { t_d_documento('', _f3,     d_documento.elementoXml()) } );
	addEvent(INS,    "click",   function() { t_d_documento('', _insert, d_documento.elementoXml()) } );
	addEvent(F7,     "click",   function() { t_d_documento('', _f7,     d_documento.elementoXml()) } );
	addEvent(F5,     "click",   function() { t_d_documento('', _f5,     d_documento.elementoXml()) } );
	addEvent(F8,     "click",   function() { t_d_documento('', _f8,     d_documento.elementoXml()) } );
	addEvent(F9,     "click",   function() { t_d_documento('', _f9,     d_documento.elementoXml()) } );
	addEvent(F10,    "click",   function() { t_d_documento('', _f10,    d_documento.elementoXml()) } );

	addEvent(m_productos_INS,   "click",   function() { t_m_productos('',_insert, m_productos.elementoXml()) } );
	addEvent(m_productos_F7,    "click",   function() { t_m_productos('', _f7, m_productos.elementoXml()) } );
	addEvent(m_productos_ENTER, "click",   function() { t_m_productos('', _enter, m_productos.elementoXml()) } );
	addEvent(m_productos_ESC,   "click",   function() { t_m_productos('', _esc,   m_productos.elementoXml()) } );

	addEvent(m_ordenes_compras_ENTER, "click",   function() { t_m_ordenes_compras('', _enter, m_ordenes_compras.elementoXml()) } );
	addEvent(m_ordenes_compras_ESC,   "click",   function() { t_m_ordenes_compras('', _esc,   m_ordenes_compras.elementoXml()) } );

	addEvent(frm_f_compra.FECHA_RECEPCION  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_compra.FECHA_RECEPCION  , 'change', Calcular_vencimiento);
	addEvent(frm_f_compra.FECHA_RECEPCION  , 'keyup',  Calcular_vencimiento);

	addEvent(frm_f_compra.CREDITO  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_compra.CREDITO  , 'change', Calcular_vencimiento);
	addEvent(frm_f_compra.CREDITO  , 'keyup',  Calcular_vencimiento);

	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'change', Calcular_vencimiento);
	addEvent(frm_f_cierre.FECHA_RECEPCION  , 'keyup',  Calcular_vencimiento);

	addEvent(frm_f_cierre.CREDITO  , 'blur',   Calcular_vencimiento);
	addEvent(frm_f_cierre.CREDITO  , 'change', Calcular_vencimiento);
	addEvent(frm_f_cierre.CREDITO  , 'keyup',         Calcular_vencimiento);

	document.onclick = function() { if (parent.menu) parent.menu.reset(); }

	return true;
}

function inicio(recargar)
{
	xID_M_ALMACENES = xid_m_almacen_estacion;
	if(recargar=='') recargar=0;
	switch(recargar)
	{
		case 0:
			xID_D_DOCUMENTOS = '';
			xID_M_DOCUMENTOS = '';
			m_proveedores.mostrar();
			m_proveedores.setFocus();
			d_documento.limpiar();
			limpiarElementos('ENCABEZADO,PIE');
		break;
		case 1:
			m_proveedores.mostrar();
			m_proveedores.setFocus();
		break;
		case 2:
		break;
	}
}

var resp = iniciar();

if(!resp)
{
	Salir();
}else{
	inicio(0);
}

function Guardar_formulario()
{
	switch (f_activo.nombre)
	{
		case 'f_edicion':
			t_f_edicion('',_f12);
		break;
		
		case 'f_producto_precio':
			t_f_producto_precio('',_f12);
		break;

		case 'f_servicio':
			t_f_servicio('',_f12);
		break;

		case 'f_compra':
			t_f_compra('',_f12);
		break;

		case 'f_cierre':
			t_f_cierre('',_f12);
		break;

		case 'f_descuento':
			t_f_descuento('',_f12);
		break;

		case 'f_proveedores':
			t_f_proveedores('',_f12);
		break;
		
		case 'f_productos':
		case 'f_productos':
			t_f_productos2('',_f12);
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

		case 'f_producto_precio':
			t_f_producto_precio('',_esc);
		break;

		case 'f_servicio':
			t_f_servicio('',_esc);
		break;

		case 'f_compra':
			t_f_compra('',_esc);
		break;

		case 'f_cierre':
			t_f_cierre('',_esc);
		break;

		case 'f_descuento':
			t_f_descuento('',_esc);
		break;

		case 'f_proveedores':
			t_f_proveedores('',_esc);
		break;

		case 'f_productos':
		case 'f_productos2':
			t_f_productos2('',_esc);
		break;
	}
}

function CrearLista()
{
	$('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').css("padding-top","0");
	$('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').css("padding-bottom","0");
	$('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').empty();
	$('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').append('<div style="width:100%;text-align:center"><select id="x_m_documentos_abiertos" class="campo_activo"></select></div>');
	$('#x_m_documentos_abiertos').append($('<option></option>').val('-1').html('-- Nuevo --'));

	$('#x_m_documentos_abiertos').change(function()
	{
		if (t1) window.clearTimeout(t1);
		t1 = setTimeout('Abrir_Documento();',500);
	});

	$('#x_m_documentos_abiertos').keydown(function(event) {
		if (event.keyCode == '13')
		{
			Refrescar();
		}
		if (event.keyCode == '117')
		{
			cancelaTecla(event);
			t_d_documento('',117);
		}
	});

	var url = server_path + "herramientas/genera_xml/genera_xml.php";
	var param =  "tabla=X_M_DOCUMENTOS&operador==&campos=ID_X_M_DOCUMENTOS,ID_M_DOC_FINAL,ID_M_ALMACENES&busca=ID_M_PROVEEDORES&xbusca=" + xID_M_PROVEEDORES + "&filtro=ESTATUS;TIPO&xfiltro=A;"+ xTIPO;
	var x = enviar(url,param,'POST');
	var registro = valida_xml(x, 'ID_X_M_DOCUMENTOS');
	if(!registro)	  
	{
		$("#x_m_documentos_abiertos option[value=-1]").attr("selected",true);
/* Benjamín - Dejo el almacén fijo en almacén principal - version Demo24 */		
		xID_M_ALMACENES='0011'; 
	}
	else{
/* Benjamín - Dejo el almacén fijo en almacén principal - version Demo24 */		
/*		xID_M_ALMACENES = registro[0]['ID_M_ALMACENES']; */
		xID_M_ALMACENES='0011';  

		var turl = server_path + "herramientas/genera_xml/genera_xml.php";
		var tparam =  "tabla=M_ALMACENES&operador==&campos=ID_M_ALMACENES,NOMBRES&busca=ID_M_ALMACENES&xbusca=" + xID_M_ALMACENES + "&filtro=ESTATUS&xfiltro=ACT";
		var tx = enviar(turl,tparam,'POST'); 
		var tregistro = valida_xml(tx, 'ID_M_ALMACENES');
		if(tregistro)	  
		{
			xNOMBRE_ALMACEN = tregistro[0]['NOMBRES'];
			actualizaTag('ENCABEZADONOMBRE_ALMACEN',xNOMBRE_ALMACEN);
			
		}
	}

	for (i=0;i<registro.length;i++)
	{
		$('#x_m_documentos_abiertos').append($('<option></option>').val(registro[i]['ID_X_M_DOCUMENTOS']).html(registro[i]['ID_M_DOC_FINAL']));
	}
	
	$('#x_m_documentos_abiertos :nth-child(2)').prop('selected', true);
	
	if(xID_M_DOCUMENTOS)
	{
		$("#x_m_documentos_abiertos option[value="+  xID_M_DOCUMENTOS +"]").attr("selected",true);
	}
	setTimeout('FocusLista();',10)
	Abrir_Documento();
}


function ValidarCompra()
{
	var xDOC_FINAL = f_activo.getValue('ID_M_DOC_FINAL');
	if(!xDOC_FINAL) return false;
	var url   = server_path + "herramientas/genera_xml/genera_xml.php";
	var param = "tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS&operador==&busca=ID_M_DOC_FINAL&xbusca="+ xDOC_FINAL+'&filtro=ID_M_PROVEEDORES;TIPO&xfiltro=' + xID_M_PROVEEDORES + ';COM';
	var x     = enviar(url,param,'POST');
	var registro = valida_xml(x,'ID_M_DOCUMENTOS');
	if(!registro){
		return false;
	}else{
		return true;
	}
}

function Abrir_Documento()
{
	if($('#x_m_documentos_abiertos').val()=='-1')
	{
		var resp = confirm('{$t_crear_documento}');
		if(resp)
		{
			f_compra.limpiar();
			f_compra.setValue('ID_M_PROVEEDORES' , xID_M_PROVEEDORES);
			f_compra.setValue('FECHA_DOCUMENTO'  , xFecha_Documento);
			f_compra.setValue('FECHA_RECEPCION'  , xFecha_Recepcion);
			f_compra.setValue('CODIGO1'       , xProveedor_Codigo1);
			f_compra.setValue('CODIGO2'       , xProveedor_Codigo2);
			f_compra.setValue('NOMBRES'       , xProveedor_Nombre);
			f_compra.setValue('DIRECCION'     , xProveedor_Direccion);
			f_compra.setValue('TELEFONO'      , xProveedor_Telefono);
			f_compra.setValue('CREDITO'       , xDias);
			f_compra.setValue('CONDICION_PAGO',xcondicion_pago);
			f_compra.setValue('xbusca'        , '-1');
			f_compra.setValue('TIPO'          , 'COM');
			f_compra.setValue('ID_M_ALMACENES',xID_M_ALMACENES);
			f_compra.ocultarCampo('MONTO_DOCUMENTO');
			f_compra.ocultarCampo('MONTO_FACTURA');
			f_compra.ocultarCampo('ID_M_ALMACENES');
			if(!xID_M_ALMACENES)
			{
				f_compra.mostrarCampo('ID_M_ALMACENES');
			}
			mostrar_formulario(f_compra);
			Calcular_vencimiento();
		}else{
			var opciones = $("#x_m_documentos_abiertos").find("option").length;
			if(opciones<=1)
			{
				setTimeout('inicio(0);',100);
				return;
			}else{
				$("#x_m_documentos_abiertos option[value="+  xID_M_DOCUMENTOS +"]").attr("selected",true);
				Refrescar();
			}
		}
	}else{
		xID_M_DOCUMENTOS = $('#x_m_documentos_abiertos').val();
		waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
		d_documento.setFocus();
	}
}


function Imprimir(xid)
{
	impresora.origin = 'reportes/r_documento_com';
	impresora.setParam('ID_M_DOCUMENTOS',xid);
	impresora.showDialog=true;
	impresora.preview();
	inicio(0);
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
    setTimeout('f_activo.setFocus();',100);
}

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
    d_documento.setFocus();
}

function actualizaPie()
{
    actualizaHTML(d_documento.elementoXml(),'PIED_');
}

function Calcular_vencimiento()
{
	var xfecha = sumaDias(f_activo.getValue('FECHA_RECEPCION'),f_activo.getValue('CREDITO'));
	if(xfecha)
	{
		f_activo.setValue('VENCE',xfecha);
	}
}

function FocusLista()
{
    $('#x_m_documentos_abiertos').focus();
}

function Refrescar()
{
	d_documento.xfiltro = xID_M_DOCUMENTOS;
	d_documento.buscar('*');
	d_documento.setFocus();
	
	var url   = server_path + "herramientas/genera_xml/genera_xml.php";
	var param = "tabla=V_X_M_DOCUMENTOS&campos=*&operador==&busca=ID_X_M_DOCUMENTOS&xbusca="+ xID_M_DOCUMENTOS;
	var xml     = enviar(url,param,'POST');
	var registro = valida_xml(xml,'ID_X_M_DOCUMENTOS');	

	actualizaHTML(xml, 'PIE', 'totales');
	if(xID_D_DOCUMENTOS) d_documento.localiza('ID_D_DOCUMENTOS',xID_D_DOCUMENTOS);
	xID_D_DOCUMENTOS=null;
}

function armarPlantilla()
{
	if (f_productos2.cuadro.childNodes.length > 0)
	{
		borraHijos(f_productos2.cuadro);
	}
	origen = f_productos.getValue('xID_M_P_DESCRIPCION');
	if(!origen) return;
	f_productos2.destruir();
	f_productos2.origen = "maestro_productos/f_" + origen;
	f_productos2.inicializa();
	f_productos2.limpiar();
	f_productos2.mostrar();
	if(xID_M_PRODUCTOS !='-1')
	{
		f_productos2.buscar(xID_M_PRODUCTOS);
	}

	f_productos2.setValue('ID_M_PRODUCTOS' , xID_M_PRODUCTOS);
	f_productos2.setValue('ID_M_P_DESCRIPCION', origen);
	f_productos2.setValue('CAMPO5', '*');
}

function CerrarDocumento(){
	var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
	var param = "tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS&operador==&busca=ID_X_M_DOCUMENTOS&xbusca="+ xID_M_DOCUMENTOS;
	var xml     = enviar(url,param,'POST');
	var registro = valida_xml(xml,'ID_M_DOCUMENTOS');	

	ocultar_formulario(f_cierre);
	inicio(0);
	
}

</script>


</body>
</html>

EOT;

?>