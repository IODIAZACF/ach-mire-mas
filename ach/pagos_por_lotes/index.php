<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$xfecha         = date("d/m/Y");
$xhora             = date("H:i:s");
$id_usuario = getsession('M_USUARIOS_ID_M_USUARIO');

$query = new sql();
$query->sql = "select nombres,id_m_cajas,campo1,campo2 from m_cajas where ip='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas = $query->Record['ID_M_CAJAS'];
$nombre_caja = $query->Record['NOMBRES'];
$ximpresora  = $query->Record['CAMPO1'];
$xpuerto     = $query->Record['CAMPO2'];

$query = new sql();
$query->sql = "SELECT ID_M_TIPO_PAGOS,ID_M_BANCOS,NUMERO,CUENTA,CODIGO1,TITULAR,TELEFONOS,ID_M_CUENTAS_BANCARIAS FROM V_M_TIPO_PAGOS_CAJAS";
$query->ejecuta_query();
$frm_campos = "var frm_campos = new Array();\n";

while($query->next_record())
{
    $tmp ='';
    while (list($c, $v) = each($query->Record))
        {
        if($v=='*' && $c!='ID_M_TIPO_PAGOS')
        {
            if(strlen($tmp)) $tmp .=',';
            $tmp .= "'" .$c . "'";
        }
        $v='';
        }
    $frm_campos .= 'frm_campos[\''.  $query->Record['ID_M_TIPO_PAGOS'] .'\'] = new Array('. $tmp .');' . "\n";
}


$onClose = 'Salir();';
$my_ini     = new ini('modulo');

encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo     = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('formulario2,utiles,auto_tabla,forma,submodal,impresora,forma_simple,jquery');

echo <<<EOT

{$modulo->inicio}

<style type="text/css">
<!--
.resaltado {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
    color: #000000;
}
.resaltado2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
    color: #FF0000;
}
-->
</style>

<div id="contenido">
<table border="0">
<tr>
        <td id="d_GRUPO1"></td>
        <td id="d_GRUPO2"></td>
</tr>
</table>
</div>

{$modulo->fin}

<script type="text/javascript">

var xid_m_cajas                  = '{$xid_m_cajas}';
var xnombre_caja          = '{$xnombre_caja}';
var xfecha                          = '{$xfecha}';
var xnombre_caja          = '{$nombre_caja}';
var xid_m_pagos_lotes = null;
var xsaldo                          = null;
var xtotal                          = null;
var t                                  = null;
var f_activo          = null;
var id_m_documentos   = null;
var xid_d_pagos_lotes = null;

var ximpresora                  = '{$ximpresora}';

var xTITULAR                    = '';
var xCODIGO1                    = '';
var xTELEFONOS                  = '';
var xID_M_BANCOS                = '';
var xNUMERO                     = '';
var xCUENTA                     = '';
var xID_M_CLIENTES          = '';
var xID_M_ANTICIPOS         = '';
var xID_M_CUENTAS_BANCARIAS = '';
var xestatus            ='';


{$frm_campos}

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 100;
contenedor.ancho           = 100;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '  ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal            = true;
contenedor.ayuda           = 1;
contenedor.onClose     = Cerrar_Contenedor;

contenedor_pagos             = new submodal();
contenedor_pagos.nombre      = 'contenedor_pagos';
contenedor_pagos.ancho       = 400;
contenedor_pagos.alto        = 200;
contenedor_pagos.titulo      = ' ';
contenedor_pagos.x           = 100;
contenedor_pagos.y           = 100;
contenedor_pagos.titulo      = 'XXXXXX';
contenedor_pagos.botonCerrar = true;
contenedor_pagos.leyenda     =  '  ';
contenedor_pagos.usaFrame    = false;
contenedor_pagos.interpretar = false;
contenedor_pagos.modal       = true;

m_pagos_lotes               = new lista("pagos_por_lotes/m_pagos_lotes");
m_pagos_lotes.nombre            = 'm_pagos_lotes';
m_pagos_lotes.url           = server_path + "herramientas/genera_xml/genera_xml.php";
m_pagos_lotes.usaCookie     = false;
m_pagos_lotes.funcion           = t_m_pagos_lotes;
m_pagos_lotes.padre             = 'd_GRUPO1';
m_pagos_lotes.buscador      = true;
m_pagos_lotes.onSelect          = select_detalle_pagos;
m_pagos_lotes.filtro        = 'ID_M_CAJAS;CONDICION1';
m_pagos_lotes.xfiltro       = xid_m_cajas+';L';

d_pagos_lotes               = new lista("pagos_por_lotes/d_pagos_lotes");
d_pagos_lotes.nombre        = 'd_pagos_lotes';
d_pagos_lotes.url           = server_path + "herramientas/genera_xml/genera_xml.php";
d_pagos_lotes.funcion       = t_d_pagos_lotes;
d_pagos_lotes.padre         = 'd_GRUPO2';
d_pagos_lotes.buscador      = true;
//d_pagos_lotes.onSelect      = actualizar_detalle;
d_pagos_lotes.filtro        = 'ID_M_PAGOS_LOTES';
d_pagos_lotes.noOcultarCombos= true;

