var scrw=(document.all)? document.body.clientWidth-4 : window.innerWidth+4;
var scrh=(document.all)? document.body.clientHeight : window.innerHeight;
var shadowDiff = 8;
var _hideTimeOut = 500;

function Shadow()
{
  /*var tshadow = document.createElement('div');
  tshadow.style.backgroundColor='#000000';
  tshadow.style.width=0;
  tshadow.style.height=0;
  tshadow.style.position='absolute';
  tshadow.style.filter='alpha(opacity=25)';
  document.body.appendChild(tshadow);
  return tshadow;*/
}

function hmenu(name){
	
	this.name   = name;
	this.items  = new Array;
	this.width  = 600;
	this.height = 16;
	this.left   = 4;
	this.top    = 0;
	this.container = null;  //-- div del menu completo
	this.table  = null;
	this.containers = new Array;
	this.level    = 0;
	this.ahref   = null;
	this.current = null;
	this.isClosed = false;
	this.arrCombos = null;
	this.focused = true;
}

function menuItem(caption, pos, url, target){
	
	this.caption = caption;
	this.pos     = pos;
	this.url     = url;
	this.target  = target;
	this.items   = new Array;
	this.parent  = null;
	this.cell    = null;
	this.menu    = null;
	this.top     = 0;
	this.left    = 0;
	this.width   = 0;
	this.hmenu   = null;
	this.next    = null;
	this.prior   = null;
	this.first   = null;
	this.last    = null;
	this.icon    = '';
	this.isSeparator = false;
}

hmenu.prototype.init = function(){
	
	if (!document.body){
		window.setTimeout(this.name+'.init()',10);
		return false;
	}

	this.container = document.createElement('div');

	this.container.className = 'menu_fondo';
	this.container.style.display = 'none';

	this.container.style.left = this.left;
	this.container.style.top  = this.top;
	this.container.style.zIndex   = '0';


	//if (this.left || this.top) this.container.style.position = 'absolute';

	/*this.container.style.height = this.height;
	this.container.style.width = this.width;
	*/
	//  this.container.onkeydown = menuKeyHandler;

	this.ahref = document.createElement('a');
	this.ahref.href = '';
	this.ahref.alt = '';
	this.ahref.id = this.name + '_a';

	this.ahref.innerHTML = '';
	this.ahref.parent = this;
	this.ahref.onkeypress = function () {return true};
	this.ahref.onkeydown = menuKeyHandler;
	this.ahref.onfocus = menuFocus;

	this.container.appendChild(this.ahref);
	this.container.hmenu = this;

	this.leeUrl(this.url);

	this.table = document.createElement('table');

	var tbody  = document.createElement('tbody');
	var row    = document.createElement('tr');
	row.className = 'menu_superior';

	for (var i=0;i<this.items.length;i++){
		this.items[i].level = 0;

		var cell = document.createElement('td');
		cell.noWrap = true;
		var cap = this.items[i].caption;

		if (!cap) return;
		cap = cap.substring(0,1).toUpperCase() + cap.substring(1).toLowerCase();

		var acckey = cap.substring(cap.indexOf('[')+1,cap.indexOf(']'));

		cap = cap.replace(']', '</u>').replace('[','<u style="padding:0px 0px 0px 0px">');

		cell.innerHTML = cap;

		if (acckey) cell.innerHTML += '<label for="'+this.name + '_a" accesskey="'+acckey+'"></label>';

		cell.style.cursor = 'pointer';
		cell.item = this.items[i];

		this.items[i].cell = cell;
		if (i > 0){
			
			this.items[i-1].next = this.items[i];
			this.items[i].prior  = this.items[i-1];
		}

		cell.onmouseover = function(e){
		  //return ;
		  var evt = window.event || e;
		  var Elem = evt.srcElement || evt.target;

		  if (Elem.tagName == 'U') Elem = Elem.parentNode;
		  if (Elem.item)
		  {
			if (Elem.item.hmenu.focused) highLight(Elem.item,false);
		  }
		}

		cell.onclick = function(e){
		  var evt = window.event || e;
		  var Elem = evt.srcElement || evt.target;
		  //console.log(Elem);

	 
		if (Elem.tagName == 'U') Elem = Elem.parentNode;
		if (Elem.item){
			Elem.item.hmenu.focused = true;
			if (Elem.item.items.length > 0){
				
				if (Elem.item.menu.style.display != 'block'){
					
					Elem.item.menu.style.display = 'block';
					if (Elem.item.menu.offsetLeft + Elem.item.menu.offsetWidth > scrw){
					
						Elem.item.menu.style.left = scrw - Elem.item.menu.offsetWidth;
					}
					
					//setShadow(Elem.item.menu);

					Elem.item.hmenu.isClosed = false;
					highLight(Elem.item.items[0]);
				}
				else{
				
					Elem.item.menu.style.display = 'none';
					//if (Elem.item.menu.shadow) Elem.item.menu.shadow.style.display='none';
					Elem.item.hmenu.isClosed = true;
				}
			}
			else if (Elem.item.url){
				
				var target = curr.target.toLowerCase();
				var url = curr.url.toLowerCase();
				$('#' + target ).attr('src', url );              

				Elem.item.hmenu.ahref.target = curr.target.toLowerCase();
				//Elem.item.hmenu.ahref.href = server_path.toLowerCase() + curr.url.toLowerCase();
				//luis nu�ez
				Elem.item.hmenu.ahref.href = curr.url.toLowerCase();
				//Elem.item.hmenu.ahref.click();
				Elem.item.hmenu.reset();
				console.log('cuando hace click 1 en el menu');
			}
		}
	}

	row.appendChild(cell);
	}

	var first = this.items[0];
	var last = this.items[this.items.length-1];

	for (var i = 0; i<this.items.length; i++)
	{
	this.items[i].first = this.items[0];
	this.items[i].last = this.items[this.items.length-1];
	}

	tbody.appendChild(row);
	this.table.appendChild(tbody);
	this.table.height = this.height;

	this.container.appendChild(this.table);
	document.body.appendChild(this.container);

	var level = 0;

	createSubmenu(this.items, level);

	document.onkeydown = menuKeyFinder;

  var self=this;

  this.container.addEventListener("mouseenter", (e)=>{
    clearTimeout(self.toClose||null);
  });

  document.querySelectorAll(".submenu_fondo").forEach(item=>{
    item.addEventListener("mouseleave", (e) => {
      clearTimeout(self.toClose||null);
      self.toClose = setTimeout(()=>{
        self.reset();
      }, _hideTimeOut);
    });

    item.addEventListener("mouseenter", (e) => {
      clearTimeout(self.toClose||null);
    });
  });
	//this.container.style.width = this.table.offsetWidth;

	//$(".submenu_fondo").on('mouseleave', function(){ $(this).hide() })

  
};

