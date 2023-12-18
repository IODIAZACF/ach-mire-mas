<?php
include('../config.php'); 
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');

$onClose = 'Salir();';

$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

$modulo = new class_modulo('modulo',$onClose);

echo '<body id="proceso" onload="ocultaCarga();">' . "\n";

cargando();
$s_id_usuario=getsession('M_USUARIOS_ID_M_USUARIO');

javascript('utiles,auto_tabla,forma,formulario2,submodal,jquery,forma_simple');

echo <<<EOT

{$modulo->inicio}

<div id="contenido">

</div>

{$modulo->fin}

<script type="text/javascript">

var f_activo    = null;
var url_icono   = '{$Sistema}/imagenes/maestro_clientes/';
var t           = null;
var xID_M_CLIENTES  = null;
var fs 			= new forma_simple();
var xTipo 		= '';

var xNOMBRES ='';
var xNIVEL ='';
var xPADRE ='';


contenedor             = new submodal();
contenedor.nombre      = 'contenedor';
contenedor.ancho       = 500;
contenedor.alto        = 300;
contenedor.titulo      = ' ';
contenedor.ayuda       = 1;
contenedor.x           = 1;
contenedor.y           = 1;
contenedor.titulo      = 'SUBMODAL 2';
contenedor.botonCerrar = true;
contenedor.leyenda     = '   ';
contenedor.usaFrame    = false;
contenedor.interpretar = false;
contenedor.modal       = true;

m_clientes                   = new lista("maestro_clientes/m_clientes")
m_clientes.nombre            = "m_clientes";
m_clientes.padre             = "contenido";
m_clientes.url               = server_path + "herramientas/genera_xml/genera_xml.php";
m_clientes.funcion           = t_m_clientes;
m_clientes.buscador          = true;
m_clientes.filtro        	 = 'FILTRO';

f_clientes                  = new formulario2('maestro_clientes/f_clientes');
f_clientes.nombre           = 'f_clientes';
f_clientes.funcion          = t_f_clientes;
f_clientes.padre            = 'contenedor_cuadro';
f_clientes.tipo             = 'formulario';

d_contactos                   = new lista("maestro_clientes/d_contactos");
d_contactos.nombre            = "d_contactos";
d_contactos.url               = server_path + "herramientas/genera_xml/genera_xml.php";
d_contactos.funcion           = t_d_contactos;
d_contactos.buscador          = true;
d_contactos.asyncLoad         = false;
d_contactos.filtro        	  = 'IDX;TABLA';
d_contactos.enter      		  = 0;

//d_contactos.modal       = true;

f_d_contactos                  = new formulario2('maestro_clientes/f_d_contactos');
f_d_contactos.nombre           = 'f_d_contactos';
f_d_contactos.funcion          = t_f_d_contactos;
f_d_contactos.padre            = 'contenedor_cuadro';
f_d_contactos.tipo             = 'formulario';

function t_d_contactos(objeto, tecla, xml, e)
{
	var evt = window.event || e;

	switch (tecla)
	{
	case _insert:
		cancelaTecla(evt);
		d_contactos.ocultar();
		f_d_contactos.limpiar();
		f_d_contactos.setValue('IDX', xID_M_CLIENTES);
		f_d_contactos.setValue('TABLA', 'M_CLIENTES');
		mostrar_formulario(f_d_contactos);				
		break;


	case _enter:
		cancelaTecla(evt);
		var registro = valida_xml(xml, 'ID_D_CONTACTOS');
		if(!registro) return;
		d_contactos.ocultar();
		f_d_contactos.buscar(registro[0]['ID_D_CONTACTOS']);
		mostrar_formulario(f_d_contactos);
		break;
	case _supr:
		cancelaTecla(evt);
		var registro = valida_xml(xml, 'ID_D_CONTACTOS');
		if(!registro) return;
		var borrar = confirm('{$t_eliminar_registro}');
		if(borrar)
		{
			var url = server_path + "herramientas/utiles/actualizar_registro.php";
			var param =  "origen=maestro_clientes/d_contactos&procedimiento=ELIMINAR&ID_D_CONTACTOS=" + registro[0]['ID_D_CONTACTOS'];
			enviar(url,param,'GET');
		}
		d_contactos.setFocus();
		d_contactos.buscar('*');
		
		break;

	case _esc:

		cancelaTecla(evt);
		d_contactos.ocultar();
		m_clientes.setFocus();
		break;

	case _f1:
	case _f2:
	case _f3:
	case _f4:
	case _f5:
	case _f6:
	case _f7:
	case _f8:
	case _f9:
	case _f10:
	case _f11:
	case _f12:
		cancelaTecla(evt);
			break;
	}
}


function t_f_d_contactos(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      	cancelaTecla(evt);
      	ocultar_formulario(f_d_contactos);
		d_contactos.mostrar();
      	d_contactos.setFocus();
      	break;

    case _f12:
        cancelaTecla(evt);
        var registro = f_d_contactos.submit();
        if(!registro) return;
        ocultar_formulario(f_d_contactos);
		d_contactos.actualizar(registro[0]['ID_D_CONTACTOS']);
		d_contactos.mostrar();
        d_contactos.setFocus();
      	break;
  }
}



