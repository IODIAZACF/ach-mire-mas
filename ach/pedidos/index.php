<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha                 = date("d/m/Y");

// Se determinan los datos de la caja en función del maestro de cajas
$query = new sql();
$query->sql = "SELECT * FROM M_CAJAS WHERE IP='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas 	 		= $query->Record['ID_M_CAJAS'];
$xnombre_caja			= $query->Record['NOMBRES'];
$tmp_data 				= explode('_', $query->Record['CAMPO1']);


// Si no hay caja asignada a este equipo se toma la caja por defecto (Registro control en M_CAJAS)
if(!$xid_m_cajas){
	$xid_m_cajas='XXXX';
	$query = new sql();
	$query->sql = "SELECT * FROM M_CAJAS WHERE ID_M_CAJAS='". $xid_m_cajas . "'";
	$query->ejecuta_query();
	$query->next_record();
	$xnombre_caja = $query->Record['NOMBRES'];
}

// Se identifica el almacén de venta asignado al equipo en el mestro de equipos
$xID_M_ALMACENES  		= getsession('M_ESTACIONES_ID_M_ALMACENES_VENTAS');

// Si no hay almacén de venta asignado a este equipo se toma el almacén de venta por defecto (Almacén principal en M_ALMACENES)
if(!$xID_M_ALMACENES){
	$xID_M_ALMACENES='0011';
}

// Se determina el nombre del almacén
$query = new sql();
$query->sql = "SELECT * FROM M_ALMACENES WHERE ID_M_ALMACENES='". $xID_M_ALMACENES . "'";
$query->ejecuta_query();
$query->next_record();
$xnombre_almacen = $query->Record['NOMBRES'];

// Se determinan los datos del cliente de control (Contado)
$query = new sql();
$query->sql = "SELECT * FROM M_CLIENTES WHERE ID_M_CLIENTES='XXXX'";
$query->ejecuta_query();
$query->next_record();

$xRAZON 	= $query->Record['RAZON'];
$xCODIGO1 	= $query->Record['CODIGO1'];
$xDIRECCION	= $query->Record['DIRECCION'];
$xTELEFONO1 = $query->Record['TELEFONO1'];
$xCORREO1   = $query->Record['CORREO1'];

$my_ini     = new ini('modulo');

encabezado( $my_ini->seccion('VENTANA','TITULO') );

$onClose = 'Salir();';

$modulo  = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga(); parent.menu.reset();">' . "\n";

cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<table border="0">
	<tr valign="top">
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td id="DETALLES">
					</td>
				</tr>
				<tr>
					<td id="EXTRA" class="" style="padding-top: 4px;">
					</td>
				</tr>
			</table>
		</td>
		<td id="IMAGEN" class="grid_cuadro_inactivo" >
			<table border="0" width="100%">
				<tr>
					<td align="center" style="width:310px; height:250px; padding: 5px;">
						<img id="FOTO" name="foto" src="" onerror="sin_imagen(this)" width="290px">
					</td>
				</tr>
				<tr>
					<td align="center" class="grid_cuadro_activo">
						<hr>
						<span id="FOTO_PRECIO" style="font-size: 25px; color: #ff0000"></span>
						<hr>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

{$modulo->fin}

<script type="text/javascript">

var ultimo_item;
var xfecha  			= '{$xfecha}';
var xID_M_PEDIDOS	= null;
var xID_M_CAJAS 		= '{$xid_m_cajas}'; 
var xID_M_ALMACENES		= '{$xID_M_ALMACENES}';
var xNOMBRE_CAJA		= '{$xnombre_caja}';
var xNOMBRE_ALMACEN		= '{$xnombre_almacen}';

var xID_M_CLIENTES		= 'XXXX';
var xRAZON 	 			= '{$xRAZON}';
var xCODIGO1 			= '{$xCODIGO1}';
var xDIRECCION			= '{$xDIRECCION}';
var xTELEFONO1 			= '{$xTELEFONO1}';
var xCORREO1   			= '{$xCORREO1}';

var xDisponibles		= 0;
var xcredito_anterior 	= 0;

//----variables de formas de pago-----//
{$frm_campos}
{$frm_cuentas}
//------------------------------------//

