<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$id_usuario = getsession('M_USUARIOS_ID_M_USUARIO');
$id_grupo   = getsession('M_GRUPOS_ID_GRUPOS');
$xfecha     = date("m/d/Y");
$xhora      = date("H");
$cook       = $_COOKIES['AGENDA'];
$usuario    = getvar('usuario');
$xtipo                = strtoupper(getvar('tipo'));

$my_ini = new ini('modulo');

encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,forma,tabpane,formulario2,impresora,submodal,calendario,impresora,jquery');


echo <<<EOT

{$modulo->inicio}
<table>
    <tr>
                <td id="GRUPO1" </td>
        </tr>
        <tr>
                <td id="GRUPO2" </td>
        </tr>
</table>
{$modulo->fin}
<script type="text/javascript">
var t;
var xdocumento;
var impresora=new printer();

// Se Crea el Contenedor del Formulario
contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 600;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.ayuda       = 100;
contenedor.x           = 50;
contenedor.y           = 1;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = ' ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.onClose     = Cerrar_contenedor;

m_documentos             = new lista("auditoria_compra/m_documentos")
m_documentos.nombre      = "m_documentos";
m_documentos.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_documentos.funcion     = t_m_documentos;
m_documentos.padre       = 'GRUPO1';
m_documentos.onSelect    = actualizar_detalle;
m_documentos.filtro      = 'TIPO';
m_documentos.xfiltro     = 'COM,DOX,DEX';
m_documentos.xoperadores = 'IN';
m_documentos.buscador    = true;

d_documentos                 = new lista("auditoria_compra/d_documentos")
d_documentos.nombre          = "d_documentos";
d_documentos.url             = server_path + "herramientas/genera_xml/genera_xml.php";
d_documentos.funcion         = t_d_documentos;
d_documentos.padre           = 'GRUPO2';
d_documentos.filtro          = 'ID_M_DOCUMENTOS';
d_documentos.onSelect        = d_documentosFocus;
d_documentos.noOcultarCombos = true;

f_documento              = new formulario2('auditoria_compra/f_documento');
f_documento.nombre       = 'f_documento';
f_documento.funcion      = t_f_documento;
f_documento.padre        = 'contenedor_cuadro';

f_d_documento            = new formulario2('auditoria_compra/f_d_documento');
f_d_documento.nombre     = 'f_d_documento';
f_d_documento.funcion    = t_f_d_documento;
f_d_documento.padre      = 'contenedor_cuadro';

f_d_documento2           = new formulario2('auditoria_compra/f_d_documento2');
f_d_documento2.nombre    = 'f_d_documento2';
f_d_documento2.funcion   = t_f_d_documento;
f_d_documento2.padre     = 'contenedor_cuadro';

f_bases              = new formulario2('auditoria_compra/f_bases');
f_bases.nombre       = 'f_bases';
f_bases.funcion      = t_f_bases;
f_bases.padre        = 'contenedor_cuadro';

function m_documentos_focus()
{
        mostrarLeyenda(0);
}

function d_documentosFocus()
{
	if (t) window.clearTimeout(t);
    t = window.setTimeout("mostrarLeyenda(1);",200);
}

function actualizar_detalle (obj,xml)
{
    if (t) window.clearTimeout(t);
    t = window.setTimeout('act_timer()',500);
}

function act_timer()
{
    var xml = m_documentos.elementoXml();
    var registro = XML2Array(xml);
         if(!registro[0] || !registro[0]['ID_M_DOCUMENTOS'])
    {
            d_documentos.xfiltro = '';
    }
    else
    {
            xdocumento=registro[0]['ID_M_DOCUMENTOS'];
            d_documentos.xfiltro = xdocumento;
        d_documentos.buscar('*');
    }
    m_documentos_focus();
}

function t_m_documentos(objeto, tecla, xml, e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case _enter:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro)return;
        d_documentos.setFocus();
        mostrarLeyenda(1);
        break;

    case _f4:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro)return;
        Imprimir(0);
        break;

    case _f6:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro) return;
        f_documento.buscar(xdocumento);
        f_documento.setValue('r_REEMBOLSABLE', registro[0]['NOMBRE_REEMBOLSABLE']);
        mostrar_formulario(f_documento);
        break;

    case _f7:
        cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro)return;
        Imprimir(1);
        m_documentos.setFocus();
    break;

    case _f8:
    	cancelaTecla(evt);
        var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
        if(!registro)return;
        f_bases.limpiar();
        f_bases.setValue('xbusca',registro[0]['ID_M_DOCUMENTOS']);
        mostrar_formulario(f_bases);
    break;

    case _esc:
        cancelaTecla(evt);
        Salir();
    break;
  }
}