d_cxc                                                  = new lista("pagos_por_lotes/d_cxc");
d_cxc.nombre                              = 'd_cxc';
d_cxc.url                                 = server_path + "herramientas/genera_xml/genera_xml.php";
d_cxc.funcion                             = t_d_cxc;
d_cxc.buscador                            = true;
d_cxc.modal                               = true;
d_cxc.botonCerrar                         = true;
d_cxc.enter                               = 1;
d_cxc.filtro 							  ='SALDO';
d_cxc.xfiltro 							  ='0';
d_cxc.xoperadores 						  ='>';

f_pagos                             = new formulario2('pagos_por_lotes/f_pagos');
f_pagos.nombre                      = 'f_pagos';
f_pagos.funcion                     = t_f_pagos;
f_pagos.padre                       = 'GRUPO1';
f_pagos.submodal                    = 'contenedor_pagos';
f_pagos.noOcultarCombos = true;
f_pagos.onExit                     = cambiar_formulario;

f_pagos2                          = new formulario2('pagos_por_lotes/f_pagos2');
f_pagos2.nombre                   = 'f_pagos2';
f_pagos2.funcion                  = t_f_pagos2;
f_pagos2.padre                    = 'GRUPO2';
f_pagos2.submodal                 = 'contenedor_pagos';
f_pagos2.noOcultarCombos= true;

f_edicion                                    = new formulario2('pagos_por_lotes/f_edicion');
f_edicion.nombre               = 'f_edicion';
f_edicion.funcion              = t_f_edicion;
f_edicion.padre                = 'contenedor_cuadro';
f_edicion.tipo                           = 'formulario';

f_edicion_cxc                                    = new formulario2('pagos_por_lotes/f_edicion_cxc');
f_edicion_cxc.nombre               = 'f_edicion_cxc';
f_edicion_cxc.funcion              = t_f_edicion_cxc;
f_edicion_cxc.padre                = 'contenedor_cuadro';
f_edicion_cxc.tipo                           = 'formulario';

f_control              = new formulario2('pagos_por_lotes/f_control');
f_control.nombre       = 'f_control';
f_control.funcion      = t_f_control;
f_control.padre        = 'contenedor_cuadro';
f_control.tipo               = 'formulario';

f_descuento              = new formulario2('pagos_por_lotes/f_descuento');
f_descuento.nombre       = 'f_descuento';
f_descuento.funcion      = t_f_descuento;
f_descuento.padre        = 'contenedor_cuadro';
f_descuento.tipo             = 'formulario';


var mask               = new Mask('#,###.##', 'number');
var fs                 = new forma_simple();
var impresora          = new printer();

function t_f_descuento(elemento, tecla)
{
  switch (tecla)
  {
    case _enter:
      break;
    case _esc:
         ocultar_formulario(f_descuento);
             d_caja.setFocus();
      break;
    case _f12:
      var registro = f_descuento.submit();
      if(registro)
      {
         ocultar_formulario(f_descuento);
             d_caja.setFocus();
                 d_caja.actualizar(id_m_documentos);
      }
      break;
  }
}

function t_f_control(elemento, tecla)
{
  switch (tecla)
  {
    case _enter:
    case _esc:
    break;
    case _f12:
      var registro = f_control.submit();
      if(registro)
      {
         d_pagos_lotes.actualizar(xid_d_pagos_lotes);
         ocultar_formulario(f_control);
         d_pagos_lotes.setFocus();
      }
      else
      {
      	 alert('{$t_guardar_doc_fiscal}');
      }
      break;
  }
}

function Cerrar_Contenedor()
{
    if(contenedor.modalResult)
    {
      switch (f_activo.nombre)
     {
       case 'f_edicion':
          t_f_edicion('',_esc)
           break;
       case 'f_edicion_cxc':
          t_f_edicion_cxc('',_esc)
           break;
           case 'f_pagos':
       case 'f_pagos2':
          t_f_pagos2('',_esc)
           break;
       case 'f_control':
          t_f_control('',_esc)
           break;
       case 'f_descuento':
          t_f_descuento('',_esc)
           break;
       default:
               alert('no hace nada para '+ f_activo.nombre);
     }
    }
    else
    {
            f_activo.setFocus();
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_edicion':
          t_f_edicion('',_esc)
       break;
       case 'f_edicion_cxc':
          t_f_edicion_cxc('',_esc)
       break;
       case 'f_control':
          t_f_control('',_esc)
       break;
       case 'f_pagos':
       case 'f_pagos2':
          t_f_pagos2('',_esc)
       break;
       case 'f_descuento':
          t_f_descuento('',_esc)
       break;
       default:
        alert('no hace nada para '+ f_activo.nombre);
    }
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_edicion':
          t_f_edicion('',_f12)
       break;
       case 'f_edicion_cxc':
          t_f_edicion_cxc('',_f12)
       break;
       case 'f_control':
          t_f_control('',_f12)
       break;
       case 'f_pagos':
       case 'f_pagos2':
          t_f_pagos2('',_f12)
       break;
       case 'f_descuento':
          t_f_descuento('',_f12)
       break;
       default:
        alert('no hace nada para '+ f_activo.nombre);
    }
}

