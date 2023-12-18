 var SubmodalAct = null;
var timerId = null;
var gTabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");

/*-----------------------------------------------------------------------------
keyHandler: Manejador de teclado para el Grid
-----------------------------------------------------------------------------*/

function SubHandler(e)
{
    var evt = window.event || e;
    var KeyCode        = evt.keyCode || evt.which;
    var Elem           = evt.srcElement || evt.target;
    var isShiftPressed = evt.shiftKey;
    var isCtrlPressed  = evt.ctrlKey;
    var isAltPressed   = evt.altKey;

    var valRet = true;
    switch (KeyCode)
    {
      case 13:
        if(e)
        {
            evt.preventDefault();
            evt.stopPropagation();
        }
        if(window.event)
        {
            evt.returnValue = false;
        }
        break;
      case 27:
        if (Elem.parent)
        {
          var parent = eval(Elem.parent);
          parent.ocultar();
        }
        break;
      default:
        break;
    }

    if (SubmodalAct.funcion) SubmodalAct.funcion(SubmodalAct.nombre, KeyCode);
    return valRet;
};


// Construccion del Multiform

function submodal(origen, nombre, x, y, alto, ancho)
{
  this.nombre     = nombre ? nombre : "form";//--- nombre
  this.url        = "";     //--- url de donde traera el XML
  this.xbusca     = "";     //--- valor que será buscado en busca_xml
  this.leyenda    = "";     //--- leyenda para el form
  this.botones    = "";     //--- botones para el form
  this.unico      = "";

  this.funcion    = "";     //--- funcion que recibe todos los eventos de teclado

//------  propiedades usadas solo internamente -------
  this.contenedor = null;
  this.cuadro     = null;
  this.xtabla     = null;
  this.xbody      = null;
  this.xcampos    = new Array;
  this.rows       = new Array;
  this.x          = (x ? x : 0);
  this.y          = (y ? y : 0);
  this.alto       = alto;
  this.ancho      = ancho;

  this.xml      = '';
  this.infHeight = 0;

  this.titulo      = "";
  this.titHeight   = 0;
  this.botonCerrar = false;
  this.botonAyuda  = null;
  this.onClose     = null;

  this.modal     = false;
  this.mascara   = null;

  this.leyHeight = 0;
  this.arrCombos = null;
  this.interpretar= false;
  this.iframe     = null;
  this.cerrar  = null;
  this.ayuda = 0;
  this.padre = null;
  this.elemPadre = null;
  this.myahref = null;
  this.divtitulo = null;
  this.divleyenda = null;
  this.caller = null;
  this.usaFrame = true;
  
  if (origen) this.origen = origen;
  else this.origen  = false;
}

/*------------------------------------------------------------------------------
Funcion lista.leeOrigen()
  Rellena el grid usando la informacion en un INI de maestro
------------------------------------------------------------------------------*/
submodal.prototype.leeOrigen = function (){
  
  if (this.origen){
    var eurl   = server_path + 'herramientas/utiles/ini2xml.php';
    var eparam = 'origen=' + this.origen;

    var x = enviar(eurl,eparam,'POST');
    try{
      var ini = parseINI(x);
    }
    catch(e){
      _prompt('submodal.js', 'Error -> Funcion: LeeOrigen', eurl + '?' + eparam + '&estilo=1');
      return false;
    }
    finally{
		
	}

    if (!ini)
    {
      alert('No se encontró el origen ("'+this.origen+'")');
      return null;
    }

    if (ini['VENTANA']['TITULO']) this.titulo = ini['VENTANA']['TITULO'];
    if (ini['VENTANA']['ANCHO'])  this.ancho  = ini['VENTANA']['ANCHO'];
    else alert('No se especificó VENTANA->ANCHO en el archivo "'+this.origen +'". Defecto: '+this.ancho);
    if (ini['VENTANA']['ALTO'])   this.alto   = ini['VENTANA']['ALTO'];
    else alert('No se especificó VENTANA->ALTO en el archivo "'+this.origen +'". Defecto: '+this.alto);
    if (ini['VENTANA']['AYUDA'])  this.ayuda  = ini['VENTANA']['AYUDA'];
    if (ini['VENTANA']['X']) this.x = ini['VENTANA']['X'];
    if (ini['VENTANA']['Y']) this.y = ini['VENTANA']['Y'];

    n = 0;

  }
};