function consultaSRI(){
	if(f_clientes.getValue('CODIGO1').length==13){
		var url 	= server_path +  'maestro_clientes/consultaSRI.php';
		var params 	= 'RUC=' + f_clientes.getValue('CODIGO1');
		var resp = enviar(url, params,'POST');		
		if(resp!=''){
			if(resp=='ERROR'){
				alert('Error al consultar SRI');
				
			}else{
				var xData = $.parseJSON(resp)[0];
				var nombreComercial =  xData.nombreComercial ==null ? '' : xData.nombreComercial;
				var obligado =  xData.obligado =='S' ? 'SI' : 'NO';
				
				f_clientes.setValue('RAZON', xData.razonSocial);		
				f_clientes.setValue('NOMBRES', nombreComercial);
				f_clientes.setValue('SRI_CONTABILIDAD', obligado);
				f_clientes.setValue('TIPO', xData.personaSociedad);		
				
			}
		}
	}
	else
	{
		alert('Longitu de RUC no Valida');
	}
	
	
}
	

function t_f_clientes(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      	cancelaTecla(evt);
      	ocultar_formulario(f_clientes);
      	m_clientes.setFocus();
      	break;

    case _f12:
        cancelaTecla(evt);
        var registro = f_clientes.submit();
        if(!registro) return;
        ocultar_formulario(f_clientes);
		m_clientes.actualizar(registro[0]['ID_M_CLIENTES']);
        m_clientes.setFocus();
      	break;
  }
}

function t_m_clientes(objeto, tecla, xml, e)
{
        var evt = window.event || e;

          switch (tecla)
          {
            case _insert:
                cancelaTecla(evt);
				f_clientes.limpiar();
				mostrar_formulario(f_clientes);				
                break;


			case _enter:
                cancelaTecla(evt);
				var registro = valida_xml(xml, 'ID_M_CLIENTES');
				if(!registro) return;
				f_clientes.buscar(registro[0]['ID_M_CLIENTES']);
				mostrar_formulario(f_clientes);				
            	break;
            case _supr:
            	cancelaTecla(evt);
                break;

            case _esc:
		
                cancelaTecla(evt);
                Salir();
            	break;

            case _f1:
                cancelaTecla(evt);
            	break;
            case _f2:
                cancelaTecla(evt);
				var registro = valida_xml(xml, 'ID_M_CLIENTES');
				if(!registro) return;
				xID_M_CLIENTES = registro[0]['ID_M_CLIENTES'];
				d_contactos.xfiltro        	  = xID_M_CLIENTES + ';M_CLIENTES';
				d_contactos.buscar('*');
				d_contactos.mostrar();
				d_contactos.setFocus();
            	break;
            case _f3:
            case _f4:
            case _f5:
            case _f6:
            case _f7:
            case _f8:
            case _f9:
            case _f10:
            case _f11:
            case _f12:
                cancelaTecla(evt);
                    break;
          }
}


function Salir()
{
   location.href = server_path + 'main/inicio.php';
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_clientes':
          t_f_clientes('',_f12);
          break;
       case 'f_d_contactos':
          t_f_d_contactos('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_clientes':
          t_f_clientes('',_esc);
          break;

       case 'f_d_contactos':
          t_f_d_contactos('',_esc);
          break;

    }
}

function mostrar_formulario(xformulario)
{
    f_activo=xformulario;
    contenedor.setTitle(xformulario.titulo);
        contenedor.setWidth(xformulario.ancho);
        contenedor.setHeight(xformulario.alto);
        centrarObj(contenedor.contenedor);
    contenedor.setLegend(xformulario.leyenda);
        contenedor.mostrar();
        xformulario.mostrar();
        setTimeout('f_activo.setFocus();',10);
}

function ocultar_formulario(xformulario)
{
    contenedor.ocultar();
    xformulario.ocultar();
}



function iniciar()
{

	contenedor.inicializa();
	
	centrarObj(contenedor.contenedor);
	
	m_clientes.inicializa(false);	
	m_clientes.mostrar();
	//centrarObj(modulo);

	d_contactos.inicializa(false);
	centrarObj(d_contactos.contenedor);

	f_clientes.inicializa();
	f_d_contactos.inicializa();
	
	document.onclick = function() { if (parent.menu) parent.menu.reset(); } 

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	addEvent(INSERT   	, "click",   function() { t_m_clientes('', _insert, m_clientes.elementoXml()) } )
	addEvent(ENTER   	, "click",   function() { t_m_clientes('', _enter, m_clientes.elementoXml()) } )
	addEvent(F2   	, "click",   function() { t_m_clientes('', _f2, m_clientes.elementoXml()) } )
	addEvent(ESC   		, "click",   function() { t_m_clientes('', _esc, m_clientes.elementoXml()) } )
	
	addEvent(d_contactos_INSERT   		, "click",   function() { t_d_contactos('', _insert, d_contactos.elementoXml()) } )
	addEvent(d_contactos_ENTER   		, "click",   function() { t_d_contactos('', _enter, d_contactos.elementoXml()) } )
	addEvent(d_contactos_SUPR   		, "click",   function() { t_d_contactos('', _supr, d_contactos.elementoXml()) } )
	addEvent(d_contactos_ESC   		, "click",   function() { t_d_contactos('', _esc, d_contactos.elementoXml()) } )

	return true;
}

function inicio()
{
    mostrarLeyenda(0);
	m_clientes.buscar('*');
	m_clientes.setFocus();

}


var resp = iniciar();
if(resp)
{
        inicio(0);
}
else
{
        Salir();
}

</script>


</body>
</html>

EOT;

?>