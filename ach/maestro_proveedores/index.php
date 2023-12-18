<?php
include('../config.php'); 
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");
include_once (Server_Path . 'herramientas/modulo/class/class_modulo.php');

$ventana = getvar('ventana','modulo');
$id = getvar('id');


//-- armar url de retorno
$retornando = '';
if (!$onClose) $onClose    = Server_Path .'main/inicio.php';
else if (!strpos($onClose,'&retornando=')) $retornando = '&retornando=true';
//--


$my_ini = new ini('modulo');
encabezado($my_ini->seccion('VENTANA','TITULO'));

/* $modulo = new class_modulo('modulo',$onClose); */

$onClose = 'Salir();';
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

var f_activo    		= null;
var url_icono   		= '{$Sistema}/imagenes/maestro_proveedores/';
var t           		= null;
var xID_M_PROVEEDORES	= null;
var fs 					= new forma_simple();
var xTipo 				= '';
var xNOMBRES 			='';
var xNIVEL 				='';
var xPADRE 				='';


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
contenedor.onClose     = Cerrar_contenedor;

m_proveedores                   = new lista("maestro_proveedores/m_proveedores")
m_proveedores.nombre            = "m_proveedores";
m_proveedores.padre             = "contenido";
m_proveedores.url               = server_path + "herramientas/genera_xml/genera_xml.php";
m_proveedores.funcion           = t_m_proveedores;
m_proveedores.buscador          = true;
m_proveedores.filtro        	= 'FILTRO';
f_proveedores                  = new formulario2('maestro_proveedores/f_proveedores');
f_proveedores.nombre           = 'f_proveedores';
f_proveedores.funcion          = t_f_proveedores;
f_proveedores.padre            = 'contenedor_cuadro';
f_proveedores.tipo             = 'formulario';

function Cerrar_contenedor()
{
	f_activo.ocultar();
	contenedor.ocultar();
	Cerrar_formulario();
}

function consultaSRI(){
	if(f_proveedores.getValue('CODIGO1').length==13){
		var url 	= server_path +  'maestro_proveedores/consultaSRI.php';
		var params 	= 'RUC=' + f_proveedores.getValue('CODIGO1');
		var resp = enviar(url, params,'POST');
		if(resp!=''){
			if(resp=='ERROR'){
				alert('Error al consultar SRI');
			}else{			
				var xData = $.parseJSON(resp)[0];
				var nombreComercial =  xData.nombreComercial ==null ? '' : xData.nombreComercial;
				var obligado =  xData.obligado =='S' ? 'SI' : 'NO';
			
				f_proveedores.setValue('RAZON', xData.razonSocial);		
				f_proveedores.setValue('NOMBRES', nombreComercial);
				f_proveedores.setValue('SRI_CONTABILIDAD', obligado);
				f_proveedores.setValue('TIPO', xData.personaSociedad);		
			}
		}
	}
	else
	{
		alert('Longitu de RUC no Valida');
	}
	
}
	

function t_f_proveedores(elemento, tecla,e)
{
  var evt = window.event || e;
  switch (tecla)
  {
    case _esc:
      	cancelaTecla(evt);
      	ocultar_formulario(f_proveedores);
      	m_proveedores.setFocus();
      	break;

    case _f12:
        cancelaTecla(evt);
        var registro = f_proveedores.submit();
        if(!registro) return;
        ocultar_formulario(f_proveedores);
		m_proveedores.actualizar(registro[0]['ID_M_PROVEEDORES']);
        m_proveedores.setFocus();
      	break;
  }
}

function t_m_proveedores(objeto, tecla, xml, e)
{
        var evt = window.event || e;

          switch (tecla)
          {
            case _insert:
                cancelaTecla(evt);
				f_proveedores.limpiar();
				mostrar_formulario(f_proveedores);				
                break;


			case _enter:
                cancelaTecla(evt);
				var registro = valida_xml(xml, 'ID_M_PROVEEDORES');
				if(!registro) return;
				f_proveedores.buscar(registro[0]['ID_M_PROVEEDORES']);
				mostrar_formulario(f_proveedores);				
            	break;
            case _supr:
            	cancelaTecla(evt);
				var registro = valida_xml(xml, 'ID_M_PROVEEDORES');
				if(!registro) return;
				if( confirm('Desea eliminar el proveedor?') )
				{
					var url = server_path + 'herramientas/utiles/actualizar_registro.php';
					var params = "origen=maestro_proveedores/m_proveedores&procedimiento=ELIMINAR_ITEM&ID_M_PROVEEDORES=" + registro[0]['ID_M_PROVEEDORES'];
					var x=enviar(url,params,'POST');
					m_proveedores.buscar('*');					
				}
			break;

            case _esc:
		
                cancelaTecla(evt);
                Salir();
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

function Salir()
{
	location.href = server_path + 'main/inicio.php';
}

function Guardar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_proveedores':
          t_f_proveedores('',_f12);
          break;
       case 'f_hijo':
          t_f_hijo('',_f12);
          break;

       case 'f_hijo_grupo':
          t_f_hijo_grupo('',_f12);
          break;
    }
}

function Cerrar_formulario()
{
    switch (f_activo.nombre)
    {
       case 'f_proveedores':
          t_f_proveedores('',_esc);
          break;

       case 'f_hijo':
          t_f_hijo('',_esc);
          break;

       case 'f_hijo_grupo':
          t_f_hijo_grupo('',_esc);
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

	m_proveedores.inicializa(false);
	m_proveedores.mostrar();

	f_proveedores.inicializa();

	document.onclick=function() { if (parent.menu) parent.menu.reset(); }
	addEvent(INSERT   	, "click",   function() { t_m_proveedores('', _insert, 	m_proveedores.elementoXml()) } )
	addEvent(ENTER  	, "click",   function() { t_m_proveedores('', _enter, 	m_proveedores.elementoXml()) } )
	addEvent(SUPR  		, "click",   function() { t_m_proveedores('', _supr, 	m_proveedores.elementoXml()) } )
	addEvent(ESC   		, "click",   function() { t_m_proveedores('', _esc, 	m_proveedores.elementoXml()) } )

	return true;
}

function inicio()
{
    mostrarLeyenda(0);
	m_proveedores.setFocus();

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