submodal.prototype.mostrarLeyenda = function(panel){
	
	var xsel = '#' + this.nombre + '_grid_leyenda td';
	
	$(xsel).each(function (i,item) {
        var sw = 'hide';
		
		if(item.panel == panel) sw ='show'
        
		var x = '$("#' + item.id + '").'+ sw +'();';
        
		if(item.panel!=undefined){
            if(item.id!='') eval(x);
        }
    });
}


submodal.prototype.inicializa = function()
{
	if (this.origen){
		this.leeOrigen();
	}
	
	this.armar();
}

/*------------------------------------------------------------------------------
funcion forma.armar()
        Crea los divs y tablas iniciales para la forma...
------------------------------------------------------------------------------*/
submodal.prototype.armar = function(doc)
{
  oThis  = this;

  if (!this.nombre)
  {
    _prompt('submodal.js','Funcion: armar - Error: No se ha designado un nombre al objeto');
    exit;
  }

  if (!document.body)
  {
    window.setTimeout(this.nombre+'.armar()',50);
    return false;
  }

  if (this.modal)
  {
    this.mascara = document.createElement('DIV');
    this.mascara.style.width  = "100%";
    this.mascara.style.height = "100%";
    this.mascara.style.display = 'none';
    this.mascara.className = 'grid_mascara';
    this.mascara.setAttribute('id',this.nombre + '_popupMask');
    this.mascara.innerHTML = '&nbsp;';
    this.mascara.parent = this.nombre;
    document.body.appendChild(this.mascara);
  }

  this.contenedor = document.createElement("div");
  this.contenedor.setAttribute("id","sub_container_"+this.nombre);
  this.contenedor.padre = this.nombre;
  this.contenedor.onclick = subContClick;

  this.contenedor.className= "grid_contenedor submodal";

  document.body.appendChild(this.contenedor);


  if ((this.x) || (this.y))
  {
    //this.contenedor.style.left     = "50%";//this.x;
    //this.contenedor.style.top      = "50%";//this.y;
    this.contenedor.style.position = "absolute";
  }

  this.contenedor.style.height   = "auto"; //this.alto;
  this.contenedor.style.width    = this.ancho;

  if (this.titulo)
  {
    divTitulo = document.createElement("div");
    divTitulo.className = "grid_title";
    divTitulo.padre = this.nombre;
    this.contenedor.appendChild(divTitulo);

    barraTitulo = document.createElement('div');
    barraTitulo.className = 'grid_barra_titulo';

    esquina1 = document.createElement('div');
    esquina1.className = 'grid_esquina1';

    esquina = document.createElement('div');
    esquina.className = 'grid_esquina';

    esquina2 = document.createElement('div');
    esquina2.className = 'grid_esquina2';

    divTitulo.appendChild(esquina1);
    divTitulo.appendChild(barraTitulo);
    divTitulo.appendChild(esquina2);

    tit = document.createElement('DIV');
    tit.className = 'grid_titulo contenedor_barra_titulo';
    tit.innerHTML = this.titulo;
    this.divtitulo = tit;

    barraTitulo.appendChild(esquina);
    barraTitulo.appendChild(tit);

    controls = document.createElement('DIV');
    controls.className = 'grid_controles';

    if (this.ayuda || this.botonCerrar)
    {
      l_controls = document.createElement('span');
      l_controls.className = 'grid_controls_left';
      r_controls = document.createElement('span');
      r_controls.className = 'grid_controls_right';
    }

    if (this.ayuda)
    {
      bayuda = document.createElement('span');
      bayuda.className = 'grid_ayuda';
      bayuda.id = 'ayuda_'+this.ayuda;
      bayuda.padre = this.nombre;
      bayuda.onclick = subbayuda;
      this.botonAyuda = bayuda;
    }

    if (this.botonCerrar)
    {
      bcerrar = document.createElement('span');
      bcerrar.className = 'grid_cerrar';
      bcerrar.padre = this.nombre;
      bcerrar.onclick = subbcerrar;

      this.cerrar = 1;
    }

    if (r_controls) barraTitulo.appendChild(r_controls);
    barraTitulo.appendChild(controls);
    if (l_controls) barraTitulo.appendChild(l_controls);

    if (this.ayuda) controls.appendChild(bayuda);
    if (this.botonCerrar) controls.appendChild(bcerrar);

    this.titHeight = divTitulo.offsetHeight;
  }


  this.cuadro                = document.createElement("div");
  this.cuadro.id             = this.nombre + '_cuadro';
  this.cuadro.className      = 'grid_cuadro';
  this.cuadro.style.overflow = "hidden";
  this.cuadro.parent         = this.nombre;
  this.cuadro.onkeydown      = SubHandler;

  this.contenedor.appendChild(this.cuadro);

  this.myahref            = document.createElement("A");
  this.myahref.href       = "";
  this.myahref.innerHTML  = "";
  this.myahref.onkeydown  = SubHandler;
  this.myahref.onkeypress = nullfunc;
  this.myahref.parent     = this.nombre;

  this.contenedor.appendChild(this.myahref);


  this.cuadro.innerHTML = "";
  this.cuadro.style.top = (this.y) + "px";
  this.cuadro.style.height = (this.alto) + "px";

  if (this.botones)
  {
		var oDiv = document.createElement('DIV');
		oDiv.className = 'grid_leyenda';
		oDiv.innerHTML = this.botones;
		this.contenedor.appendChild(oDiv);
		// this.leyHeight = oDiv.offsetYSHeight;
  }


  if (this.leyenda){
		
		var oDiv = document.createElement('DIV');
		oDiv.className = 'grid_leyenda';
		this.divleyenda = oDiv;
		this.contenedor.appendChild(oDiv);
		oDiv.innerHTML = this.leyenda;
		this.leyHeight = oDiv.offsetHeight;
		
  }

  var difAlt = this.cuadro.offsetHeight - this.titHeight - this.leyHeight - 2;
  difAlt = difAlt > 0 ? difAlt : 0;
  this.cuadro.style.height = difAlt;

  if (this.rows.length > 0) this.resalta(this.rows[0],this);

  this.contenedor.style.display  = "none";
  this.contenedor.style.zIndex = 0;

  this.creaForma();

}