function Guardar_Pago()
{
    t_f_pagos('',_f12);
}

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
        xformulario.ocultar();
}

function mostrar_formulario(xformulario,b_cerrar)
{
    if(b_cerrar==undefined)
    {
            b_cerrar = true;
    }
    f_activo=xformulario;
    contenedor.modalResult = b_cerrar;
    contenedor.setTitle(xformulario.titulo);
    contenedor.setWidth(xformulario.ancho);
    contenedor.setHeight(xformulario.alto);
    contenedor.setLegend(xformulario.leyenda);
    centrarObj(contenedor.contenedor);
    contenedor.mostrar();
        xformulario.mostrar();
    xformulario.setFocus();
    setTimeout('f_activo.setFocus();',10);
}

function t_f_edicion_cxc (objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12:

      for (i=1;i<=3;i++)
      {
        if (unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO"+i)) > 0)
        {
         if(!f_edicion_cxc.getValue("ID_M_CONCEPTOS"+i))
         {
           alert('{$t_agregar_concepto}'+i+'!');
           return false;
         }
        }
      }

      if(unformat(f_edicion_cxc.getValue("MONTO_RETENCION_IVA")) > 0 && f_edicion_cxc.getValue("DOCUMENTO_RENTECION_IVA")=='')
      {
        alert('{$t_agregar_concepto}');
        return;
      }
      if(unformat(f_edicion_cxc.getValue("MONTO_RETENCION_ISLR")) > 0 && f_edicion_cxc.getValue("DOCUMENTO_RENTECION_ISLR")=='')
      {
        alert('{$t_agregar_concepto}');
        return;
      }

            var xmonto = unformat(f_edicion_cxc.getValue('MONTO'));
            if(xmonto<=0)
            {
            alert('{$t_monto_mayor}');
            f_edicion_cxc.setFocus();
            return false;
            }
            if(xmonto>xsaldo)
            {
            alert('{$t_valor_no_valido}');
            f_edicion_cxc.setFocus();
            return false;
            }

            var tsaldo = unformat(f_edicion_cxc.getValue('SALDO'));
            if(tsaldo<0)
            {
            alert('{$t_valor_no_valido}');
            f_edicion_cxc.setFocus();
            return false;
            }
        var registro = f_edicion_cxc.submit();
            if(!registro) return false;
        ocultar_formulario(f_edicion_cxc);
                Refrescar();
    break;
    case _esc:
              ocultar_formulario(f_edicion);
        d_pagos_lotes.setFocus();
    break;

  }
}

function t_f_edicion (objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12:
            var xmonto = unformat(f_edicion.getValue('MONTO'));
            if(xmonto<=0)
            {
                alert('{$t_monto_mayor}');
            f_edicion.setFocus();
            return false;
            }
            if(xmonto>xsaldo)
            {
                alert('{$t_valor_no_valido}');
            f_edicion.setFocus();
            return false;
            }
            var tsaldo = unformat(f_edicion.getValue('SALDO'));
            if(tsaldo<0)
            {
                alert('{$t_valor_no_valido}');
            f_edicion.setFocus();
            return false;
            }

              var xdescuento  = unformat(f_edicion.getValue("MONTO_DESCUENTO"));
            if(xdescuento)
            {
                var xmonto_descuento = xdescuento / 1.12;
                          var tsaldo   = mask.format(xmonto_descuento);
            fs.armar('pagos_por_lotes/fs_descuento_documento');
                fs.xbusca = f_edicion.getValue('DOCUMENTO');
                fs.setValue('DESCUENTO_DOCUMENTO',xmonto_descuento);
                var registro = fs.submit();
            }

            var registro = f_edicion.submit();
            if(!registro) return false;
        ocultar_formulario(f_edicion);
        Refrescar();
    break;
    case _esc:
              ocultar_formulario(f_edicion);
        d_pagos_lotes.setFocus();
    break;
  }
}
function Refrescar()
{
    m_pagos_lotes.actualizar(xid_m_pagos_lotes);
    var xml =m_pagos_lotes.elementoXml();
    var registro = valida_xml(xml, 'ID_M_PAGOS_LOTES');
    if(!registro) return;
    d_pagos_lotes.buscar('*');
    d_pagos_lotes.setFocus();
    xsaldo = unformat(registro[0]['SALDO']);
}

function select_detalle_pagos()
{
  if (t) window.clearTimeout(t);
  t = window.setTimeout('actualizar_detalle_pagos()',300);
}

function actualizar_detalle_pagos()
{
    d_pagos_lotes.limpiar();
    var registro = valida_xml(m_pagos_lotes.elementoXml(), 'ID_M_PAGOS_LOTES');
    if(!registro) return;

    xid_m_pagos_lotes     = registro[0]['ID_M_PAGOS_LOTES'];
    xabono                               = registro[0]['CREDITOS'];
    xestatus                       = registro[0]['ESTATUS'];
    xsaldo                = unformat(registro[0]['SALDO']);
    d_pagos_lotes.xfiltro = xid_m_pagos_lotes;
    d_pagos_lotes.buscar('*');
    mostrarLeyenda(0);
}

function cambiar_formulario()
{
          f_pagos2.setFocus();
}

