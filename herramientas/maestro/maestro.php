<?php
header('Content-Type: text/html; charset=iso-8859-1');
include_once (Server_Path . 'herramientas/utiles/comun.php');
include_once (Server_Path . "herramientas/ini/class/class_ini.php");


//die(RUTA_SISTEMA);
$configuracion_sistema  = getsession('CONFIGURACION_SISTEMA');

$origen  = getvar('origen');
$x_config = getvar('xconfig');

$my_ini1 = new ini(RUTA_SISTEMA . $origen);

$titulo     = $my_ini1->seccion('VENTANA','TITULO');
$indice     = $my_ini1->seccion('TABLA','INDICE');
$xbusca     = $my_ini1->seccion('TABLA','LOCALIZA');
$form_ancho = $my_ini1->seccion('FORMULARIO','ANCHO');
$form_alto  = $my_ini1->seccion('FORMULARIO','ALTO');
$form_ayuda = $my_ini1->seccion('VENTANA','AYUDA');

$filtros  = $my_ini1->secciones('FILTRO');
$validar=false;

if($filtros){	
	$form_xfiltro = '';
	for($i=0;$i<sizeof($filtros);$i++)
	{
	   $xfiltro  = variable($filtros[$i][VALOR]);
	   $vxfiltro = variable2($filtros[$i][VALOR]);
	   if($xfiltro==$vxfiltro)
	   {
		if(strlen($str_script)) $str_script .= "\n";
		$str_script .= "alert('". $filtros[$i]['ALERTA'] ."'); ";
		if(trim($filtros[$i]['REQUERIDO'])=='SI') $str_script .= "return false;";
	   }
	   $form_xfiltro .= $xfiltro     .";";
	   $form_filtro  .= $filtros[$i][VARIABLE] .";";
	}
	if(strlen($form_filtro))
	{
	   $form_xfiltro = substr($form_xfiltro,0,-1);
	   $form_filtro  = substr($form_filtro,0, -1);

	  $filtro ="oGrid.filtro     = \"". $form_filtro ."\";\n";
	  $filtro.="oGrid.xfiltro    = \"". $form_xfiltro ."\";";
	}
}


$origen2 = $my_ini1->seccion('FORMULARIO','ORIGEN');

$my_ini2 = new ini(RUTA_SISTEMA . $origen2);
$id_usuario = getsession('M_USUARIOS_ID_M_USUARIO');
//$titulo2 = $my_ini2->seccion('VENTANA','TITULO');

encabezado($titulo);

echo '<body id="proceso" onload="ocultaCarga()">' . "\n";

cargando();
javascript('utiles,auto_tabla,formulario2,forma,tabpane,password,submodal');

$ancho   = ($my_ini2->seccion('VENTANA','ANCHO') ? $my_ini2->seccion('VENTANA','ANCHO') : 0);
$alto    = ($my_ini2->seccion('VENTANA','ALTO') ? $my_ini2->seccion('VENTANA','ALTO') : 0);
//$altoGrid = $alto - 200;
$paneles = $my_ini2->secciones('PANEL');
$cant    = sizeof($paneles);
$arrPaneles = 'var arrPaneles = new Array(:cont);';



$xpaneles = '';
$xobjetos = '';