/*------------------------------------------------------------------------------
funcion borraHijos(obj)
        Elimina todos los tag's hijos de un elemento html...
------------------------------------------------------------------------------*/
function borraHijos(obj)
{
  if (!obj) return;
  for (i=obj.childNodes.length-1;i>=0;i--)
  {
    obj.removeChild(obj.childNodes[i]);
  }
}

/*------------------------------------------------------------------------------
funcion forma.creaForma()
   Realiza la busqueda y genera la lista con los nuevos registros encontrados
------------------------------------------------------------------------------*/
submodal.prototype.creaForma = function(xml)
{
  var oTabla = null;
  var oTBody = null;
  var oRow  = null;
  var oCell = null;
  var linea = "";
  var c     = "";
  var oThis = this;

  this.rows = new Array;

  freeDOM_Obj(this.cuadro);

  try
  {
    if (this.xbody) this.xbody.parentNode.removeChild(this.xbody);
  }
  catch(e)
  {
  }
  finally
  {
    this.xbody = null;
  }

//------- inicio de la lista -----

  this.xml = xml;

  xancho = this.ancho-3;
  xalto  = this.alto-25;

  //if (debug()) _prompt('submodal.js', 'Funcion: creaForma - Objeto: '  + this.nombre + '-->' + this.url, this.url);
  if (this.interpretar)
  {
    xmlDoc.load(this.url);
    objNodo = xmlDoc.documentElement.childNodes[0].firstChild;
    this.cuadro.innerHTML = objNodo.data;

    objNodo = xmlDoc.documentElement.childNodes[1].firstChild;
    document.write(objNodo.data);
  }
  else if (this.usaFrame)
  {
    this.iframe = document.createElement("IFRAME");
    this.iframe.setAttribute('id',this.nombre + '_iframe');
    this.iframe.width="100%";
    this.iframe.height="100%";
    this.iframe.src = this.url;
    this.cuadro.appendChild(this.iframe);
    // if (!this.interpretar) this.actualiza();

  }


}

/*------------------------------------------------------------------------------
Funcion forma.mostrar()
  muestra la forma...
------------------------------------------------------------------------------*/
submodal.prototype.mostrar = function ()
{
  SubmodalAct = this;
  if (!this.contenedor)
  {
    window.setTimeout(this.nombre+'.mostrar();', 50);
    return false;
  }
//  this.deshabilitarTabs();
  this.ocultarCombos2();

  if (this.modal) this.mascara.style.display = "block";
  this.contenedor.style.display = "flex";
};

/*------------------------------------------------------------------------------
Funcion forma.ocultar()
  oculta la forma...
------------------------------------------------------------------------------*/
submodal.prototype.ocultar = function (){
  //if (this.onClose) this.onClose(this);
  
  
  this.contenedor.style.display = "none";
  if (this.modal) this.mascara.style.display = "none";
  this.mostrarCombos();
//  this.restauraTabs();
  //if (this.usaFrame && this.iframe) this.iframe.src = 'about:blank';
};



submodal.prototype.destruir = function ()
{
  freeDOM_Obj(this.contenedor);
};