var xTITULAR                = '';
var xCODIGO1                = '';
var xTELEFONOS              = '';
var xID_M_BANCOS            = '';
var xNUMERO                 = '';
var xCUENTA                 = '';
var xID_M_ANTICIPOS         = '';
var xID_M_CUENTAS_BANCARIAS = '';

var xmonto_factura      = 0;
var xmonto_pagado       = 0;
var xmonto_saldo        = 0;
var tope                = 1;
var xvalor              = 0;

var mask                = new Mask('#,###.##', 'number');

//*************************************//

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 100;
contenedor.alto        = 100;
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

d_pedidos              = new lista('pedidos/d_pedidos');
d_pedidos.nombre       = 'd_pedidos';
d_pedidos.url          = server_path + 'herramientas/genera_xml/genera_xml.php';
d_pedidos.usaCookie    = false;
d_pedidos.funcion      = t_d_pedidos;
d_pedidos.padre        = "DETALLES";
d_pedidos.buscador     = true;
d_pedidos.onSelect     = ver_imagen;
d_pedidos.onFocus      = d_pedidosFocus;
d_pedidos.filtro       = 'ID_M_PEDIDOS';

m_productos             = new lista("pedidos/d_i_prod_almacen_facturables")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.buscador    = true;
m_productos.x           = 1;
m_productos.y           = 1;
m_productos.filtro      ='ID_M_ALMACENES';
m_productos.xfiltro     = xID_M_ALMACENES;
m_productos.modal       = true;
m_productos.botonCerrar = true;
m_productos.enter       = 2;

m_servicios             = new lista("pedidos/m_servicios")
m_servicios.nombre      = "m_servicios";
m_servicios.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_servicios.funcion     = t_m_servicios;
m_servicios.buscador    = true;
m_servicios.x           = 1;
m_servicios.y           = 1;
m_servicios.modal       = true;
m_servicios.botonCerrar = true;
m_servicios.enter       = 1;

f_edicion = new formulario2('pedidos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'EXTRA';
f_edicion.tipo         = 'formulario';

f_edicion2              = new formulario2('pedidos/f_edicion2');
f_edicion2.nombre       = 'f_edicion2';
f_edicion2.funcion      = t_f_edicion2;
f_edicion2.padre        = 'contenedor_cuadro';
f_edicion2.tipo         = 'formulario';
f_edicion2.onExit       = function(){ t_f_edicion2('',_f12);};

f_servicios_xxxx              = new formulario2('pedidos/f_servicios_xxxx');
f_servicios_xxxx.nombre       = 'f_servicios_xxxx';
f_servicios_xxxx.funcion      = t_f_servicios_xxxx;
f_servicios_xxxx.padre        = 'contenedor_cuadro';
f_servicios_xxxx.tipo         = 'formulario';
f_servicios_xxxx.onExit       = function(){ f_servicios_xxxx('',_f12);};

f_cierre               = new formulario2('pedidos/f_cierre');
f_cierre.nombre        = 'f_cierre';
f_cierre.funcion       = t_f_cierre;
f_cierre.padre         = 'contenedor_cuadro';
f_cierre.tipo          = 'formulario';

var resp = iniciar();
if(!resp)
{
	Salir();
}
else
{
	inicio();
}

function t_d_pedidos(objeto, tecla, xml, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
			m_productos.mostrar();
			m_productos.setFocus();
		break;

		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PEDIDOS');
			if(!registro){
				d_pedidos.setFocus();
				return;
			}
			xtipoprod     = registro[0]['TIPO_PRODUCTO'];
			xproducto     = registro[0]['ID_M_PRODUCTOS'];
			
			if(registro[0]['ID_M_PRODUCTOS']=='XXXX'){
				f_servicios_xxxx.buscar(registro[0]['ID_D_PEDIDOS']);
				mostrar_formulario(f_servicios_xxxx);				
			}else{
				f_edicion2.buscar(registro[0]['ID_D_PEDIDOS']);
				mostrar_formulario(f_edicion2);				
			}
		break;

		case _supr:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PEDIDOS');
			if(!registro) return;

			if(confirm('{$t_eliminar_registro}'))
			{
				var url   = server_path + "herramientas/utiles/actualizar_registro.php";
				var param = "origen=pedidos/d_pedidos&procedimiento=ELIMINAR_ITEM&ID_D_PEDIDOS=" + registro[0]['ID_D_PEDIDOS'];
				var x     = enviar(url,param,'POST');
			}
			d_pedidos.buscar('*');
		break;

		case _esc:
			cancelaTecla(evt);
			f_edicion.setFocus(); 
		break;

		case _f1:
		case _f2:
			cancelaTecla(evt);
		break;

		case _f3:
			cancelaTecla(evt);
			m_usuarios.mostrar();
			m_usuarios.setFocus();
		break;

		case _f4:
		case _f5:
			cancelaTecla(evt);
		break;

		case _f6:
			cancelaTecla(evt);
		break;

		case _f7:
			cancelaTecla(evt);
			m_servicios.buscar('*');
			m_servicios.mostrar();
			m_servicios.setFocus();
		break;
		case _f8:
			cancelaTecla(evt);
		break;
		case _f9:
			cancelaTecla(evt);
			var anular = confirm('{$t_documento_anular}');
			if(!anular){ 
				f_edicion.setFocus();
				return;				
			}
			
				var url = server_path + 'herramientas/utiles/actualizar_registro.php';
				var param =  "origen=pedidos/d_pedidos&procedimiento=ELIMINAR_DETALLE_PEDIDO&ID_M_PEDIDOS=" + xID_M_PEDIDOS;
				
				var xml = enviar(url,param,'POST');

				var resp = controlError(xml);
				if(!resp) return;			
				
				inicio(0);
		break;
		case _f10:
			cancelaTecla(evt);
			if(d_pedidos.rows.length>0){
				Refrescar();
					f_cierre.limpiar();
					f_cierre.buscar(xID_M_PEDIDOS);
					mostrar_formulario(f_cierre);					
				} else{
					alert('{$t_documento_noregistro}');
					return;
			}
			break;
		case _f11:
		case _f12:
			cancelaTecla(evt);
		break;
	}
}

