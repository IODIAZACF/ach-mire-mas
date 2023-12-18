<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
$ventana                 = getvar('ventana','modulo');
$id                         = getvar('id');

$xalmacen_compra = getsession('M_ESTACIONES_ID_M_ALMACENES_COMPRAS');
$xdb             = getsession('db');
$xestatus   = 'C';
$xFecha_Documento                 = date("d/m/Y");
$my_ini         = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

$query = new sql();
$query->sql = "select id_m_almacenes from m_almacenes where condicion1 = '*'";
$query->ejecuta_query();
$query->next_record();
$xid_m_almacenes = $query->Record['ID_M_ALMACENES'];

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}
<table>
	<tr>
		<td id="NETO"></td>
	</tr>
</table>
{$modulo->fin}
<script type="text/javascript">

var xProveedor            = '';
var xProveedor_Nombre     = '';
var xProveedor_Codigo1    = '';
var xProveedor_Codigo2    = '';
var xProveedor_Direccion  = '';
var xProveedor_Telefono   = '';
var xFecha_Documento      = '{$xFecha_Documento}';
var xFecha_Recepcion      = '{$xFecha_Documento}';
var xDias                                  =   0;
var xtipo                 ='ODC';
var xdocumento            = 0;
var xid_m_almacenes       = '{$xid_m_almacenes}'; /*variable para definir el almacen principal*/
var f_activo              = null;
var xproducto             = null;
var t1                    = null;
var xbandera              = null;
var impresora             = new printer();
impresora.db              = '{$xdb}';
var id                    = null;
var t 					  = null;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 720;
contenedor.alto        = 580;
contenedor.titulo      = ' ';
contenedor.ayuda       = 1;
contenedor.x           = 100;
contenedor.y           = 100;
contenedor.titulo      = 'XXXXXX';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.onClose     = Cerrar_contenedor;


documento             = new lista('orden_compra/d_documento');
documento.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
documento.nombre      = 'documento';
documento.funcion     = t_documento;
documento.padre       = "NETO";
documento.buscador    = true;
documento.filtro      = 'ID_X_M_DOCUMENTOS';
documento.onSelect     = actualizaPie;


// Se Crea el Grid de Maestro de proveedores
m_proveedores                         = new lista("maestros/m_proveedores")
m_proveedores.nombre       = "m_proveedores";
m_proveedores.url          = server_path + "herramientas/genera_xml/genera_xml.php";
m_proveedores.funcion      = t_m_proveedores;
m_proveedores.buscador     = true;
m_proveedores.x            = 1;
m_proveedores.y            = 1;
m_proveedores.enter        = 1;
m_proveedores.modal        = true;
m_proveedores.botonCerrar  = true;

m_productos             = new lista("orden_compra/d_i_prod_almacen")
m_productos.nombre      = "m_productos";
m_productos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_productos.funcion     = t_m_productos;
m_productos.usaCookie   = false;
m_productos.buscador    = true;
m_productos.filtro      ='ID_M_ALMACENES';
m_productos.xfiltro     = xid_m_almacenes;
m_productos.x           = 1;
m_productos.y           = 1;
m_productos.enter       = 1;
m_productos.modal       = true;
m_productos.botonCerrar = true;


// Se Crea el Formulario de Edicion de Item
f_edicion              = new formulario2('orden_compra/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo                   = 'formulario';

// Se Crea el Formulario de Edicion de Item
f_servicio              = new formulario2('orden_compra/f_servicio');
f_servicio.nombre       = 'f_servicio';
f_servicio.funcion      = t_f_servicio;
f_servicio.padre        = 'contenedor_cuadro';
f_servicio.tipo         = 'formulario';

// Se Crea el Formulario de Edicion de Item
f_cierre              = new formulario2('orden_compra/f_cierre');
f_cierre.nombre       = 'f_cierre';
f_cierre.funcion      = t_f_cierre;
f_cierre.padre        = 'contenedor_cuadro';
f_cierre.tipo         = 'formulario';

f_productos                            = new formulario2('maestro_productos/f_edicion');
f_productos.nombre       = 'f_productos';
f_productos.funcion      = t_f_productos;
f_productos.padre        = 'contenedor_cuadro';

f_productos2              = new formulario2('maestro_productos/f_XXXXMPDES0011');
f_productos2.nombre       = 'f_productos2';
f_productos2.funcion      = t_f_productos2;
f_productos2.padre        = 'f_productos_grupo2';
f_productos2.modal        = true;
f_productos2.noOcultarCombos = true;

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
	Cerrar_formulario();
}