function t_d_documentos(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  var d_documento = d_documentos.getValue('ID_D_DOCUMENTOS');
  var xtipo_prod=d_documentos.getValue('TIPO_PRODUCTO');

  switch (tecla)
  {
           case _enter:
            cancelaTecla(evt);
        if(!d_documentos || xtipo_prod!='S')
        {
            f_d_documento2.buscar(d_documento);
            f_d_documento2.setValue('CAMPO5','MON');
            mostrar_formulario(f_d_documento2);
            return;
        }
        f_d_documento.buscar(d_documento);
        f_d_documento.setValue('CAMPO5','MON');
                mostrar_formulario(f_d_documento);
        break;

    case _supr:
            cancelaTecla(evt);
            break;

    case _esc:
        cancelaTecla(evt);
        m_documentos.setFocus();
        mostrarLeyenda(0);
        break;
  }
}

function cond_centro_costo()
{
  var xid_concepto_comp = f_d_documento.getValue('ID_M_CONCEPTOS_COMPRAS');
  var url   = server_path + "herramientas/genera_xml/genera_xml.php";
  var param = "tabla=V_M_CONCEPTOS_COMPRAS&campos=CONDICION_CENTRO_COSTOS,ID_M_CENTRO_COSTOS,NOMBRE_CENTRO_COSTOS&filtro=ID_M_CONCEPTOS_COMPRAS&xfiltro="+ xid_concepto_comp;
  var x     = enviar(url,param,'POST');
  var registro = valida_xml(x,'CONDICION_CENTRO_COSTOS');

  if(!registro)
  {
   f_d_documento.ocultarCampo('ID_M_CENTRO_COSTOS');
  }
  else
  {
   f_d_documento.mostrarCampo('ID_M_CENTRO_COSTOS');
   f_d_documento.setValue('ID_M_CENTRO_COSTOS',registro[0]['ID_M_CENTRO_COSTOS']);
   f_d_documento.setValue('r_ID_M_CENTRO_COSTOS',registro[0]['NOMBRE_CENTRO_COSTOS']);
  }
}

function t_f_documento(objeto,tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
        cancelaTecla(evt);
        ocultar_formulario(f_documento);
        m_documentos.setFocus();
            break;

     case _f12:
        cancelaTecla(evt);
        var registro = f_documento.submit();
        if (!registro)return;
        ocultar_formulario(f_documento);
        m_documentos.setFocus();
        m_documentos.actualizar(xdocumento);
        break;
  }
}

function t_f_bases(objeto,tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
        cancelaTecla(evt);
        ocultar_formulario(f_bases);
        m_documentos.setFocus();
        break;

     case _f12:
        cancelaTecla(evt);
        if(confirm('{$t_cofirmar_cambio}'))
        {
        	var registro = f_bases.submit();
       		if (!registro)return;
        	ocultar_formulario(f_bases);
        	m_documentos.setFocus();
        	m_documentos.actualizar(xdocumento);
        }
        break;
  }
}

function t_f_d_documento(objeto,tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
        cancelaTecla(evt);
        if(f_activo.nombre=='f_d_documento')
        {
            ocultar_formulario(f_d_documento);
        }
        else
        {
            ocultar_formulario(f_d_documento2);
        }
        d_documentos.setFocus();
        break;

     case _f12:
        cancelaTecla(evt);
        var accion = null;
        if (f_activo.nombre=='f_d_documento')
        {
                accion = f_d_documento.getValue('xbusca');
                var registro = f_d_documento.submit();
                if (!registro)return;
                ocultar_formulario(f_d_documento);
        }
        else
        {
                accion = f_d_documento2.getValue('xbusca');
                var registro = f_d_documento2.submit();
                if (!registro)return;
                ocultar_formulario(f_d_documento2);
        }
        if(accion!='-1')
        {
            d_documentos.actualizar(accion);
        }
        else
        {
                d_documentos.buscar('*');
        }
        d_documentos.setFocus();
        break;
  }
}