function t_m_productos(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		case _enter:
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_M_PRODUCTOS');
			if(!registro){
				m_productos.setFocus();
				return;
			}
			if(unformat(registro[0]['DISPONIBLES'])<=0){
				alert('No se permite facturar productos sin existencia');
				return;
			}
			
			m_productos.ocultar();
			f_edicion2.limpiar();
			xproducto     	= registro[0]['ID_M_PRODUCTOS'];
			xpresentacion 	= registro[0]['PRESENTACION'];
			xid_m_lineas 	= registro[0]['ID_M_LINEAS'];
			xDisponibles  	= unformat(registro[0]['DISPONIBLES']);
			
			f_edicion2.setValue('ID_M_PRODUCTOS' , xproducto);
			f_edicion2.setValue('ID_M_ALMACENES' , registro[0]['ID_M_ALMACENES']);
			f_edicion2.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
			f_edicion2.setValue('ID_D_PRODUCTOS' , registro[0]['ID_D_PRODUCTOS']);
			f_edicion2.setValue('ID_I_PROD_ALMA' , registro[0]['ID_I_PROD_ALMA']);
			f_edicion2.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
			f_edicion2.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
			f_edicion2.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
			f_edicion2.setValue('COSTO'          , registro[0]['COSTO']);
			f_edicion2.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
			f_edicion2.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
			f_edicion2.setValue('ID_D_I_PROD_ALMA', registro[0]['ID_D_I_PROD_ALMA']);
			f_edicion2.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
			f_edicion2.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);
			
			// Evaluar solucion para la oferta Benjamin
			// Debería evaluarse a la luz de las politicas del cliente
			// Hay campos de Oferta Cantidad y Oferta Desde - Hasta
			
			f_edicion2.setValue('PRECIO'    	 , registro[0]['PRECIO1']);
			f_edicion2.setValue('PRESENTACION'   , xpresentacion);
			f_edicion2.setValue('xbusca'         , '-1');
			f_edicion2.setValue('CANTIDAD'       , 1);
			f_edicion2.setValue('ID_M_PEDIDOS', xID_M_PEDIDOS);
			f_edicion2.setValue('TIPO'           , 'FAC');
			xtipoprod ='P';
				  
			f_edicion2.activarCampo('CANTIDAD');
			mostrar_formulario(f_edicion2);
		break;

		case _supr:
			cancelaTecla(evt);
		break;		
		case _esc:
			m_productos.ocultar();
			f_edicion.setFocus();
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