hmenu.prototype.setFocus = function(){
	try{
		this.ahref.focus();
	}
	catch(e)
	{}
	window.status = '';
	
};

hmenu.prototype.show = function()
{
  this.container.style.display = 'flex';
  if (this.items.length)
  {
    highLight(this.items[0]);
  }
  this.setFocus();
};

function hideMenu(item)
{
  if (item.menu)
  {
    item.menu.style.display = 'none';
    //if (item.menu.shadow) item.menu.shadow.style.display='none';
    for (var i=0;i<item.items.length;i++)
    {
      hideMenu(item.items[i]);
    }
  }
  if (item.cell)
  {
    if (item.level >= 1) item.cell.className = 'submenu_inactivo';
    else item.cell.className = 'menu_inactivo';
  }
}

function highLight(item,focus)
{
  if (!item) return;
  var master = item.parent;

  item.hmenu.current = item;
//  window.status = item.level;

  for (var i=0;i<master.items.length;i++)
  {
    hideMenu(master.items[i]);
  }

  if (item.menu)
  {
    if (item.level >= 1)
    {
      var l = item.parent.menu.offsetWidth + parseInt(item.parent.menu.style.left) - 5;
      item.menu.style.left = l;
      item.menu.style.top  = item.cell.offsetTop + parseInt(item.parent.menu.style.top);
    }
    else
    {
      item.menu.style.top  = item.hmenu.top + item.hmenu.container.offsetHeight - 2;
      var l = item.hmenu.left + item.cell.offsetLeft + 3;
      item.menu.style.left = l;
    }

    if ((item.level == item.hmenu.level) && (!item.hmenu.isClosed))
    {
      item.menu.style.display = item.hmenu.container.style.display;
      highLight(item.items[0]);
    }

    if (l+item.menu.offsetWidth>scrw) item.menu.style.left=scrw-item.menu.offsetWidth;
    //setShadow(item.menu);
  }

  if (item.parent.cell)
  {
    if (item.parent.level >= 1) item.parent.cell.className = 'submenu_activo';
    else item.parent.cell.className = 'menu_activo';
  }

  if (item.cell)
  {
    if (item.level >= 1) item.cell.className = 'submenu_activo';
    else item.cell.className = 'menu_activo';
  }

  if(focus) item.hmenu.setFocus();
}