function buscaBases()
{
    var campo = f_bases.getValue('XCAMPO_IMPUESTO');

    var url   = server_path + "herramientas/genera_xml/genera_xml.php";
    var param = "tabla=M_DOCUMENTOS&campos=ID_M_DOCUMENTOS,BASE"+campo+",DEBITO_FISCAL"+campo+",IMPUESTO"+campo+"&filtro=ID_M_DOCUMENTOS&xfiltro="+ xdocumento;
    var x     = enviar(url,param,'POST');
    var registro = valida_xml(x,'ID_M_DOCUMENTOS');
    if(!registro)return;

    f_bases.setValue("BASEX",registro[0]['BASE'+campo]);
	f_bases.setValue("DEBITO_FISCALX",registro[0]['DEBITO_FISCAL'+campo]);
    f_bases.setValue("IMPUESTOX",registro[0]['IMPUESTO'+campo]);
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

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
    d_documentos.setFocus();
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_d_documento':
          t_f_d_documento('',_f12);
          break;
       case 'f_d_documento2':
          t_f_d_documento('',_f12);
          break;
       case 'f_documento':
          t_f_documento('',_f12);
          break;

       case 'f_bases':
          t_f_bases('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_d_documento':
          t_f_d_documento('',_esc);
          break;
       case 'f_d_documento2':
          t_f_d_documento('',_esc);
          break;
       case 'f_documento':
          t_f_documento('',_esc);
          break;

       case 'f_bases':
          t_f_bases('',_esc);
          break;
    }
}

function Imprimir(registro)
{
    impresora.setParam('ID_M_DOCUMENTOS', m_documentos.getValue('ID_M_DOCUMENTOS'));
    impresora.origin='reportes/r_documento_com';
    switch(registro)
    {
        case 0:
           impresora.showDialog = true;
           impresora.preview();
           break;

        case 1:
           impresora.showDialog = true;
           impresora.print();
           break;
    }
}

function Cerrar_contenedor()
{
          f_activo.ocultar();
          contenedor.ocultar();
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
    contenedor.inicializa();
    centrarObj(contenedor.contenedor);

    m_documentos.inicializa(false);
    m_documentos.mostrar();
    m_documentos.setFocus();

    d_documentos.inicializa(false);
    d_documentos.mostrar();

    f_d_documento.inicializa(false);
    f_d_documento2.inicializa(false);
    f_documento.inicializa(false);
    f_bases.inicializa(false);

    inicio(0);

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(M_DOCUMENTOS_ENTER, "click",   function() { t_m_documentos('', _enter, m_documentos.elementoXml()) } )
    addEvent(M_DOCUMENTOS_F6,    "click",   function() { t_m_documentos('', _f6,    m_documentos.elementoXml())} )
    addEvent(M_DOCUMENTOS_F4,    "click",   function() { t_m_documentos('', _f4,    m_documentos.elementoXml())} )
    addEvent(M_DOCUMENTOS_F8,    "click",   function() { t_m_documentos('', _f8,    m_documentos.elementoXml())} )
    addEvent(M_DOCUMENTOS_F7,    "click",   function() { t_m_documentos('', _f7,    m_documentos.elementoXml()) } )
    addEvent(M_DOCUMENTOS_ESC,   "click",   function() { t_m_documentos('', _esc,   m_documentos.elementoXml()) } )

    addEvent(frm_f_bases.XCAMPO_IMPUESTO, 'change', buscaBases);

    addEvent(D_DOCUMENTOS_ENTER, "click",  function() { t_d_documentos('', _enter,  d_documentos.elementoXml()) } )
    //addEvent(D_DOCUMENTOS_SUPR,  "click",  function() { t_d_documentos('', _supr,         d_documentos.elementoXml())} )
    addEvent(D_DOCUMENTOS_ESC,   "click",  function() { t_d_documentos('', _esc, 	d_documentos.elementoXml()) } )

    return true;
}

function inicio(registro)
{
   switch (registro)
   {
           case 0:
            mostrarLeyenda(0);
            break;
   }
}


iniciar();
</script>

</body>
</html>

EOT;

?>