function t_m_productos(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
	case _enter:
		cancelaTecla(evt);
		var registro = XML2Array(xml);
		if(!registro[0]['ID_M_PRODUCTOS']) return;
		xproducto = registro[0]['ID_M_PRODUCTOS'];
		m_productos.ocultar();
		f_edicion.limpiar();
		mostrar_formulario(f_edicion);

		f_edicion.setValue('xbusca','-1');
		f_edicion.setValue('ID_M_PRODUCTOS' ,xproducto);
		f_edicion.setValue('DESCRIPCION'    ,registro[0]['DESCRIPCION']);
		f_edicion.setValue('PRESENTACION'   ,registro[0]['PRESENTACION']);
		f_edicion.setValue('COSTO'          ,registro[0]['COSTO']);
		f_edicion.setValue('TIPO'           ,'ODC');
		f_edicion.setValue('ID_X_M_DOCUMENTOS',xdocumento);
		f_edicion.setValue('ID_I_PROD_ALMA'    ,registro[0]['ID_I_PROD_ALMA']);
		f_edicion.setValue('ID_D_I_PROD_ALMA'    ,registro[0]['ID_D_I_PROD_ALMA']);
		f_edicion.setValue('ID_D_PRODUCTOS'    ,registro[0]['ID_D_PRODUCTOS']);
		f_edicion.setValue('ID_M_IMPUESTOS'       ,registro[0]['ID_M_IMPUESTOS2']);

	break;

	case _insert:
		cancelaTecla(evt);
		xproducto = '-1';
		f_productos.limpiar();
		armarPlantilla();
		mostrar_formulario(f_productos);
		m_productos.ocultar();
	break;

	case _esc:
		cancelaTecla(evt);
		m_productos.ocultar();
		documento.setFocus();
	break;
  }
}

function t_f_edicion(elemento, tecla,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
	case _enter: // Enter
		cancelaTecla(evt);
	break;

	case _esc: //Escape
		cancelaTecla(evt);
		ocultar_formulario(f_edicion);
		documento.setFocus();
	break;

	case _f12: // F12 Guardar_item
		cancelaTecla(evt);
		var registro = f_edicion.submit();
		if(!registro) return;
		id    = registro[0]['ID_D_DOCUMENTOS'];
		waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
		ocultar_formulario(f_edicion);
		documento.setFocus();

	break;
	}
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
	setTimeout('f_activo.setFocus();',10);
}

function ocultar_formulario(xformulario)
{
	contenedor.ocultar();
	xformulario.ocultar();
	documento.setFocus();
}
function actualizaPie()
{
	actualizaHTML(documento.elementoXml(),'PIED_');
}

function t_m_proveedores(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _enter:
			cancelaTecla(evt);
			var registro = XML2Array(xml);
			if(!registro[0]['ID_M_PROVEEDORES']){
			inicio(0);
			return;
			}

			xDias                 = registro[0]['DIAS_CREDITO'];
			xProveedor            = registro[0]['ID_M_PROVEEDORES'];
			xProveedor_Nombre     = registro[0]['RAZON'];
			xProveedor_Codigo1    = registro[0]['CODIGO1'];
			xProveedor_Codigo2    = registro[0]['CODIGO2'];
			xProveedor_Direccion  = registro[0]['DIRECCION'];
			xProveedor_Telefono   = registro[0]['TELEFONO'];
			if (!xDias) xDias = 0;
			actualizaHTML(xml, 'ENCABEZADO');
			m_proveedores.ocultar();
			CrearLista();
		break;
		case _esc: // Escape
			cancelaTecla(evt);
			if(!xbandera)
			{
				Salir();
			}
			else
			{
				m_proveedores.ocultar();
				documento.setFocus();
			}
			xbandera=null;
		break;
	}
}

