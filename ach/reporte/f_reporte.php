<?php

define('Server_Path','../');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$origen1 = "reportes/f_" . strtolower(getvar('origen'));
$origen2 = "reportes/r_" . strtolower(getvar('origen'));

encabezado('');

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,auto_tabla,formulario,forma,tabpane');

echo <<<EOT

<script type="text/javascript">
oFormulario = new formulario('{$origen1}');
oFormulario.titulo      = 'Edicion	';
oFormulario.nombre      = 'oFormulario';
//oFormulario.url         = server_path + 'herramientas/utiles/ini2xml.php?tabla=P_MAESTROS&campos=*&busca=TABLA&xbusca=M_CLIENTES';
oFormulario.x           = 100;
oFormulario.y           = 100;
//oFormulario.debug       = true;

//oFormulario.titulo      = 'SUBMODAL v2.0';
oFormulario.funcion     = teclaForm;
oFormulario.botonCerrar = true;

oFormulario.botones     = "<button onClick='oFormulario.submit();'>Aceptar</button><button onClick='oFormulario.ocultar();'>Cancelar</button>";
oFormulario.modal = false;
oFormulario.accion = procesa;

oFormulario.inicializa();

centrarObj(oFormulario.contenedor);

oFormulario.mostrar();

oFormulario.setFocus();

function teclaForm(obj, tecla)
{
  switch (tecla)
  {
    case 13:
      break;
    case 27:
      oFormulario.ocultar();
      break;

    case 123: // -- F12 = guardar
      oFormulario.submit();
      break;
  }

}

function procesa(objForm)
{
  var params = server_path + 'herramientas/genera_reporte/genera_reporte.php?origen={$origen2}';
  for (var i=0; i<objForm.length; i++)
  {
    var campo = objForm[i].name;

    if(campo.substring(0,2)=='c_') params += '&' + objForm[i].id + '=' + objForm[i].value;

  }

  parent.reporte.location.href = params;


}


</script>

EOT;

?>