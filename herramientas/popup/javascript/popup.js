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
  this.currGroup=null;
  this.onClose=null;
  this.action=null;
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
  for (var j in this.options)
  {
    for (var i=l-1;i>=0;i--)
    {
      delete this.options[j][i];
    }
    this.options[j]=new Array;
  }
  this.options.length=0;
};

popup.prototype.addItem=function(group, caption, funcDef)
{
  var elem=new popupElem(caption, funcDef);
  if(!this.options[group]) this.options[group]=new Array;
  var l=this.options[group].length;
  this.options[group][l]=elem;
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

}

function rowCode(objName,ndx,classNam,caption)
{
  return '<tr><td nowrap style="cursor:pointer;" id="'+objName+'_op'+ndx+'" class="'+classNam+'" onmouseover="'+objName+'.highLight('+ndx+');" onmousedown="'+objName+'.doFunc();">&nbsp;&nbsp;&nbsp;'+caption+'&nbsp;&nbsp;&nbsp;</td></tr>';
}

popup.prototype.doFunc=function()
{
  if (this.currItem>=0&&this.currItem<this.options[this.currGroup].length)
  {
    this.hide();
    var cmd=this.options[this.currGroup][this.currItem].funcDef;
    eval(cmd);
  }
}

popup.prototype.draw=function()
{
  var l=this.options[this.currGroup].length;
  var html='<table border="0" cellpadding="0" cellspacing="0" class="submenu_fondo">';
  var cls='submenu_inactivo';
  if (this.position==_popupUp)
  {
    for (var i=l-1;i>=0;i--)
    {
      if (i>0) cls='submenu_inactivo';
      html+=rowCode(this.name,i,cls,this.options[this.currGroup][i].caption);
    }
  }
  else
  {
    for (var i=0;i<l;i++)
    {
      if (i>0) cls='submenu_inactivo';
      html+=rowCode(this.name,i,cls,this.options[this.currGroup][i].caption);
    }
  }
  html+='<table>';
  html+='<a href="" alt="" id="'+this.name+'_href"></a>';

  this.container.innerHTML = html;
  this.ahref=_(this.name+'_href');
  this.ahref.onkeypress = function () {return true};
  this.ahref.onkeydown = ppKeyHandler;
  this.ahref.onblur=ppLostFocus;
};

popup.prototype.highLight=function(ndx)
{
  for (var i=0;i<this.options[this.currGroup].length;i++)
  {
    var obj=_(this.name+'_op'+i);
    if (obj.className=='submenu_activo'&&i!=ndx) obj.className='submenu_inactivo';
    else if (obj.className!='submenu_activo'&&i==ndx) obj.className='submenu_activo';
  }
  this.currItem=ndx;
  this.setFocus();
}

function GetElementAbsolutePos(element) {
    var res = new Object();
    res.x = 0; res.y = 0;
    if (element !== null) {
        res.x = element.offsetLeft;
        res.y = element.offsetTop;

        var offsetParent = element.offsetParent;
        var parentNode = element.parentNode;

        while (offsetParent !== null) {
            res.x += offsetParent.offsetLeft;
            res.y += offsetParent.offsetTop;

            if (offsetParent != document.body && offsetParent != document.documentElement) {
                res.x -= offsetParent.scrollLeft;
                res.y -= offsetParent.scrollTop;
            }
            //next lines are necessary to support FireFox problem with offsetParent
            if (false/*Isfirefox*/) {
                while (offsetParent != parentNode && parentNode !== null) {
                    res.x -= parentNode.scrollLeft;
                    res.y -= parentNode.scrollTop;

                    parentNode = parentNode.parentNode;
                }
            }
            parentNode = offsetParent.parentNode;
            offsetParent = offsetParent.offsetParent;
        }
    }
    return res;
}

popup.prototype.show=function(Elem, group)
{
  this.currGroup=group;

  this.draw();
  var c=this.container;
  c.style.display='block';
  if (Elem)
  {
    //console.log("pos", $(Elem).position());
    //console.log("off", $(Elem).offset());
    
    var xpos = $(Elem).offset();
    var pos = {
      x: xpos.left,
      y: xpos.top
    };

    //var pos=GetElementAbsolutePos(Elem);

    //console.log(pos);

    var x=pos.x-1;
    var y=pos.y+1;

    switch(this.position)
    {
      case 0:
        c.style.left=x;
        c.style.top=y - c.offsetHeight;
        break;
      case 1:
        c.style.left=x;
        c.style.top=y + Elem.offsetHeight;
        break;
    }
  }
  this.currItem=0;
  this.highLight(0);

  this.container.style.width=10;
  this.container.style.height=10;
  this.ahref.focus();
};

popup.prototype.hide=function()
{
  this.container.style.display='none';
  if (this.onClose)
  {
    this.onClose();
  }
};

popup.prototype.setFocus=function()
{
  try
  {
  this.ahref.focus();
  }
  catch(e)
  {}
  finally{}
};

popup.prototype.up=function()
{
  var l=this.options[this.currGroup].length;
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
  var l=this.options[this.currGroup].length;
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

popup.prototype.left=function()
{
};

popup.prototype.right=function()
{
};


function ppLostFocus(e)
{
  var evt = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var obj=eval(Elem.id.replace('_href',''));
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
      cancelaTecla(evt);
      obj.doFunc();
      break;
    case 27: // escape
      obj.hide();
      cancelaTecla(evt);
      break;
    default:
      cancelaTecla(evt);
      break;
  }
  if (obj.action)
  {
    obj.action(obj, keyCode);
  }
  return ret;
};