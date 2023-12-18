<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$fecha   = date("d/m/Y");

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$query = new sql();
$query->sql = "SELECT ID_M_CONCEPTOS,NOMBRES FROM M_CONCEPTOS WHERE CAMPO1='*'";
$query->ejecuta_query();
$query->next_record();
$xid_m_conceptos     = $query->Record['ID_M_CONCEPTOS'];
$xnombre_concepto    = $query->Record['NOMBRES'];


$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,tabpane,formulario2,forma,forma_simple,submodal,impresora,jquery,popup');

echo <<<EOT

{$modulo->inicio}

<table class="contenido_modulo" border="0">
	<tr>
		<td id="GRUPO1"></td>
		<td id="GRUPO2"></td>
	</tr>
</table>


{$modulo->fin}

<script type="text/javascript">

var mask               = new Mask('#,###.##', 'number');

var xproveedor    	  = 0;
var xsaldo            = 0;
var xcodigo1          = null;
var xcodigo2      	  = null;
var xdireccion    	  = null;
var xtelefono         = null;
var xnombre_proveedor = null;
var f_activo          = null;
var idcxccxp          = null;
var xdias_credito 	  = null;
var xdocumento        = null;
var xpadre;
var xreferencia;
var xid_reverso;
var xid_reversar;

var xdoc_final   =null;
var xmonto =0;
var xsaldo  =0;

var dxml;
var t;
var xrazon_proveedor
var fecha = '{$fecha}';

var xid_m_conceptos  = '{$xid_m_conceptos}';
var xnombre_concepto = '{$xnombre_concepto}';
var impresora  = new printer();
var xid_cxp    = null;
var fs 	       = new forma_simple();

var xTIPO      = null;


//Se Crea El Contenedor
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.ayuda       = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'titulo';
contenedor.botonCerrar = true;
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.leyenda     = '  ';
contenedor.onClose     = function ()
{
    f_activo.ocultar();
    contenedor.ocultar();
}

cxp                   = new lista("cxp/d_cxp_documentos")
cxp.nombre            = "cxp";
cxp.padre             = "GRUPO1";
cxp.funcion           = t_cxp;
cxp.url               = server_path + "herramientas/genera_xml/genera_xml.php";
cxp.buscador          = true;
cxp.onSelect          = Actualiza_detalle;
cxp.filtro            = 'IDX;SALDO';
cxp.xoperadores 	  = '=;<>';
cxp.onFocus           = focus_cxp;

d_cxp           = new lista("cxp/d_cxp_detalles")
d_cxp.nombre    = "d_cxp";
d_cxp.padre     = "GRUPO2";
d_cxp.funcion   = t_d_cxp;
d_cxp.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_cxp.filtro    = 'ID_PADRE';
d_cxp.onFocus   = focus_d_cxp;

m_proveedores             = new lista("cxp/m_proveedores")
m_proveedores.nombre      = "m_proveedores";
m_proveedores.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_proveedores.funcion     = t_m_proveedores;
m_proveedores.buscador    = true;
m_proveedores.x           = 1;
m_proveedores.y           = 1;
m_proveedores.modal       = true;
m_proveedores.botonCerrar = true;
m_proveedores.enter       = 0;

f_pagos          = new formulario2('cxp/f_pagos');
f_pagos.nombre   = 'f_pagos';
f_pagos.funcion  = t_f_pagos;
f_pagos.padre    = 'contenedor_cuadro';
f_pagos.script   = server_path + 'cxp/actualizar_cxp.php';

f_nota_credito          = new formulario2('cxp/f_nota_credito');
f_nota_credito.nombre   = 'f_nota_credito';
f_nota_credito.funcion  = t_f_nota_credito;
f_nota_credito.padre    = 'contenedor_cuadro';

f_nota_debito          = new formulario2('cxp/f_nota_debito');
f_nota_debito.nombre   = 'f_nota_debito';
f_nota_debito.funcion  = t_f_nota_debito;
f_nota_debito.padre    = 'contenedor_cuadro';

f_retenciones          = new formulario2('cxp/f_retenciones');
f_retenciones.nombre   = 'f_retenciones';
f_retenciones.funcion  = t_f_retenciones;
f_retenciones.padre    = 'contenedor_cuadro';

f_anticipos          = new formulario2('cxp/f_anticipos');
f_anticipos.nombre   = 'f_anticipos';
f_anticipos.funcion  = t_f_anticipos;
f_anticipos.padre    = 'contenedor_cuadro';

f_rem          = new formulario2('cxp/f_rem');
f_rem.nombre   = 'f_rem';
f_rem.funcion  = t_f_rem;
f_rem.padre    = 'contenedor_cuadro';