/*------------------------------------------------------------------------------
Funcion forma.leeUrl(url,params)
  Llena el grid con la data en xml retornada por url + params
------------------------------------------------------------------------------*/
submodal.prototype.leeUrl = function(url, params)
{
  if (!this.iframe)
  {
    alert('Se necesita un iframe para leer el formulario via URL');
    return false;
  }
  this.iframe.src = 'about:blank';
  if (params)
  {
    if (this.url.indexOf('?')) this.url += '&' + params;
    else this.url += '?' + params;
  }
  this.url = url;
  this.iframe.src = this.url;
  this.actualiza();
};

submodal.prototype.setZIndex = function(indice)
{
  if (!indice) this.contenedor.zIndex = ++zIndexGral;
  else this.contenedor.zIndex = indice;
};


submodal.prototype.limpiar = function()
{
};


submodal.prototype.setFocus = function()
{
    SubmodalAct = this;
    zIndexGral += 2;
    this.contenedor.style.zIndex = zIndexGral;
    if (this.modal) this.mascara.style.zIndex = zIndexGral-1;
    this.myahref.focus();
};

submodal.prototype.ocultarCombos = function()
{
    this.arrCombos = new Array;

    sels = document.getElementsByTagName('SELECT');
    for (var i=0;i<sels.length;i++)
    {
      if ((sels[i].form.parentNode.id != 'sub_container_'+this.nombre) && (sels[i].style.display!="none"))
      {
        this.arrCombos[this.arrCombos.length] = sels[i];
        sels[i].style.display="none";
      }
    }

};

submodal.prototype.ocultarCombos2 = function()
{
    this.arrCombos = new Array;

    var all = (document.all || document.getElementsByTagName('*'));

    if (this.frame) var xframeactual = this.frame.name;
    else xframeactual = '';

    for(var i = 0; i < all.length; i++)
    {
      if((all[i].tagName.toUpperCase() == "SELECT") && (all[i].style.display!="none"))
      {
         if(all[i].form!=null)
         {
            if(all[i].form.parentNode.parentNode.id != 'f_' + this.nombre)
                 {
                   this.arrCombos[this.arrCombos.length] = all[i];
                   all[i].style.display="none";
            }
         }
         else
         {
                   this.arrCombos[this.arrCombos.length] = all[i];
                   all[i].style.display="none";
         }

      }
      else if ((all[i].tagName.toUpperCase() == 'IFRAME') && (xframeactual != all[i].name))
      {
        try
        {
          var obj = all[i].contentWindow.document;
          var myall = (obj.all || obj.getElementsByTagName('*'));

          if (xframeactual != all[i].name) //alert(document.all[i].name);
          {
            for (var j=0;j<myall.length;j++)
            {
              if ((myall[j].tagName.toUpperCase() == 'SELECT') &&
                (myall[j].style.display != "none"))
              {
                this.arrCombos[this.arrCombos.length] = myall[j];
                myall[j].style.display = "none";
              }
            }
          }
        }
        catch(e)
        {}
        finally
        {}
      }
    }
};


submodal.prototype.mostrarCombos = function()
{
  if (this.arrCombos && this.arrCombos.length)
  {
    for (var i=0;i<this.arrCombos.length;i++)
    {
      this.arrCombos[i].style.display = 'block';
    }
  }
};

submodal.prototype.deshabilitarTabs = function()
{
        this.gTabIndexes = new Array;
        if (document.all) {
                var i = 0;
                for (var j = 0; j < gTabbableTags.length; j++) {
                        var tagElements = document.getElementsByTagName(gTabbableTags[j]);
                        for (var k = 0 ; k < tagElements.length; k++)
                        {
                                if ((!tagElements[k].parent) || (document.getElementById(tagElements[k].parent) != this))
                                {
                                  this.gTabIndexes[i] = tagElements[k].tabIndex;
                                  tagElements[k].tabIndex="-1";
                                  i++;
                                }
                        }
                }
        }
};

submodal.prototype.restauraTabs = function(){
	if (this.gTabIndexes){
		if (document.all) {
			var i = 0;
			for (var j = 0; j < gTabbableTags.length; j++) {
				var tagElements = document.getElementsByTagName(gTabbableTags[j]);
				for (var k = 0 ; k < tagElements.length; k++) {
					tagElements[k].tabIndex = this.gTabIndexes[i];
					tagElements[k].tabEnabled = true;
					i++;
				}
			}
		}
	}
};

