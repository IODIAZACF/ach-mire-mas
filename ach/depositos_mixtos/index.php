<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$xusuario = getsession('M_USUARIOS_ID_M_USUARIO');

$onClose = 'Salir();';
$modulo  = new class_modulo('modulo',$onClose);

$fecha   = date("d/m/Y");

$query = new sql();
$query->sql = "select id_m_cajas from m_cajas where ip='". $_SERVER['REMOTE_ADDR'] ."'";
$query->ejecuta_query();
$query->next_record();
$xid_m_cajas = $query->Record['ID_M_CAJAS'];

$query = new sql();
$query->sql = "SELECT ID_M_TIPO_PAGOS,ID_M_BANCOS,NUMERO,CUENTA,CODIGO1,TITULAR,TELEFONOS,ID_M_CUENTAS_BANCARIAS FROM V_M_TIPO_PAGOS_CAJAS WHERE ID_M_TIPO_PAGOS IN ('0011','0012','0014','0015','0018')";
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
    $tipo_pagos .= $query->Record['ID_M_TIPO_PAGOS'].',';
}

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('auto_tabla,utiles,formulario2,forma,submodal,impresora,jquery');

echo <<<EOT

{$modulo->inicio}

<div id="contenido">
</div>

{$modulo->fin}
<script type="text/javascript">

var xid_m_cajas 	  ='{$xid_m_cajas}';
var xtipo_pago_filtro = '{$tipo_pagos}';
xtipo_pago_filtro 	  = xtipo_pago_filtro.substring(0, xtipo_pago_filtro.length-1);
var xusuario    	  = '{$xusuario}';
var xfecha	    	  = '{$fecha}';
var t		    	  = null;
var xtipopago   	  = null;
var impresora   	  = new printer();
var dxml			  = null;

var xTITULAR    		= '';
var xCODIGO1    		= '';
var xTELEFONOS  		= '';
var xID_M_BANCOS		= '';
var xNUMERO     		= '';
var xCUENTA     		= '';
var xID_M_CLIENTES  	= '';
var xID_M_ANTICIPOS 	= '';
var xID_M_CUENTAS_BANCARIAS = '';

{$frm_campos}

// Se Crea el Contenedor del Formulario
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'Formulario de Deposito';
contenedor.botonCerrar = true;
contenedor.leyenda     = '    ';
contenedor.usaFrame    = false;
contenedor.ayuda	   = 1;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.onClose	   = Cerrar_contenedor;

contenedor_pagos             = new submodal();
contenedor_pagos.nombre      = 'contenedor_pagos';
contenedor_pagos.ancho       = 400;
contenedor_pagos.alto        = 200;
contenedor_pagos.titulo      = ' ';
contenedor_pagos.x           = 100;
contenedor_pagos.y           = 100;
contenedor_pagos.titulo      = 'XXXXXX';
contenedor_pagos.botonCerrar = true;
contenedor_pagos.ayuda	   = 1;
contenedor_pagos.leyenda     = '  ';
contenedor_pagos.usaFrame    = false;
contenedor_pagos.interpretar = false;
contenedor_pagos.modal       = true;

d_pagos             = new lista('depositos_mixtos/d_pagos');
d_pagos.nombre      = 'd_pagos';
d_pagos.funcion     = t_d_pagos;
d_pagos.padre       = "contenido";
d_pagos.url         = server_path + 'herramientas/genera_xml/genera_xml.php';
d_pagos.filtro		= 'ID_M_TIPO_PAGOS';
d_pagos.xfiltro		= xtipo_pago_filtro;
d_pagos.xoperadores	= 'IN';
d_pagos.onSelect    = act_timer;
d_pagos.asyncLoad	= true;
d_pagos.buscador    = true;

// Se Crea el Formulario de deposito
f_deposito         = new formulario2('depositos_mixtos/f_deposito');
f_deposito.nombre  = 'f_deposito';
f_deposito.funcion = t_f_deposito;
f_deposito.padre   = 'contenedor_cuadro';

f_pagos          = new formulario2('depositos_mixtos/f_pagos');
f_pagos.nombre   = 'f_pagos';
f_pagos.funcion  = t_f_pagos;
f_pagos.padre    = 'GRUPO1';
f_pagos.submodal = 'contenedor_pagos';
f_pagos.noOcultarCombos = true;
f_pagos.onExit          = function(){ f_pagos2.setFocus();};

f_pagos2          = new formulario2('depositos_mixtos/f_pagos2');
f_pagos2.nombre   = 'f_pagos2';
f_pagos2.funcion  = t_f_pagos2;
f_pagos2.padre    = 'GRUPO2';
f_pagos2.submodal = 'contenedor_pagos';
f_pagos2.noOcultarCombos = true;

impresora.origin='reportes/r_deposito';

function act_timer(obj,xml)
{
    dxml = xml;
    if (t) window.clearTimeout(t);
    t = window.setTimeout("actualizar_detalles(dxml)",300);
}