function focus_cxp()
{
        mostrarLeyenda(0);
}
function focus_d_cxp(obj)
{
    if(GridAct.nombre==obj.nombre) mostrarLeyenda(1);
}
function Filtrar_credito(xtipo)
{

	  if(xtipo=='ACP')
      {
           f_nota_credito.origen="cxp/f_nota_credito_admin";
      }
          if(xtipo=='NCP')
      {
           f_nota_credito.origen="cxp/f_nota_credito";
      }
      f_nota_credito.destruir();
      f_nota_credito.inicializa();
      f_nota_credito.limpiar();
      mostrar_formulario(f_nota_credito);
      f_nota_credito.setValue('CREDITO',xdias_credito);
      f_nota_credito.setValue("FECHA_DOCUMENTO",'{$fecha}');
      f_nota_credito.setValue("FECHA_RECEPCION",'{$fecha}');

      f_nota_credito.setValue("ID_M_PROVEEDORES",xproveedor);
      f_nota_credito.setValue("TIPO",xtipo);
      //if(xpadre) f_nota_credito.setValue("CAMPO3",xpadre);
      f_nota_credito.setValue("DOCUMENTO",xdocumento);

}

function Filtrar_debito(xtipo)
{

          if(xtipo=='ADP')
      {
           f_nota_debito.origen="cxp/f_nota_debito admin";
      }
          if(xtipo=='NDP')
      {
           f_nota_debito.origen="cxp/f_nota_debito";
      }
      f_nota_debito.destruir();
      f_nota_debito.inicializa();
      f_nota_debito.limpiar();
      mostrar_formulario(f_nota_debito);
	  f_nota_debito.setValue('CREDITO',xdias_credito);
      f_nota_debito.setValue("FECHA_DOCUMENTO",'{$fecha}');
      f_nota_debito.setValue("FECHA_RECEPCION",'{$fecha}');
      f_nota_debito.setValue("ID_M_PROVEEDORES",xproveedor);
      f_nota_debito.setValue("TIPO",xtipo);
      f_nota_debito.setValue("NOMBRES",xnombre_proveedor);
      f_nota_debito.setValue("CODIGO1",xcodigo1);
      f_nota_debito.setValue("CODIGO2",xcodigo2);
      f_nota_debito.setValue("DIRECCION",xdireccion);
      f_nota_debito.setValue("TELEFONO",xtelefono);
      f_nota_debito.setValue("DOCUMENTO",xdocumento);
      f_nota_debito.setValue("CAMPO3",xpadre);
      f_nota_debito.setValue("REFERENCIA",xreferencia);


}

function 	Actualiza_detalle(objeto, xml)
{
    var registro = XML2Array(xml);
    if (!registro[0]||!registro[0]['ID_D_CXCCXP'])
    {
       d_cxp.limpiar();
       xid_cxp =null;
       return;
    }
     xid_cxp   = registro[0]['ID_D_CXCCXP'];
     xdocumento   = registro[0]['DOCUMENTO'];
     xdoc_final = registro[0]['ID_M_DOC_FINAL'];
     xmonto    = registro[0]['MONTO']
     xsaldo = registro[0]['SALDO']
     dxml = xml;
        setTimeout('act_timer(dxml)',500);
}
function act_timer(xml)
{
    if (t) window.clearTimeout(t);
    dxml = xml;
    t = window.setTimeout("muestra_detalle(dxml)",300);
    actualizaHTML(xml,'PIED_');

}
function muestra_detalle(xml)
{
    var registro = XML2Array(xml);
    xid_cxp 	 = registro[0]['ID_D_CXCCXP'];
	xTIPO 		 = registro[0]['TIPO'];  	
    d_cxp.xfiltro = xid_cxp;
    d_cxp.buscar('*');
}

function Reverso_compra(reverso)
{
    var url = server_path + "herramientas/utiles/actualizar_registro.php";
    var params = "tabla=D_CXCCXP&busca=ID_D_CXCCXP&xbusca="+xid_reversar +"&c_CAMPO5_CSS=REV";
    var x = enviar(url,params,'POST');
    var registro2 = valida_xml(x);
    if(!registro2)return;

    cxp.buscar('*');
    cxp.setFocus();
    Calcula_saldo(xproveedor);
    mostrarLeyenda(0);


}
function t_m_proveedores(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _enter: // Enter
      var registro = valida_xml(xml,'ID_M_PROVEEDORES');
      if (!registro)return;
      m_proveedores.ocultar();
	  cxp.limpiar();
	  limpiarElementos("ENCABEZADO,PIE");	
      actualizaHTML(xml,'ENCABEZADO');
      xdias_credito= registro[0]['DIAS_CREDITO'];
      xproveedor = registro[0]['ID_M_PROVEEDORES'];
      xcodigo1  = registro[0]['CODIGO1'];
      xcodigo2  = registro[0]['CODIGO2'];
      xnombre_proveedor =registro[0]['NOMBRES'];
      xrazon_proveedor = registro[0]['RAZON'];
      xdireccion      = registro[0]['DIRECCION'];
      xtelefono       = registro[0]['TELEFONOS'];
      xpadre=null;
      Calcula_saldo(xproveedor);
      cxp.xfiltro = xproveedor + ',M_PROVEEDORES;0';
      cxp.buscar('*');
      cxp.setFocus();
      break;

    case _esc: // Escape
      if (xproveedor)
      {
        m_proveedores.ocultar();
        cxp.setFocus();
      }
      else Salir();
      break;
  }
}