function t_d_cxc(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
      cancelaTecla(evt);
      break;

    case _enter:
      cancelaTecla(evt);
      var registro   = valida_xml(xml,'INDICE');
      if(!registro)return;
      d_cxc.ocultar();

      var xcliente   = registro[0]['ID_M_CLIENTES'];
      if(registro[0]['CLASE']=='FAC')
      {
		f_edicion.limpiar();
        f_edicion.setValue('xbusca','-1');
        f_edicion.setValue('SALDO1',registro[0]['SALDO']);
        f_edicion.setValue('O_SALDO',registro[0]['SALDO']);
        var tsaldo = unformat(registro[0]['SALDO']);
        if(tsaldo>xsaldo)
        {
         tsaldo= xsaldo;
        }
        f_edicion.setValue('MONTO'            ,tsaldo);
        f_edicion.setValue('ID_PADRE'         ,registro[0]['ID_PADRE']);
        f_edicion.setValue('ID_X_M_DOCUMENTOS',registro[0]['DOCUMENTO']);
        f_edicion.setValue('DOCUMENTO'        ,registro[0]['DOCUMENTO']);
        f_edicion.setValue('ID_PADRE'         ,registro[0]['ID_PADRE']);

        f_edicion.setValue('NOMBRES'         ,registro[0]['NOMBRES']);
        f_edicion.setValue('FECHA_DOCUMENTO' ,registro[0]['FECHA_DOCUMENTO']);
        f_edicion.setValue('ID_M_DOCUMENTOS' ,registro[0]['DOCUMENTO']);
        f_edicion.setValue('ID_M_PAGOS_LOTES',xid_m_pagos_lotes);

        mostrar_formulario(f_edicion);
      }
      else
      {
        f_edicion_cxc.limpiar();
        f_edicion_cxc.setValue('xbusca','-1');
        f_edicion_cxc.setValue('SALDO1',registro[0]['SALDO']);
        var tsaldo = unformat(registro[0]['SALDO']);

        if(tsaldo>xsaldo)
        {
          tsaldo= xsaldo;
        }

        f_edicion_cxc.setValue('MONTO'            ,tsaldo);
        f_edicion_cxc.setValue('ID_PADRE'         ,registro[0]['ID_PADRE']);
        f_edicion_cxc.setValue('ID_X_M_DOCUMENTOS',registro[0]['ID_X_M_DOCUMENTOS']);
        f_edicion_cxc.setValue('ID_M_DOC_FINAL'   ,registro[0]['ID_M_DOC_FINAL']);
        f_edicion_cxc.setValue('ID_PADRE'         ,registro[0]['ID_PADRE']);
        f_edicion_cxc.setValue('NOMBRES'         ,registro[0]['NOMBRES']);
        f_edicion_cxc.setValue('FECHA_DOCUMENTO' ,registro[0]['FECHA_DOCUMENTO']);
        f_edicion_cxc.setValue('ID_M_DOCUMENTOS' ,registro[0]['DOCUMENTO']);
        f_edicion_cxc.setValue('ID_M_PAGOS_LOTES',xid_m_pagos_lotes);
        f_edicion_cxc.setValue('FECHA_DOCUMENTO_ISLR',xfecha);
        f_edicion_cxc.setValue('FECHA_RECEPCION_ISLR',xfecha);
        f_edicion_cxc.setValue('FECHA_DOCUMENTO_IVA',xfecha);
        f_edicion_cxc.setValue('FECHA_RECEPCION_IVA',xfecha);
        mostrar_formulario(f_edicion_cxc);
      }
      break;

    case _supr:
      cancelaTecla(evt);
      break;
    case _esc:
    cancelaTecla(evt);
        d_cxc.ocultar();
        d_pagos_lotes.setFocus();
        Refrescar();
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



function t_f_pagos(objeto, tecla, xml)
{
  t_f_pagos2('',tecla,xml);
}

function t_f_pagos2(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
          case _esc:
            cancelaTecla(evt);
        ocultar_formulario_pagos();
        //m_pagos_lotes.setFocus();
    break;
    case _f12:
            cancelaTecla(evt);
            var xmonto      = unformat(f_pagos2.getValue('CREDITOS'));
            if(xmonto<=0)
            {
                alert('{$t_monto_mayor}');
            f_pagos.setFocus();
            return false;
            }

            var registro = f_pagos2.submit();
            if (!registro) return false;
        ocultar_formulario_pagos();
        xid_m_pagos_lotes = registro[0]['ID_M_PAGOS_LOTES'];
        m_pagos_lotes.setFocus();
        m_pagos_lotes.actualizar(xid_m_pagos_lotes);
    break;
  }
}


function mostrar_formulario_pagos()
{
    f_activo = f_pagos;
    contenedor_pagos.setTitle('Abono');
    contenedor_pagos.setWidth(800);
    contenedor_pagos.setHeight(350);
    centrarObj(contenedor_pagos.contenedor);
    contenedor_pagos.setLegend(f_pagos.leyenda);
    contenedor_pagos.mostrar();
    f_pagos.mostrar();
    f_pagos2.mostrar();
    setTimeout('f_pagos.setFocus();',10);
}
function ocultar_formulario_pagos()
{
    f_pagos.ocultar();
    f_pagos2.ocultar();
    contenedor_pagos.ocultar();
    m_pagos_lotes.setFocus();
}

