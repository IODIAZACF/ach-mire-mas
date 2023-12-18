var _popupUp=0;
var _popupDown=1;

function popup(name)
{
  this.options=new Array;
  this.name=name;
  this.container=null;
  this.currItem=0;
  this.ahref=null;
  this.position=0; // 0=arriba,1=abajo
  this.width=10;
  this.height=10;
};

function popupCall(func)
{
  window.setTimeout(func, 10);
};

function popupElem(caption, funcDef)
{
  this.caption = caption;
  this.funcDef = funcDef;
};

popup.prototype.clear=function()
{
  var l=this.options.length;
  for (var i=l-1;i>=0;i--)
  {
    delete this.options[i];
  }
  this.options.length=0;
};

popup.prototype.addItem=function(caption, funcDef)
{
  var elem=new popupElem(caption, funcDef);
  this.options[this.options.length]=elem;
};

function _(elemId)
{
  return document.getElementById(elemId);
}
function $create(tagName)
{
  return document.createElement(tagName);
};

function $add(Elem)
{
  document.body.appendChild(Elem);
};

popup.prototype.init=function()
{
  if (!this.name)
  {
    alert('Falta el nombre del popup');
    return;
  }
  this.container=$create('DIV');
  this.container.style.display='none';
  this.container.style.position='absolute';
  this.container.style.left=10;
  this.container.style.top=10;
  this.container.style.width=10;
  this.container.style.height=10;
  $add(this.container);
  this.ahref=$create('A');
  this.ahref.href='';
  this.ahref.alt='';
  this.ahref.id=this.name+'_href';
  this.ahref.onkeypress = function () {return true};
  this.ahref.onkeydown = ppKeyHandler;
  $add(this.ahref);
}

function rowCode(objName,ndx,classNam,caption)
{
  return '<tr><td nowrap style="cursor:pointer;" id="'+objName+'_op'+ndx+'" class="'+classNam+'" onmouseover="'+objName+'.highLight('+ndx+');">&nbsp;&nbsp;&nbsp;'+caption+'&nbsp;&nbsp;&nbsp;</td></tr>';
}
popup.prototype.draw=function()
{
  var l=this.options.length;
  var html='<table border="0" cellpadding="0" cellspacing="0" class="submenu_fondo">';
  var cls='submenu_inactivo';
  if (this.position==_popupUp)
  {
    for (var i=l-1;i>=0;i--)
    {
      if (i>0) cls='submenu_inactivo';
      html+=rowCode(this.name,i,cls,this.options[i].caption);
    }
  }
  else
  {
    for (var i=0;i<l;i++)
    {
      if (i>0) cls='submenu_inactivo';
      html+=rowCode(this.name,i,cls,this.options[i].caption);
    }
  }
  html+='<table>';
  this.container.innerHTML = html;
};

popup.prototype.highLight=function(ndx)
{
  for (var i=0;i<this.options.length;i++)
  {
    var obj=_(this.name+'_op'+i);
    if (obj.className=='submenu_activo'&&i!=ndx) obj.className='submenu_inactivo';
    else if (obj.className!='submenu_activo'&&i==ndx) obj.className='submenu_activo';
  }
  this.currItem=ndx;
  this.setFocus();
}

popup.prototype.show=function(Elem)
{
  this.draw();
  var c=this.container;
  c.style.display='block';
  if (Elem)
  {
    switch(this.position)
    {
      case 0:
        c.style.left=Elem.offsetLeft;
        c.style.top=Elem.offsetTop - c.offsetHeight;
        break;
      case 1:
        c.style.left=Elem.offsetLeft;
        c.style.top=Elem.offsetTop + Elem.offsetHeight;
        break;
    }
  }
  this.currItem=0;
  this.highLight(0);

  this.container.style.width=10;
  this.container.style.height=10;
  this.setFocus();
};

popup.prototype.hide=function()
{
  this.container.style.display='none';
};

popup.prototype.setFocus=function()
{
  this.ahref.focus();
  this.ahref.onblur=lostFocus;
};

popup.prototype.up=function()
{
  var l=this.options.length;
  switch(this.position)
  {
    case _popupUp:
      if (this.currItem<l-1) this.highLight(this.currItem+1);
      else if (l) this.highLight(0);
      break;
    case _popupDown:
      if (this.currItem>0) this.highLight(this.currItem-1);
      else if (l) this.highLight(l-1);
      break;
  }
};

popup.prototype.down=function()
{
  var l=this.options.length;
  switch(this.position)
  {
    case _popupDown:
      if (this.currItem<l-1) this.highLight(this.currItem+1);
      else if (l) this.highLight(0);
      break;
    case _popupUp:
      if (this.currItem>0) this.highLight(this.currItem-1);
      else if (l) this.highLight(l-1);
      break;
  }
};

function lostFocus()
{
  var obj=eval(this.id.replace('_href',''));
  obj.hide();
};

function ppKeyHandler(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var keyCode = evt.keyCode;
  var ret = false;
  var isAltPressed   = evt.altKey;

  var obj=eval(this.id.replace('_href',''));

  switch (keyCode)
  {
    case 38: //-- arriba
      cancelaTecla(evt);
      obj.up();
      break;
    case 39: //-- derecha
      cancelaTecla(evt);
      obj.right();
      break;
    case 37: //-- izquierda
      cancelaTecla(evt);
      obj.left();
      break;
    case 40: //-- abajo
      cancelaTecla(evt);
      obj.down();
      break;
    case 13: // return
      if (obj.currItem>=0&&obj.currItem<obj.options.length)
      {
        obj.hide();
        var cmd=obj.options[obj.currItem].funcDef;
        eval(cmd);
      }
      cancelaTecla(evt);
      break;
    case 27: // escape
      obj.hide();
      cancelaTecla(evt);
      break;
    default:;
  }
  return ret;
};