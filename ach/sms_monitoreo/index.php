<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$hora     = date("H:i");

$xempresa   = getsession('CONFIGURACION_NOMBRES');
$nombre_usuario = getsession('M_USUARIOS_NOMBRES');
$id_m_usuario    = getsession('M_USUARIOS_ID_M_USUARIO');

$my_ini 	= new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal,password');

echo <<<EOT

{$modulo->inicio}

<div id="contenido"> </div>

{$modulo->fin}

<script type="text/javascript">

var opcion	   = '';
var usuario    = '{$nombre_usuario}';
var id_usuario ='{$id_usuario}';
var xempresa   ='{$xempresa}';
var fecha      = '{$xfecha}';
var hora       = '{$hora}';
var tiempo1=0;
var t1;
var pXML,tXML;
var filtroAct;

//se crea el contenedor
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

// Se Crea el Grid de las Detalle De Los Mensajes
d_sms           = new lista("sms_monitoreo/d_sms")
d_sms.nombre    = "d_sms";
d_sms.padre     = "contenido";
d_sms.funcion   = tecla_doc;
d_sms.onSelect  = resetCuenta1;
d_sms.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_sms.buscador  = true;
d_sms.enter     = 0;
d_sms.botonCerrar = true;

//d_sms.xfiltro     = '04144267809';

//d_sms.onSelect  = actualizar_detalle_cxc;

// Se Crea el Formulario de Edición
f_edicion 			   = new formulario2('sms_monitoreo/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_f_edicion;
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.tipo		   = 'formulario';


function tecla_doc(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _f6: // Responder
    cancelaTecla(evt);
    //alert(fecha);
      var registro = valida_xml(xml,'NUMERO');
      if(!registro)return;
      {
      	f_edicion.setValue('NUMERO',registro[0]['NUMERO']);
      	f_edicion.setValue('FECHA_ENVIO',fecha);
      	f_edicion.setValue('FECHA_ENVIO_H',hora);
        mostrar_formulario(f_edicion);
      }
      break;

    case _esc: // Esc
    	cancelaTecla(evt);
        Salir();
        break;

    case _f2: // F2
    	cancelaTecla(evt);
	    var registro = valida_xml(xml,'NUMERO');
        d_sms.filtro      = 'NUMERO';
        d_sms.xfiltro=registro[0]['NUMERO'];
        d_sms.buscar('*');
        break;

         case _f3: // F3
    	cancelaTecla(evt);
	    var registro = valida_xml(xml,'TIPO');
        d_sms.filtro      = 'TIPO';
        d_sms.xfiltro='IN';
        d_sms.buscar('*');
        break;

        case _f4: // F4
    	cancelaTecla(evt);
	    var registro = valida_xml(xml,'TIPO');
        d_sms.filtro = 'TIPO';
        d_sms.xfiltro='OUT';
        d_sms.buscar('*');
        break;

        case _f5: // F4
    	cancelaTecla(evt);
        d_sms.filtro  = '';
        d_sms.xfiltro ='';
        d_sms.buscar('*');
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
		ocultar_formulario(f_edicion,d_sms);
      	break;

    case _f12: // F12 Guardar_item
    	cancelaTecla(evt);
        Enviar();
      	break;
  }
}

function Enviar()
{
        var envio = fechaMDY(f_activo.getValue('FECHA_ENVIO'))+' '+f_activo.getValue('FECHA_ENVIO_H');
        var aCampos =new Array();
        aCampos['NUMERO']  = new Campo(f_activo.getValue('NUMERO'),'C');
        aCampos['MENSAJE'] = new Campo(f_activo.getValue('MENSAJE'),'L');
        aCampos['FECHA_ENVIO'] = new Campo(envio);
        var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
        var registro = XML2Array(x);
        if(registro[0])
        {
            f_activo.limpiar();
        	ocultar_formulario(f_activo,d_sms);
            d_sms.buscar('*');
        }
}

function Cuenta1()
{
    tiempo1--;
    if(tiempo1==0)
    {
        pXML=tXML;
        Refrescar();
        setTimeout('ubicar();',300);
        resetCuenta1();

    }
}

function ubicar()
{
    var registro = XML2Array(pXML);
    d_sms.localiza('ID_D_SMS_ENTRADA',registro[0]['ID_D_SMS_ENTRADA']);
}