function t_m_pagos_lotes(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
          case _insert:
            cancelaTecla(evt);

        f_pagos.limpiar();
        f_pagos2.limpiar();
        masDatos();
        mostrar_formulario_pagos();
                f_pagos2.setValue('FECHA_PAGO',xfecha);
        f_pagos2.setValue('CONDICION1','L');
        f_pagos2.setValue('ID_M_CAJAS',xid_m_cajas);
        f_pagos2.mostrar();
        f_pagos.setFocus();
        break;

    case _enter:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_PAGOS_LOTES');
      if(!registro)return;
      xsaldo = unformat(registro[0]['SALDO']);
      d_pagos_lotes.setFocus();
      xestatus= registro[0]['ESTATUS'];
      mostrarLeyenda(1);
      break;

    case _supr:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_M_PAGOS_LOTES');
      if(!registro) return false;
      if(registro[0]['ESTATUS']!= 'C')
      {
       if(confirm('{$t_eliminar_registro}'))
       {
         fs.armar('pagos_por_lotes/fs_m_pagos_lotes');
         fs.xbusca = registro[0]['ID_M_PAGOS_LOTES'];
         fs.eliminar();
         m_pagos_lotes.buscar();
       }
      }
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
      var registro = valida_xml(xml,'ID_M_PAGOS_LOTES');
      if(!registro) return false;
      if(registro[0]['ESTATUS']!= 'C')
      {
       xid_m_pagos_lotes = registro[0]['ID_M_PAGOS_LOTES'];

       f_pagos.limpiar();
       f_pagos2.limpiar();

       f_pagos.buscar(xid_m_pagos_lotes);
       f_pagos2.buscar(xid_m_pagos_lotes);

       xTITULAR            = registro[0]['TITULAR'];
       xCODIGO1            = registro[0]['CODIGO1'];
       xTELEFONOS          = registro[0]['TELEFONOS'];
       xID_M_BANCOS        = registro[0]['ID_M_BANCOS'];
       xNUMERO             = registro[0]['NUMERO'];
       xCUENTA             = registro[0]['CUENTA'];
       xID_M_CUENTAS_BANCARIAS = registro[0]['ID_M_CUENTAS_BANCARIAS'];
       masDatos();
       mostrar_formulario_pagos();
      }
      break;

    case _f7:
    case _f8:
    case _f9:
      cancelaTecla(evt);
      break;

    case _f10:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ESTATUS');
//        alert(registro[0]['ID_M_PAGOS_LOTES']);
      if(!registro || registro[0]['ESTATUS']!='A' ) return false;
      if(d_pagos_lotes.rows.length)
      {
       xid_m_pagos_lotes = registro[0]['ID_M_PAGOS_LOTES'];

       if (confirm('{$t_documento_cerrar}'))
       {
        fs.armar('pagos_por_lotes/fs_m_pagos_lotes');
        fs.xbusca = registro[0]['ID_M_PAGOS_LOTES'];
        fs.setValue('ESTATUS','C');
        var registro = fs.submit();
        m_pagos_lotes.buscar('*');
       }
      }
 	  return;
    break;

    case _f11:
    case _f12:
      cancelaTecla(evt);
      break;
  }
}

function t_d_pagos_lotes(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
      cancelaTecla(evt);
      if(xestatus== 'C' || !xid_m_pagos_lotes) return;
      d_cxc.limpiar();
	  d_cxc.mostrar();
      d_cxc.setFocus();
      break;

    case _enter:
      cancelaTecla(evt);
      break;

    case _supr:
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_D_PAGOS_LOTES');
      if(!registro) return;
      if(xestatus== 'C' || !xid_m_pagos_lotes)
      {
       alert('{$t_operacion_no_permitida}');
       return;
      }

      if(confirm('{$t_eliminar_registro}'))
      {
       fs.armar('pagos_por_lotes/fs_d_pagos_lotes');
       fs.xbusca = registro[0]['ID_D_PAGOS_LOTES'];
       fs.eliminar();
       Refrescar();
      }
      break;

    case _esc:
      cancelaTecla(evt);
      m_pagos_lotes.setFocus();
      mostrarLeyenda(0);
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
      cancelaTecla(evt);
      var registro = valida_xml(xml,'ID_X_M_DOCUMENTOS');
      if(!registro)return;
      if(confirm('{$t_documento_cerrar}'))
      {
       xid_d_pagos_lotes = registro[0]['ID_D_PAGOS_LOTES'];
       fs.armar('pagos_por_lotes/fs_cerra_factura');
       fs.setValue('ESTATUS','C');
       fs.xbusca = registro[0]['ID_X_M_DOCUMENTOS'];
       fs.submit();

       id_m_documentos = Busca_Documento(registro[0]['ID_X_M_DOCUMENTOS']);
       Imprimir();
      }
   	  break;

    case _f11:
    case _f12:
      cancelaTecla(evt);
      break;
  }
}
function masDatos()
{

    var xtipo = f_pagos.getValue("ID_M_TIPO_PAGOS");
    if(!xtipo) return;
    f_pagos2.ocultarCampo("ID_M_BANCOS");

    f_pagos2.setValue("ID_M_BANCOS","");
    f_pagos2.ocultarCampo("NUMERO");
    f_pagos2.setValue("NUMERO","");
    f_pagos2.ocultarCampo("CUENTA");
    f_pagos2.setValue("CUENTA","");

    f_pagos2.ocultarCampo("CODIGO1");
    f_pagos2.setValue("CODIGO1","");

    f_pagos2.ocultarCampo("TITULAR");
    f_pagos2.setValue("TITULAR","");

    f_pagos2.ocultarCampo("TELEFONOS");
    f_pagos2.setValue("TELEFONOS","");


    f_pagos2.ocultarCampo("ID_M_CLIENTES");
    f_pagos2.setValue("ID_M_CLIENTES",xID_M_CLIENTES);

    f_pagos2.ocultarCampo("ID_M_CUENTAS_BANCARIAS");
    f_pagos2.setValue("ID_M_CUENTAS_BANCARIAS","");

    for(i=0;i<frm_campos[xtipo].length;i++)
    {
      f_pagos2.mostrarCampo(frm_campos[xtipo][i]);
      var xvalor = eval('x'+frm_campos[xtipo][i]);
      f_pagos2.setValue(frm_campos[xtipo][i],xvalor);
    }
    f_pagos2.setValue('ID_M_TIPO_PAGOS',xtipo);
}