//----------------------------------------------------------- CREAFORMULARIO ---
function creaFormulario($xorigen, $xpagina,$adicional=false)
{
	global $x_config;
	//-- marco 2021

	$ini = new ini();
	$ini->origen = RUTA_SISTEMA . $xorigen;
	if (file_exists($ini->origen.".ini")) {
		$ini->cargar_ini();
		$cont = $ini->generaObj();
		$cont = utf8_string_array_encode($cont);
		$cont = json_encode($cont);
	}
	$txt = "inis[\"$xorigen\"]= $cont;\n";

	$txt .='
	oFormulario'.$xpagina.' = new formulario2(\''.$xorigen.'\');
	oFormulario'.$xpagina.'.nombre   = \'oFormulario'.$xpagina.'\';
	oFormulario'.$xpagina.'.funcion  = TeclaForm;
	oFormulario'.$xpagina.'.padre    = \'pag'.$xpagina.'\';
	
	leyendas['.($xpagina-1).'] = null;
	';

	if ($adicional) $txt .= '
	oFormulario'.$xpagina.'.tipo = \'adicional\';
	';

	$txt .= '
	//oFormulario'.$xpagina.'.alto=600;
	//oFormulario'.$xpagina.'.ancho=600;
	oFormulario'.$xpagina.'.xconfig=\''.$x_config.'\';
	oFormulario'.$xpagina.'.inicializa();
	';


	return $txt;
}
//------------------------------------------------------------------------------


//-------------------------------------------------------------- CREAMAESTR0 ---
function creaMaestro($xorigen, $xpagina)
{
	global $x_config, $form_filtro, $form_xfiltro;
	
	$ini = new ini();
	$txt = "";
	$ini->origen =  RUTA_SISTEMA . $xorigen;
	if (file_exists($ini->origen.".ini")) {
		$ini->cargar_ini();
		$cont = $ini->generaObj();
		$cont = utf8_string_array_encode($cont);
		$cont2 = json_encode($cont);
		$txt = "inis[\"$xorigen\"]= $cont2;\n";
	}

	$txt = "inis[\"$xorigen\"]= $cont2;\n";
	if ($cont["FORMULARIO"]["ORIGEN"]) {
		$ifile = $cont["FORMULARIO"]["ORIGEN"];
		$ini->origen = RUTA_SISTEMA . $ifile;
		if (file_exists($ini->origen.".ini")) {
			$ini->cargar_ini();
			$cont = $ini->generaObj();
			$cont = utf8_string_array_encode($cont);
			$cont2 = json_encode($cont);
			$txt .= "inis[\"$ifile\"]= $cont2;\n";
		}
	}
  
	$txt .= '
	oGrid'.$xpagina.' = new lista(\''.$xorigen.'\', \'oGrid'.$xpagina.'\');
	oGrid'.$xpagina.'.titulo = \'\';
	oGrid'.$xpagina.'.padre = \'pag'.$xpagina.'\';
	oGrid'.$xpagina.'.url = server_path + \'herramientas/genera_xml/genera_xml.php\';
	oGrid'.$xpagina.'.usaLeyenda = false;
	oGrid'.$xpagina.'.funcion = TeclaGrid2;
	oGrid'.$xpagina.'.xconfig=\''.$x_config.'\';
	oGrid'.$xpagina.'.inicializa();
	oFormGrid'.$xpagina.' = new formulario2(oGrid'.$xpagina.'.origenForm);
	oFormGrid'.$xpagina.'.nombre = \'oFormGrid'.$xpagina.'\';
	oFormGrid'.$xpagina.'.funcion  = TeclaForm2;
	oFormGrid'.$xpagina.'.padre    = \'oSubmodalform_cuadro\';
	oFormGrid'.$xpagina.'.xconfig  =\''.$x_config.'\';
	oFormGrid'.$xpagina.'.inicializa();
	';
	return $txt;
}
//------------------------------------------------------------------------------

//-------------------------------------------------------------- INTERCEPCION ---
function creaInterseccion($xorigen, $xpagina)
{
	global $x_config;
	$txt = '
	oGridInter'.$xpagina.' = new lista(\''.$xorigen.'\', \'oGridInter'.$xpagina.'\');
	oGridInter'.$xpagina.'.titulo = \'\';
	oGridInter'.$xpagina.'.padre = \'pag'.$xpagina.'\';
	oGridInter'.$xpagina.'.url = server_path + \'herramientas/genera_xml/genera_xml.php\';
	oGridInter'.$xpagina.'.usaLeyenda = false;
	oGridInter'.$xpagina.'.funcion = TeclaGridInter;
	oGridInter'.$xpagina.'.xconfig=\''.$x_config.'\';
	oGridInter'.$xpagina.'.inicializa();

	oFormInter'.$xpagina.' = new formulario2(oGridInter'.$xpagina.'.origenForm, \'oGridInter'.($xpagina+1).'\');
	oFormInter'.$xpagina.'.nombre = \'oFormInter'.$xpagina.'\';
	oFormInter'.$xpagina.'.funcion  = TeclaFormInter;
	oFormInter'.$xpagina.'.padre    = \'oSubmodalform_cuadro\';
	oFormInter'.$xpagina.'.xconfig  =\''.$x_config.'\';
	oFormInter'.$xpagina.'.inicializa();
	';
	return $txt;

}
//------------------------------------------------------------------------------


//-------------------------------------------------------------- CREAMAESTR02 ---
function creaMaestro2($xorigen, $xpagina)
{
	global $x_config;
	$ini = new ini();
	$txt = "";
	$ini->origen =  RUTA_SISTEMA . $xorigen;
	if (file_exists($ini->origen.".ini")) {
		$ini->cargar_ini();
		$cont = $ini->generaObj();
		$cont = utf8_string_array_encode($cont);
		$cont2 = json_encode($cont);
		$txt = "inis[\"$xorigen\"]= $cont2;\n";
	}

	$txt = "inis[\"$xorigen\"]= $cont2;\n";
	if ($cont["FORMULARIO"]["ORIGEN"]) {
		$ifile = $cont["FORMULARIO"]["ORIGEN"];
		$ini->origen = RUTA_SISTEMA . $ifile;
		if (file_exists($ini->origen.".ini")) {
			$ini->cargar_ini();
			$cont = $ini->generaObj();
			$cont = utf8_string_array_encode($cont);
			$cont2 = json_encode($cont);
			$txt .= "inis[\"$ifile\"]= $cont2;\n";
		}
	}
  
	$txt .= '
	xoGrid'.$xpagina.' = new lista(\''.$xorigen.'\', \'xoGrid'.$xpagina.'\');
	xoGrid'.$xpagina.'.titulo = \'\';
	xoGrid'.$xpagina.'.padre = \'pag'.$xpagina.'\';
	xoGrid'.$xpagina.'.url = server_path + \'herramientas/genera_xml/genera_xml.php\';
	xoGrid'.$xpagina.'.usaLeyenda = false;
	xoGrid'.$xpagina.'.funcion = TeclaGrid3;
	xoGrid'.$xpagina.'.origenForm;
	xoGrid'.$xpagina.'.xconfig=\''.$x_config.'\';
	xoGrid'.$xpagina.'.inicializa();

	xoGrid'.($xpagina+1).' = new lista(xoGrid'.$xpagina.'.origenForm, \'xoGrid'.($xpagina+1).'\');
	xoGrid'.($xpagina+1).'.funcion  = TeclaForm3;
	xoGrid'.($xpagina+1).'.botonCerrar = true;
	xoGrid'.($xpagina+1).'.buscador    = true;
	xoGrid'.($xpagina+1).'.modal = true;
	xoGrid'.($xpagina+1).'.enter = 1;
	xoGrid'.($xpagina+1).'.onClose = close;
	xoGrid'.($xpagina+1).'.xconfig=\''.$x_config.'\';
	xoGrid'.($xpagina+1).'.url = server_path + \'herramientas/genera_xml/genera_xml.php\';
	xoGrid'.($xpagina+1).'.inicializa();
	';
	return $txt;
}
//------------------------------------------------------------------------------

//-------------------------------------------------------------- CREAIFRAME ---
function creaIframe($xorigen, $xparams, $xfiltro, $xpagina)
{
  $txt = '
var pg=document.getElementById(\'pag'.$xpagina.'\');
xoIframe'.$xpagina.' = document.createElement(\'IFRAME\');
pg.appendChild(xoIframe'.$xpagina.');
xoIframe'.$xpagina.'.width=\'100%\';
xoIframe'.$xpagina.'.height=\'100%\';
xoIframe'.$xpagina.'_url=\''.$xorigen.'\';
xoIframe'.$xpagina.'_params=\''.$xparams.'\';
xoIframe'.$xpagina.'_filtro=\''.$xfiltro.'\';

';
  return $txt;

}

for ($i=0;$i<$cant;$i++)
{
   $xpaneles .= 'oTabpane.addTab(\''.$my_ini2->seccion('PANEL'.($i+1),'ROTULO').'\',\'pag'.($i+1).'\');'."\n";

   switch ($paneles[$i]['TIPO'])
   {
     case 'FORMULARIO':
       if (!isset($paneles[$i]['ORIGEN']))
       {

        $xobjetos .= creaFormulario($origen2,$i+1);
       }
       else
       {
               $xobjetos .= creaFormulario($paneles[$i]['ORIGEN'],$i+1);
         //- pasarle el origen segun el establecido en la seccion [PANEL?] de f_xxxx
       }
       break;

     case 'MAESTRO':
     case 'MAESTRO3': //-- CREADO POR MARCO OCTUBRE 2008
       $origen3 = $my_ini2->seccion('PANEL'.($i+1),'ORIGEN');
       $xobjetos .= creaMaestro($origen3, $i+1);
       break;

     case 'MAESTRO2':
       $origen4 = $my_ini2->seccion('PANEL'.($i+1),'ORIGEN');
       $xobjetos .= creaMaestro2($origen4, $i+1);
       break;

     case 'INTERSECCION':
       $origen5 = $my_ini2->seccion('PANEL'.($i+1),'ORIGEN');
       $xobjetos .= creaInterseccion($origen5, $i+1);
       break;

     case 'ADICIONAL':
       $origArr = explode(',',$my_ini2->seccion('TABLA','TABLA'));
       $origen3 = $origArr[0];
       $xobjetos .= creaFormulario($origen3,$i+1,true);
       break;

     case 'URL':
       $origen3 = $my_ini2->seccion('PANEL'.($i+1),'ORIGEN');
       $origen3 = str_replace('{SISTEMA}', strtolower($configuracion_sistema), $origen3);
       $params  = $my_ini2->seccion('PANEL'.($i+1),'PARAMETROS');
       $tfiltro  = $my_ini2->seccion('PANEL'.($i+1),'FILTRO');
       $xobjetos .= creaIframe($origen3, $params, $tfiltro, $i+1);
       break;
   }
   $pans .= isset($pans) ? ',\''.$paneles[$i]['TIPO'].'\'' : '\''.$paneles[$i]['TIPO'].'\'';
}

$arrPaneles = str_replace(':cont', $pans, $arrPaneles);

?>

<script type="text/javascript">

var inis = [];

<?=$arrPaneles;?>

var leyendas    = new Array;
var currIndex   = 0;
var currForm    = null;
var id          = '';
var id_usuario  = '<?=$id_usuario;?>';
var isS24Maestro	= true;

<?
	$ini = new ini();
	$ini->origen = RUTA_SISTEMA . $origen;
	if ( file_exists( $ini->origen . ".ini")) {
		$ini->cargar_ini();
		$cont = $ini->generaObj();
		$cont = utf8_string_array_encode($cont);
		$cont = json_encode($cont);
	}
	$txt = "inis[\"$origen\"]= $cont;\n";
	echo $txt;
?>

oGrid             = new lista('<?=$origen;?>');
oGrid.nombre      = 'oGrid';
oGrid.x           = 10;
oGrid.y           = 10;
//oGrid.modal     = true;
oGrid.url         = server_path + "herramientas/genera_xml/genera_xml.php";
oGrid.funcion     = TeclaGrid;
<?=$filtro;?>
oGrid.buscador    = true;
oGrid.botonCerrar = true;
oGrid.xconfig     = '<?=$x_config;?>';
oGrid.asyncLoad = false;
oGrid.onClose     = menuFocus;
// oGrid.debug                  = true;

oGrid.inicializa();
oGrid.asyncLoad = false;
centrarObj(oGrid.contenedor);
//oGrid.buscar();
oGrid.mostrar();
oGrid.setFocus();


oSubmodal             = new submodal();
oSubmodal.nombre      = 'oSubmodal';
oSubmodal.ancho       = <?=$ancho;?>+30;
oSubmodal.alto        = <?=$alto;?>;
oSubmodal.x           = 1;
oSubmodal.y           = 1;
oSubmodal.titulo      = '<?=$titulo;?>';
oSubmodal.botonCerrar = true;
oSubmodal.leyenda     = creaLeyenda();
oSubmodal.ayuda       = <?=$form_ayuda;?>;
oSubmodal.usaFrame    = false;
oSubmodal.interpretar = false;
oSubmodal.modal       = true;
oSubmodal.onClose     = Salir;
oSubmodal.inicializa();
centrarObj(oSubmodal.contenedor);

oTabpane = new tabPane();
oTabpane.onSelect = f_enter;

<?=$xpaneles;?>
oTabpane.parent = 'oSubmodal_cuadro';
oTabpane.width = oSubmodal.ancho - 26;
oTabpane.height = oSubmodal.alto - 105;
oTabpane.init();
oTabpane.show();

oSubmodalform             = new submodal();
oSubmodalform.nombre      = 'oSubmodalform';
oSubmodalform.ancho       = 500;
oSubmodalform.alto        = 300;
oSubmodalform.titulo      = '';
oSubmodalform.x           = 1;
oSubmodalform.y           = 1;
oSubmodalform.titulo      = 'Formulario';
oSubmodalform.botonCerrar = true;
oSubmodalform.leyenda     = creaLeyenda2();
oSubmodalform.usaFrame    = false;
oSubmodalform.interpretar = false;
oSubmodalform.modal       = true;
oSubmodalform.onClose     = ocultarForm;
oSubmodalform.inicializa(false);

<?=$xobjetos;?>

function f_enter(index, oldindex)
{
  var f0=eval('oFormulario1');

  switch(arrPaneles[oldindex])
  {
    case 'FORMULARIO':
      var valida = f0.validar();
      if(!valida) return;
      Guardar();
      break;
    case 'ADICIONAL':
      Guardar();
      break;
    case 'MAESTRO':
    case 'MAESTRO3': //-- CREADO POR MARCO OCTUBRE 2008
      var f=eval('oGrid'+(oldindex+1));
      f.ocultar();
      break;
    case 'MAESTRO2':
    case 'URL':
    case 'INTERSECCION':
      break;
  }

  currIndex = index;

  switch(arrPaneles[index])
  {
    case 'FORMULARIO':
      oSubmodal.divleyenda.innerHTML = oSubmodal.leyenda;
      oTabpane.setIndex(index);
      oFormulario1.mostrar();
      oFormulario1.setFocus();
      break;

    case 'ADICIONAL':
      var f=eval('oFormulario'+(index+1));
      oTabpane.setIndex(index);
      f.tabla = f0.tabla;
      f.buscar(id);
      f.mostrar();
      oSubmodal.divleyenda.innerHTML = oSubmodal.leyenda;
      f.setFocus();
      f.setValue('tabla','A_'+f0.tabla);
      f.setValue('busca','IDX');
      break;

    case 'MAESTRO':

      var f=eval('oGrid'+(index+1));
      f.filtro = 'IDX;TABLA';
      f.xfiltro = id+';'+f0.tabla;
      f.xoperadores='=';
      var x = f.buscar('*');
      var registro = XML2Array(x);

      f.ancho = f.ancho;
      f.alto  = f.alto;
      f.reset();
      f.mostrar();
      if(id)
      {
              f.buscar('*');
      }
      else
      {
              f.limpiar();
      }

      if((!registro[0]) || (!registro[0][f.campoIndice]))
      {
         TeclaGrid2('', 45, f.elementoXml());
      }
      else
      {
              f.setFocus();
      }
      oSubmodal.divleyenda.innerHTML = oSubmodal.leyenda;

      if (f.leyenda)
      {
        var ih = '<center><table border="0" cellpading="0" cellspacing="0"><tr><td>'+f.leyenda+'</td></tr></table></center>';
        oSubmodal.divleyenda.innerHTML = ih;
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA1'), "click",   function() { TeclaGrid2(f.nombre, 45, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA2'), "click",   function() { TeclaGrid2(f.nombre, 13, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA3'), "click",   function() { TeclaGrid2(f.nombre, 46, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA4'), "click",   function() { TeclaGrid2(f.nombre, 27, f.elementoXml()) } )
      }
      break;

    case 'MAESTRO2':

      var f=eval('xoGrid'+(index+1));
      f.filtro  = oGrid.campoIndice;
      f.xfiltro = id;
      if(id)
      {
              f.buscar('*');
      }
      else
      {
              f.limpiar();
      }

      if (f.leyenda)
      {
        var ih = '<center><table border="0" cellpading="0" cellspacing="0"><tr><td>'+f.leyenda+'</td></tr></table></center>';
        oSubmodal.divleyenda.innerHTML = ih;
        addEvent(eval('xoGrid'+(index+1)+'_LEYENDA1'), "click",   function() { TeclaGrid3('', 45, f.elementoXml()) } )
        addEvent(eval('xoGrid'+(index+1)+'_LEYENDA2'), "click",   function() { TeclaGrid3('', 46, f.elementoXml()) } )
        addEvent(eval('xoGrid'+(index+1)+'_LEYENDA3'), "click",   function() { TeclaGrid3('', 27, f.elementoXml()) } )
      }
      f.mostrar();
      f.setFocus();

      break;
    case 'INTERSECCION':
      var f=eval('oGridInter'+(index+1));

      f.filtro  = f.indiceForm;
      f.xfiltro = id;
      if(id)
      {
              f.buscar('*');
      }
      else
      {
              f.limpiar();
      }

      if (f.leyenda)
      {
        var ih = '<center><table border="0" cellpading="0" cellspacing="0"><tr><td>'+f.leyenda+'</td></tr></table></center>';
        oSubmodal.divleyenda.innerHTML = ih;
        addEvent(eval('oGridInter'+(index+1)+'_LEYENDA1'), "click",   function() { TeclaGridInter('', _insert, f.elementoXml()) } )
        addEvent(eval('oGridInter'+(index+1)+'_LEYENDA2'), "click",   function() { TeclaGridInter(f, _enter , f.elementoXml()) } )
        addEvent(eval('oGridInter'+(index+1)+'_LEYENDA3'), "click",   function() { TeclaGridInter('', _supr  , f.elementoXml()) } )
        addEvent(eval('oGridInter'+(index+1)+'_LEYENDA4'), "click",   function() { TeclaGridInter('', _esc   , f.elementoXml()) } )
      }
      f.mostrar();
      f.setFocus();

      break;

    case 'MAESTRO3': //-- CREADO POR MARCO OCTUBRE 2008

      var f=eval('oGrid'+(index+1));
      f.filtro = 'IDX;TABLA';
      f.xfiltro = id+';'+f0.tabla;
      var x = f.buscar('*');
      var registro = XML2Array(x);

      f.ancho = f.ancho;
      f.alto  = f.alto;
      f.reset();
      f.mostrar();
      if(id)
      {
              f.buscar('*');
      }
      else
      {
              f.limpiar();
      }

      if((!registro[0]) || (!registro[0][f.campoIndice]))
      {
         TeclaGrid2('', 45, f.elementoXml());
      }
      else
      {
              f.setFocus();
      }
      oSubmodal.divleyenda.innerHTML = oSubmodal.leyenda;

      if (f.leyenda)
      {
        var ih = '<center><table border="0" cellpading="0" cellspacing="0"><tr><td>'+f.leyenda+'</td></tr></table></center>';
        oSubmodal.divleyenda.innerHTML = ih;
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA1'), "click",   function() { TeclaGrid2(f, 45, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA2'), "click",   function() { TeclaGrid2(f, 13, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA3'), "click",   function() { TeclaGrid2(f, 46, f.elementoXml()) } )
        addEvent(eval('oGrid'+(index+1)+'_LEYENDA4'), "click",   function() { TeclaGrid2(f, 27, f.elementoXml()) } )
      }
      break;

    case 'URL':
      var ih = '<center><table class="tabla_leyenda" border="0" cellpading="0" cellspacing="0"><tr><td onselectstart="return false;" style="width:100px"; class="td_leyenda_inactiva" onmouseup="this.className=\'td_leyenda_activa\'" onmouseover="this.className=\'td_leyenda_activa\'" onmousedown="this.className=\'td_leyenda_click\'" onmouseout="this.className=\'td_leyenda_inactiva\'" onclick=oSubmodal.ocultar();Salir();><B>[ESC]</B><br>Salir</td></tr></table></center>';
        oSubmodal.divleyenda.innerHTML = ih;
      var f=eval('xoIframe'+(index+1));
      var f2=eval('xoIframe'+(index+1)+'_url');
      var p2=eval('xoIframe'+(index+1)+'_params');
      var ft=eval('xoIframe'+(index+1)+'_filtro');
      p2=p2.replace('{'+ft+'}',id);
      f.src=f2+'?'+p2;
      break;
  }
  return true;

}



function creaLeyenda()
{
  var l = '';

  l += '<div class="tabla_leyenda">';
  l += etiqLeyenda('F12', '<?=$t_guardar;?>', '90', 'Guardar(\'cerrar\');');
  l += etiqLeyenda('ESC', '<?=$t_salir;?>', '90', 'Salir();');
  l += '</div>';
  return l;
}

function creaLeyenda2()
{
  var l = '';

  l += '<div class="tabla_leyenda">';
  l += etiqLeyenda('F12', '<?=$t_guardar;?>', '90', 'Guardar2();');
  l += etiqLeyenda('ESC', '<?=$t_salir;?>', '90', 'ocultarForm();');
  l += '</div>';
  return l;
}

function TeclaGrid(objeto, tecla, xml)
{
    var evt = window.event || e;

  switch (tecla)
  {
    case 13:  // Enter Edicion de Registro
      registro = XML2Array(xml);

      if(registro[0]['<?=$indice;?>'])
      {
         id = registro[0]['<?=$indice;?>'];

         waitExec('Cargando el registro "' + id + '"', 'CargarForm(\''+id+'\')',10, 270, 200);

         return false;
      }
      break;

    case 45: //  Insert Insercion de Registro
      id = '';
      oGrid.ocultar();
      oFormulario1.limpiar();
      oSubmodal.setTitle(oFormulario1.titulo);
      oSubmodal.mostrar();
      oTabpane.setIndex(0);
      oFormulario1.mostrar();
      oFormulario1.setFocus();
      break;

    case 46: //  Delete Eliminar Registro
    if (oGrid.getValue('<?=$indice;?>'))
     {
       var registro = XML2Array(xml);
       var origen ='<?=$origen;?>';

       var borrar = confirm('<?=$t_eliminar_registro;?>');
       if(borrar)
       {
                var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
                var tabla  = 'tabla=' + oFormulario1.getValue('tabla');
                var campos = 'c_ESTATUS_CSS=INA';
                    campos+= '&busca='+ oFormulario1.getValue('busca');
                var xbusca = 'xbusca='+ registro[0][oGrid.campoIndice];
                var params = tabla + '&' + campos + '&' + xbusca;

            var x = enviar(url, params, 'post');
            oGrid.actualizar(registro[0][oGrid.campoIndice]);
            oGrid.setFocus();
                 //oGrid.url=url;
                 //oGrid.params='';
       }
       oGrid.setFocus();
      }
      break;

    case 27:  // Escape  Cierra el Grid Maestro
      oGrid.ocultar();
      parent.proceso.src = server_path + 'main/inicio.php';
      break;

    case 112:
               cancelaTecla(evt);
          break;
     case 114:
               cancelaTecla(evt);
          break;
     case 115:
               cancelaTecla(evt);
          break;
     case 116:
               cancelaTecla(evt);
          break;
     case 117:
               cancelaTecla(evt);
          break;
     case 121:
               cancelaTecla(evt);
          break;
     case 122:
               cancelaTecla(evt);
          break;
     case 123:
               cancelaTecla(evt);
          break;
  }
}

function TeclaGrid2(objeto, tecla, xml)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case 13:
      registro = XML2Array(xml);
      var ob = eval(objeto);
      if(registro[0][ob.campoIndice])
      {
        var id = registro[0][ob.campoIndice];

        waitExec('Cargando el registro "'+id+'"', 'CargarForm2(\''+id+'\')',10, 270,200);
      }

      break;

    case 45:
     CargarForm2();
    break;

    case 46:
    var ob = eval(objeto);
    var xpanelid = objeto.replace('oGrid','');
    var objF     = eval('oFormGrid'+xpanelid);
   var registro = XML2Array(xml);
   var borrar = confirm('<?=$t_eliminar_registro;?>');
   if(borrar)
   {
        var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
        var tabla  = 'tabla=' + objF.getValue('tabla');
        var campos = '&busca='+ objF.getValue('busca');
            campos+= '&onload=elimina_maestro';
        var xbusca = 'xbusca='+ registro[0][ob.campoIndice];
        var params = tabla + '&' + campos + '&' + xbusca;

        var x = enviar(url, params, 'post');
        ob.actualizar();
        ob.setFocus();
        //oaqui es la cosa;
   }

    break;
    case 27:
            Salir();
    break;

    case 34:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.next();
      break;

    case 33:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.prior();
      break;

    case 112:
               cancelaTecla(evt);
          break;
    case 114:
               cancelaTecla(evt);
          break;
    case 115:
               cancelaTecla(evt);
          break;
    case 116:
               cancelaTecla(evt);
          break;
    case 117:
               cancelaTecla(evt);
          break;
    case 121:
               cancelaTecla(evt);
          break;
    case 122:
               cancelaTecla(evt);
          break;
    case 123:
               cancelaTecla(evt);
          break;
  }
}

function TeclaGrid3(objeto, tecla, xml)
{
     var evt = window.event || e;
     var f=eval('xoGrid'+(currIndex+2));
     var ff=eval('xoGrid'+(currIndex+1));

  switch (tecla)
  {
    case 45: //  Insert Insercion de Registro
     // f.buscar('');
      centrarObj(f.contenedor);
      f.mostrar();
      f.setFocus();

      addEvent(eval('xoGrid'+(currIndex+2)+'_LEYENDA5'), "click",   function() { TeclaForm3('', 13, f.elementoXml()) } )
      addEvent(eval('xoGrid'+(currIndex+2)+'_LEYENDA6'), "click",   function() { TeclaForm3('', 27, f.elementoXml()) } )
      break;

    case 27:
     oSubmodal.ocultar();
    break;

    case 46:
     var registro = XML2Array(xml);
     var origen2 ='<?=$origen4;?>';

     var borrar = confirm('<?=$t_eliminar_registro;?>');
       if(borrar)
       {
        var url = ff.url;
             var xml = ff.leeUrl(ff.url , "origen=" + origen2 +"&procedimiento=BORRAR&" + ff.campoIndice + "=" + registro[0][ff.campoIndice]);
        ff.url=url;
        ff.params='';
       }

     f.ocultar();
     ff.buscar('*');
     ff.setFocus();
    break;

    case 34:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.next();
      break;

    case 33:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.prior();
      break;

    case 112:
               cancelaTecla(evt);
          break;
    case 114:
               cancelaTecla(evt);
          break;
    case 115:
               cancelaTecla(evt);
          break;
    case 116:
               cancelaTecla(evt);
          break;
    case 117:
               cancelaTecla(evt);
          break;
    case 121:
               cancelaTecla(evt);
          break;
    case 122:
               cancelaTecla(evt);
          break;
    case 123:
               cancelaTecla(evt);
          break;
  }
}
function TeclaGridInter(objeto, tecla, xml)
{
  var evt = window.event || e;

  switch (tecla)
  {
    case 13:
      registro = XML2Array(xml);
      var ob = eval(objeto);

      if(registro[0][ob.campoIndice])
      {
        var id = registro[0][ob.campoIndice];
        waitExec('Cargando el registro "'+id+'"', 'CargarFormInter(\''+id+'\')',10, 270,200);
      }

      break;

    case 45:
     CargarFormInter();
    break;

    case 46:

    break;
    case 27:
            oSubmodal.ocultar();
            Salir();
    break;

    case 34:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.next();
      break;

    case 33:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.prior();
      break;

    case 112:
               cancelaTecla(evt);
          break;
    case 114:
               cancelaTecla(evt);
          break;
    case 115:
               cancelaTecla(evt);
          break;
    case 116:
               cancelaTecla(evt);
          break;
    case 117:
               cancelaTecla(evt);
          break;
    case 121:
               cancelaTecla(evt);
          break;
    case 122:
               cancelaTecla(evt);
          break;
    case 123:
               cancelaTecla(evt);
          break;
  }
}
function CargarForm(id,titulo)
{
  oGrid.ocultar();
  //prompt('',oSubmodal.contenedor.innerHTML);
  oSubmodal.setTitle(oFormulario1.titulo);
  oTabpane.setIndex(0);
  oSubmodal.mostrar();
  oSubmodal.setAyuda(oFormulario1.ayuda);
  oFormulario1.mostrar();
  oFormulario1.buscar(id);
  oFormulario1.setFocus();
}

function CargarForm2(id,titulo)
{
  var f = eval('oFormGrid'+(currIndex+1));
  
  oSubmodalform.setTitle(f.titulo);
  oSubmodalform.setWidth(f.ancho);
  oSubmodalform.setHeight(f.alto);
 // oSubmodalform.cuadro.style.overflow = 'auto';
  oSubmodalform.mostrar();
  centrarObj(oSubmodalform.contenedor);

  if(id)
  {
          f.buscar(id);
  }
  else
  {
    f.limpiar();
  }
  f.mostrar();
  f.setFocus();
}
function CargarFormInter(id,titulo)
{
  var f = eval('oFormInter'+(currIndex+1));
  
  oSubmodalform.setTitle(f.titulo);
  oSubmodalform.setWidth(f.ancho);
  oSubmodalform.setHeight(f.alto);
 // oSubmodalform.cuadro.style.overflow = 'auto';
  oSubmodalform.mostrar();
  centrarObj(oSubmodalform.contenedor);

  if(id)
  {
          f.buscar(id);
  }
  else
  {
    f.limpiar();
  }
  f.mostrar();
  f.setFocus();
}

function TeclaForm(objeto, tecla, evt, e)
{
   var evt = window.event || e;

  switch (tecla)
  {
    case 123: // F12 Guardar
	cancelaTecla(evt);
	Guardar('cerrar');
      break;

    case 13:
      break;

    case 27:
	  cancelaTecla(evt);
      oSubmodal.ocultar();
      oGrid.mostrar();
      oGrid.setFocus();
      //alert('alerta');
     // Salir();
      break;

    case 34:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.next();
      break;

    case 33:
      cancelaTecla(evt);
      if (evt.ctrlKey) oTabpane.prior();
      break;
    case 112:
               cancelaTecla(evt);
          break;
    case 114:
               cancelaTecla(evt);
          break;
    case 115:
               cancelaTecla(evt);
          break;
    case 116:
               cancelaTecla(evt);
          break;
    case 117:
               cancelaTecla(evt);
          break;
    case 121:
               cancelaTecla(evt);
          break;
    case 122:
               cancelaTecla(evt);
          break;
  }
}

function TeclaForm2(objeto, tecla, evt, e)
{
	var evt = window.event || e;
	switch (tecla)
	{
		case 27:
		  cancelaTecla(evt);
		  Salir2();
		  break;

		case 112:
			cancelaTecla(evt);
			break;
		case 114:
			cancelaTecla(evt);
			break;
		case 115:
			cancelaTecla(evt);
			break;
		case 116:
			cancelaTecla(evt);
			break;
		case 117:
			cancelaTecla(evt);
			break;
		case 121:
			cancelaTecla(evt);
			break;
		case 122:
			cancelaTecla(evt);
			break;

		case 123:
			cancelaTecla(evt);
			Guardar2();
			break;
	}
}

function TeclaForm3(objeto, tecla, xml, e)
{
  var evt = window.event || e;
  var f=eval('xoGrid'+(currIndex+2));
  var ff=eval('xoGrid'+(currIndex+1));

  switch (tecla)
  {
    case 13:
		cancelaTecla(evt);
        var registro = XML2Array(xml);
              var url    = server_path + 'herramientas/utiles/actualizar_registro.php';
              var tabla  = 'tabla=' + ff.tablaGuardar;
              var campos = 'c_' + oGrid.campoIndice + '_CSS=' + id;
            campos+= '&c_'+ f.campoIndice +'_CSS=' + registro[0][f.campoIndice] ;
              var xbusca = 'xbusca=-1';

              var params = tabla + '&' + campos + '&' + xbusca;
              var x = enviar(url, params, 'post');

        f.ocultar();
        ff.buscar('*');
        ff.setFocus();
    break;

    case 27:
		cancelaTecla(evt);
        f.ocultar();
        close();
    break;

    case 112:
		cancelaTecla(evt);
		break;
    case 114:
		cancelaTecla(evt);
		break;
    case 115:
		cancelaTecla(evt);
		break;
    case 116:
		cancelaTecla(evt);
		break;
    case 117:
		cancelaTecla(evt);
		break;
    case 121:
		cancelaTecla(evt);
		break;
    case 122:
		cancelaTecla(evt);
		break;
    case 123:
		cancelaTecla(evt);
		break;
  }
}
function TeclaFormInter(objeto, tecla, evt, e)
{
        var evt = window.event || e;
  switch (tecla)
  {
    case 27:
      cancelaTecla(evt);
      Salir2();
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
    case 123:
		cancelaTecla(evt);
		Guardar2();
		break;
  }
}

function Guardar(cerrar)
{
    var ndx = oTabpane.getIndex();
    switch (arrPaneles[ndx])
    {
      case 'FORMULARIO':
        var f=eval('oFormulario'+(ndx+1));
        if (f)
         {
                 var registro = f.submit();
         }
         if(registro[0])
         {
            id = registro[0]['<?=$indice;?>'];
            setCookie('<?=$indice;?>',id);
         }

          if ((registro) && (cerrar))
          {
            oSubmodal.ocultar();
            oGrid.mostrar();
            oGrid.actualizar(id);
            oGrid.setFocus();
//            oGrid.buscar(id);

          }
        break;
      case 'ADICIONAL':
        var f=eval('oFormulario'+(ndx+1));
         var valida = f.validar();
         if(!valida) return;
        var registro = f.submit();
       // prompt('',oSubmodal.contenedor.innerHTML);
        if (f)
        {
          var registro = f.submit();

          if ((registro) && (cerrar))
          {
            oSubmodal.ocultar();
          }
        }
    }
}

function Guardar2()
{
   var ndx = oTabpane.getIndex();

   switch (arrPaneles[ndx])
   {
      case 'MAESTRO':
      case 'MAESTRO3':
            var ndx = oTabpane.getIndex();
            var f=eval('oFormGrid'+(ndx+1));
            f.setValue('TABLA',oFormulario1.tabla);
            f.setValue('IDX',id);
            if(f.tabla=='D_TELEFONOS')
            {
                var nombres   = oFormulario1.getValue('NOMBRES');
                var apellidos = oFormulario1.getValue('APELLIDOS');

                if(apellidos)
                {
                    f.setValue('NOMBRES', apellidos + ' ' + nombres);
                }
                else
                {
                    f.setValue('NOMBRES', nombres);
                }
            }

              if (f)
              {
                if (f.submit())
                {
                  oSubmodalform.ocultar();
                  f.ocultar();
                  var f=eval('oGrid'+(ndx+1));
                  f.filtro = 'IDX;TABLA';
                  f.xfiltro = id+';'+oFormulario1.tabla;
                  f.xoperadores='=';
                  f.buscar('*');
                }
              }
        break;
      case 'INTERSECCION':
        var ndx = oTabpane.getIndex();
        var f=eval('oFormInter'+(ndx+1));
        var g=eval('oGridInter'+(ndx+1));
        f.setValue(g.indiceForm,id);

        if(f)
        {
                    if (f.submit())
                    {
                      oSubmodalform.ocultar();
                      f.ocultar();
                      g.filtro = g.indiceForm;
                      g.xfiltro = id;
                      g.xoperadores='=';
                      g.buscar('*');
                    }
            }
        break;
    }
}

function menuFocus()
{
  if (parent.menu) parent.menu.setFocus();
}

function formCerrar()
{
  oGrid.setFocus();
}

function setMenu()
{
  if (parent.menu) parent.menu.reset();
}


function Salir()
{
//    oGrid.buscar(id,'=');
        oSubmodal.divleyenda.innerHTML = oSubmodal.leyenda;
    setTimeout('oSubmodal.ocultar()',10);
    oGrid.mostrar();
    oGrid.setFocus();
    oTabpane.WebFXTabPane.setSelectedIndex(0);
}

function Salir2()
{
  var f = eval('oFormGrid'+(currIndex+1));
  f.ocultar();
  oSubmodalform.ocultar();
  
}

function close()
{
        var f=eval('xoGrid'+(currIndex+1));
    f.setFocus();
}

function SetFocus()
{
  switch (arrPaneles[currIndex])
  {
    case 'FORMULARIO':
    case 'ADICIONAL':
      var f=eval('oFormulario'+(currIndex+1));
      if (f) f.setFocus();
      break;
    case 'MAESTRO':
      var f=eval('oGrid'+(currIndex+1));
      if (f) f.setFocus();
      break;
    case 'MAESTRO3':
      var f=eval('oGrid'+(currIndex+1));
      if (f) f.setFocus();
      break;
    case 'INTERSECCION':
      var f=eval('oGridInter'+(currIndex+1));
      if (f) f.setFocus();
      break;
  }
}

function ocultarForm()
{
  /*var f = eval('oFormGrid'+(currIndex+1));
  f.ocultar();
  SetFocus();*/

  switch (arrPaneles[currIndex])
  {
    case 'INTERSECCION':
      var f = eval('oFormInter'+(currIndex+1));
      break;
    default:
      var f = eval('oFormGrid'+(currIndex+1));
      break;
  }
  Salir2();
 // f.ocultar();
  //oSubmodalform.ocultar();
  //SetFocus();
}
/*function ocultarFormInter()
{
  var f = eval('oFormInter'+(currIndex+1));
  f.ocultar();
  SetFocus();
} */



function iniciar()
{
    <?=$str_script;?>
    document.onclick = setMenu;

        addEvent(oGrid_LEYENDA1, "click",   function() { TeclaGrid('', 45, oGrid.elementoXml()) } )
        addEvent(oGrid_LEYENDA2, "click",   function() { TeclaGrid('', 13, oGrid.elementoXml()) } )
        addEvent(oGrid_LEYENDA3, "click",   function() { TeclaGrid('', 46, oGrid.elementoXml()) } )
        addEvent(oGrid_LEYENDA4, "click",   function() { TeclaGrid('', 27, oGrid.elementoXml()) } )
    return true;
}

function SalirMaestro()
{
   parent.proceso.src = server_path + 'main/inicio.php';
}

var resp = iniciar();
if(!resp) SalirMaestro();



</script>


</body>
</html>