function resetCuenta1()
{
    tiempo1=10;
    clearTimeout(t1);
    t1 = setInterval(Cuenta1,50000);
    tXML = d_sms.elementoXml();
}

function Refrescar()
{
    d_sms.buscar('*');
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

function Guardar(valor)
{
/*
  var url=server_path + 'herramientas/sms/sms.php';
  var params='numero='+f_edicion.getValue('NUMERO')+'&mensaje='+f_edicion.getValue('MENSAJE')+'--'+xempresa;
*/
  var envio = f_edicion.getValue('FECHA_ENVIO')+' '+f_edicion.getValue('FECHA_ENVIO_H');
  var aCampos =new Array();
  aCampos['NUMERO']  = new Campo(f_edicion.getValue('NUMERO'));
  aCampos['MENSAJE'] = new Campo(f_edicion.getValue('MENSAJE'));
  aCampos['FECHA_ENVIO'] = new Campo(envio);
  var x = actualizar_registro('D_SMS',aCampos,'ID_D_SMS','-1',null,'=');
  var registro = XML2Array(x);
  if(registro[0])
  {
      f_edicion.limpiar();
      ocultar_formulario(f_edicion);
      d_sms.buscar('*');
      d_sms.setFocus();


  }
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


/*
function creaLeyenda()
{
  var l = '';

  l += '<center><table class="tabla_leyenda">';
  l += '<tr>';
  l += etiqLeyenda('F12', '{$t_guardar}', '90', 'Guardar(1);');
  l += etiqLeyenda('ESC', '{$t_salir}', '90', 'Salir();');
  l += '</tr>';
  l += '</table></center>';
  return l;
}
*/

function Cerrar_contenedor()
{
          f_activo.ocultar();
          contenedor.ocultar();
}


function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function Switch(opcion)
{

	xESTATUS = opcion;

	if(xESTATUS == 'TODOS') {
        d_sms.filtro  = '';
        d_sms.xfiltro = '';
	} else {
		d_sms.filtro  = 'ESTATUS';
		d_sms.xfiltro = xESTATUS;

	}
	d_sms.buscar('*');
    d_sms.setFocus();
}



function verUsuario(aceptado)
{
/*    f_edicion.setValue('USUARIO',usuario);
    f_edicion.setValue('ID_M_USUARIOS1',id_usuario);*/
//    f_edicion.setFocus();
}

function iniciar()
{
    contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	f_edicion.inicializa();

    d_sms.inicializa();
    d_sms.mostrar();
    d_sms.setFocus();
    d_sms.buscar('*');

   /* contenedor.setTitle(f_edicion.titulo);
    contenedor.setWidth(f_edicion.ancho);
    contenedor.setHeight(f_edicion.alto);*/

    addEvent(F2,   "click",  function() { tecla_doc  ('', _f2,   d_sms.elementoXml()) } )
    addEvent(F3,   "click",  function() { tecla_doc  ('', _f3,   d_sms.elementoXml()) } )
    addEvent(F4,   "click",  function() { tecla_doc  ('', _f4,   d_sms.elementoXml()) } )
    addEvent(F5,   "click",  function() { tecla_doc  ('', _f5,   d_sms.elementoXml()) } )
    addEvent(F6,   "click",  function() { tecla_doc  ('', _f6,   d_sms.elementoXml()) } )
    addEvent(ESC,  "click",  function() { tecla_doc  ('', _esc,  d_sms.elementoXml()) } )

    var extra = '<center>';
    extra +='<input name="FILTRO" id="oPEN"  	   type="radio" onclick=" Switch(\'PEN\') ">Pendientes</input>';
    extra +='<input name="FILTRO" id="oEP"         type="radio" onclick=" Switch(\'EP\')  ">En Proceso</input>';
    extra +='<input name="FILTRO" id="oENV"        type="radio" onclick=" Switch(\'ENV\') ">Enviados</input>';
    extra +='<input name="FILTRO" id="oTODOS"      type="radio" checked="checked" onclick="Switch(\'TODOS\')">Todos</input>';
    extra +='</center>';

    $('#d_sms_extra').html(extra);

	document.onclick=function() {if (parent.menu) parent.menu.reset();}

}
iniciar();

</script>


</body>
</html>

EOT;

?>