submodal.prototype.setHeight = function (newHeight)
{
  if (!newHeight) return;

  newHeight = parseFloat(newHeight) + 30;
  
  this.alto = newHeight;
  //comentado por marco ago 2021
  //this.contenedor.style.top = parseInt(this.contenedor.style.top) + (parseInt(this.contenedor.style.height) / 2) - (parseInt(newHeight) / 2);
  this.contenedor.style.height = newHeight;
  var difAlt = newHeight - this.titHeight - 2;
  this.cuadro.style.height = difAlt;
};

submodal.prototype.setWidth = function (newWidth)
{
  if (!newWidth) return;

  newWidth = parseInt(newWidth) + 5;
  this.ancho = newWidth;
  //this.contenedor.style.left = parseInt(this.contenedor.style.left) + (parseInt(this.contenedor.style.width) / 2) - (parseInt(newWidth) / 2);
  this.contenedor.style.width = newWidth;
  this.cuadro.style.width = newWidth;
  if (this.iframe) this.iframe.width = newWidth;
};

submodal.prototype.setTitle = function (newTitle)
{
  if (!newTitle) return;
  this.divtitulo.innerHTML = newTitle;
};

submodal.prototype.setLegend = function (newLegend)
{
  if (!newLegend) return;
  this.divleyenda.innerHTML = newLegend;
};

submodal.prototype.actualiza = function()
{
  var obj = window.frames[this.nombre + '_iframe'].document;
  if (obj)
  {
    var oTitle   = obj.getElementById('titulo');
    var oWidth   = obj.getElementById('ancho');
    var oHeight  = obj.getElementById('alto');
    var oLegend  = obj.getElementById('leyenda');
    var oCenter  = obj.getElementById('centrar');
    var oMostrar = obj.getElementById('mostrar');
  }

  if (oTitle == null)
  {
    window.setTimeout(this.nombre + '.actualiza()', 50);
    return false;
  }

  if (oTitle  && oTitle.innerHTML  && (this.titulo!=oTitle.innerHTML))   this.setTitle(oTitle.innerHTML);
  if (oWidth  && oWidth.innerHTML  && (this.ancho!=oWidth.innerHTML))    this.setWidth(oWidth.innerHTML);
  if (oHeight && oHeight.innerHTML && (this.alto!=oHeight.innerHTML))    this.setHeight(oHeight.innerHTML);
  if (oLegend && oLegend.innerHTML && (this.leyenda!=oLegend.innerHTML)) this.setLegend(oLegend.innerHTML);

  if (oCenter && oCenter.innerHTML)
  {
    window.setTimeout('centrarObj('+this.nombre + '.contenedor)', 50);
  }
  if (oMostrar && oMostrar.innerHTML) window.setTimeout(this.nombre+'.mostrar()', 100);


};

submodal.prototype.reset = function()
{
  this.setTitle('');
  centrarObj(this.contenedor);
};

submodal.prototype.setAyuda = function(codigo)
{
  this.botonAyuda.id = codigo;
};

submodal.prototype.setPos = function(x,y)
{
  if ((x) && (y))
  {
    this.contenedor.style.left     = x;
    this.contenedor.style.top      = y;
    this.contenedor.style.position = "absolute";
  }
}

submodal.prototype.setX = function (pos)
{
  if (!pos) return;
  this.contenedor.style.left = pos;
};

submodal.prototype.setY = function (pos)
{
  if (!pos) return;
  this.contenedor.style.top = pos;
};


function subContClick(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  if (Elem.padre)
  {
    var padre = eval(Elem.padre);
    if (Elem != padre.cerrar) padre.setFocus();
  }
}

function subbayuda(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var codAyuda = Elem.id.substring(6);
  if(FormularioAct){
  	ayuda(FormularioAct.ayuda,0,FormularioAct.titulo,FormularioAct.origen);
  }else
  {
    alert('No hay ayuda');
  }
}

function subbcerrar(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  if(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  if(window.event)
  {
    window.event.returnValue = false;
    window.event.cancelBubble = true;
  }

  if (Elem.padre)
  {
    var padre = eval(Elem.padre);
    if (padre.onClose)
    {
      padre.onClose(padre);
  	}
    else
    {
		padre.ocultar();
    }
  }
}

function nullfunc()
{
  return false;
}

function freeDOM_Obj(DOMobj)
{
        if (DOMobj.childNodes)
        {
                for (var i=DOMobj.childNodes.length-1;i>=0;i--)
                {
                        freeDOM_Obj(DOMobj.childNodes[i]);
                        DOMobj.removeChild(DOMobj.childNodes[i]);
                }
        }
        DOMobj = null;
}
