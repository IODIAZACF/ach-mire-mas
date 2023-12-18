<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$fecha   = date("d/m/Y");
$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario2,impresora,forma,tabpane,submodal,jquery,clave');

echo <<<EOT


{$modulo->inicio}

<div id="contenido">

<table border="0">
<tr>
<td id="GRUPO1"> </td>
</tr>
<tr>
<td id="GRUPO2"></td>
</tr>
</table>

</div>

{$modulo->fin}

<script type="text/javascript">
var xusuarios          = '{$xusuarios}';
var xfecha             = '{$fecha}';
var t                  = null;
var xidpagoslotes      = null;
var xpagos             = null;
var xclave             = new clave('xclave');

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.alto        = 1;
contenedor.ancho       = 1;
contenedor.titulo      = ' ';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;
contenedor.ayuda       = 1;
contenedor.onClose     = Cerrar_contenedor;

m_pagos_proveedores             = new lista("auditoria_pagos_emitidos/m_pagos_proveedores")
m_pagos_proveedores.nombre      = "m_pagos_proveedores";
m_pagos_proveedores.url         = server_path + "herramientas/genera_xml/genera_xml.php";
m_pagos_proveedores.funcion     = t_m_pagos_proveedores;
m_pagos_proveedores.buscador    = true;
m_pagos_proveedores.padre       = "GRUPO1";
m_pagos_proveedores.onSelect    = actualizarDetalles;
m_pagos_proveedores.enter       = 1;
m_pagos_proveedores.botonCerrar = true;

d_pagos_proveedores               = new lista("auditoria_pagos_emitidos/d_pagos_proveedores")
d_pagos_proveedores.nombre        = "d_pagos_proveedores";
d_pagos_proveedores.padre         = "GRUPO2";
d_pagos_proveedores.url           = server_path + "herramientas/genera_xml/genera_xml.php";
d_pagos_proveedores.funcion       = t_d_pagos_proveedores;
d_pagos_proveedores.enter         = 1;
d_pagos_proveedores.filtro        ='ID_D_PAGOS';
d_pagos_proveedores.onSelect      = d_pagos_proveedoresFocus;
d_pagos_proveedores.noOcultarCombos         = true;