function t_cxp(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // Enter
                    cancelaTecla(evt);
        var registro = valida_xml(xml,'DOCUMENTO');
              if (!registro)return;
              xdocumento   = registro[0]['DOCUMENTO'];
              d_cxp.setFocus();
              mostrarLeyenda(1);
      break;

    case _f3:
              cancelaTecla(evt);
              m_proveedores.mostrar();
              m_proveedores.setFocus();
      break;

    case _f4:
        cancelaTecla(evt);
        var registro = XML2Array(xml);
        xdocumento = registro[0]['DOCUMENTO'];
        var xtipo = registro[0]['TIPO'];
        if (!xdocumento) return false;
        if(xtipo =='COM' || xtipo =='NCP' || xtipo =='ACP') VerPreview(xdocumento,xtipo);
    break;

	    case _f6:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'DOCUMENTO');
		if (!registro)
		{
			alert('{$t_operacion_no_permitida}');
			return false;
		}
		var registro = valida_xml(xml,'ID_D_CXCCXP');
		if(!registro) return;
		if( unformat(registro[0]['SALDO'])==0)
		{
			alert('{$t_operacion_no_permitida}');
			return false;
		}
		xpadre = registro[0]['ID_D_CXCCXP'];
		xreferencia = registro[0]['ID_M_DOC_FINAL'];
		xdocumento  = registro[0]['DOCUMENTO'];

		Filtrar_credito("ACP")
	break;

    case _f7:
		cancelaTecla(evt);
		var registro = valida_xml(xml,'DOCUMENTO');
		if (!registro)
		{
		alert('{$t_operacion_no_permitida}');
		return false;
		}
		var registro = valida_xml(xml,'ID_D_CXCCXP');
		if(!registro) return;
		if( unformat(registro[0]['SALDO'])==0)
		{
		alert('{$t_operacion_no_permitida}');
		return false;
		}
		xpadre = registro[0]['ID_D_CXCCXP'];
		xreferencia = registro[0]['ID_M_DOC_FINAL'];
		xdocumento  = registro[0]['DOCUMENTO'];

		Filtrar_credito("NCP")
	break;

    case _f8:
        cancelaTecla(evt);
        f_anticipos.limpiar();
        f_anticipos.setValue('FECHA_PAGO','{$fecha}');
        f_anticipos.setValue('IDX',xproveedor);
        f_anticipos.setValue('TABLA','M_PROVEEDORES');
        mostrar_formulario(f_anticipos);
    break;
	
    case _f9:
		cancelaTecla(evt);
	break;

    case _f10:
        cancelaTecla(evt);
    break;

	  case _f11:
        cancelaTecla(evt);
        return;
    break;

   case _f12:
      cancelaTecla(evt);
      break;
   case _supr: // Del
      cancelaTecla(evt);
      var registro = valida_xml(xml,'DOCUMENTO');
      if (!registro)
	  {
		  alert('Operación no permitida');
		  return;
	  }
      var registro = valida_xml(xml,'ID_D_CXCCXP');
      if(!registro || registro[0]['TIPO']=='ANP')
      {
              alert('{$t_operacion_no_permitida}');
              return;
      }
      var eliminar = confirm('{$t_reversar_documento}');
       if(eliminar)
       {
                      if(registro[0]['TIPO']=='COM')
                      {
							xid_reversar   =registro[0]['ID_D_CXCCXP'];
          					waitExec('{$t_realizando_proceso}', 'Reverso_compra()', 5, 250, 250);
                      }
                      else if(registro[0]['TIPO']=='ACP'||registro[0]['TIPO']=='ADP'||registro[0]['TIPO']=='NDP'||registro[0]['TIPO']=='NCP')
                      {
                              var url = server_path + "herramientas/utiles/actualizar_registro.php";
                              var params = "origen=cxp/d_cxp_documentos&procedimiento=DELETE&ID_D_CXCCXP="+ registro[0]['ID_D_CXCCXP'];
                              var x = enviar(url,params,'POST');
                              var registro2 = valida_xml(x);
                              if(!registro2)return;
                      }
       }
      cxp.buscar('*');
      cxp.setFocus();
      Calcula_saldo(xproveedor);
      mostrarLeyenda(0);
          break;

    case _esc:
      Salir();
      break;

    case _insert: // Insertar
    case _supr: // Del
    case _f1:
    case _f2:
    case _f5:
    case _f7:
    default:
      break;
  }
}
function aplicarAnticipo()
{
 var registro2 = fs.submit();
 if(!registro2)return;
 else
 {
  cxp.actualizar(xpadre);
  cxp.setFocus();
 }
}

