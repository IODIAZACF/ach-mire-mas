<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$tipo= getvar('tipo');
$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);


$query = new sql();
$query->sql = "select id_m_cajas,campo1,campo2 from m_cajas where ip='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas = $query->Record['ID_M_CAJAS'];

$tmp_data = explode('_', $query->Record['CAMPO1']);
$impresora_protocolo     = $tmp_data[0];
$impresora_formato  	 = $tmp_data[1];
$xpuerto     	 		 = $query->Record['CAMPO2'];


echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,formulario2,forma,tabpane,impresora,submodal,wait');

echo <<<EOT
{$modulo->inicio}

<table border="0" class="contenido_modulo">
	<tr>
		<td id="GRUPO1"></td>
	</tr>
	<tr>
		<td id="GRUPO2"></td>
	</tr>
</table>


{$modulo->fin}


<script type="text/javascript">
var tipo ='{$tipo}';
var xtipo ;
var id_documentos;
var xtiempo            	  = null;
var impresora_protocolo   = '{$impresora_protocolo}';
var impresora_formato     = '{$impresora_formato}';
var xpuerto               = '{$xpuerto}';
var xid_m_cajas           = '{$xid_m_cajas}';
var xURL			      = '';
var f_activo		 = null;
var xIDX			 = '';

var t;

var impresora = new printer();

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


m_documentos             = new lista('facturacion_electronica/m_documentos');
m_documentos.nombre      = 'm_documentos';
m_documentos.funcion     = t_m_documentos;
m_documentos.buscador    = true;
m_documentos.botonCerrar = true;
m_documentos.enter	   = 0;
m_documentos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
m_documentos.padre        = "GRUPO1";
m_documentos.onSelect   	= actualizar_d_documentos;


d_documentos             = new lista('facturacion_electronica/d_documentos');
d_documentos.nombre      = 'd_documentos';
d_documentos.funcion     = t_d_documentos;
d_documentos.buscador    = true;
d_documentos.botonCerrar = true;
d_documentos.enter	   = 0;
d_documentos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
d_documentos.padre        = "GRUPO2";
d_documentos.filtro       = "IDX;TABLA";

f_edicion_factura              = new formulario2('facturacion_electronica/f_edicion_factura');
f_edicion_factura.nombre       = 'f_edicion_factura';
f_edicion_factura.funcion      = t_f_edicion_factura;
f_edicion_factura.padre        = 'contenedor_cuadro';
f_edicion_factura.tipo         = 'formulario';

f_edicion_proveedor              = new formulario2('facturacion_electronica/f_edicion_proveedor');
f_edicion_proveedor.nombre       = 'f_edicion_proveedor';
f_edicion_proveedor.funcion      = t_f_edicion_proveedor;
f_edicion_proveedor.padre        = 'contenedor_cuadro';
f_edicion_proveedor.tipo         = 'formulario';


f_correo              = new formulario2('facturacion_electronica/f_correo');
f_correo.nombre       = 'f_correo';
f_correo.funcion      = t_f_correo;
f_correo.padre        = 'contenedor_cuadro';
f_correo.tipo         = 'formulario';


function t_f_correo(objeto, tecla, xml){
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      cancelaTecla(evt);
      ocultar_formulario(f_correo,m_documentos);
      break;

    case _f12:
      cancelaTecla(evt);

	  if (!f_correo.validar()) return false;
	  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  if(!re.test(String(f_correo.getValue('CORREO')).toLowerCase())){
		   alert('Formato de correo Incorrecto');
		   $('#frm_f_correo #CORREO').focus();
		   $('#frm_f_correo #CORREO').select();
		   return;
	  }
		var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
		var params   = "origen=facturacion_electronica/m_documentos&procedimiento=REENVIAR&IDX=" + xIDX +'&TABLA='+xTABLA+'&CORREO='+f_correo.getValue('CORREO');
		var x      = enviar(url, params, 'POST');
		if(!controlError(x)) return;
		alert('Correo enviado');
		ocultar_formulario(f_correo,m_documentos);



      break;
  }
}

