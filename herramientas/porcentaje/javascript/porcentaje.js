function _(id)
{
  return document.getElementById(id);
}

function xxTag(tag)
{
  return document.createElement(tag);
}

function porcentaje(o)
{
  o=o||{};
  this.padre=o.padre||"";
  this.texto=o.texto||false;
  this.alto=o.alto||16;
  this.maxValue=o.maxValue||100;
  this.color=o.color||'#FFFFFF';
  this.contenedor=null;
  this.cuadro=null;
}

porcentaje.prototype.inicializa = function()
{
  if (this.inicializado) return;
  if (typeof this.padre === "string") {
    this.nodoPadre = (()=>{
      var ret=_(this.padre);
      if (!ret) try { 
        ret = document.querySelector(this.padre);
      } catch(e) {
        ret = document.querySelector("#"+this.padre);
      }
      return ret;
    })();
  }
  else this.nodoPadre=this.padre||document.body;
  if (this.nodoPadre != document.body) {
    this.nodoPadre.style.display="flex";
    this.nodoPadre.style.flexDirection="column";
  }
  this.contenedor=xxTag('DIV');
  this.contenedor.className="barra-porcentaje";
  this.nodoPadre.appendChild(this.contenedor);
  this.contenedor.style.height=this.alto;
  this.contenedor.style.display="flex";
  this.contenedor.style.backgroundColor=this.color;
  var div=document.createElement("div");
  div.className="bp-valor";
  this.contenedor.appendChild(div);
  var div2=document.createElement("div");
  div2.className="bp-texto";
  if (this.texto) {
    if (typeof this.texto==="boolean") {
      div2.innerHTML="0%";
    } else {
      div2.innerHTML=this.texto;
    }
  }
  this.contenedor.appendChild(div2);
  this.inicializado=true;
};

porcentaje.prototype.setValue=function(valor, texto)
{
  if (!this.contenedor) return;
  var p=this.contenedor.querySelector(".bp-valor");
  if (!p) return;
  var w=Math.round(valor/this.maxValue*100)+"%";
  p.style.width=w;
  texto=texto||this.texto;
  if (texto) {
    var p=this.contenedor.querySelector(".bp-texto");
    if (typeof texto==="boolean") {
      p.innerHTML=w;
    } else {
      p.innerHTML=texto;
    }
  }
};