f_edicion              = new formulario2('auditoria_pagos_emitidos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo         = 'formulario';

var impresora=new printer();

function actualizarDetalles()
{
   if (t) clearTimeout(t);
   t = setTimeout('Detalles();',200);
}

function d_pagos_proveedoresFocus()
{
   if (t) clearTimeout(t);
   t = setTimeout('mostrarLeyenda(2);',500);
}

function Detalles()
{
   var xml = m_pagos_proveedores.elementoXml();
   var registro = XML2Array(xml);
   if(!registro[0]||!registro[0]['ID_D_PAGOS'])
   {
        d_pagos_proveedores.limpiar();
        return false;
   }
   d_pagos_proveedores.xfiltro = registro[0]['ID_D_PAGOS'];
   d_pagos_proveedores.buscar('*');
   mostrarLeyenda(1);
}

function t_m_pagos_proveedores(objeto, tecla, xml, e)
{
   var evt = window.event || e;
   switch (tecla)
          {
        case _insert:  //INS
                    cancelaTecla(evt);
                break;
        case _enter:  //ENTER
                    cancelaTecla(evt);
            var registro= XML2Array(xml);
            if(m_pagos_proveedores.rows.length)
            {
                        d_pagos_proveedores.setFocus();
                        mostrarLeyenda(2);
            }
            else{
                     return;
            }
                break;
        case _esc:  //ESC
                    cancelaTecla(evt);
                   Salir();
        break;
             case _f1:  //F1
            case _f2:  //F2
            case _f3:  //F3
                cancelaTecla(evt);
                break;
        case _f4:  //F4
                       cancelaTecla(evt);
            break;

            case _f5:  //F5
                    cancelaTecla(evt);
                    break;

            case _f6:  //F6
				cancelaTecla(evt);
				var registro = valida_xml(xml,'ID_D_PAGOS');
				if(!registro)return;
				if(registro[0]['ESTATUS'] == 'ANU'){
					alert('Operacion no permitida, el pago ha sido anulado');
					return;
				}

				f_edicion.limpiar();

				f_edicion.buscar(registro[0]['ID_D_PAGOS']);

				mostrar_formulario(f_edicion);
				f_edicion.setFocus();
				break;

        case _f7:
            cancelaTecla(evt);
            var registro = valida_xml(xml,'ID_D_PAGOS');
            if(!registro)return;
            pideClave('ImprimirCheque',8,true);
                break;

            case _f8:  //F8
            case _f9:  //F9
            case _f10:  //F10
            case _f11:  //F11
            case _f12:  //F12
                    cancelaTecla(evt);
                    break;
        }
}

function ImprimirCheque(confirmado)
{
  if (!confirmado)
  {
    xclave.hide();
    m_pagos_proveedores.setFocus();
  }
  else
  {
      xclave.hide();
	  imprimir(0);

  }
}


function imprimir(tipo)
{
        switch(tipo)
    {
            case 0:
                    var xml     = m_pagos_proveedores.elementoXml();
                    var registro= valida_xml(xml,'ID_D_PAGOS');
                    if(!registro[0]['ID_D_PAGOS']) return;
                    xpagos      = registro[0]['ID_D_PAGOS'];


					if(registro[0]['TIPO_TITULAR']=='PROVEEDORES'){
					    impresora.origin='reportes/r_cheque_pendiente';
					}
					else{
						impresora.origin='reportes/r_cheque_manual';
					}

                    var ximpresion = registro[0]['IMPRESION'];
                    ximpresion++;
                           var url   = server_path + 'herramientas/utiles/actualizar_registro.php';
                           var param = 'tabla=D_PAGOS&busca=ID_D_PAGOS&xbusca='+ xpagos+'&c_IMPRESION_NSS=' + ximpresion;
                           var x     = enviar(url, param, 'POST');
                           var registro = XML2Array(x);


                    impresora.setParam('ID_D_PAGOS',xpagos);
                    impresora.setParam('ID_M_USUARIOS',xusuarios);
                    impresora.setParam('letras',1);

                    impresora.showDialog = true;
                    impresora.preview(); 
                    //impresora.print();
            break;

        case 1:
            var xml      = d_pagos_proveedores.elementoXml();
            var registro = valida_xml(xml,'ID_M_DOCUMENTOS');

            impresora.origin  = 'reportes/r_documento_fac';
            impresora.setParam('ID_M_DOCUMENTOS', registro[0]['ID_M_DOCUMENTOS']);
            impresora.showDialog = true;
            impresora.preview();
            d_pagos_proveedores.setFocus();
            break;
        }
}

function t_d_pagos_proveedores(objeto, tecla, xml, e)
{
   var evt = window.event || e;
   switch (tecla)
          {
        case _supr:  //SUPR
          cancelaTecla(evt);
          break;
        case _esc:  //ESC
          cancelaTecla(evt);
          m_pagos_proveedores.setFocus();
          mostrarLeyenda(1);
          break;
        case _f1:  //F1
        case _f2:  //F2
        case _f3:  //F3
          cancelaTecla(evt);
          break;

        case _f4:  //F4
          cancelaTecla(evt);
          var registro = valida_xml(xml,'ID_M_DOCUMENTOS');
          if(!registro)return;
          imprimir(1);
          break;

        case _f5:  //F5
        case _f6:  //F6
        case _f7:  //F7
        case _f8:  //F8
        case _f9:  //F9
        case _f10:  //F10
        case _f11:  //F11
        case _f12:  //F12
          cancelaTecla(evt);
          break;
      }
}

function t_f_edicion(objeto, tecla, xml)
{
  var evt = window.event || e;
  switch (tecla)
  {
          case _esc: //Escape
            cancelaTecla(evt);
                ocultar_formulario(f_edicion,m_pagos_proveedores);
              break;

    case _f12: // F12 Guardar_item
            cancelaTecla(evt);
              /*var registro = f_edicion.submit();
        if(!registro)return;
        ocultar_formulario(f_edicion,m_pagos_proveedores);
        m_pagos_proveedores.actualizar();*/
           var registro = f_edicion.submit();
        if (!registro)return;
        ocultar_formulario(f_edicion);
        m_pagos_proveedores.actualizar(registro[0]['ID_D_PAGOS']);
        m_pagos_proveedores.setFocus();
              break;
  }
}

function Refrescar()
{
        d_pagos_proveedores.limpiar();
    m_pagos_proveedores.buscar('*');
    m_pagos_proveedores.setFocus();
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

function Cerrar_contenedor()
{
          f_activo.ocultar();
          contenedor.ocultar();
}

function onclose()
{
   m_pagos_proveedores.setFocus();
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function iniciar()
{
    contenedor.inicializa();
        centrarObj(contenedor.contenedor);

    m_pagos_proveedores.inicializa();
    m_pagos_proveedores.mostrar();
    m_pagos_proveedores.setFocus();

    d_pagos_proveedores.inicializa(false);
    d_pagos_proveedores.mostrar();

    f_edicion.inicializa();

    document.onclick=function() { if (parent.menu) parent.menu.reset(); }

    addEvent(F6     , "click",   function() { t_m_pagos_proveedores('', _f6,         m_pagos_proveedores.elementoXml()) } )
    addEvent(F7     , "click",   function() { t_m_pagos_proveedores('', _f7,         m_pagos_proveedores.elementoXml()) } )
    addEvent(ENTER  , "click",   function() { t_m_pagos_proveedores('', _enter, m_pagos_proveedores.elementoXml()) } )
	addEvent(M_PAGOS_PROVEEDORES_ESC  , "click",   function() { t_m_pagos_proveedores('', _esc, m_pagos_proveedores.elementoXml()) } )

    addEvent(D_PAGOS_PROVEEDORES_F4   , "click",   function() { t_d_pagos_proveedores('', _f4, d_pagos_proveedores.elementoXml()) } )
    addEvent(D_PAGOS_PROVEEDORES_ESC  , "click",   function() { t_d_pagos_proveedores('', _esc, d_pagos_proveedores.elementoXml()) } )
    return true;
}

function inicio()
{
    mostrarLeyenda(1);
}

var resp = iniciar();
if(!resp)
{
        Salir();
}
else
{
        inicio();
}
</script>


</body>
</html>

EOT;

?>