function Cambia_Estilo()
{
    frm_f_pagos.CREDITOS.className='resaltado';
    f_pagos2.setValue('CREDITOS', f_pagos.getValue('CREDITOS'));
}

function Calcular_saldo2()
{
  var xdescuento  = unformat(f_edicion.getValue("MONTO_DESCUENTO"));
  var xmonto1     = unformat(f_edicion.getValue("O_SALDO"));
  var tsaldo   = xmonto1-xdescuento;
  if(tsaldo=='NaN') tsaldo = 0;
  var tsaldo   = mask.format(tsaldo);
  f_edicion.setValue("SALDO1",tsaldo);
  f_edicion.setValue("MONTO",tsaldo);

  var xmonto1  = unformat(f_edicion.getValue("SALDO1"));
  var xmonto   = unformat(f_edicion.getValue("MONTO"));

  var tsaldo   = xmonto1-xmonto;
  if(tsaldo=='NaN') tsaldo = 0;
  var tsaldo   = mask.format(tsaldo);

  f_edicion.setValue("SALDO",tsaldo);
}

function Calcular_saldo()
{
  var xmonto1  = unformat(f_edicion.getValue("SALDO1"));
  var xmonto   = unformat(f_edicion.getValue("MONTO"));

  var tsaldo   = xmonto1-xmonto;
  if(tsaldo=='NaN') tsaldo = 0;
  var tsaldo   = mask.format(tsaldo);
  f_edicion.setValue("SALDO",tsaldo);
}

function Imprimir()
{
    if(!ximpresora)
    {
            impresora.origin = 'reportes/r_documento_fact';
            impresora.setParam('ID_M_DOCUMENTOS',id_m_documentos);
            do
            {
                    impresora.print();
            } while (confirm("{$t_reimprimir_documento}")==1)

        f_control.buscar(id_m_documentos);
        mostrar_formulario(f_control,false);
    }
    else
    {
            var xsufijo      = ximpresora.toLowerCase();
        if(xsufijo=='hka') xsufijo = 'bmc';
            impresora.origin = 'reportes/r_documento_fact_' + xsufijo;
            impresora.setParam('ID_M_DOCUMENTOS',id_m_documentos);
            var res = impresora.printToCom(xpuerto, ximpresora);

        impresora.printerType = ximpresora;
                impresora.port        = xpuerto;
            while (!res)
            {
                alert('EpsonPrinterCode: ' + impresora.EpsonPrinterCode() + '\\nEpsonStatus: ' + impresora.EpsonStatus() +  '\\nEpsonLastMessageCode: ' + impresora.EpsonLastMessageCode() +  '\\nEpsonLastMessage: ' + impresora.EpsonLastMessage());
            }
            if (res)
            {
                    var docfiscal = impresora.lastNumber;
                var url       = server_path + 'herramientas/utiles/actualizar_registro.php';
                var params = 'tabla=M_DOCUMENTOS&';
                params    += '&busca=ID_M_DOCUMENTOS';
                params    += '&xbusca='+id_m_documentos;
                params    += '&c_DOC_FISCAL_CSS=' + docfiscal;
                var x = enviar(url, params, 'POST');
            }
        inicio(0);
        }
}