function t_m_servicios(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla){
		case _insert:
			cancelaTecla(evt);
		break;
		
		case _enter:
			cancelaTecla(evt);
			var registro   = valida_xml(xml,'ID_M_SERVICIOS');
			if(!registro){
				m_servicios.setFocus();
				return;
			}
			m_servicios.ocultar();
			xproducto      = registro[0]['ID_M_SERVICIOS'];
			xtipoprod      = 'S';
			if(registro[0]['ID_M_SERVICIOS']=='XXXX'){
				f_servicios_xxxx.limpiar();
				f_servicios_xxxx.setValue('ID_M_PRODUCTOS' , registro[0]['ID_M_SERVICIOS']);
				f_servicios_xxxx.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
				f_servicios_xxxx.setValue('ID_I_PROD_ALMA' , registro[0]['ID_I_PROD_ALMA']);
				f_servicios_xxxx.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
				f_servicios_xxxx.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
				f_servicios_xxxx.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
				f_servicios_xxxx.setValue('COSTO'          , registro[0]['COSTO']);
				f_servicios_xxxx.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
				f_servicios_xxxx.setValue('COMISION'       , registro[0]['MONTO_COMISION1']);
				f_servicios_xxxx.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
				f_servicios_xxxx.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
				f_servicios_xxxx.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);
				f_servicios_xxxx.setValue('PRECIO'			, registro[0]['PRECIO1'] );

				f_servicios_xxxx.setValue('xbusca'         , '-1');
				f_servicios_xxxx.setValue('ID_M_PEDIDOS', xID_M_PEDIDOS);
				f_servicios_xxxx.setValue('TIPO'             , 'FAC');
				f_servicios_xxxx.setValue('CANTIDAD'         , 1);
				f_servicios_xxxx.setValue('DESCUENTO'        , 0);
				mostrar_formulario(f_servicios_xxxx);		  
			}else{	  
				f_edicion2.limpiar();
				f_edicion2.setValue('ID_M_PRODUCTOS' , registro[0]['ID_M_SERVICIOS']);
				f_edicion2.setValue('DESCRIPCION'    , registro[0]['DESCRIPCION']);
				f_edicion2.setValue('IMPUESTO'       , registro[0]['IMPUESTO']);
				f_edicion2.setValue('ID_M_IMPUESTOS' , registro[0]['ID_M_IMPUESTOS']);
				f_edicion2.setValue('NOMBRE_IMPUESTO', registro[0]['NOMBRE_IMPUESTO']);
				f_edicion2.setValue('COSTO'          , registro[0]['COSTO']);
				f_edicion2.setValue('COSTO_PROMEDIO' , registro[0]['COSTO_PROMEDIO']);
				f_edicion2.setValue('COMISION'       , registro[0]['MONTO_COMISION1']);
				f_edicion2.setValue('TIPO_PRODUCTO'  , registro[0]['TIPO']);
				f_edicion2.setValue('CLASE_PRODUCTO' , registro[0]['TIPO']);
				f_edicion2.setValue('COMENTARIOS'    , registro[0]['PREPARACION']);

				f_edicion2.setValue('xbusca'         , '-1');
				f_edicion2.setValue('ID_M_PEDIDOS', xID_M_PEDIDOS);
				f_edicion2.setValue('TIPO'             , 'FAC');
				f_edicion2.setValue('CANTIDAD'         , 1);
				f_edicion2.setValue('DESCUENTO'        , 0);
				f_edicion2.setValue('PRECIO'			, registro[0]['PRECIO1'] );

				mostrar_formulario(f_edicion2);
			}
		break;
		case _supr:
			cancelaTecla(evt);
		break;
		
		case _esc:
			cancelaTecla(evt);
			m_servicios.ocultar();
			f_edicion.setFocus();
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


function t_f_servicios_xxxx(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
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
			ocultar_formulario(f_servicios_xxxx);
			f_edicion.setFocus();
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
			var registro = f_servicios_xxxx.submit();
			if(registro){
				var indice = registro[0]['UNICO'];
				Refrescar();
				ocultar_formulario(f_servicios_xxxx);
				d_pedidos.localiza('ID_M_PRODUCTOS',xproducto);
				d_pedidos.setFocus();
			}
		break;
	}
}