function t_f_rem(objeto, tecla, xml)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _insert:
			cancelaTecla(evt);
		break;

		case _esc:
			cancelaTecla(evt);
			ocultar_formulario(f_rem,d_cxp);
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
			var accion = f_rem.getValue('xbusca');
			
			var xfechapago = fechaMDY(f_rem.getValue('FECHA_PAGO'));
			var xSRI_AUTORIZACION = f_rem.getValue('SRI_AUTORIZACION');
			var xidpadre = f_rem.getValue('ID_PADRE');
			var xid_d_cxccxp = accion;
			var url = server_path + "herramientas/utiles/actualizar_registro.php";
			var params = "origen=cxp/d_cxp_detalles&procedimiento=SRI_FECHA&ID_PADRE="+ xidpadre+"&FECHA_PAGO="+xfechapago;
			var x = enviar(url,params,'POST');
			if(xSRI_AUTORIZACION != '')
			{
				var url = server_path + "herramientas/utiles/actualizar_registro.php";
				var params = "origen=cxp/d_cxp_detalles&procedimiento=SRI_AUTORIZACION&ID_PADRE="+xidpadre+"&SRI_AUTORIZACION="+xSRI_AUTORIZACION;
				var x = enviar(url,params,'POST');
			}
			else
			{
				var url = server_path + "herramientas/utiles/actualizar_registro.php";
				var params = "origen=cxp/d_cxp_detalles&procedimiento=SRI_NOAUTORIZADO&ID_PADRE="+xidpadre;
				var x = enviar(url,params,'POST');				
			}

			var registro = f_rem.submit();
			if(!registro)return;
			if(accion!='-1')
			{
				d_cxp.actualizar(accion);
			}
			else d_cxp.buscar();
			ocultar_formulario(f_rem,d_cxp);
		break;
  }
}

function t_f_pagos(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12:
        var xmonto = unformat(f_pagos.getValue("DEBITOS"));
        xsaldo = unformat(xsaldo);
		
		if(parseFloat(xmonto.toFixed(2)) > parseFloat(xsaldo.toFixed(2)) || xmonto==0)
        {
        	alert("$t_monto_no_mayor");
            return false;
        }
        var registro = f_pagos.submit();
        if(!registro) return;
        Calcula_saldo(xproveedor);
        ocultar_formulario(f_pagos);
        cxp.actualizar(xpadre);
		d_cxp.buscar('*');
        d_cxp.setFocus();
      break;

    case _esc: // ESC Cerrar Formulario
      ocultar_formulario(f_pagos);
      d_cxp.setFocus();
      break;

  }
}

