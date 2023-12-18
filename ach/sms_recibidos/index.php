<?php
include('../config.php');
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
include_once (Server_Path . 'herramientas/sql/class/class_sql.php');

$xfecha 	= date("d/m/Y");
$ventana 	= getvar('ventana','modulo');
$id 		= getvar('id');
$fecha     = date("d/m/Y");
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
<div id="contenido">
</div>
{$modulo->fin}
<script type="text/javascript">

var opcion	   = '';
var usuario    = '{$nombre_usuario}';
var id_usuario ='{$id_usuario}';
var xempresa   ='{$xempresa}';
var fecha      = '{$fecha}';
var hora       = '{$hora}';
var tiempo1=0;
var t1;
var pXML,tXML;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 600;
contenedor.alto        = 280;
contenedor.x           = 300;
contenedor.y           = 10;
contenedor.titulo      = 'Pager';
contenedor.botonCerrar = true;
contenedor.leyenda     = creaLeyenda();
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal 	   = true;
contenedor.padre       ="GRUPO2";
// Se Crea el Formulario
f_edicion   		   = new formulario2('sms_recibidos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.funcion      = t_f_edicion;

// Se Crea el Grid de las Detalle De Los Mensajes
d_sms           = new lista("sms_recibidos/d_sms")
d_sms.nombre    = "d_sms";
d_sms.padre     = "contenido";
d_sms.funcion   = tecla_doc;
d_sms.onSelect  = resetCuenta1;
d_sms.url       = server_path + "herramientas/genera_xml/genera_xml.php";
d_sms.buscador  = true;
d_sms.enter     = 0;
d_sms.botonCerrar = true;
//d_sms.onSelect  = actualizar_detalle_cxc;

function t_f_edicion(objeto,tecla,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case 27:
      ocultar_formulario(f_edicion);
      d_sms.mostrar();
      d_sms.setFocus();
      break;

    case _f12:
      Guardar();
      break;

    case 112:
    case 114:
    case 115:
    case 116:
    case 117:
    case 121:
    case 122:
     	  cancelaTecla(evt);
          break;
  }
}
function tecla_doc(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case 13: // Enter
    	cancelaTecla(evt);
        break;

    case 27: // Esc
    	cancelaTecla(evt);
        Salir();
        break;

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
    t1 = setInterval(Cuenta1,1000);
    tXML = d_sms.elementoXml();

}

function Refrescar()
{
    d_sms.buscar('*');
}

function mostrar_formulario(xformulario)
{
	contenedor.setTitle(xformulario.titulo);
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
      d_sms.mostrar();
      d_sms.buscar('*');
      d_sms.setFocus();


  }
}

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

function Salir()
{
   location.href = server_path + 'main/inicio.php';
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

	contenedor.setTitle(f_edicion.titulo);
	contenedor.setWidth(f_edicion.ancho);
	contenedor.setHeight(f_edicion.alto);

    addEvent(ESC,  "click",  function() { tecla_doc  ('', 27,  d_sms.elementoXml()) } )

	document.onclick=function() {if (parent.menu) parent.menu.reset();}

}
iniciar();

</script>


</body>
</html>

EOT;

?>