function t_f_edicion(elemento, tecla,e){
	var evt = window.event || e;
	switch (tecla){
		case _enter:
			cancelaTecla(evt);
			
			if(elemento.id=='CODIGO1' && f_edicion.getValue("CODIGO1")=='') {
				f_edicion.setFocus();
				return;
			}
			
			if(elemento.id=='CODIGO1' && f_edicion.getValue("CODIGO1")!=''){
				var xbusca = f_edicion.getValue("CODIGO1");
				
				buscar_producto(xbusca, function(registro){
	
					if(!registro) {
						alert('Producto no encontrado');
						f_edicion.setFocus();
						return;
					}
					
					f_edicion.limpiar();

					for (var campo in registro[0]){
						f_edicion.setValue(campo, registro[0][campo]);
					}

					f_edicion.setValue('NETO'            , registro[0]['NETO1']);
					f_edicion.setValue('PRECIO'          , registro[0]['PRECIO1']);

					f_edicion.setValue('CANTIDAD'         , 1);
					f_edicion.setValue('COSTO'            , registro[0]['COSTO']);
					f_edicion.setValue('COSTO_PROMEDIO'   , registro[0]['COSTO_PROMEDIO']);
					f_edicion.setValue('ID_M_PEDIDOS', xID_M_PEDIDOS);
					f_edicion.setValue('TIPO'             , 'FAC');
					f_edicion.setValue('xbusca'           , '-1');
					f_edicion.setValue('CODIGO1'           , xbusca);

					$("#frm_f_edicion #CANTIDAD").focus();
					$("#frm_f_edicion #CANTIDAD").select();
				});
				
				
			}
			
			if(elemento.id=='CANTIDAD' && f_edicion.getValue("DESCRIPCION")!=''){
				Guardar_item(f_edicion, function(){
					f_edicion.limpiar();
					f_edicion.setFocus();
				});
			}
		break;
		
		case _esc:
			cancelaTecla(evt);
			Salir();
		break;
		case 40: // 
			cancelaTecla(evt);
			d_pedidos.localiza('ID_D_PEDIDOS', ultimo_item);
			d_pedidos.setFocus();
		break;
		case 41: // flecha abajo
			cancelaTecla(evt);
			d_pedidos.localiza('ID_D_PEDIDOS', ultimo_item);
			d_pedidos.setFocus();
		break;
		case _insert:
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
			t_d_pedidos('', _f10);			
		break;
		case _f11:
		break;
		case _f12:
			cancelaTecla(evt);
			Guardar_item(f_edicion);
		break;
	}
}

function t_f_edicion2(objeto, tecla, xml, e){
	var evt = window.event || e;
	switch (tecla){
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
			ocultar_formulario(f_edicion2);
			f_edicion.setFocus();
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
			var xdescuento = unformat(f_edicion2.getValue('DESCUENTO'));
			var ximpuesto  = unformat(f_edicion2.getValue('IMPUESTO'));
			var xprecio    = unformat(f_edicion2.getValue('PRECIO'));
			var xcantidad  = unformat(f_edicion2.getValue('CANTIDAD'));
			var xcosto     = unformat(f_edicion2.getValue('COSTO_PROMEDIO'));

			var url     = server_path + "herramientas/genera_xml/genera_xml.php";
			var param 	= "tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=DISPONIBLES&busca=ID_D_I_PROD_ALMA&operador==&xbusca="+ f_edicion2.getValue('ID_D_I_PROD_ALMA');
			var x       = enviar(url,param,'POST');
						
			var xreg  	= valida_xml(x,'DISPONIBLES');
			xTIPO_PRODUCTO = f_edicion2.getValue('TIPO_PRODUCTO');
			if(xTIPO_PRODUCTO!='S'){
				xDisponibles  	= unformat(xreg[0]['DISPONIBLES']);
			
				if((xDisponibles<xcantidad) && (xtipoprod =='P')){
					alert('La cantidad ingresada '+ xcantidad +' es mayor a cantidad disponible ' + xDisponibles);
					return false;	
				}
			}	
			if((xdescuento>0) && (xtipoprod =='P')){
				xdescuento        = (Math.abs(xdescuento) / 100) + 1;
				ximpuesto         = (ximpuesto / 100) + 1;
				var xcosto_iva    = xcosto;// * ximpuesto;
				var xprecio_des   = xprecio / xdescuento;
				if(xprecio_des < xcosto_iva){
					alert('Descuento No permitido');
					return;
				}
			}
			var registro = f_edicion2.submit();
			if(registro){
				var indice = registro[0]['UNICO'];
				Refrescar();
				ocultar_formulario(f_edicion2);
				d_pedidos.localiza("ID_M_PRODUCTOS",xproducto);
				f_edicion.setFocus();
			}
		 break;
	}
}

