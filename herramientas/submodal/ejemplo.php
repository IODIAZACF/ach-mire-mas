<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');

echo '<body onload="ocultaCarga();">' . "\n";

cargando();

encabezado('EJEMPLO DE MULTIFORM');

javascript('utiles,submodal');

echo <<<EOT

<script type="text/javascript">

contenedor = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 510;
contenedor.alto        = 300;

contenedor.x           = 1;
contenedor.y           = 1;

//contenedor.debug       = true;

contenedor.url         = server_path + "herramientas/submodal/genera_html.php?nombre=EDSON DAZA";
//contenedor.url         = 'http://www.google.com';
contenedor.titulo      = 'SUBMODAL 2';
contenedor.funcion     = teclaForm;
contenedor.botonCerrar = true;

contenedor.botones     = "<button onClick='contenedor.submit();'>Aceptar</button><button onClick='contenedor.ocultar();'>Cancelar</button>";
contenedor.leyenda     = "LEYENDA DEL FORMULARIO";
contenedor.interpretar = true;
// contenedor.modal = true;
contenedor.inicializa();

centrarObj(contenedor.contenedor);
contenedor.mostrar();
contenedor.setFocus();


contenedor2 = new submodal();
contenedor2.nombre      = 'contenedor2';
contenedor2.ancho       = 510;
contenedor2.alto        = 300;

contenedor2.x           = 1;
contenedor2.y           = 1;

//contenedor2.debug       = true;

//contenedor2.url         = server_path + "herramientas/submodal/genera_html.php?nombre=EDSON DAZA";
contenedor2.url         = server_path + "herramientas/submodal/prueba.html";
contenedor2.titulo      = 'SUBMODAL 3';
contenedor2.funcion     = teclaForm;
contenedor2.botonCerrar = true;

contenedor2.botones     = "<button onClick='contenedor2.submit();'>Aceptar</button><button onClick='contenedor2.ocultar();'>Cancelar</button>";
contenedor2.leyenda     = "LEYENDA DEL FORMULARIO";
contenedor2.interpretar = false;
// contenedor2.modal = true;
contenedor2.inicializa();
contenedor2.mostrar();

function teclaForm(obj, tecla)
{
  switch (tecla)
  {
    case 13:
      alert('Presionaron Enter en el Formulario');
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



<br>
<br>
<button onclick="contenedor.limpiar()">Limpiar</button><br>
<button onclick="contenedor.setFocus()">Colocar Foco</button><br>
<button onclick="contenedor.mostrar()">Mostrar</button><br>
<button onclick="contenedor.cambiar()">Cambiar de Panel</button><br>

<input type="text" name="buscador" value="1"><button onclick="contenedor.buscar(buscador.value)">Buscar este Codigo en el formulario activo</button>
<p>
<button onclick="alert(contenedor2.frame.document)">Ver contenido2</button>
</body>


EOT;


?>