function actualizar_detalles(xml)
{
    var registro = valida_xml(xml,'ID_M_TIPO_PAGOS');
    if(!registro)return;
    xtipopago = registro[0]['ID_M_TIPO_PAGOS'];
    actualizar_monto_deposito();
    mostrarLeyenda(0);
}

function actualizar_monto_deposito()
{
   var xbusca_tipo = xtipo_pago_filtro.replace(/,/g,"&#39;,&#39;");
   var url = server_path + "herramientas/genera_xml/genera_xml.php";
   var params = "origen=depositos_mixtos/d_pagos&procedimiento=TOTAL_PAGOS&ID_M_TIPO_PAGOS=" + xbusca_tipo;
   var xml = enviar(url,params,'POST');

   limpiarElementos("PIED_");
   actualizaHTML(xml,'PIE', 'totales');
   actualizaHTML(d_pagos.elementoXml(),'PIED_');
}

function t_d_pagos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _insert:
      if(!xid_m_cajas)
      {
        alert('{$t_caja_no_valida}');
        return;
      }
      f_pagos.limpiar();
      f_pagos2.limpiar();
      f_pagos2.setValue("ID_M_CAJAS"  ,xid_m_cajas);
      f_pagos2.setValue("FECHA_PAGO"  ,xfecha);
      f_pagos2.setValue('xbusca'      ,'-1');
      f_pagos2.setValue('CAMPO5'      ,'ARQ');
      f_pagos2.setValue("ESTATUS"  ,'C');
      f_pagos.setValue("ID_M_TIPO_PAGOS",'0012');
      f_pagos2.setValue("ID_M_TIPO_PAGOS",'0012');
      mostrar_formulario_pagos();

      masDatos();
    break;

    case 187://Tecla + laptop
    case 107://Tecla + desktop
      cancelaTecla(evt);
      var registro    = XML2Array(xml);
      var xid_d_pagos = registro[0]['ID_D_PAGOS'];
      var xcondicion  = registro[0]['CONDICION1'];
      var xtabla      = registro[0]['TABLA'];
	  var xresponsable = registro[0]['RESPONSABLE'];
	  var url = server_path + 'herramientas/utiles/actualizar_registro.php';
      if (xtabla == 'M_PAQUETES')
      {
          if (xcondicion == '*')
          {
              params = 'tabla=M_PAQUETES&busca=ID_M_PAQUETES&xbusca='+xid_d_pagos+'&c_CONDICION1_RSS=+';
          }
          else
          {
              params = 'tabla=M_PAQUETES&busca=ID_M_PAQUETES&xbusca='+xid_d_pagos+'&c_CONDICION1_RSS=*';
          }
      } else
      {
          if (xcondicion == '*')
	      {
	          params = 'tabla=D_PAGOS&busca=ID_D_PAGOS&xbusca='+xid_d_pagos+'&c_CONDICION1_RSS=+';
	      }
	      else
	      {
	          params = 'tabla=D_PAGOS&busca=ID_D_PAGOS&xbusca='+xid_d_pagos+'&c_CONDICION1_RSS=*';

	      }
      }
      var x = enviar(url, params, 'POST');
      d_pagos.actualizar(xid_d_pagos);
      actualizar_monto_deposito();
    break;

    case _f10: //F10 Cerrar Deposito
      cancelaTecla(evt);
      var url = server_path + "herramientas/genera_xml/genera_xml.php";
      var params = "tabla=V_PAGOS_DEPOSITAR_MIXTOS&campos=ID_M_TIPO_PAGOS&busca=ID_M_TIPO_PAGOS&xbusca="+xtipopago+"&limite=1&operador==&filtro=CONDICION1;ID_M_USUARIOS&xfiltro=*;"+xusuario;
      var x = enviar(url,params,'POST');

	  var registro = valida_xml(x,'ID_M_TIPO_PAGOS');
      if (!registro)
      {
        alert("{$t_proceso_incorrecto}");
        return false;
      }

      f_deposito.limpiar();
      f_deposito.setValue("REFERENCIA",xtipopago)
      f_deposito.setValue("FECHA_DOCUMENTO",xfecha)
      mostrar_formulario(f_deposito);
    break;

    case _esc: //Escape
      cancelaTecla(evt);
      Salir();
    break;

    case _f1: //F1
    case _f2: //F2
    case _f3: //F4
    case _f4: //F5
    case _f5: //F6
    case _f6: //F7
    case _f7: //F8
    case _f8: //F9
    case _f12: // F12
      cancelaTecla(evt);
    break;
  }
}

function t_f_pagos(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12:
      t_f_pagos2('',tecla,xml);
      break;
    case _esc:
      ocultar_formulario_pagos();
      d_pagos.setFocus();
      break;
  }
}

function t_f_pagos2(objeto, tecla, xml)
{
  switch (tecla)
  {
    case _f12:
      var registro = f_pagos2.submit();
   	  if (!registro) return false;

      ocultar_formulario_pagos();
      d_pagos.buscar('*');
      d_pagos.localiza('ID_D_PAGOS',registro[0]['ID_D_PAGOS']);
  	  d_pagos.setFocus();
      break;

    case _esc: // ESC Cerrar Formulario
	  ocultar_formulario_pagos();
      d_pagos.setFocus();
      break;
  }
}