function createSubmenu(items, level)
{
  if (!items.length) return false;

  for (var i=0;i<items.length;i++)
  {
    if (items[i].items.length > 0)
    {
      var backMenu = document.createElement('iframe');
      backMenu.style.position = 'absolute';

      var divMenu = document.createElement('div');
      divMenu.className = 'submenu_fondo';
      divMenu.style.position = 'absolute';

      if (level == 0)
      {
        items[i].level = level;
        divMenu.style.top  = items[i].cell.offsetTop + items[i].cell.offsetHeight;
        divMenu.style.left = items[i].cell.offsetLeft;
      }
      else
      {
        items[i].level = level;
        divMenu.style.top  = items[i].top;
        divMenu.style.left = items[i].left + items[i].width;
      }

      divMenu.style.display = 'none';
      //if (divMenu.shadow) divMenu.shadow.style.display='none';

      var tabla = document.createElement('table');
      tabla.cellSpacing = 0;
      tabla.cellPadding = 0;
      var body  = document.createElement('tbody');

      tabla.appendChild(body);
      divMenu.appendChild(tabla);
//      document.body.appendChild(backMenu);
      document.body.appendChild(divMenu);


      var separador = false;

      for (var j=0;j<items[i].items.length;j++)
      {
        var row   = document.createElement('tr');
        var cell  = document.createElement('td');
        cell.noWrap = true;
        var cellLeft  = document.createElement('td');
        cellLeft.valign = 'bottom';
		cellLeft.className = 'iconos_menu';

        var cellRight = document.createElement('td');
        cellRight.valign = 'bottom';

        row.item = items[i].items[j];

        items[i].items[j].level = level + 1;
        items[i].items[j].top   = parseInt(divMenu.style.top) + items[i].top;
        items[i].items[j].left  = parseInt(divMenu.style.left) + items[i].left;

        var cap = items[i].items[j].caption;

        separador = (cap == '-');

        cap = cap.replace(']', '</u></b>').replace('[','<b><u style="padding:0px 0px 0px 0px">');
        cap = cap.substring(0,1).toUpperCase() + cap.substring(1).toLowerCase();

        if (!separador) cell.innerHTML = cap;
        else
        {
          cell.valign = 'middle';
          cell.innerHTML = '<table class="submenu_separador" style="height:1px"><tr><td></td></tr></table>';
          cellLeft.innerHTML = cell.innerHTML;
          cellRight.innerHTML = cell.innerHTML;
          cell.style.height = 5;
        }

        cell.valign = 'top';

        row.appendChild(cellLeft);
        row.appendChild(cell);
        row.appendChild(cellRight);
        body.appendChild(row);

        if (!separador)
        {
          if (row.item.items.length)
          {
            cellRight.innerHTML += '<div class="arrow"></div>'; // OJO ESTILO!!!
          }

          if (row.item.icon)
          {
            //cellLeft.innerHTML += '<img src="'+row.item.icon.toLowerCase()+'">';
            cellLeft.innerHTML += '<i class="' + row.item.icon.toLowerCase() + '"></i>';
          }

          items[i].items[j].cell = row;


          row.style.cursor = 'pointer';
          row.onmouseover = function(e)
          {
            var evt = window.event || e;
            var Elem = evt.srcElement || evt.target;

            while (Elem.tagName != 'TR') Elem = Elem.parentNode;
            if (Elem.item != Elem.item.hmenu.current.parent)
            {
              highLight(Elem.item);
            }
          }

          row.onclick = function(e)
          {
            var evt = window.event || e;
            var Elem = evt.srcElement || evt.target;

            while (Elem.tagName != 'TR') Elem = Elem.parentNode;

            var curr = Elem.item;
            if (curr.items.length)
            {
              curr.menu.style.display = 'block';
              openMenu(curr);
              highLight(curr.items[0]);
            }
            else if (curr.url)
            {
			  var target = curr.target.toLowerCase();
			  var url = curr.url.toLowerCase();
			  $('#' + target ).attr('src', url );              
              curr.hmenu.ahref.target = curr.target.toLowerCase();
			  curr.hmenu.ahref.href = curr.url.toLowerCase();
              //curr.hmenu.ahref.click();
              curr.hmenu.reset();

            }
          }
        }
      }
      items[i].menu = divMenu;
      //items[i].menu.shadow=Shadow();
    }
    createSubmenu(items[i].items,level+1);
  }
};

