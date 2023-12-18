<?php
include('../config.php');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');
$xnivel 	= getvar('nivel');

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$onClose = 'Salir();';
$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";
cargando();

javascript('utiles,formulario2,forma,auto_tabla,submodal');

echo <<<EOT
{$modulo->inicio}
<table>
    <tr>
        <td id="GRUPO1" </td>
    </tr>
</table>
{$modulo->fin}

<script type="text/javascript">

var xnivel = "{$xnivel}";

d_cheques             = new lista("control_pagos_emitidos/d_cheques")
d_cheques.nombre      = "d_cheques";
d_cheques.url         = server_path + "herramientas/genera_xml/genera_xml.php";
d_cheques.funcion     = t_d_cheques;
d_cheques.padre		  = "GRUPO1";
d_cheques.buscador    = true;
d_cheques.enter       = 0;
d_cheques.botonCerrar = true;

contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 1;
contenedor.alto        = 1;
contenedor.x           = 100;
contenedor.y           = 50;
contenedor.titulo      = 'Edición de Item';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal 	   = true;

f_edicion 				= new formulario2('control_pagos_emitidos/f_edicion');
f_edicion.nombre       = 'f_edicion';
f_edicion.funcion      = t_edicion;
f_edicion.padre        = 'contenedor_cuadro';

function t_d_cheques(objeto, tecla, xml,e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case _f3: // F3
			cancelaTecla(evt);
			break;
		case _f4: // F4
		cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(registro[0]['ESTATUS'] == 'ANU'){
			alert('Operacion no permitida, el pago ha sido anulado');
			return;
			}

			if (xnivel=='')
			{

			}
			else
			{
				var registro = XML2Array(xml);

				if(!registro[0]['ID_D_PAGOS'] && registro[0]['ESTATUS'] != 'REA') return false;
				var xpago     = registro[0]['ID_D_PAGOS'];
				var entregado = confirm('{$t_cambiar_estatus}');

				if(entregado)
				{
				url = server_path + 'herramientas/utiles/actualizar_registro.php';
				params = 'tabla=D_PAGOS&c_ESTATUS_CSS=AUT&busca=ID_D_PAGOS&xbusca='+xpago;
				x = enviar(url, params, 'POST');
				d_cheques.actualizar(xpago);
				}
				else return false;
			}
		break;

		case _f5: // F5
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(registro[0]['ESTATUS'] == 'ANU'){
				alert('Operacion no permitida, el pago ha sido anulado');
				return;
			}
			switch (xnivel)
			{
				case "2":
				case "3":
				case "4":
					var registro = XML2Array(xml);
					if(!registro[0]['ID_D_PAGOS']) return false;
					var xpago     = registro[0]['ID_D_PAGOS'];
					if(registro[0]['ESTATUS'] != 'AUT')
					{
						alert('$t_proceso_incorrecto');
						return false;
					}
					var entregado = confirm('{$t_cambiar_estatus}');

					if(entregado)
					{
						url = server_path + 'herramientas/utiles/actualizar_registro.php';
						params = 'tabla=D_PAGOS&c_ESTATUS_CSS=ENT&busca=ID_D_PAGOS&xbusca='+xpago;
						x = enviar(url, params, 'POST');
						d_cheques.actualizar(xpago);
					}
					else return false;
				break;
			}
		break;

		case _f6:  // F6
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(registro[0]['ESTATUS'] == 'ANU'){
				alert('Operacion no permitida, el pago ha sido anulado');
				return;
			}
			switch (xnivel)
			{
				case "3":
				case "4":
					var registro = XML2Array(xml);

					if(!registro[0]['ID_D_PAGOS']) return false;
					var xpago = registro[0]['ID_D_PAGOS'];
					if(registro[0]['ESTATUS'] != 'ENT')
					{
						alert('$t_proceso_incorrecto');
						return false;
					}

					var cobrado = confirm('{$t_cambiar_estatus}');

					if(cobrado)
					{
						url = server_path + 'herramientas/utiles/actualizar_registro.php';
						params = 'tabla=D_PAGOS&c_ESTATUS_CSS=COB&busca=ID_D_PAGOS&xbusca='+xpago;
						x = enviar(url, params, 'POST');
						d_cheques.actualizar(xpago);
					}
					else return false;
				break;
	
			}
		break;

		case _f7://F7
			cancelaTecla(evt);
			var registro = valida_xml(xml,'ID_D_PAGOS');
			if(registro[0]['ESTATUS'] == 'ANU'){
			alert('Operacion no permitida, el pago ha sido anulado');
			return;
			}
			if (xnivel=="4")
			{
				var registro = XML2Array(xml);

				if(!registro[0]['ID_D_PAGOS']) return false;
				var xpago = registro[0]['ID_D_PAGOS'];
				var anular = confirm('{$t_anular_registro}');

				if(anular)
				{
					url = server_path + 'herramientas/utiles/actualizar_registro.php';
					params = 'tabla=D_PAGOS&c_ESTATUS_CSS=ANU&busca=ID_D_PAGOS&xbusca='+xpago;
					x = enviar(url, params, 'POST');
					d_cheques.actualizar(xpago);
				}
				else return false;
			}
		break;
		case _esc:  //Escape Salir
			Salir();
		break;
	}
}

function t_edicion(objeto,tecla)
{
  switch (tecla)
  {
    case _esc: //Salir
        contenedor.ocultar()
        f_edicion.ocultar();
        d_cheques.setFocus();
      	break;

    case _f12: // F12
        break;
  }
}

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function ocultaLeyenda()
{
    for (var i=1;i<=5;i++)
    {
       var obj = document.getElementById('LEYENDA'+i);
       obj.style.display='none';
    }
}

function Iniciar()
{
	d_cheques.inicializa();
	d_cheques.mostrar();
	d_cheques.setFocus();

	contenedor.inicializa();
	centrarObj(contenedor.contenedor);

	f_edicion.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }

	addEvent(LEYENDA1, "click",   function() { t_d_cheques('', _f4, d_cheques.elementoXml()) } )//f4
	addEvent(LEYENDA2, "click",   function() { t_d_cheques('', _f5, d_cheques.elementoXml()) } )//f5
	addEvent(LEYENDA3, "click",   function() { t_d_cheques('', _f6, d_cheques.elementoXml()) } )//f6
	addEvent(LEYENDA4, "click",   function() { t_d_cheques('', _f7, d_cheques.elementoXml()) } )//f7
	addEvent(LEYENDA5, "click",   function() { t_d_cheques('', _esc, d_cheques.elementoXml()) } )//esc
}

Iniciar();
</script>


</body>
</html>

EOT;

?>