function FocusLista()
{
        $('#xdocumentos_abiertos').focus();
}
function Abrir_Documento()
{
    if($('#xdocumentos_abiertos').val()=='-1')
    {
        var resp = confirm('{$t_crear_documento}');
        if(resp)
        {
                var url = server_path + 'herramientas/utiles/actualizar_registro.php';
                var params = 'tabla=X_M_DOCUMENTOS&busca=ID_X_M_DOCUMENTOS&xbusca=-1&c_TIPO_CSS=ODC&c_ID_M_PROVEEDORES_CSS=' + xProveedor + '&c_NOMBRES_CSS=' + escape(xProveedor_Nombre) + '&c_CODIGO1_CSS=' + xProveedor_Codigo1 + '&c_DIRECCION_CSS=' + escape(xProveedor_Direccion) +'&c_TELEFONO_CSS=' + xProveedor_Telefono+'&c_CREDITO_NSS=' +xDias;
                var x = enviar(url,params,'POST');
                var registro = XML2Array(x);
                xdocumento = registro[0]['ID_X_M_DOCUMENTOS'];

            $('#xdocumentos_abiertos').append($('<option></option>').val(xdocumento).html(xdocumento));
            $("#xdocumentos_abiertos option[value="+xdocumento+"]").attr("selected",true);

            waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
        }
        else
        {
            var opciones = $("#xdocumentos_abiertos").find("option").length;

            if(opciones<=1)
            {
                setTimeout('inicio(0);',100);
                return;
            }
        }
        $("#xdocumentos_abiertos option[value="+  xdocumento +"]").attr("selected",true);
        Refrescar();
    }
    else
    {
                xdocumento = $('#xdocumentos_abiertos').val();
                waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
        }
    documento.setFocus();
}
function CrearLista()
{
      $('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').empty();
      $('#ENCABEZADODOC_ID_X_M_DOCUMENTOS').append('<select id="xdocumentos_abiertos" class="campo_activo"name="documentos" ></select>');

      $('#xdocumentos_abiertos').change(function()
      {
              if (t1) window.clearTimeout(t1);
          t1 = setTimeout('Abrir_Documento();',500);
      });

            $('#xdocumentos_abiertos').keydown(function(event) {
              if (event.keyCode == '13')
          {
                 Refrescar();
                 documento.setFocus();
               }
              if (event.keyCode == '117')
          {
             cancelaTecla(event);
             t_documento('',117);
               }
            });

      var url = server_path + "herramientas/genera_xml/genera_xml.php";
      var param =  "tabla=X_M_DOCUMENTOS&operador==&campos=ID_X_M_DOCUMENTOS&busca=ID_M_PROVEEDORES&xbusca=" + xProveedor + "&filtro=ESTATUS;TIPO&xfiltro=A;ODC";
      var x = enviar(url,param,'POST');
      var registro = XML2Array(x);

      if(registro.length==0)
      {
             $("#xdocumentos_abiertos option[value=-1]").attr("selected",true);
      }

      for (i=0;i<registro.length;i++)
          {
          $('#xdocumentos_abiertos').append($('<option></option>').val(registro[i]['ID_X_M_DOCUMENTOS']).html(registro[i]['ID_X_M_DOCUMENTOS']));
          }
      $('#xdocumentos_abiertos').append($('<option></option>').val('-1').html('-- Nuevo --'));
      setTimeout('FocusLista();',10)
      Abrir_Documento();
	  m_productos.buscar('*');
      m_productos.mostrar();
      m_productos.setFocus();
}

function Refrescar()
{
  documento.xfiltro = xdocumento;
  documento.buscar();
  var xml= genera_xml('X_M_DOCUMENTOS','MONTO_BRUTO,MONTO_DESCUENTO,MONTO_IMPUESTO,NETO','ID_X_M_DOCUMENTOS',xdocumento,'=');
  actualizaHTML(xml, 'PIE', 'totales');
  if(id) documento.localiza('ID_D_DOCUMENTOS',id);
  id=null;

}