function loadItems(obj, rec, hmenu)
{
  var newItem  = new menuItem();

  if (rec.hasChildNodes())
  {
    for (var elem=rec.firstChild; elem != null; elem = elem.nextSibling)
    {
      if (!elem.firstChild) continue;
      var value = elem.firstChild.nodeValue;

      switch (elem.nodeName)
      {
        case 'ROTULO':
          newItem.caption = value;
          if (value == '-') newItem.isSeparator = true;
          break;
        case 'URL':
          newItem.url = value.toLowerCase();
          break;
        case 'TARGET':
          newItem.target = value;
          break;
        case 'ICONO':
          newItem.icon = value;
          break;
        case 'ITEM':
          loadItems(newItem, elem, hmenu);
          break;
      }
    }

    obj.items[obj.items.length] = newItem;
    newItem.hmenu = hmenu;
    newItem.parent = obj;
  }
  else delete newItem;

  for (var i=0;i<obj.items.length;i++)
  {
    if (i<obj.items.length-1) obj.items[i].next = obj.items[i+1];
    if (i>0) obj.items[i].prior  = obj.items[i-1];
    obj.items[i].first = obj.items[0];
    obj.items[i].last = obj.items[obj.items.length-1];
  }

}

hmenu.prototype.leeUrl = function(url) // trae el xml con la estructura del menu
{
  if (!this.url) this.url = server_path + 'herramientas/hmenu2/prueba.xml';

  if (debug()) prompt('Url llamado por el menu', this.url);

  var xml = enviar(this.url,'','POST');
  //console.log(this.url);
  try
  {
    xmlDoc.loadXML(xml);
  }
  catch(e)
  {
    if (document.implementation.createDocument)
    {
      var parser = new DOMParser();
      var uxmlDoc = parser.parseFromString(xml, "text/xml");
    }
    if((xmlDoc == null) && (uxmlDoc == null))
    {
      alert("XML Doc Load Failed");
      return;
    }
  }

  if (!uxmlDoc) uxmlDoc = xmlDoc;

  var table = uxmlDoc.getElementsByTagName('tabla');
  var path = 'tabla/registro';

  var recs = uxmlDoc.getElementsByTagName('registro');

  this.items = new Array;

  if (!table.length || !recs.length) return;

  for (var i=0;i<recs.length;i++)
  {
    loadItems(this, recs[i], this);
  }

};

hmenu.prototype.reset = function() // oculta el menu y solo muestra la opcion resaltada de los principales
{
  if (this.current)
  {
    while (this.current.level > 0)
    {
      this.current = this.current.parent;
      hideMenu(this.current);
    }
    this.current.cell.className = 'menu_inactivo';
    this.current.hmenu.isClosed = true;
  }
};

hmenu.prototype.setLeft = function(left)
{
  this.container.style.position = 'absolute';
  this.container.style.left = left;
  this.left = left;
  this.setFocus();
};

hmenu.prototype.setTop = function(top)
{
  this.container.style.position = 'absolute';
  this.container.style.top = top;
  this.top = top;
  this.setFocus();
};