function t_f_cierre(objeto, tecla, xml,e){
	var evt = window.event || e;
	switch (tecla){
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
			ocultar_formulario(f_cierre,d_pedidos);
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
			var xtipo  = f_cierre.getValue('SRI_TIPO');
			var xcodigo1  = f_cierre.getValue('CODIGO1');
			if(xtipo=='07'){
				//CONSUMIDOR FINAL
				if(xcodigo1!='9999999999999'){
					alert('Ruc invalido para tipo de cliente CONSUMIDOR FINAL');
					f_cierre.setFocus();
					return;
				}
				if(xmonto_factura>=200){
					alert('El monto de la factura ' + xmonto_factura + ' supera los 200$ permitido para este tipo de cliente');
					f_cierre.setFocus();
					return;
				}
			}
			if(xtipo=='04'){
				//RUC
				if(xcodigo1=='9999999999999'){
					alert('Ruc reservado para tipo de liente CONSUMIDOR FINAL');
					f_cierre.setFocus();
					return;				   
				}
				if(xcodigo1.length!=13){
					alert('la RUC debe contener 13 digitos');
					f_cierre.setFocus();
					return;
				}
			}
			if(xtipo=='05'){
				//CI
				if(xcodigo1.length!=10){
					alert('la Cedula debe contener 10 digitos');
					f_cierre.setFocus();
					return;
				}
			}
			var registro = f_cierre.submit();
			if(registro){
				var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
				var params = 'tabla=M_PEDIDOS&busca=ID_M_PEDIDOS&xbusca=' + xID_M_PEDIDOS + '&c_ESTATUS_CSS=CERRADO&c_ID_M_CAJAS_CSS='+ xID_M_CAJAS;
				var x      = enviar(url, params, 'POST');
				//var resp = controlError(x);
				//if(!resp) return;

				var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
				var param = "tabla=M_PEDIDOS&campos=ID_M_PEDIDOS&operador==&busca=ID_M_PEDIDOS&xbusca="+ xID_M_PEDIDOS;
				var xml     = enviar(url,param,'POST');
				var registro = valida_xml(xml,'ID_M_PEDIDOS');
				if(!registro){
					alert('Error al buscar documento');
					return false;					
				}
				xID_M_PEDIDOS = registro[0]['ID_M_PEDIDOS'];
				ocultar_formulario(f_cierre);
				//Imprimir();
				inicio(0);
			}
		break;
	}
}


function d_pedidosFocus()
{
       mostrarLeyenda(0)
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
		case 'f_edicion2':
			t_f_edicion2('',_f12);
		break;		

		case 'f_servicios_xxxx':
			t_f_servicios_xxxx('',_f12);
		break;		
		
		case 'f_cierre':
			t_f_cierre('',_f12);
		break;

		case 'f_cierre':
			t_f_cierre('',_f12);
		break;
		}
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion2':
			t_f_edicion2('',_esc);
		break;
		
		case 'f_servicios_xxxx':
			t_f_servicios_xxxx('',_esc);
		break;		

		case 'f_cierre':
			t_f_cierre('',_esc);
		break;	
    }
}


function Refrescar(){
	d_pedidos.buscar('*');
    var url   	= server_path + "herramientas/genera_xml/genera_xml.php";
    var param 	= 'tabla=M_PEDIDOS&campos=MONTO_BRUTO,MONTO_DESCUENTO,MONTO_IMPUESTO,NETO&busca=ID_M_PEDIDOS&xbusca=' +xID_M_PEDIDOS +'&operador==';
	var xml     = enviar(url,param,'POST');
	var registro = valida_xml(xml);
	actualizaHTML(xml, 'PIE', 'totales');
}