function t_documento(objeto, tecla, xml, e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case _enter:
    cancelaTecla(evt);
      var registro = XML2Array(xml);
      if(!registro[0]['ID_D_DOCUMENTOS']) return;
      var xid_d_documentos = registro[0]['ID_D_DOCUMENTOS'];
      var xid = registro[0]['ID'];

      f_edicion.limpiar();
      mostrar_formulario(f_edicion);
      f_edicion.buscar(xid);
    break;
    case _esc:
    cancelaTecla(evt);
            Salir();
    break;
    case _supr:
          cancelaTecla(evt);
      var borrar = confirm('{$t_eliminar_registro}');
        if(borrar)
        {
            var registro = XML2Array(xml);
            var url = server_path + "herramientas/utiles/actualizar_registro.php";
            var param =  "origen=orden_compra/d_documento&procedimiento=ELIMINAR_ITEM&ID=" + unformat(registro[0]['ID']);
            enviar(url,param,'GET');
            waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
        }
      break;
   case 76:
      cancelaTecla(evt);
      if (evt.ctrlKey)
      {
               FocusLista();
      }
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
    case _f5:
            cancelaTecla(evt);
            break;
    case _insert:
            cancelaTecla(evt);
			m_productos.buscar('*');
        m_productos.mostrar();
        m_productos.setFocus();
        break;

    case _f7:
        cancelaTecla(evt);
        f_servicio.limpiar();
        mostrar_formulario(f_servicio);
        f_servicio.setValue('ID_X_M_DOCUMENTOS',xdocumento);
        f_servicio.setValue('TIPO',xtipo);
      break;
    case _f8:
      cancelaTecla(evt);
          break;
    case _f9:
      cancelaTecla(evt);
      var anular = confirm('{$t_documento_anular}');
          if(anular)
          {
                var url = server_path + "herramientas/utiles/actualizar_registro.php";
                var param =  "origen=orden_compra/d_documento&procedimiento=ELIMINAR_DOCUMENTO&ID_X_M_DOCUMENTOS=" + xdocumento;
            enviar(url,param,'GET');
            inicio(0);
        }
      break;

    case _f10:
      if(documento.rows.length)
      {
              var aceptar = confirm('{$t_cerrar_documento}');
        if(aceptar)
        {

                              f_cierre.limpiar();
                f_cierre.buscar(xdocumento);
                f_cierre.setValue('ESTATUS','C');
                              mostrar_formulario(f_cierre);
            }
          }
      else
      {
              alert('{$t_documento_noregistro}');
      }
      break;
    case _f11:
    case _f12:
            cancelaTecla(evt);
            break;
  }
}

function cond_centro_costo()
{
  var xid_concepto_comp = f_servicio.getValue('ID_M_CONCEPTOS_COMPRAS');
  var url   = server_path + "herramientas/genera_xml/genera_xml.php";
  var param = "tabla=V_M_CONCEPTOS_COMPRAS&campos=CONDICION_CENTRO_COSTOS,ID_M_CENTRO_COSTOS,NOMBRE_CENTRO_COSTOS&filtro=ID_M_CONCEPTOS_COMPRAS&xfiltro="+ xid_concepto_comp;
  var x     = enviar(url,param,'POST');
  var registro = valida_xml(x,'CONDICION_CENTRO_COSTOS');
  if(!registro)
  {
   f_servicio.ocultarCampo('ID_M_CENTRO_COSTOS');
  }
  else
  {
   f_servicio.mostrarCampo('ID_M_CENTRO_COSTOS');
   f_servicio.setValue('ID_M_CENTRO_COSTOS',registro[0]['ID_M_CENTRO_COSTOS']);
   f_servicio.setValue('r_ID_M_CENTRO_COSTOS',registro[0]['NOMBRE_CENTRO_COSTOS']);
  }
}