function t_d_cxp(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {

    case _enter: // Enter
		cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_D_CXCCXP');
		if (!registro)return;
		
		if(registro[0]['TIPO']!='REM' || registro[0]['ID_M_DOC_FINAL']!='') {
		   alert('Operacion No Valida');
			
	   }
		
		if(registro[0]['TIPO']=='REM')
		{
			f_rem.buscar(registro[0]['ID_D_CXCCXP']);
			f_rem.setValue('FECHA_PAGO',registro[0]['FECHA_PAGO'])
			
			var xID_PADRE = registro[0]['ID_PADRE'];
			var url = server_path + 'herramientas/genera_xml/genera_xml.php';
			var params = "origen=cxp/d_cxp_detalles&procedimiento=SRI_AUTORIZADO&ID_PADRE="+xID_PADRE;
			var xml = enviar(url, params, 'GET');
			var registro = valida_xml(xml);
			if(registro) 
			{
				f_rem.setValue("SRI_AUTORIZACION",registro[0]['SRI_AUTORIZACION']);
			}
			mostrar_formulario(f_rem);
		}
      break;

  
    case _esc:
       cancelaTecla(evt);
       cxp.setFocus();
       mostrarLeyenda(0);
       break;
    case _f4:
       cancelaTecla(evt);
       var registro = valida_xml(xml,'ID_D_CXCCXP');
       if(!registro) return;
       var xtipo = registro[0]['TIPO'];
       if(xtipo!='RRP' && xtipo!='RIP' && xtipo!='REM') return;
       xpadre = registro[0]['ID_D_CXCCXP'];
       var xdocumento = registro[0]['DOCUMENTO'];
       if(xtipo='REM')
       {
       	Imprimir_rem(registro[0]['ID_PADRE'],xtipo);
       }else{
       	VerPreview(xdocumento,xtipo);
       }
       break;	   
	case _f6:
		cancelaTecla(evt);
		xml = cxp.elementoXml();
		var registro = valida_xml(xml,'DOCUMENTO');
		if (!registro)
		{
			alert('{$t_operacion_no_permitida}');
			return false;
		}
		var registro = valida_xml(xml,'ID_D_CXCCXP');
		if(!registro) return;
		if( unformat(registro[0]['SALDO'])==0)
		{
			alert('Verifique el saldo del documento');
			return false;
		}
		xpadre = registro[0]['ID_D_CXCCXP'];
		xreferencia = registro[0]['ID_M_DOC_FINAL'];
		xdocumento  = registro[0]['DOCUMENTO'];
		Filtrar_debito("ADP");
	break;

	case _f7:
		cancelaTecla(evt);
		xml = cxp.elementoXml();
		var registro = valida_xml(xml,'DOCUMENTO');
		if (!registro || registro[0]['TIPO']=='ACP')
		{
			alert('{$t_operacion_no_permitida}');
			return false;
		}
		var registro = valida_xml(xml,'ID_D_CXCCXP');
		if(!registro) return;
		if( unformat(registro[0]['SALDO'])==0)
		{
			alert('Verifique el saldo del documento');
			return false;
		}
		xpadre = registro[0]['ID_D_CXCCXP'];
		xreferencia = registro[0]['ID_M_DOC_FINAL'];
		xdocumento  = registro[0]['DOCUMENTO'];
		Filtrar_debito("NDP");
	break;	
	
	case _f9:
		xml=cxp.elementoXml();
		var registro = valida_xml(xml,'ID_D_CXCCXP');
		if(!registro) return;
		if( unformat(registro[0]['SALDO'])==0){
			alert('Verifique el saldo del documento');
			return false;
		}
		xpadre = registro[0]['ID_D_CXCCXP'];
		var xtipo    = registro[0]['TIPO'];

		if (xtipo == 'COM' || xtipo == 'NCP' || xtipo == 'IMP' || xtipo == 'ACP')
		{
		xsaldo = registro[0]['SALDO'];
		xdocumento   = registro[0]['DOCUMENTO'];
		f_pagos.setValue("FECHA_PAGO",fecha);
		f_pagos.setValue("DEBITOS",xsaldo);
		f_pagos.setValue("ID_PADRE", registro[0]['ID_D_CXCCXP']);
		f_pagos.setValue("DOCUMENTO", registro[0]['DOCUMENTO']);
		f_pagos.setValue("TIPO", "ABO");
		f_pagos.setValue("IDX", xproveedor);
		f_pagos.setValue("TABLA","M_PROVEEDORES");
		mostrar_formulario(f_pagos);
		}
		else return false;
	break;
	   
    case _supr:
       cancelaTecla(evt);
       var registro = valida_xml(xml,'ID_D_CXCCXP');
       if(!registro) return;	   
	   
       if(registro[0]['TIPO']!='REM') {
		   alert('Operacion No Valida');
			
	   }
	   if(registro[0]['ID_M_DOC_FINAL']!=''){ 
		   alert('Operacion No Valida');
			return;   
	   }
		   

		var url = server_path + 'herramientas/utiles/actualizar_registro.php';
		var params = "origen=cxp/d_cxp_detalles&procedimiento=DELETE&ID_D_CXCCXP="+registro[0]['ID_D_CXCCXP'];
		var xml = enviar(url, params, 'GET');
	   
	     d_cxp.buscar('*');
		 d_cxp.setFocus();
	   
	   
	   
       break;

   case _f11:
   cancelaTecla(evt);

      var registro = valida_xml(xml,xdocumento);
      //if (!registro)return;
      //alert(xid_cxp);
      //alert(registro[0]['DOCUMENTO']);
      //var registro = xid_cxp;
      //if(!registro) return;
      if( unformat(xsaldo)==0){ 
		alert('Verifique el saldo del documento');
		return false;
	  }
		
      xpadre = xid_cxp;
      if(xpadre=='') return;
      f_retenciones.limpiar();
      //xsaldo = unformat(registro[0]['SALDO']);
      f_retenciones.setValue("NOMBRES",xnombre_proveedor);
      f_retenciones.setValue("CODIGO1",xcodigo1);
      f_retenciones.setValue("CODIGO2",xcodigo2);
      f_retenciones.setValue("DIRECCION",xdireccion);
      f_retenciones.setValue("TELEFONO",xtelefono);
      f_retenciones.setValue("FECHA_DOCUMENTO",'{$fecha}');
      f_retenciones.setValue("ID_M_PROVEEDORES",xproveedor);
      f_retenciones.setValue("NOMBRE_VENDEDOR",xnombre_concepto);
      f_retenciones.setValue("DOCUMENTO",xdocumento);
      f_retenciones.setValue("REFERENCIA",xdoc_final);
      f_retenciones.setValue("CAMPO3",xpadre);
      f_retenciones.setValue("XNETO",mask.format(xmonto));
      f_retenciones.setValue("ID_PADRE",xpadre);
	  
	  
	  //prompt('', cxp.elementoXml());
	  
	  var xml = cxp.elementoXml();
	  var registro = valida_xml(xml,xdocumento);
	  f_retenciones.setValue("XSUBTOTAL", mask.format(registro[0]['SUB_TOTAL']));
	  f_retenciones.setValue("XIMPUESTO", mask.format(registro[0]['MONTO_IMPUESTO']));
	  
	  

      //edson
	  /*
	  var url = server_path + 'herramientas/genera_xml/genera_xml.php';
	  var params = "origen=cxp/d_cxp_documentos&procedimiento=ULTIMA_RETENCION";
	  var xml = enviar(url, params, 'GET');
	  var registro = valida_xml(xml);
      if(registro) {
      	  f_retenciones.setValue("REFERENCIA",registro[0]['REFERENCIA']);
      }
	  */

      mostrar_formulario(f_retenciones);

      break;

  }
}
function Reverso_nd(xid)
{
    var url = server_path + 'herramientas/utiles/actualizar_registro.php';
    var params = 'tabla=D_CXCCXP&busca=ID_D_CXCCXP&xbusca='+xid_reverso+"&c_CAMPO5_CSS=REV";
    x = enviar(url, params, 'POST');
    cxp.actualizar(xpadre);
    d_cxp.buscar('*');
    cxp.setFocus();
    xid_reverso=null;
}