function t_f_edicion_proveedor(objeto, tecla, e){
	var evt = window.event || e;
	switch (tecla)
	{
	  case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion_proveedor,m_documentos);
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
			var registro = f_edicion_proveedor.submit();
			if(!registro)return;
			ocultar_formulario(f_edicion_proveedor,m_documentos);
			m_documentos.actualizar(xIDX);
		break;
  }
}


function t_f_edicion_factura(objeto, tecla, e){
	var evt = window.event || e;
	switch (tecla){
	  case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_edicion_factura,m_documentos);
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
			var registro = f_edicion_factura.submit();
			if(!registro)return;
			ocultar_formulario(f_edicion_factura,m_documentos);
			m_documentos.actualizar(xIDX);
		break;
  }
}


function actualizar_d_documentos(obj,xml)
{
  if (t) window.clearTimeout(t);
  t = window.setTimeout('ver_d_documentos()',300);
}
function ver_d_documentos()
{
	var xml = m_documentos.elementoXml();
	var registro = valida_xml(xml,'IDX');
    if(!registro)
    {
        d_documentos.limpiar();
        return;
    }
	d_documentos.xfiltro 		= registro[0]['IDX'] + ';' + registro[0]['TABLA'];
	d_documentos.buscar('*');
}


function t_d_documentos(objeto, tecla, xml,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
   case _esc:
      cancelaTecla(evt);
      m_documentos.setFocus();
      break;
		case _f1:
		case _f2:
		case _f3:
		case _f4:
		case _f5:
		case _f4:
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


function t_m_documentos(objeto, tecla, xml,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
   case _esc:
      cancelaTecla(evt);
      Salir();
      break;
	case _f1:
	case _f2:
	case _f3:
		cancelaTecla(evt);
	break;

	case _f4:
      cancelaTecla(evt);

      if (!evt.altKey)
      {
        var registro = valida_xml(xml,'IDX');
        if(!registro) return;
        Preview(registro[0]['IDX'], registro[0]['TIPO']);
      }

      break;

   case _f5:
      cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_SRI_DOCUMENTOS');
        if(!registro) return;

		switch (registro[0]['TIPO'])
		{
			case 'NCC':
				//xTABLA = 'D_CXC';
			//break;
			case 'FAC':
			case 'GUI':
				xTABLA = 'M_DOCUMENTOS';
			break;
			case 'REM':
				xTABLA = 'D_CXCCXP';
			break;
		}

		xIDX= registro[0]['IDX'];
		f_correo.setValue('CORREO', registro[0]['CORREO']);
		mostrar_formulario(f_correo);
	break;
   case _f6:
    	cancelaTecla(evt);
        var registro = valida_xml(xml,'IDX');
        if(!registro) return;
		xIDX = registro[0]['ID_SRI_DOCUMENTOS'];
		if(registro[0]['TIPO']=='FAC' || registro[0]['TIPO']=='NCC'){
			f_edicion_factura.buscar(registro[0]['IDX']);
			mostrar_formulario(f_edicion_factura);
			return;
		}
		if(registro[0]['TIPO']=='REM'){
			alert(registro[0]['ID_M_PROVEEDORES']);
			f_edicion_proveedor.buscar(registro[0]['ID_M_PROVEEDORES']);
			mostrar_formulario(f_edicion_proveedor);
			return;
		}
		alert('Proceso Invalido');
    break;
   case _f6:
   case _f8:
   case _f9:
      cancelaTecla(evt);
      break;
   case _f10:
      cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_SRI_DOCUMENTOS');
        if(!registro[0]) return;
			alerta.mostrarMensaje('Enviando y autorizando documento a el SRI....');
			setTimeout(function(){
			switch (registro[0]['TIPO'])
			{
				case 'NCC':
					var url   = server_path + 'facturacion_electronica/cmd_nota_credito_electronica.php';
				break;
				case 'FAC':
					var url   = server_path + 'facturacion_electronica/cmd_factura_electronica.php';
				break;
				case 'REM':
					var url   = server_path + 'facturacion_electronica/cmd_retenciones_electronica.php';
				break;
				case 'GUI':
					var url   = server_path + 'facturacion_electronica/cmd_guias_remision_electronica.php';
				break;
			}

			var param = 'IDX=' + registro[0]['IDX'];
			var resp  = enviar(url,param,'POST');
			alerta.ocultarMensaje();
			alert(resp);
			//m_documentos.actualizar(registro[0]['ID_SRI_DOCUMENTOS']);
			m_documentos.buscar('*');
			m_documentos.setFocus();
		}, 1000);


      break;

   case _f11:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'ID_SRI_DOCUMENTOS');
		if(!registro[0]) return;
		var params ='TIPO=PDF';
			params+='&IDX='+ registro[0]['IDX'];
			params+='&ID_M_DOC_FINAL=' + registro[0]['ID_M_DOC_FINAL'];
			params+='&TIPO_COMPROBANTE=' + registro[0]['TIPO'];
		$.download(server_path + 'facturacion_electronica/download.php',params);
      break;
   case _f12:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_SRI_DOCUMENTOS');
      if(!registro[0]) return;
		var registro = valida_xml(xml,'ID_SRI_DOCUMENTOS');
		if(!registro[0]) return;
		var params ='TIPO=XML';
			params+='&IDX='+ registro[0]['IDX'];
			params+='&ID_M_DOC_FINAL=' + registro[0]['ID_M_DOC_FINAL'];
			params+='&TIPO_COMPROBANTE=' + registro[0]['TIPO'];
		$.download(server_path + 'facturacion_electronica/download.php',params);
      break;

  }
}