function Calcular_monto()
{
  var xsaldo1 = unformat(f_edicion_cxc.getValue("SALDO1"));
  var xsaldo2 = unformat(f_edicion_cxc.getValue("SALDO1"));
  if(xsaldo1 > unformat(xsaldo))
  {
  	xsaldo1 = unformat(xsaldo);
  }
  var xmonto = unformat(f_edicion_cxc.getValue("MONTO"));

  var xmonto1 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO1"));
  var xmonto2 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO2"));
  var xmonto3 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO3"));
  var xmonto4 = unformat(f_edicion_cxc.getValue("MONTO_RETENCION_ISLR"));
  var xmonto5 = unformat(f_edicion_cxc.getValue("MONTO_RETENCION_IVA"));
  var x       = mask.format(xmonto1 + xmonto2 + xmonto3 + xmonto4 + xmonto5);
  var xmonto_retenciones = unformat(x);
  var x = (xsaldo1 - xmonto_retenciones);

  var xmonto_pago = unformat(x);
  if(xsaldo2>unformat(xsaldo))
  {
   xmonto_pago = unformat(xsaldo);
  }
  var x = (xmonto_pago+xmonto_retenciones);
  var t1 = unformat(x);
  var tsaldo1   = mask.format(xmonto_pago);

  if(xsaldo2>unformat(xsaldo))
  {
   tsaldo1   = xsaldo;
  }
  var xmonto_saldo_a = xsaldo2 - t1;
  xmonto_saldo_a     = (Math.round(xmonto_saldo_a*100)/100);

  var tsaldo2   = mask.format(xmonto_saldo_a);
  f_edicion_cxc.setValue("MONTO",tsaldo1);
  f_edicion_cxc.setValue("SALDO",tsaldo2);
}



function Calcular_monto2()
{
  var xsaldo1 = unformat(f_edicion_cxc.getValue("SALDO1"));
  var xsaldo2 = unformat(f_edicion_cxc.getValue("SALDO1"));

  if(xsaldo1>xsaldo)
  {
          xsaldo1 = xsaldo;
  }
  var xmonto = unformat(f_edicion_cxc.getValue("MONTO"));

  var xmonto1 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO1"));
  var xmonto2 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO2"));
  var xmonto3 = unformat(f_edicion_cxc.getValue("MONTO_CONCEPTO3"));
  var xmonto4 = unformat(f_edicion_cxc.getValue("MONTO_RETENCION_ISLR"));
  var xmonto5 = unformat(f_edicion_cxc.getValue("MONTO_RETENCION_IVA"));
  var x  =    mask.format(xmonto1 + xmonto2 + xmonto3 + xmonto4 + xmonto5);
  var xmonto_con = unformat(x);
//  alert((xmonto+xmonto1+ xmonto2 +xmonto3 +xmonto4 +xmonto5));  favor revisar luis nuñez
  var y = (xmonto+xmonto_con);

  var t1 = (Math.round(y*100)/100);
  var xmonto_saldo = xsaldo2 - (t1);
  var tsaldo2   = mask.format(xmonto_saldo);
  f_edicion_cxc.setValue("SALDO",tsaldo2);
}


function iniciar()
{
    if(xid_m_cajas!='')
    {
            contenedor.inicializa();
            centrarObj(contenedor.contenedor);

                contenedor_pagos.inicializa(false);
                centrarObj(contenedor_pagos.contenedor);

        $('#contenedor_pagos_cuadro').empty();
            $('#contenedor_pagos_cuadro').append('<table border="0"><tr><td colspan="2" valign="top" id="xMONEDA" align="center"><span id="moneda"></span></td></tr><tr><td id="GRUPO1" valign="top"></td><td id="GRUPO2" valign="top"></td></tr></table>');

            m_pagos_lotes.inicializa(false);
            m_pagos_lotes.mostrar();

            d_pagos_lotes.inicializa(false);
            d_pagos_lotes.mostrar();

        d_cxc.inicializa(false);
        centrarObj(d_cxc.contenedor);

        f_pagos.inicializa();
        f_pagos2.inicializa();

        f_edicion.inicializa(false);
        f_edicion_cxc.inicializa(false);

        f_control.inicializa(false);

        f_descuento.inicializa(false);

        actualizaTag('ENCABEZADONOMBRE_CAJA',xnombre_caja);

        frm_f_edicion.MONTO_DESCUENTO.className ='resaltado2';
        frm_f_edicion.SALDO.className ='resaltado';
        frm_f_edicion.MONTO.className ='resaltado';
        frm_f_edicion.SALDO1.className='resaltado';

        addEvent(frm_f_edicion.SALDO, 'focus', function(){frm_f_edicion.SALDO.className='resaltado';});
        addEvent(frm_f_edicion.SALDO, 'blur', function(){frm_f_edicion.SALDO.className='resaltado';});
        addEvent(frm_f_edicion.SALDO, 'keyup', function(){frm_f_edicion.SALDO.className='resaltado';});

        addEvent(frm_f_edicion.MONTO, 'focus', function(){frm_f_edicion.MONTO.className='resaltado';});
        addEvent(frm_f_edicion.MONTO, 'blur', function(){frm_f_edicion.MONTO.className='resaltado';});
        addEvent(frm_f_edicion.MONTO, 'keyup', function(){frm_f_edicion.MONTO.className='resaltado';});

        addEvent(frm_f_edicion.SALDO1, 'focus', function(){frm_f_edicion.SALDO1.className='resaltado';});
        addEvent(frm_f_edicion.SALDO1, 'blur', function(){frm_f_edicion.SALDO1.className='resaltado';});
        addEvent(frm_f_edicion.SALDO1, 'keyup', function(){frm_f_edicion.SALDO1.className='resaltado';});

        addEvent(frm_f_edicion.MONTO_DESCUENTO, 'focus', function(){frm_f_edicion.MONTO_DESCUENTO.className='resaltado2';});
        addEvent(frm_f_edicion.MONTO_DESCUENTO, 'blur', function(){frm_f_edicion.MONTO_DESCUENTO.className='resaltado2';});
        addEvent(frm_f_edicion.MONTO_DESCUENTO, 'keyup', function(){frm_f_edicion.MONTO_DESCUENTO.className='resaltado2';});

        addEvent(frm_f_pagos.ID_M_TIPO_PAGOS, 'change', masDatos);
        addEvent(frm_f_pagos.CREDITOS, 'focus', Cambia_Estilo);
        addEvent(frm_f_pagos.CREDITOS, 'blur' , Cambia_Estilo);
        addEvent(frm_f_pagos.CREDITOS, 'keyup', Cambia_Estilo);


//------------------------------------------------------------------------------

          //addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO1, 'focus', Calcular_monto);
          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO1, 'blur',  Calcular_monto);
//          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO1, 'keyup', Calcular_monto);

          //addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO2, 'focus', Calcular_monto);
          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO2, 'blur',  Calcular_monto);
