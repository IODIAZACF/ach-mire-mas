<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));
$id_usuario = getsession('M_USUARIOS_ID_M_USUARIO');
$nombre_usuario = getsession('M_USUARIOS_NOMBRES');

$onClose = 'Salir();';

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal,password');

echo <<<EOT

<div id="contenido">

</div>

<script type="text/javascript">

var opcion	  = '';
var usuario = '{$nombre_usuario}';
var id_usuario='{$id_usuario}';

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 600;
contenedor.alto        = 280;
contenedor.x           = 100;
contenedor.y           = 50;
contenedor.titulo      = 'Reporte de casos';
contenedor.botonCerrar = true;
contenedor.leyenda     = creaLeyenda();
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.ayuda       = 502;
contenedor.modal 	   = false;

// Se Crea el Formulario
f_edicion   		   = new formulario2('reporte_casos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.padre        = 'contenedor_cuadro';
f_edicion.funcion      = t_f_formulario;

function t_f_formulario(objeto,tecla,e)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case 27:
      Salir();
      break;

    case 123: // F12 Guardar

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


function Guardar(valor)
{
  /*
  var idarea=f_edicion.getValue('ID_M_AREA');
  var xml=genera_xml('M_AREAS', 'GRUPO,CAMPO1', 'ID_M_AREA', idarea, '=', '', 'automatizacion');
  var reg=XML2Array(xml);
  if (idarea)
  {
  	f_edicion.setValue('NOMBRE_AREA',f_edicion.getValue('r_ID_M_AREA'));
  	f_edicion.setValue('ZONA',reg[0]['GRUPO']);
  	f_edicion.setValue('NIVEL',reg[0]['CAMPO1']);
  }
  */
  f_edicion.setValue('NOMBRE_AREA',f_edicion.getValue('r_ID_M_AREA'));
  var registro = f_edicion.submit();
  if (!registro) return false;
  f_edicion.limpiar();
  verUsuario();

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
   parent.proceso.location.href = server_path + 'main/inicio.php';
}

function verUsuario(aceptado)
{
    f_edicion.setValue('USUARIO',usuario);
    f_edicion.setValue('ID_M_USUARIOS1',id_usuario);
    f_edicion.setValue('ID_M_USUARIOS3',id_usuario);
    f_edicion.setValue('r_ID_M_USUARIOS3',usuario);
    f_edicion.setFocus();
}

function iniciar()
{
	contenedor.inicializa();
	centrarObj(contenedor.contenedor);
	contenedor.mostrar();

	f_edicion.inicializa();
    f_edicion.mostrar();
    verUsuario();

	contenedor.setTitle(f_edicion.titulo);
	contenedor.setWidth(f_edicion.ancho);
	contenedor.setHeight(f_edicion.alto);

	document.onclick=function() {if (parent.menu) parent.menu.reset();}

}
iniciar();

</script>


</body>
</html>

EOT;

?>