function Preview(idx, tipo)
{
    impresora.origin = 'reportes/r_documento_' + tipo.toLowerCase();
    impresora.setParam('ID_M_DOCUMENTOS',idx);
	impresora.showDialog=true;
	impresora.preview();
    m_documentos.setFocus();
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
		case 'f_edicion_factura':
			t_f_edicion_factura('',_f12);
		break;
		case 'f_edicion_proveedor':
			t_f_edicion_proveedor('',_f12);
		break;
       case 'f_correo':
          t_f_correo('',_f12);
          break;

	}
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_edicion_factura':
			t_f_edicion_factura('',_esc);
		break;
		case 'f_edicion_proveedor':
			t_f_edicion_proveedor('',_esc);
		break;
       case 'f_correo':
          t_f_correo('',_esc);
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
	window.setTimeout("f_activo.setFocus()", 10);
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}


function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);


    d_documentos.inicializa();
    d_documentos.mostrar();

    m_documentos.inicializa();
    m_documentos.mostrar();
	m_documentos.setFocus();

	f_edicion_factura.inicializa();
	f_edicion_proveedor.inicializa();
	f_correo.inicializa();

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(M_DOCUMENTOS_F4, "click",   function() { t_m_documentos('', _f4,  m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_F5, "click",   function() { t_m_documentos('', _f5,  m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_F6, "click",   function() { t_m_documentos('', _f6,  m_documentos.elementoXml()) } )
    addEvent(M_DOCUMENTOS_F10, "click",   function() { t_m_documentos('', _f10,  m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_F11, "click",   function() { t_m_documentos('', _f11,  m_documentos.elementoXml()) } )
	addEvent(M_DOCUMENTOS_F12, "click",   function() { t_m_documentos('', _f12,  m_documentos.elementoXml()) } )
    addEvent(M_DOCUMENTOS_ESC, "click",  function() { t_m_documentos('', _esc, m_documentos.elementoXml()) } )

	m_documentos.filtro  = 'AUTORIZADO';
    m_documentos.xfiltro = 'NO';

    var extra = '<center>';
    extra +='<input name="FILTRO" id="oNOAUTORIZADA"  type="radio" checked="checked" onclick="Switch(\'NO\') ">No Autorizada</input>';
    extra +='<input name="FILTRO" id="oAUTORIZADA"    type="radio" onclick="Switch(\'SI\')">Autorizada</input>';
    extra +='</center>';
	
	m_documentos.extra(extra);
}

function Switch(opcion)
{
	m_documentos.filtro  = 'AUTORIZADO';
    m_documentos.xfiltro = opcion;
	m_documentos.buscar('*');
    m_documentos.setFocus();
}

iniciar();
</script>


</body>
</html>

EOT;

?>