//          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO2, 'keyup', Calcular_monto);

          //addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO3, 'focus', Calcular_monto);
          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO3, 'blur',  Calcular_monto);
//          addEvent(frm_f_edicion_cxc.MONTO_CONCEPTO3, 'keyup', Calcular_monto);

          //addEvent(frm_f_edicion_cxc.MONTO_RETENCION_ISLR, 'focus', Calcular_monto);
          addEvent(frm_f_edicion_cxc.MONTO_RETENCION_ISLR, 'blur',  Calcular_monto);
//          addEvent(frm_f_edicion_cxc.MONTO_RETENCION_ISLR, 'keyup', Calcular_monto);


//          addEvent(frm_f_edicion_cxc.MONTO_RETENCION_IVA, 'focus', Calcular_monto);
          addEvent(frm_f_edicion_cxc.MONTO_RETENCION_IVA, 'blur',  Calcular_monto);
//          addEvent(frm_f_edicion_cxc.MONTO_RETENCION_IVA, 'keyup', Calcular_monto);


          addEvent(frm_f_edicion_cxc.MONTO, 'keyup', Calcular_monto2);


//------------------------------------------------------------------------------

        frm_f_pagos.CREDITOS.className='resaltado';
        addEvent(frm_f_edicion.MONTO, 'blur' ,  Calcular_saldo);
//        addEvent(frm_f_edicion.MONTO, 'keyup', Calcular_saldo);

        addEvent(frm_f_edicion.MONTO_DESCUENTO, 'blur' ,  Calcular_saldo2);
//        addEvent(frm_f_edicion.MONTO_DESCUENTO, 'keyup',  Calcular_saldo2);

            document.onclick=function() { if (parent.menu) parent.menu.reset(); }

        addEvent(M_PAGOS_INS,         "click",   function() { t_m_pagos_lotes('', _insert,          m_pagos_lotes.elementoXml())});
        addEvent(M_PAGOS_F6,         "click",   function() { t_m_pagos_lotes('', _f6,             m_pagos_lotes.elementoXml())});
        addEvent(M_PAGOS_SUPR,         "click",   function() { t_m_pagos_lotes('', _supr,          m_pagos_lotes.elementoXml())});
        addEvent(M_PAGOS_F10,         "click",   function() { t_m_pagos_lotes('', _f10,           m_pagos_lotes.elementoXml())});
        addEvent(M_PAGOS_ENTER,        "click",   function() { t_m_pagos_lotes('', _enter,          m_pagos_lotes.elementoXml())});
        addEvent(M_PAGOS_ESC,        "click",   function() { t_m_pagos_lotes('', _esc,           m_pagos_lotes.elementoXml())});

        addEvent(D_PAGOS_INS,        "click",   function() { t_d_pagos_lotes('', _insert,           d_pagos_lotes.elementoXml())});
        addEvent(D_PAGOS_SUPR,        "click",   function() { t_d_pagos_lotes('', _supr,           d_pagos_lotes.elementoXml())});
        addEvent(D_PAGOS_F10,        "click",   function() { t_d_pagos_lotes('', _f10,           d_pagos_lotes.elementoXml())});
        addEvent(D_PAGOS_ESC,        "click",   function() { t_d_pagos_lotes('', _esc,           d_pagos_lotes.elementoXml())});

        addEvent(d_cxc_ENTER,         "click",   function() { t_d_cxc('', _enter,         d_cxc.elementoXml()) } ) //ENTER
        addEvent(d_cxc_ESC,         "click",   function() { t_d_cxc('', _esc,                 d_cxc.elementoXml()) } ) //ESC

            return true;
        }
    else
    {
        alert('{$t_autorizacion_recibir_pagos}');
            return false;
    }
}



function Salir()
{
        parent.proceso.location.href = server_path + 'main/inicio.php';
}

function inicio(registro)
{
        switch(registro)
    {
            case 0:
                mostrarLeyenda(0);
            m_pagos_lotes.buscar('*');
            m_pagos_lotes.setFocus();
        break;
    }

}


var resp =iniciar();

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