hmenu.prototype.ocultarCombos = function()
{
    this.arrCombos = new Array;

    var all = (document.all || document.getElementsByTagName('*'));

    for(var i = 0; i < all.length; i++)
    {
      if((all[i].tagName.toUpperCase() == "SELECT") && (all[i].style.display!="none"))
      {
         this.arrCombos[this.arrCombos.length] = all[i];
         all[i].style.display="none";
      }
      else if ((all[i].tagName.toUpperCase() == 'IFRAME') && (xframeactual != all[i].name))
      {
        var obj = all[i].contentWindow.document;
        var myall = (obj.all || obj.getElementsByTagName('*'));

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
};


hmenu.prototype.mostrarCombos = function()
{
  for (var i=0;i<this.arrCombos.length;i++)
  {
    this.arrCombos[i].style.display = 'block';
  }
};


function menuKeyHandler(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var keyCode = evt.keyCode;
  var ret = false;
  var isAltPressed   = evt.altKey;

  var curr = Elem.parent.current;

//  alert(keyCode);
//  alert(curr.level);

  if (curr.level == 0)
  {
    switch (keyCode)
    {
      case 39: //-- derecha
        if (curr.next) highLight(curr.next);
        else if (curr.first) highLight(curr.first);
        break;
      case 37: //-- izquierda
        if (curr.prior) highLight(curr.prior);
        else if (curr.last) highLight(curr.last);
        break;
      case 40: //-- abajo
        if (curr.items.length)
        {
          curr.hmenu.isClosed = false;
          if (curr.menu.style.display != 'block')
          {
            curr.menu.style.display = 'block';
            openMenu(curr);
            //setShadow(curr.menu);
            highLight(curr.items[0]);
          }
          else
          {
            if (curr.items.length > 1) highLight(curr.items[1]);
            else highLight(curr.items[0]);
          }
        }
        break;
      case 13:
        if (curr.items.length)
        {
          curr.hmenu.isClosed = false;
          curr.menu.style.display = 'block';
          openMenu(curr);
          //setShadow(curr.menu);
          highLight(curr.items[0]);
        }
        else if (curr.url)
        {
          ret = true;
          curr.hmenu.ahref.target = curr.target.toLowerCase();
          curr.hmenu.ahref.src = curr.url.toLowerCase();
          curr.hmenu.ahref.click();
          curr.hmenu.reset();
        }
        break;
      default:
        if (isAltPressed)
        {
          curr.hmenu.isClosed = false;
          curr.menu.style.display = 'block';
          //setShadow(curr.menu);
          highLight(curr.items[0]);
        }
    }
  }

  if (curr.level >= 1)
  {
    switch (keyCode)
    {
      case 27:
        curr.hmenu.reset();
        break;
      case 39: //-- derecha
        if (curr.items.length)
        {
          curr.menu.style.display = 'block';
          openMenu(curr);
          highLight(curr.items[0]);
        }
        else
        {
          while (curr.level != 0) curr = curr.parent;
          if (curr.next) highLight(curr.next);
          else if (curr.first) highLight(curr.first);
        }
        break;
      case 37: //-- izquierda
        if (curr.level == 1)
        {
          if (curr.parent.prior) highLight(curr.parent.prior);
          else if (curr.parent.last) highLight(curr.parent.last);
        }
        else
        {
          highLight(curr.parent);
        }
        break;
      case 40: //-- abajo
        if (curr.next)
        {
          var obj = curr.next;
          if (obj.next && obj.isSeparator) highLight(obj.next);
          else highLight(curr.next);
        }
        else if (curr.first) highLight(curr.first);
        break;
      case 38: //-- arriba
        if (curr.prior)
        {
          var obj = curr.prior;
          if ((obj.prior) && (obj.isSeparator)) highLight(obj.prior);
          else highLight(curr.prior);
        }
        else if (curr.last) highLight(curr.last);
        break;
      case 13:
        if (curr.items.length)
        {
          curr.menu.style.display = 'block';
          openMenu(curr);
          curr.hmenu.isClosed = false;
          highLight(curr.items[0]);
        }
        else if (curr.url)
        {
          ret = true;
          curr.hmenu.ahref.target = curr.target.toLowerCase();
          //curr.hmenu.ahref.href = server_path.toLowerCase() + curr.url.toLowerCase();
          //luisman
          curr.hmenu.ahref.href = curr.url.toLowerCase();
          curr.hmenu.ahref.click();
          curr.hmenu.reset();
        }
        break;
    }
  }

  return ret;

}

function menuKeyFinder(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var keyCode = evt.keyCode;
  var isAltPressed   = evt.altKey;
}

function menuFocus(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;

  if (Elem.parent)
  {
    highLight(Elem.parent.current);
  }
}

function openMenu(c)
{
  var m=c.menu;
  var p=c.parent.menu;

  try
  {
    if (m.offsetLeft + m.offsetWidth > scrw) m.style.left = scrw - m.offsetWidth + 5;
    else m.style.left = p.offsetLeft + p.offsetWidth - 5;
    if (m.offsetTop + m.offsetHeight > scrh) m.style.top = scrh - m.offsetHeight;
    //setShadow(m);
  }
  catch(e)
  {}
  finally
  {}
}

function setShadow(obj, msg)
{
  /*if (msg) alert(msg);
  if (obj.shadow)
  {
    obj.style.zIndex=1;
    obj.shadow.style.zIndex=0;
    obj.shadow.style.left  = obj.offsetLeft + shadowDiff;
    obj.shadow.style.top   = obj.offsetTop + shadowDiff;
    obj.shadow.style.width = obj.offsetWidth;
    obj.shadow.style.height= obj.offsetHeight;
    obj.shadow.style.display='block';
  }*/
}