function iniciar()
{
	console.clear();
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
	
	m_productos.inicializa();
	centrarObj(m_productos.contenedor);	

	m_servicios.inicializa(false);
	centrarObj(m_servicios.contenedor);	

	d_pedidos.inicializa(false);
	d_pedidos.mostrar();

	f_edicion.inicializa(false);
	f_edicion.mostrar();
	f_edicion2.inicializa(false);
	f_servicios_xxxx.inicializa(false);
	f_cierre.inicializa(false);

	var xHtml ='<table border="0">';
	xHtml+='	<tr>';
	xHtml+='    	<td colspan="2" valign="top" id="xMONEDA" align="center">';
	xHtml+='  			<span id="moneda"></span>';
	xHtml+='  		</td>';
	xHtml+='	</tr>';
	xHtml+='	<tr>';
	xHtml+='  		<td id="GRUPO1" valign="top" style="width:280px;"></td>';
	xHtml+='  		<td id="GRUPO2" valign="top"></td>';
	xHtml+='	</tr>';
	xHtml+='</table>';
	
	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(D_PEDIDOS_INS,     	"click",   function() { t_d_pedidos('', _insert,	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_ENTER,	"click",   function() { t_d_pedidos('', _enter, 	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_F7,      	"click",   function() { t_d_pedidos('', _f7,    	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_SUP,    	"click",   function() { t_d_pedidos('', _supr,  	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_F9,     	"click",   function() { t_d_pedidos('', _f9,    	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_F10,     	"click",   function() { t_d_pedidos('', _f10,   	d_pedidos.elementoXml()) } );
	addEvent(D_PEDIDOS_ESC,     	"click",   function() { t_d_pedidos('', _esc,   	d_pedidos.elementoXml()) } );

	addEvent(m_productos_ENTER, 	"click",   function() { t_m_productos('', _enter, 	m_productos.elementoXml()) } );
	addEvent(m_productos_ESC  , 	"click",   function() { t_m_productos('', _esc,   	m_productos.elementoXml()) } );
	
	addEvent(m_servicios_LEYENDA5, 	"click",   function() { t_m_servicios('', _enter, 	m_servicios.elementoXml()) } );
	addEvent(m_servicios_LEYENDA6, 	"click",   function() { t_m_servicios('', _esc,   	m_servicios.elementoXml()) } );
	
	return true;

}

function inicio()
{
	d_pedidos.limpiar();
	
	limpiarElementos("ENCABEZADO,PIE");
	actualizaTag('ENCABEZADOID_M_CLIENTES',xID_M_CLIENTES);
	actualizaTag('ENCABEZADOCODIGO1',xCODIGO1);
	actualizaTag('ENCABEZADORAZON',xRAZON);
	actualizaTag('ENCABEZADONOMBRE_CAJA',xNOMBRE_CAJA);
	actualizaTag('ENCABEZADONOMBRE_ALMACEN',xNOMBRE_ALMACEN);
	
	//Buscar si existe documento para el almacen ---->cambiar por la caja
	var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
	var param = 'origen=pedidos/d_pedidos&procedimiento=ABIERTAS&ID_M_CAJAS=' + xID_M_CAJAS;
	var xml_encabezado   = enviar(url, param, 'POST');
	
	var registro = valida_xml(xml_encabezado, 'ID_M_PEDIDOS');
	
	if(!registro) {
		// Si no hay documento abierto se crea
		var url = server_path + 'herramientas/utiles/actualizar_registro.php';
		var params  = '';
			params += 'tabla=M_PEDIDOS';
			params += '&c_TEMP_NSS=0';
			params += '&busca=ID_M_PEDIDOS';
			params += '&xbusca=-1';
			params += '&c_TIPO_CSS=FAC';
			params += '&c_ID_M_CLIENTES_CSS=XXXX';
			params += '&c_NOMBRES_CSS='+ escape(xRAZON);
			params += '&c_CODIGO1_CSS='+ xCODIGO1;
			params += '&c_DIRECCION_CSS='+escape(xDIRECCION);
			params += '&c_TELEFONO_CSS=' + xTELEFONO1;
			params += '&c_ID_M_ALMACENES_CSS='+ xID_M_ALMACENES;
			params += '&c_ID_M_CAJAS_CSS='+ xID_M_CAJAS;
			params += '&c_CORREO_CSS=' + xCORREO1;

			var xml_encabezado = enviar(url, params, 'POST');
			var registro = valida_xml(xml_encabezado,'ID_M_PEDIDOS');
			if(!registro) {
				console.error('Error al crear el documento');
				return;	
			}	
			actualizaHTML(xml_encabezado,'ENCABEZADODOC_');
			d_pedidos.limpiar();
			
	} else {
		//Si existe el documento se muestra
		actualizaHTML(xml_encabezado, 'ENCABEZADODOC_');
	}
	
	xID_M_PEDIDOS = registro[0]['ID_M_PEDIDOS'];
	d_pedidos.xfiltro = xID_M_PEDIDOS;
	d_pedidos.setFocus();

	Refrescar();

	setTimeout(function(){ 
			f_edicion.setFocus();
	}, 300);
	
}


function buscar_producto(xbusca, callback){

	if(!xbusca) return;
	
	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR CODIGO1
	var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
	var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&operador==&busca=CODIGO1&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
	var xml    = enviar(url, params, 'POST');
	var registro = valida_xml(xml, 'ID_M_PRODUCTOS');
	
	
	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR CODIGO4
	if(!registro){
		var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
		var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&operador==&busca=CODIGO4&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
		var xml    = enviar(url, params, 'POST');
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
	}	

	//BUSCO EL PRODUCTO EN LA LISTA DE INVENTARIO POR DESCRIPCION CON LIKE
	if(!registro){
		var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
		var params = 'tabla=V_D_I_PROD_ALMA_FACTURABLES&campos=*&busca=DESCRIPCION&xbusca=' + xbusca + '&filtro=ID_M_ALMACENES&xfiltro='+xID_M_ALMACENES;
		var xml    = enviar(url, params, 'POST');
		var registro = valida_xml(xml,'ID_M_PRODUCTOS');
	}
	
	if(callback) {
		setTimeout( function(){ callback(registro); }, 100 );
	}	
}

function Guardar_item(xformulario){
	if(xformulario.getValue("ID_I_PROD_ALMA")==''){
		return false;
	}
	var registro = xformulario.submit();
	if(registro){
		ultimo_item  			= registro[0]['ID_D_PEDIDOS'];
		var indice 				= registro[0]['UNICO'];
		xid_i_prod_alma			= xformulario.getValue("ID_I_PROD_ALMA");
		Refrescar();
		xformulario.limpiar();
		xformulario.setFocus();
		return true;
	}
	return false;
}

function Refrescar(){
	
    var url   	= server_path + "herramientas/genera_xml/genera_xml.php";
    var param 	= 'tabla=M_PEDIDOS&campos=MONTO_BRUTO,MONTO_DESCUENTO,MONTO_IMPUESTO,NETO&busca=ID_M_PEDIDOS&xbusca=' +xID_M_PEDIDOS +'&operador==';
	var xml     = enviar(url,param,'POST');
	var registro = valida_xml(xml);
	xmonto_factura = unformat(registro[0]['NETO']);

	actualizaHTML(xml, 'PIE', 'totales');

	//d_pedidos.xfiltro = xID_M_PEDIDOS;
	d_pedidos.buscar('*');

}


function sin_imagen(img)
{
	img.src = server_path + 'imagenes/productos/0.jpg';
}

function ver_imagen()
{
	
	$("#IMAGEN").show();

	var xml = d_pedidos.elementoXml();
	var registro = XML2Array(xml);
	if(!registro[0]['ID_M_PRODUCTOS']) return false;
	
	
	if(!registro[0]) return false;
	var rand_no = Math.ceil(100000000*Math.random())

	var url = server_path + 'imagenes/productos/'+ registro[0]['ID_M_PRODUCTOS'] +'.jpg?rndid='+rand_no ;
	$("#FOTO").attr('src', url);
	$("#FOTO_PRECIO").html( registro[0]['PRECIO'] );
	
}

function Salir(){

	$.confirm({
		title: 'Realmente desea salir?',
		content: 'Presione ENTER si desea salir, ESC si desea regresar..',
		closeIcon: true,
		theme: 'my-theme',
		backgroundDismiss: true,
		useBootstrap: false,
		boxWidth: '30%',
		escapeKey: 'cancelar',
		buttons: {
			aceptar: {
				text: 'Aceptar',
				keys: ['enter'],
				action: function(){
					location.href = server_path + 'main/inicio.php';
				}
			},
			cancelar: {
				text: 'Cancelar',
				action: function(){
					f_edicion.setFocus();
				}
			}	
		}
	});
}


</script>


<style>


</style>

</body>
</html>

EOT;

?>