function t_f_servicio(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // Enter
      cancelaTecla(evt);
      break;

    case _esc: //Escape
      cancelaTecla(evt);
      ocultar_formulario(f_servicio);
      break;

    case _f12: // F12
      cancelaTecla(evt);
          var registro = f_servicio.submit();
          if(!registro) return;
          id    = registro[0]['ID_D_DOCUMENTOS'];
          waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
          ocultar_formulario(f_servicio);
          documento.setFocus();


      break;
  }
}
function t_f_cierre(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _enter: // Enter
      cancelaTecla(evt);
      break;

    case _esc: //Escape
      cancelaTecla(evt);
      ocultar_formulario(f_cierre);
          documento.setFocus();
      break;

    case _f12: // F12
      cancelaTecla(evt);
          var registro = f_cierre.submit();
          if(!registro) return;
          waitExec('{$t_realizando_proceso}', 'Refrescar()', 5, 250, 283);
          ocultar_formulario(f_cierre);
          var xid = Busca_Documento(xdocumento);
          Imprimir(xid);

      break;
  }
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
        if(xproducto !='-1')
    {
        f_productos2.buscar(xproducto);
    }

        f_productos2.setValue('ID_M_PRODUCTOS' , xproducto);
        f_productos2.setValue('ID_M_P_DESCRIPCION', origen);
        f_productos2.setValue('CAMPO5', '*');
}
 function t_f_productos(obj, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case _esc://Salir
        f_productos.ocultar();
        ocultar_formulario(f_productos2);
		m_productos.buscar('*');
        m_productos.mostrar();
        m_productos.setFocus();
        break;

    case _f12://Salir
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
    case _esc://Salir
        cancelaTecla(evt);
        f_productos.ocultar();
        ocultar_formulario(f_productos2);
		m_productos.buscar();
        m_productos.mostrar();
        m_productos.setFocus();
        break;

    case _f12: // F12
        cancelaTecla(evt);
        var registro = f_productos2.submit();
        if(!registro) return false;

        xproducto = registro[0]['ID_M_PRODUCTOS'];

        ocultar_formulario(f_productos);

		m_productos.buscar('*');
        m_productos.mostrar();
        m_productos.setFocus();
        m_productos.buscar(xproducto);
        break;
  }
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_edicion':
          t_f_edicion('',_f12);
          break;
       case 'f_servicio':
          t_f_servicio('',_f12);
          break;

       case 'f_cierre':
          t_f_cierre('',_f12);
          break;

       case 'f_productos':
       case 'f_productos2':
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

       case 'f_servicio':
          t_f_servicio('',_esc);
          break;

       case 'f_cierre':
          t_f_cierre('',_esc);
          break;

       case 'f_productos':
       case 'f_productos2':
          t_f_productos2('',_esc);
          break;
    }
}


function Imprimir(xid)
{
   impresora.origin = 'reportes/r_documento_odc';
   impresora.setParam('ID_M_DOCUMENTOS',xid);
   impresora.showDialog=true;
   impresora.preview();
   inicio(0);
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function inicio(recargar)
{
    if(recargar=='') recargar=0;
    switch(recargar)
    {
        case 0:
            m_proveedores.mostrar();
            m_proveedores.setFocus();
            documento.limpiar();
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

function iniciar()
{
     contenedor.inicializa(false);
     centrarObj(contenedor.contenedor);

     documento.inicializa(false);
     documento.mostrar();

     m_proveedores.inicializa(false);
     centrarObj(m_proveedores.contenedor);

     m_productos.inicializa(false);
     centrarObj(m_productos.contenedor);

     f_edicion.inicializa(false);
     f_servicio.inicializa(false);
     f_cierre.inicializa(false);

     f_productos.inicializa();
     addEvent(frm_f_productos.xID_M_P_DESCRIPCION, 'change', armarPlantilla);
     f_productos2.inicializa();

     addEvent(m_proveedores_ENTER, "click",   function() { t_m_proveedores('', _enter, m_proveedores.elementoXml()) } )
     addEvent(m_proveedores_ESC, "click",   function() { t_m_proveedores('', _esc, m_proveedores.elementoXml()) } )

     addEvent(ENTER,  "click",   function() { t_documento('', _enter,  documento.elementoXml()) } )        //Enter
     addEvent(SUP,    "click",   function() { t_documento('', _supr,   documento.elementoXml()) } )        //Delete
     addEvent(ESC,    "click",   function() { t_documento('', _esc,    documento.elementoXml()) } )      //Esc
     addEvent(F3,     "click",   function() { t_documento('', _f3,     documento.elementoXml()) } )      //f3
     addEvent(INS,    "click",   function() { t_documento('', _insert, documento.elementoXml()) } )      //f6
     addEvent(F7,     "click",   function() { t_documento('', _f7,     documento.elementoXml()) } )      //f4
     addEvent(F9,     "click",   function() { t_documento('', _f9,     documento.elementoXml()) } )        //F9
     addEvent(F10,    "click",   function() { t_documento('', _f10,    documento.elementoXml()) } )        //F10
     addEvent(CTRL_L, "click",   function() { t_documento('', 76,      documento.elementoXml()) } )        //ctrl + l

     addEvent(m_productos_INS,   "click",   function() { t_m_productos('',_insert, m_productos.elementoXml()) } )
     addEvent(m_productos_ENTER, "click",   function() { t_m_productos('', _enter, m_productos.elementoXml()) } )
     addEvent(m_productos_ESC,   "click",   function() { t_m_productos('', _esc,   m_productos.elementoXml()) } )

	document.onclick                =        function() { if (parent.menu) parent.menu.reset(); }


     return true;
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