function t_f_retenciones(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
   		   /*
		   var xreferencia = f_retenciones.getValue("REFERENCIA");
           var url = server_path + 'herramientas/genera_xml/genera_xml.php';
           var params = "origen=cxp/d_cxp_documentos&procedimiento=VALIDA_RETENCION&XPADRE=" + xpadre + "&XREFERENCIA=" + xreferencia;
	       var xml = enviar(url, params, 'GET');
           var registro = valida_xml(xml);
           if(registro) {
           	 alert('Este Documento Ya existe asociado a otra Compra, Favor Verifique.');
             f_retenciones.setFocus();
             return;
           }
		   */

           Guardar_ret();

            break;

    case _esc: // ESC Cerrar Formulario
        Salir_ret();
              break;

  }
}

function Guardar_ret()
{

   var xnombre_concepto = f_retenciones.getValue("r_CAMPO1");
    f_retenciones.setValue("CAMPO2",xnombre_concepto.substring(0,59));
    var monto_ret=f_retenciones.getValue("NETO");
    if (monto_ret < 0)
    {
    	f_retenciones.setValue("NETO",0);
    }
     var registro = f_retenciones.submit();
     if(!registro) return;
     ocultar_formulario(f_retenciones);
     Calcula_saldo(xproveedor);
     cxp.actualizar(xpadre);
     d_cxp.buscar();
     d_cxp.setFocus();

}

function Salir_ret()
{
      ocultar_formulario(f_retenciones);
      cxp.setFocus();
}
function t_f_nota_debito(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
        Guardar_nd(objeto);
      break;

    case _esc: // ESC Cerrar Formulario
      ocultar_formulario(f_nota_debito);
          d_cxp.setFocus();
      break;

  }
}
function t_f_nota_credito(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
            Guardar_nc();

      break;

    case _esc: // ESC Cerrar Formulario
      ocultar_formulario(f_nota_credito);
      break;

  }
}

function t_f_anticipos(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12: // F12 Guardar
                  Guardar_anticipo();
      break;

    case _esc: // ESC Cerrar Formulario
      ocultar_formulario(f_anticipos);
      cxp.setFocus();
      break;

  }
}

