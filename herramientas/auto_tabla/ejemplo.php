<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');


encabezado('EJEMPLO DE GRID HTML');

echo '<body onload="ocultaCarga();">' . "\n";
cargando();
javascript('utiles,auto_tabla');


echo <<<EOT

<script type="text/javascript">

/**** Construccion del Grid 1 ****/

hgrid = new lista();
hgrid.nombre     = 'hgrid';

hgrid.ancho      = 400;
hgrid.alto       = 500;

hgrid.x          = 100;
hgrid.y          = 20;

hgrid.debug = true;
hgrid.url        = server_path + "herramientas/genera_xml/genera_xml.php";
hgrid.tabla_xml  = "M_PACIENTES";
hgrid.campos_xml = "*";
hgrid.busca_xml  = "ID_PACIENTES,NOMBRES,CODIGO1";
hgrid.limite     = 100;
hgrid.titulo     = 'GRID NUMERO 1';

hgrid.rotulos = "Nombres,RIF,NIT";
hgrid.campos  = "NOMBRES,CODIGO1,CODIGO2";
hgrid.tipos   = "C,N,N";
hgrid.medidas = "150,80,80";

hgrid.buscador        = true;
hgrid.botonCerrar = true;

hgrid.debug        =true;

hgrid.agregaPie('Nombre:', 'NOMBRES', 1, 200, 'C');
hgrid.agregaPie('Codigo1:', 'CODIGO1', 1, 200, 'C');
hgrid.agregaPie('Direccion:', 'DIRECCION', 2, 100, 'C');

hgrid.inicializa();
hgrid.mostrar();
// hgrid.buscar("EDSON")

hgrid.setFocus();



/*
// Construccion del Grid 2 //
hgrid2 = new lista(null);
hgrid2.nombre           = 'hgrid2';

hgrid2.ancho           = 400;
hgrid2.alto             = 200;

hgrid2.x                   = 400;
hgrid2.y                     = 250;

hgrid2.url        = server_path + "herramientas/genera_xml/genera_xml.php";
hgrid2.tabla_xml  = "M_LINEAS";
hgrid2.campos_xml = "*";
hgrid2.busca_xml  = "ID_LINEAS,NOMBRES";
hgrid2.limite            = 100;

hgrid2.rotulos = "CODIGO,NOMBRE";
hgrid2.campos  = "ID_LINEAS,NOMBRES";
hgrid2.tipos   = "N,C";
hgrid2.medidas = "80,250";
hgrid2.funcion = Teclagrid2;
hgrid2.buscador= true;
hgrid2.titulo  = 'GRID NUMERO 2   (Modal) <button>Boton</button>';
hgrid2.botonCerrar = true;

//hgrid2.modal = true;
hgrid2.inicializa();
hgrid2.mostrar();
//hgrid2.onSelect = actualizaPie;
// hgrid2.buscar("EDSON")

//hgrid2.setFocus();

*/

// Construccion del Grid 3 //
/*
hgrid3 = new lista('maestros/m_clientes');
hgrid3.nombre = 'hgrid3';
hgrid3.usaFrame = false;

//hgrid3.leyenda = '<b>sadasdf sadfasd fasdf</b><h1>afdasdfasf</h1>';

hgrid3.titulo      = "GRID 3";

hgrid3.x           = 10;
hgrid3.y           = 10;
hgrid3.modal       = true;
hgrid3.url         = server_path + "herramientas/genera_xml/genera_xml.php";
hgrid3.funcion     = Teclagrid3;
hgrid3.buscador    = true;
hgrid3.botonCerrar = true;

//hgrid3.onClose     = cerrarGrid;

hgrid3.inicializa();

hgrid3.mostrar();

hgrid3.setFocus();
*/

function Teclagrid(objeto, tecla, xml)
{

  registro = XML2Array(xml);
  switch (tecla)
  {

    case 13:
      alert(xml);
      linea.value    = registro[0]['NOMBRES'];
      id_linea.value = registro[0]['DIRECCION'];
      break;

    case 27:
      hgrid.ocultar();
      break;

  }
}

function Teclagrid2(objeto, tecla, xml)
{
  switch (tecla)
  {
    case 13:
      // alert(xml);
      hgrid2.ocultar();
      registro = XML2Array(xml);
      linea2.value    = registro[0]['NOMBRES'];
      id_linea2.value = registro[0]['ID_LINEAS'];
      break;

    case 27:
      hgrid2.ocultar();
      break;

  }
}

function Teclagrid3(objeto, tecla, xml)
{

  registro = XML2Array(xml);
  switch (tecla)
  {

    case 13:
      alert(xml);
      linea3.value    = registro[0]['NOMBRES'];
      id_linea3.value = registro[0]['ID_LINEAS'];

      break;

    case 27:
      hgrid3.ocultar();
      break;

  }
}

</script>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<br>
GRID 1
<input name="id_linea" type="text" value="">
<input name="linea" type="text" value=""><button onclick="hgrid.buscar(linea.value);hgrid.mostrar(); hgrid.setFocus();">...</button>

<br>
GRID 2
<input name="id_linea2" type="text" value="">
<input name="linea3" type="text" value=""><button onclick="hgrid2.buscar(linea.value);hgrid2.mostrar(); hgrid2.setFocus();">...</button>

<br>
GRID 3
<input name="id_linea3" type="text" value="">
<input name="linea3" type="text" value=""><button onclick="hgrid3.buscar(linea.value);hgrid3.mostrar(); hgrid3.setFocus();">...</button>
<p>
<button onClick="hgrid.cambiarOrigen('maestros/m_estados');hgrid.mostrar();hgrid.setFocus();">Cambiar Origen</button>
<button onClick="hgrid.cambiarOrigen('maestros/m_clientes');hgrid.mostrar();hgrid.setFocus();">Cambiar Origen</button>
<button onClick="hgrid.cambiarOrigen('maestros/m_tareas');hgrid.mostrar();hgrid.setFocus();">Cambiar Origen</button>
<p>
Leer Fila:<input name="FILA" type="text" value="0" size=3>&nbsp;&nbsp;
Columna:<input Name="COLUMNA" type="text" value="0" size=3>
<button name="LEEVALOR" onclick="alert(hgrid.leeValor(FILA.value,COLUMNA.value));">PRUEBA LEEVALOR</button>

<p>
Escribir Fila:<input name="FILA2" type="text" value="0" size=3>&nbsp;&nbsp;
Columna:<input Name="COLUMNA2" type="text" value="0" size=3>
Valor: <input name="NUEVO_VALOR" type="text">
<button name="LEEVALOR" onclick="hgrid.escribeValor(FILA2.value,COLUMNA2.value, NUEVO_VALOR.value);">PRUEBA ESCRIBEVALOR</button>

<p>
<button onclick="for (var i=0;i<100;i++) hgrid.buscar('');">Buscar muchos</button>
</body>


EOT;


?>