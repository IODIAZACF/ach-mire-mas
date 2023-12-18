<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

echo '<body onload="ocultaCarga();">' . "\n";

cargando();

encabezado('EJEMPLO DE FORMA VALIDADOR Y MASCARA');

javascript('formulario,forma,utiles,auto_tabla');

echo <<<EOT

<script type="text/javascript">

oFormulario = new formulario('formularios/f_clientes');

oFormulario.nombre      = 'oFormulario';
oFormulario.ancho       = 510;
oFormulario.alto        = 350;

oFormulario.x           = 100;
oFormulario.y           = 100;

//oFormulario.debug       = true;

oFormulario.titulo      = 'SUBMODAL v2.0';
oFormulario.funcion     = teclaForm;
oFormulario.botonCerrar = true;

oFormulario.botones     = "<button onClick='oFormulario.submit();'>Aceptar</button><button onClick='oFormulario.ocultar();'>Cancelar</button>";
oFormulario.leyenda     = "LEYENDA DEL FORMULARIO";
oFormulario.modal       = true;

oFormulario.inicializa();
oFormulario.mostrar();


oForm = new forma('f_clientes', 'oForm');
oForm.validacion('NOMBRES','minlen:10','Mensaje de Validacion Min');
oForm.validacion('NOMBRES','maxlen:12','Mensaje de Validacion Max');
oForm.validacion('CODIGO1','unico:M_PACIENTES,CODIGO1', 'La cédula {3} ya existe');


oForm.mascara('NOMBRES','L-00.000.000');
/*
var x4 = oForm.dependencia('campo4');
x4.tabla  = "M_CIUDADES";
x4.campo_guardar = "ID_CIUDADES";
x4.campo_mostrar = "NOMBRES";
x4.campos = "ID_CIUDADES,NOMBRES";
x4.busca = "ID_ESTADOS";
x4.padre = 'campo3';

var x5 = oForm.dependencia('campo5');
x5.tabla  = "M_URBANIZACIONES";
x5.campo_guardar = "ID_URBANIZACIONES";
x5.campo_mostrar = "NOMBRES";
x5.campos = "ID_URBANIZACIONES,NOMBRES";
x5.busca = "ID_CIUDADES";
x5.padre = 'campo4';

var x6 = oForm.maestro('campo7');
x6.origen = server_path + 'maestros/m_clientes';
x6.campo_guardar = "ID_URBANIZACIONES";
x6.campo_mostrar = "NOMBRES";
x6.dmostrar = "r_campo7";
x6.boton = "b_campo7";

*/
oForm.inicializa();



function teclaForm(obj, tecla)
{
  switch (tecla)
  {
    case 13:
      break;
    case 27:
      obj.ocultar();
      break;

    case 119:
      obj.submit();
      obj.ocultar();
      break;
  }

}

</script>

<button onclick="oForm.submit">Submit</button>

</body>


EOT;


?>