function Guardar_anticipo()
{
        var registro=f_anticipos.submit();
    if(!registro) return;
    ocultar_formulario(f_anticipos);
    Calcula_saldo(xproveedor);
   // actualizaHTML(xml,'PIED_');
    cxp.buscar('*');
    cxp.setFocus();

}
function Guardar_nd()
{
   xNETO = f_nota_debito.getValue('NETO'); 
   if(xNETO>xsaldo){
	   alert('El monto de la nota de crédito es mayor al saldo del documento');
	   return;
   };   
   var registro = f_nota_debito.submit();
   if(!registro||!registro[0]) return;
   ocultar_formulario(f_nota_debito);
   cxp.actualizar(xpadre);
   if(unformat(xNETO)==unformat(xsaldo) ){
	   d_cxp.limpiar(); 
	   cxp.setFocus();
   }
   else{
		d_cxp.buscar('*');
		d_cxp.setFocus();   
   }
   Calcula_saldo(xproveedor);
   
}
function Guardar_nc()
{
     var xconcepto=f_nota_credito.getValue("r_ID_M_CONCEPTOS" );
     f_nota_credito.setValue("NOMBRE_CONCEPTO",xconcepto);
     var registro = f_nota_credito.submit();
     if(!registro) return;
     ocultar_formulario(f_nota_credito);
     cxp.buscar('*');
     cxp.setFocus();
     xpadre=null;
     Calcula_saldo(xproveedor);
}
function Calcula_retencion()
{
  var xconcepto = f_retenciones.getValue('CAMPO1');
  var x =genera_xml('M_CONCEPTOS_RETEN','PORCENTAJE1,MONTO1','ID_M_CONCEPTOS_RETEN',xconcepto,'=');
  var registro= valida_xml(x,'PORCENTAJE1');
  if(!registro) return;
	
  //f_retenciones.setValue('CAMPO1',f_retenciones.getValue('r_CAMPO1').substring(0,59));
  //f_retenciones.setValue('r_CAMPO1',f_retenciones.getValue('CAMPO1'));
  f_retenciones.setValue('MONTO_DESCUENTO',registro[0]['MONTO1']);
  f_retenciones.setValue('MONTO_IMPUESTO', mask.format(registro[0]['PORCENTAJE1']));

}
function Calcula_impuesto(objeto,xml)
{

  var formulario = objeto.nombre;
  var xid_con = f_activo.getValue('CAMPO1');

//  f_activo.setValue("CAMPO1",xid_con);

  var registro = XML2Array(xml);
  var impuesto = registro[0]['MONTO_IMPUESTO'];
  f_activo.setValue("IMPUESTO",impuesto);
  var concepto = registro[0]['NOMBRES'];
  f_activo.setValue("CAMPO2",concepto.substring(0,59));

}
function VerPreview(doc,tipo)
{
   if(tipo !='ABO' && tipo !='ANP')
   {
    if (tipo =='NCP' || tipo =='ACP') tipo='NDC_GRAF';
    if (tipo =='REM') tipo='REM_MEDIA_GRA';
    var xsufijo = tipo.toLowerCase();
    impresora.origin = 'reportes/r_documento_'+xsufijo;
    impresora.setParam('ID_M_DOCUMENTOS',doc);
   }
    impresora.showDialog=1;
    impresora.preview();
    if(GridAct.nombre=='cxp') cxp.setFocus();
    else d_cxp.setFocus();
    return;
}

function Imprimir_rem(doc,tipo)
{

    if (tipo =='REM') tipo='REM_MEDIA_GRA';
    var xsufijo = tipo.toLowerCase();
    impresora.origin = 'reportes/r_documento_'+xsufijo;
    impresora.setParam('ID_PADRE',doc);

    impresora.showDialog=1;
    impresora.preview();
    if(GridAct.nombre=='cxp') cxp.setFocus();
    else d_cxp.setFocus();
    return;
}
 

function Salir(){
	location.href = server_path + 'main/inicio.php';
}

function irMenu()
{
  if (parent.menu) parent.menu.reset();
}


function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_pagos':
          t_f_pagos('',_f12);
          break;
       case 'f_nota_debito':
          t_f_nota_debito('',_f12);
          break;
       case 'f_nota_credito':
          t_f_nota_credito('',_f12);
          break;
       case 'f_anticipos':
          t_f_anticipos('',_f12);
          break;
       case 'f_retenciones':
          t_f_retenciones('',_f12);
          break;
	   case 'f_rem':
          t_f_rem('',_f12);
          break;

    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
		case 'f_pagos':
		  t_f_pagos('',_esc);
		  break;
		case 'f_nota_debito':
		  t_f_nota_debito('',_esc);
		  break;
		case 'f_anticipos':
		  t_f_anticipos('',_esc);
		  break;
		case 'f_nota_credito':
		  t_f_nota_credito('',_esc);
		  break;
		case 'f_retenciones':
		  t_f_retenciones('',_esc);
		  break;
		case 'f_rem':
		  t_f_rem('',_esc);
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
    window.setTimeout("f_activo.setFocus()", 100);
}

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
}
function Calcula_saldo(xproveedor)
{
   var url = server_path + 'herramientas/genera_xml/genera_xml.php';
   var params = "origen=cxp/d_cxp_documentos&procedimiento=SALDO&IDX=" + xproveedor;
   var xml= enviar(url, params, 'GET');
   actualizaHTML(xml,'PIE','totales');
}
function cambia_estilo2()
{
        frm_f_pagos.DEBITOS.className='style1';
}
function cambia_estilo3()
{
        frm_f_anticipos.DEBITOS.className='style1';
}