function t_f_deposito(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc: //Escape
      cancelaTecla(evt);
	  ocultar_formulario(f_deposito);
      d_pagos.setFocus();
      break;

    case _f12: // F12 Guardar deposito
      cancelaTecla(evt);
      var url    = server_path + 'herramientas/genera_xml/genera_xml.php';
	  var params = 'tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS&busca=ID_M_CUENTAS_BANCARIAS&xbusca=' + f_deposito.getValue('ID_M_CUENTAS_BANCARIAS') +'&filtro=ID_M_DOC_FINAL&xfiltro='+f_pagos2.getValue('ID_M_DOC_FINAL');
	  var x      = enviar(url, params, 'POST');

      var registro = valida_xml(x,'ID_M_DOCUMENTOS');

      if(registro)
      {
     	alert('{$t_documento_existe}');
        return;
      }
      Guardar();
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
    f_pagos2.setValue("ID_M_CLIENTES","");

    f_pagos2.ocultarCampo("ID_M_ANTICIPOS");
    f_pagos2.setValue("ID_M_ANTICIPOS","");

	f_pagos2.ocultarCampo("ID_M_CUENTAS_BANCARIAS");
    f_pagos2.setValue("ID_M_CUENTAS_BANCARIAS","");

    for(i=0;i<frm_campos[xtipo].length;i++)
    {
    	f_pagos2.mostrarCampo(frm_campos[xtipo][i]);
        var xvalor = eval('x'+frm_campos[xtipo][i]);
        f_pagos2.setValue(frm_campos[xtipo][i],xvalor);
    }

/*    for(i=0;i<frm_campos[xtipo].length;i++)
    {
        f_pagos2.mostrarCampo(frm_campos[xtipo][i]);
    }*/
    f_pagos2.setValue('ID_M_TIPO_PAGOS',xtipo);
}

function Guardar()
{
  var x=  f_deposito.submit();
  if (!x) return false;

 var xdocumento = x[0]["UNICO"];

  ocultar_formulario(f_deposito);
  VerPreview(xdocumento);
}

function VerPreview(documento)
{
  impresora.setParam('ID_M_DOCUMENTOS', documento);
  impresora.preview();
  impresora.showDialog=true;
  d_pagos.setFocus();
  d_pagos.buscar('*');
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_deposito':
          t_f_deposito('',_f12);
          break;

       case 'f_pagos':
          t_f_pagos('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_deposito':
          t_f_deposito('',_esc);
          break;

       case 'f_pagos':
          t_f_pagos('',_esc);
          break;
    }
}

function Cerrar_contenedor()
{
	f_activo.ocultar();
    contenedor.ocultar();
}

function mostrar_formulario_pagos()
{
    contenedor_pagos.setTitle(f_pagos.titulo);
    contenedor_pagos.setWidth(700);
	contenedor_pagos.setHeight(500);
	centrarObj(contenedor_pagos.contenedor);
	contenedor_pagos.setLegend(f_pagos.leyenda);
	contenedor_pagos.mostrar();
    f_pagos.mostrar();
    f_pagos2.mostrar();
    f_activo = f_pagos;
    setTimeout('f_pagos.setFocus();',1);
}
function ocultar_formulario_pagos()
{
    f_pagos.ocultar();
    f_pagos2.ocultar();
    contenedor_pagos.ocultar();
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

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

    contenedor_pagos.inicializa(false);
	centrarObj(contenedor_pagos.contenedor);

    d_pagos.inicializa(false);
    d_pagos.mostrar();

    $('#contenedor_pagos_cuadro').empty();
    $('#contenedor_pagos_cuadro').append('<table border="0"><tr><td valign="top" id="GRUPO1"></td></tr><tr><td id="GRUPO2" valign="top"></td></tr></table>');

    f_deposito.inicializa(false);
    f_pagos.inicializa(false);
    f_pagos2.inicializa(false);

    d_pagos.setFocus();
    d_pagos.buscar('*');

    mostrarLeyenda(0);

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(frm_f_pagos.ID_M_TIPO_PAGOS, 'change', masDatos);

    addEvent(INS, "click",   function() { t_d_pagos('', _insert,d_pagos.elementoXml()) } )
    addEvent(MAS, "click",   function() { t_d_pagos('', 107,  	d_pagos.elementoXml()) } )
    addEvent(F10, "click",   function() { t_d_pagos('', _f10, 	d_pagos.elementoXml()) } )
    addEvent(ESC, "click",   function() { t_d_pagos('', _esc, 	d_pagos.elementoXml()) } )
}

var resp = iniciar();
if(!resp)
{

}

function enviarForma1(objForm)
{
  f_pagos2.setFocus();
}
</script>

</body>
</html>

EOT;

?>