function iniciar()
{
	console.clear();
	contenedor.inicializa(false);
	centrarObj(contenedor.contenedor);

	cxp.inicializa(false);
	cxp.mostrar();
	cxp.limpiar();

	d_cxp.inicializa(false);
	d_cxp.mostrar();

	m_proveedores.inicializa(false);
	centrarObj(m_proveedores.contenedor);
	m_proveedores.mostrar();
	m_proveedores.setFocus();

	f_pagos.inicializa(false);
	f_nota_credito.inicializa(false);
	f_nota_debito.inicializa(false);
	f_retenciones.inicializa(false);
	f_anticipos.inicializa(false);
	f_rem.inicializa(false);

	var xHTML ='&nbsp;<button type="button" id="btn_usar_monto" style="HEIGHT: 20px;">Usar Monto</button>';
	$('#celda_f_retenciones_XSUBTOTAL td:nth-child(2)').append(xHTML);
	$('#celda_f_retenciones_XIMPUESTO td:nth-child(2)').append(xHTML);

	$('body').on('click', '#btn_usar_monto', function() 
	{
		f_retenciones.setValue('SUB_TOTAL', $(this).prev('input').val());
		f_retenciones.setFocus();
	});

	mostrarLeyenda(0);

	addEvent(CXP_ENTER,	"click",   function()   { t_cxp('', _enter,	cxp.elementoXml()) } )
	addEvent(CXP_F3,    "click",   function()   { t_cxp('', _f3,    cxp.elementoXml()) } )
	addEvent(CXP_F4,    "click",   function()   { t_cxp('', _f4,    cxp.elementoXml()) } )
	addEvent(CXP_F6,  	"click",   function() 	{ t_cxp('', _f6,    cxp.elementoXml()) } )
	addEvent(CXP_F7,  	"click",   function() 	{ t_cxp('', _f7,    cxp.elementoXml()) } )
	addEvent(CXP_F8,    "click",   function()   { t_cxp('', _f8,    cxp.elementoXml()) } )
	addEvent(CXP_SUPR,  "click",   function()   { t_cxp('', _supr,  cxp.elementoXml()) } )
	addEvent(CXP_ESC,   "click",   function()   { t_cxp('', _esc,   cxp.elementoXml()) } )

	addEvent(D_CXP_ENTER,	"click",   function() { t_d_cxp('', _enter,	d_cxp.elementoXml()) } )
	addEvent(D_CXP_ESC, 	"click",   function() { t_d_cxp('', _esc,   d_cxp.elementoXml()) } )
	addEvent(D_CXP_F4,  	"click",   function() { t_d_cxp('', _f4,    d_cxp.elementoXml()) } )
	addEvent(D_CXP_F6,  	"click",   function() { t_d_cxp('', _f6,    d_cxp.elementoXml()) } )
	addEvent(D_CXP_F7,  	"click",   function() { t_d_cxp('', _f7,    d_cxp.elementoXml()) } )
	addEvent(D_CXP_F9,    	"click",   function() { t_d_cxp('', _f9,    d_cxp.elementoXml()) } )	
	addEvent(D_CXP_F11,  	"click",   function() { t_d_cxp('', _f11,   d_cxp.elementoXml()) } )
	addEvent(D_CXP_SUPR, 	"click",   	function() { t_d_cxp('', _supr, d_cxp.elementoXml()) } )

	addEvent(m_proveedores_ENTER, 	"click",	function() { t_m_proveedores('', _enter, m_proveedores.elementoXml()) } )
	addEvent(m_proveedores_ESC, 	"click",   	function() { t_m_proveedores('', _esc, m_proveedores.elementoXml()) } )

	addEvent(frm_f_pagos.DEBITOS, 'focus', cambia_estilo2);
	addEvent(frm_f_pagos.DEBITOS, 'blur', cambia_estilo2);
	addEvent(frm_f_pagos.DEBITOS, 'keyup', cambia_estilo2);

	addEvent(frm_f_anticipos.DEBITOS, 'focus', cambia_estilo3);
	addEvent(frm_f_anticipos.DEBITOS, 'blur', cambia_estilo3);
	addEvent(frm_f_anticipos.DEBITOS, 'keyup', cambia_estilo3);

	cambia_estilo2();
	cambia_estilo3();
	document.onclick = function() { if (parent.menu) parent.menu.reset(); }

	var extra = '<center>';
	extra +='<input name="FILTRO" id="oTODOS" type="radio" onclick="Switch(\'TODOS\')"><b><u>M</u></b>ostrar Todos';
	extra +='<input name="FILTRO" id="oSALDO" type="radio" checked="checked" onclick="Switch(\'SALDO\')"> Mostrar Con Sa<b><u>l</u></b>do';
	extra +='</center>';

	cxp.extra(extra);
	return true;

}

function Switch(opcion)
{
        switch(opcion)
    {
            case 'TODOS':
                cxp.filtro  = 'IDX';
            cxp.xfiltro = xproveedor + ',M_PROVEEDORES';
            cxp.xoperadores = '';
        break;
        case 'SALDO':
                cxp.filtro  = 'IDX;SALDO';
            cxp.xfiltro = xproveedor + ',M_PROVEEDORES;0';
            cxp.xoperadores = '=;<>';
        break
    }
    cxp.buscar('*');
    cxp.setFocus();
}



function inicio()
{
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