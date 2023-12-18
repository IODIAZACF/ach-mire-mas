var isNN = (navigator.appName.indexOf("Netscape") != -1);
var isIE = (navigator.appName.indexOf("Microsoft") != -1);
var IEVersion = (isIE ? getIEVersion() : 0);
var NNVersion = (isNN ? getNNVersion() : 0);

var ver    = parseFloat (navigator.appVersion.slice(0,4));
var verIE  = (navigator.appName == "Microsoft Internet Explorer" ? ver : 0.0);
var verNS  = (navigator.appName == "Netscape" ? ver : 0.0);
var verOP  = (navigator.appName == "Opera"    ? ver : 0.0);
var verOld = (verIE < 4.0 && verNS < 5.0);
var isMSIE = (verIE >= 4.0);
var codHijoDefecto = '';

var bandera = null;

function getNNVersion()
{
  var userAgent = window.navigator.userAgent;
  var isMajor = parseInt(window.navigator.appVersion);
  var isMinor = parseFloat(window.navigator.appVersion);
  if (isMajor == 2) return 2;
  if (isMajor == 3) return 3;
  if (isMajor == 4) return 4;
  if (isMajor == 5)
  {
    if (userAgent.toLowerCase().indexOf('netscape')!=-1)
    {
      isMajor = parseInt(userAgent.substr(userAgent.toLowerCase().indexOf('netscape')+9));
      if (isMajor>0) return isMajor;
    }
    if (userAgent.toLowerCase().indexOf('firefox')!=-1) return 7;
    return 6;
  }
  return isMajor;
}

function getIEVersion()
{
  var userAgent = window.navigator.userAgent;
  var MSIEPos = userAgent.indexOf("MSIE");
  return (MSIEPos > 0 ? parseInt(userAgent.substring(MSIEPos+5, userAgent.indexOf(".", MSIEPos))) : 0);
}

function dependencia(obj, xconfig, objForm)
{
  //this.objeto = document.getElementById(obj);
  this.objeto = objForm[obj];
  this.tabla  = '';
  this.campos = '';
  this.busca = '';
  this.campo_mostrar = '';
  this.campo_guardar = '';
  this.padre = '';
  this.tipo = 'dependencia';
  this.orden = '';
  this.xconfig = xconfig;
}

function filtros(padre, xconfig)   //esta funcion se utiliza para la forma 10 actual
{
    var url = server_path + 'herramientas/genera_xml/genera_xml.php';
    xbusca = padre.value ? padre.value : -1;

    if (!padre.hijo) return false;
    var param = 'tabla='+  padre.hijo.tabla + '&operador==&campos='+  padre.hijo.campo_guardar + ',' + padre.hijo.campo_mostrar +'&busca='+  padre.hijo.busca  + '&xbusca='+ padre.value;
    if (padre.hijo.orden) param +='&orden='+padre.hijo.orden;
    if (xconfig) param += '&xconfig='+xconfig;

    padre.hijo.options.length = 1;
    padre.hijo.options[0].value = codHijoDefecto;
    padre.hijo.options[0].text = '--';

    if (padre.value)
    {
       var x = enviar(url,param, 'get');
       filtroregistro = XML2Array(x);
       for(y=0;y<filtroregistro.length;y++)
       {
           padre.hijo.options.length++;
           intOptions = padre.hijo.options.length - 1;
           padre.hijo.options[intOptions].value =  "" + filtroregistro[y][padre.hijo.campo_guardar];
           padre.hijo.options[intOptions].text = "" + filtroregistro[y][padre.hijo.campo_mostrar];
       }
    }
    if(padre.hijo.hijo)
    {
      filtros(padre.hijo, xconfig);
    }
}


dependencia.prototype.setPadre = function(form, elem, xconfig)
{
  var fobj = document.getElementById(form);
  var obj =fobj[elem];

  obj.hijo = this.objeto;
  obj.hijo.tabla = this.tabla;
  obj.hijo.campos = this.campos;
  obj.hijo.busca = this.busca;
  obj.hijo.campo_guardar = this.campo_guardar;
  obj.hijo.campo_mostrar = this.campo_mostrar;
  obj.hijo.orden = this.orden;

  this.padre = obj;

  obj.onchange = function (e)
  {
    var evt  = window.event || e;
    var Elem = evt.srcElement || evt.target;

    if (Elem)
    {
      filtros(Elem, xconfig);
    }
  };

};

/*******************************************************************************

                             CLASE forma

*******************************************************************************/
function forma(nombre, nom, xconfig)
{
  try
  {
    this.formobj = eval('frm_'+nombre);
  }
  catch(e)
  {
    this.formobj = eval(nombre);
  }
  finally{}

  this.funcion  = null;
  //this.validar  = null; // (CAMPO,CONDICION,MSJERROR,ESALERTA)
  this.maestros = new Array;
  this.validacion  = add_validation; // (CAMPO,CONDICION,MSJERROR,ESALERTA)

/*  this.validar = function (obj)
  {
    form_submit_handler(obj);
  };*/

  this.padre = null;
  this.grid = null;
  this.xconfig=xconfig?xconfig:null;
  this.arrayElementos = new Array;
}

forma.prototype.validar = function(obj)
{
  this.parent.abort = true;
  var ret = form_submit_handler(obj, this);
  this.parent.abort = !ret;
  return ret;
};

forma.prototype.inicializa = function()
{
  if (!this.formobj)
  {
    alert('Error: falta el objeto forma "'+nombre+'"');
    return false;
  }
  this.funcion  = null;

  for (i=0;i<this.arrayElementos.length;i++)
  {
    var elem = this.arrayElementos[i];
    switch (elem.tipo)
    {
      case 'dependencia':
        //alert(this.formobj.name + 'dependecia');
        //alert(elem.objeto.name);
        //alert(this.arrayElementos[i].padre);
        elem.setPadre(this.formobj.name, this.arrayElementos[i].padre, this.xconfig);
        break;
      case 'maestro':
        if (!this.grid) this.grid = new lista();
        elem.boton.padre = this;
        elem.boton.origen = elem.origen;
        elem.boton.onclick = function (e)
        {
          var evt  = window.event || e;
          var Elem = evt.srcElement || evt.target;

          if (Elem)
          {
            Elem.padre.grid.origen = Elem.origen;
            Elem.padre.grid.leeOrigen();
            Elem.padre.grid.inicializa();
            Elem.padre.grid.mostrar();
          }
        };

        break;
//        this.arrayElementos[i].
      default:;
    }
  }
//  forms_onload();
};

forma.prototype.buscar = function(xbusca)
{
  url = server_path + 'herramientas/genera_xml/genera_xml.php';
  params = 'tabla=' + this.formobj['tabla'].value + '&campos=*&busca='+ this.formobj['busca'].value + '&xbusca='+xbusca+'&operador==';

  x = enviar(url,params,'post');
  registro = XML2Array(x);

  for (i in registro[0])
  {
    f = this.formobj[i];
    if (f)
    {
      switch (f.tagName)
      {
        case 'INPUT':
          f.value = registro[0][i];
          break;
        case 'SELECT':
          break;
        default:;
      }
    }
  }

};



forma.prototype.dependencia = function (campo, xconfig, objForm)
{
  n = this.arrayElementos.length;
  this.arrayElementos[n] = new dependencia(campo, xconfig, objForm);
  return this.arrayElementos[n];
};

function maestro(campo)
{
  this.origen = '';
  this.campo_guardar = '';
  this.campo_mostrar = '';
  this.dmostrar = '';
  this.boton = null;
  this.tipo = 'maestro';
}

forma.prototype.maestro = function (campo)
{
  n = this.arrayElementos.length;
  this.arrayElementos[n] = new maestro(campo);
  return this.arrayElementos[n];
};

forma.prototype.mascara = function(campo, mascara, tipo)
{

//  if (debug()) alert('M�scara:\n'+campo+' - '+mascara + ' - ' + tipo);
  var obj = this.formobj[campo];
  var xtipo;

//  var mask = new Mask(mascara, obj.name);

  switch (tipo)
  {
    case 'N':
      xtipo = 'number';
      obj.mask = new Mask(mascara, xtipo);
      obj.mask.attach(obj);
      obj.value = obj.mask.format(obj.value);
      break;
    case 'D':
      xtipo = 'date';
/*      if (!mascara) mascara = 'dd/mm/yyyy';
      mascara = mascara.toLowerCase();
      obj.mask = new Mask(mascara, xtipo);
      obj.mask.attach(obj);*/
      setDateEditor(obj);
      break;
    case 'T':
      xtipo = 'time';
      setTimeEditor(obj);
      break;
    default:
      xtipo = '';
      obj.mask = new Mask(mascara);
      obj.mask.attach(obj);
      obj.value = obj.mask.format(obj.value);
      break;
  }

//  obj.mask = new Mask(mascara, xtipo); //mask;
//  obj.mask.attach(obj);

  if (tipo != 'D')
  {
    //addEvent(obj, 'blur', applyFormat);
  }


};

forma.prototype.formula = function(campoDestino, formula, campos)
{

};

forma.prototype.datepicker = function(campo, formato)
{

};

forma.prototype.limpiar = function()
{
  this.formobj.reset();
};


/*
  -------------------------------------------------------------------------
                                JavaScript Form Validator
                                Version 3.0
                    Part of the JavaScript Coder software

        Copyright 2003 JavaScript-coder.com. All rights reserved.
    This javascript code is installed as part of JavaScript Coder software.
        You may adapt this script for your Web pages, provided these opening credit
    lines (down to the lower dividing line) are kept intact.
    You may not reprint or redistribute this code without permission from
    JavaScript-Coder.com.

        JavaScript Coder
        It precisely codes what you imagine!
        Grab your copy here:
                http://www.javascript-coder.com/
    -------------------------------------------------------------------------
*/
function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}


function clear_all_validations()
{
        for(var itr=0;itr < this.formobj.elements.length;itr++)
    {
        this.formobj.elements[itr].validationset = null;
    }
}



function form_submit_handler(obj, me)
{

        if (obj)
        {
                  if(obj.validationset && !obj.validationset.validate())
                  {
                          return false;
                  }

                  if(me && (me.addnlvalidation))
                  {
                          str =" var ret = "+me.addnlvalidation+"()";
                          eval(str);
                          if(!ret) return ret;
                  }

        }
        else
        {
           for(var itr=0;itr < me.formobj.elements.length;itr++)
           {
                   /* validar fecha */

                   var nm=me.formobj.elements[itr].name;
                   if (nm)
                   {
                      nm=nm.substr(nm.length-4,2);
                      /*
					  if (nm=='_D') //fecha
                      {
                        var ret = isValidDate(me.formobj.elements[itr].value);
                        if (!ret)
                        {
                          me.formobj.elements[itr].focus();
                          return ret;
                        }
                      }
					  */
                   }

                   var fx = me.formobj.elements[itr].validationset;
                   if (!fx) fx = null;

                   if(me.formobj.elements[itr].validationset && !me.formobj.elements[itr].validationset.validate(fx.formobj))
                   {
                           return false;
                   }
           }
           if(me.addnlvalidation)
           {
                   str =" var ret = "+me.addnlvalidation+"()";
                   eval(str);
                   if(!ret) return ret;
           }
        }
        return true;
}

function add_validation(itemname,descriptor,errstr,AlertLevel)
{
//    if (debug()) alert('Validaci�n:\n'+itemname+' - '+descriptor);
    if(!this.formobj)
    {
            alert("BUG: the form object is not set properly");
            return;
    }//if
    var itemobj = this.formobj[itemname];

    if(itemobj.length && isNaN(itemobj.selectedIndex) )
        //for radio button; don't do for 'select' item
    {
        itemobj = itemobj[0];
    }
    if(!itemobj)
    {
        //alert("BUG: Couldnot get the input object named: "+itemname);
        alert("BUG: No se puede tener referencia al objeto: " + itemname);
        return;
    }

    if(!itemobj.validationset)
    {
        itemobj.validationset = new ValidationSet(itemobj.id, this.formobj);
    }
    var AlertLevel = AlertLevel =="undefined" ? 0 : AlertLevel;
    itemobj.validationset.add(descriptor,errstr,AlertLevel);

}

function ValidationDesc(inputitem,desc,error,AlertLevel)
{
  this.desc=desc;
  this.error=error;
  this.itemobj = inputitem;
  this.validate=vdesc_validate;
  this.AlertLevel=AlertLevel;
}

function vdesc_validate(formobj)
{
  if(!validateInput(this.desc,this.itemobj,this.error,this.AlertLevel,formobj))
  {
    try
    {
      if (formobj)
      {
         var obj = formobj[this.itemobj];
      }
      else
      {
        var obj = document.getElementById(this.itemobj);
      }
      if(obj.type=='hidden')
      {
		//es oculto reviso si es boton maestro....
        var tTipo = 'b_'+ obj.id;
        var tObj = document.getElementById(tTipo);
        if(tObj)
        {
        	tObj.focus();
        	return false;
        }

      }
      obj.focus();

    }
    catch(e){}

    return false;
  }
  return true;
}

function ValidationSet(inputitem, formobj)
{
  this.vSet=new Array();
  this.itemobj = inputitem;
  this.add= add_validationdesc;
  this.validate=vset_validate;
  this.formobj=formobj;
  this.clase='validationSet';
}

function add_validationdesc(desc,error,AlertLevel)
{
  this.vSet[this.vSet.length]=
  new ValidationDesc(this.itemobj,desc,error,AlertLevel);
}
function vset_validate(formobj)
{
   for(var itr=0;itr<this.vSet.length;itr++)
   {
     if(!this.vSet[itr].validate(formobj))
     {
       return false;
     }
   }
   return true;
}
/*  checks the validity of an email address entered
*   returns true or false
*/
function validateEmail(email)
{
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null)
      {
            var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
            if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
    return false;
}

function TestUnico(objValue,cmdvalue,strError,AlertLevel)
{
    var ret = '';

    if(objValue.value == '') return ret;

    var t        = 0;
    var campo    = '';
    var params = cmdvalue.split(',');



    var otabla     = params[0];
    var ocampo     = params[1];
    var xbuscaForm = objValue.form['xbusca'].value;
    var buscaForm  = objValue.form['busca'].value;

    objValueName = objValue.name;

    objValueName = objValueName.substring(2);
    campo        = objValueName.substring(0,objValueName.length-3);
    var tipo     = objValueName.substring(objValueName.length-3).substring(0,1);

    var tvalor =objValue.value;
    if(tipo=='D')
    {
    	var xdate = isValidDate(tvalor,true);
        if(xdate) tvalor = fechaMDY(tvalor);
    }

    var url   = server_path + 'herramientas/genera_xml/genera_xml.php';
    var param = 'tabla='+  otabla + '&operador==&campos='+  buscaForm + '&busca='+  ocampo + '&xbusca='+ tvalor;

    if (debug()) _prompt('Leer el dato unico:', url+'?'+param);
    //prompt('Leer el dato unico:', url+'?'+param);
    var x = enviar(url,param, 'get');
    registro = XML2Array(x);

    strError = strError.replace('{1}', otabla);
    strError = strError.replace('{2}', ocampo);
    strError = strError.replace('{3}', objValue.value);

    if(registro.length)
    {
        if(registro[0][buscaForm] != xbuscaForm)
        {
               if(!strError || strError.length ==0)
               {
                    strError = objValue.name + " : Debe ser un valor �nico"; // ojo: idiomas
               }
               if(AlertLevel == 1)
                    {
                        objValue.focus();
                        return false;
                    }
               ret = strError;
        }
    }
    return ret;
}

function TestComparison(objValue,strCompareElement,strvalidator,strError)
{
   var bRet='';
   var objCompare=null;
   if(!objValue.form)
   {
      bRet =  "BUG: No Form object!";
   }
   objCompare = objValue.form.elements[strCompareElement];
   if(!objCompare)
   {
     bRet = "BUG: Elemento de nombre "+strCompareElement+" no se ha encontrado !";
   }
   if(strvalidator != "eqelmnt" &&
             strvalidator != "neelmnt")
   {
      if(isNaN(objValue.value))
      {
        bRet = objValue.name+": Debe contener solo numeros ";
      }//if
      if(isNaN(objCompare.value))
      {
        bRet = objCompare.name+": Should be a number ";
      }//if
   }//if
   switch(strvalidator)
   {
       case "I=":
                        {
                           if(objValue.value != objCompare.value)
                           {
                                bRet = " should be equal to ";
                           }//if
                           break;
                        }//case
                case "I<":
                        {
                        if(eval(objValue.value) >= eval(objCompare.value))
                                {
                               bRet =  " should be less than ";
                                }
                          break;
                        }//case
                case "I<=":
                        {
                                if(eval(objValue.value) >  eval(objCompare.value))
                                {
                               bRet =  " should be less than or equal to";
                                }
                          break;
                        }//case
                case "I>":
                        {
                                if(eval(objValue.value) <=  eval(objCompare.value))
                                {
                               bRet =  " should be greater than";
                                }
                          break;
                        }//case
                case "I>=":
                        {
                                if(eval(objValue.value) < eval(objCompare.value))
                                {
                               bRet =  " should be greater than or equal to";
                                }
                          break;
                        }//case
                case "<>":
                        {
                           if(objValue.value.length > 0 &&
                             objCompare.value.length > 0 &&
                             objValue.value == objCompare.value)
                           {
                                bRet = " should be different from ";
                           }//if
                           break;
                        }
   }//switch
   if(!strError || strError.length==0)
   {
                   bRet = objValue.name + cmpstr + objCompare.value;
    }//if
   return bRet;
}
function TestSelMin(objValue,strMinSel,strError)
{
    var bret = true;
        var objcheck = objValue.form.elements[objValue.name];
        var chkcount =0;
        if(objcheck.length)
        {
                for(var c=0;c < objcheck.length;c++)
                {
                   if(objcheck[c].checked == "1")
                   {
                     chkcount++;
                   }//if
                }//for
        }
        else
        {
          chkcount = (objcheck.checked == "1")?1:0;
        }
        var minsel = eval(strMinSel);
        if(chkcount < minsel)
        {
                if(!strError || strError.length ==0)
                {
                        strError = "Please Select atleast"+minsel+" check boxes for"+objValue.name;
                }//if
                alert(strError);
                bret = false;
        }
        return bret;
}

function TestDontSelectChk(objValue,chkValue,strError)
{
    var pass=true;
        var objcheck = objValue.form.elements[objValue.name];
    if(objcheck.length)
        {
                var idxchk=-1;
                for(var c=0;c < objcheck.length;c++)
                {
                   if(objcheck[c].value == chkValue)
                   {
                     idxchk=c;
                         break;
                   }//if
                }//for
                if(idxchk>= 0)
                {
                  if(objcheck[idxchk].checked=="1")
                  {
                    pass=false;
                  }
                }//if
        }
        else
        {
                if(objValue.checked == "1")
                {
                        pass=false;
                }//if
        }//else
        if(pass==false)
        {
     if(!strError || strError.length ==0)
        {
                strError = "Can't Proceed as you selected "+objValue.name;
        }//if
          alert(strError);

        }
    return pass;
}

function TestRequiredInput(objValue,strError)
{
 //var ret = true;
    var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }

    if (debug())
    {
      _prompt('valor requerido: ('+f+'_'+objValue.id+')"'+objValue.value+'"','');
    }
    if(objValue.value=='undefined') objValue.value ='';
    if(eval(objValue.value.length) == 0)
    {
       if(!strError || strError.length ==0)
       {
         strError = objValue.name + " : Required Field";
       }//if
       return strError;
    }//if
        return ret;
}

function TestMaxLen(objValue,strMaxLen,strError)
{
    var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
	
    if(eval(objValue.value.length) > eval(strMaxLen))
    {
      if(!strError || strError.length ==0)
      {
        strError = objValue.name + " : "+ strMaxLen +" M�ximo de Caracteres ";
      }//if
      return strError;// + "\n[" + objValue.value.length + "]";
    }//if
        return ret;
}

function TestMinLen(objValue,strMinLen,strError)
{
    var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
	
    if(eval(objValue.value.length) <  eval(strMinLen))
    {
      if(!strError || strError.length ==0)
      {
        strError = objValue.name + " : " + strMinLen + " M�nimo de Caracteres   ";
      }//if
      ret = strError;// + "\n[" + objValue.value.length + " ]";
    }//if
    return ret;
}

function TestInputType(objValue,strRegExp,strError,strDefaultError)
{
   var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
   
    var charpos = objValue.value.search(strRegExp);
    if(objValue.value.length > 0 &&  charpos >= 0)
    {
     if(!strError || strError.length ==0)
      {
        strError = strDefaultError;
      }//if
      return strError + "\n [Error caracter en posicion " + eval(charpos+1)+"]";
    }//if
 return ret;
}

function TestEmail(objValue,strError)
{
        var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
		
     if(objValue.value.length > 0 && !validateEmail(objValue.value))
     {
       if(!strError || strError.length ==0)
       {
          strError = objValue.name+": Ingrese un E-mail Valido ";
       }//if
       return strError;
     }//if
        return ret;
}

function TestLessThan(objValue,strLessThan,strError)
{
        var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
		
        var tmp_objValue = unformat(objValue.value);
        if(isNaN(tmp_objValue))
        {
                alert(objValue.name+": Should be a number ");
                ret = false;
        }//if
        else
                if(eval(tmp_objValue) >=  eval(strLessThan))
                {
                        if(!strError || strError.length ==0)
                {
                          strError = objValue.name + " : value should be less than "+ strLessThan;
                }//if
                return strError;
        }//if
        return ret;
}

function TestGreaterThan(objValue,strGreaterThan,strError)
{

     var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }
	 
     var tmp_objValue = unformat(objValue.value);
     if(isNaN(tmp_objValue))
     {
       alert(objValue.name+": Should be a number ");
       ret = false;
     }//if
         else
     if(eval(tmp_objValue) <=  eval(strGreaterThan))
      {
        if(!strError || strError.length ==0)
        {
          strError = objValue.name + " : value should be greater than "+ strGreaterThan;
        }//if
        return strError;
      }//if
        return ret;
}

function TestRegExp(objValue,strRegExp,strError)
{
        var ret = '';
    if( objValue.value.length > 0 &&
        !objValue.value.match(strRegExp) )
    {
      if(!strError || strError.length ==0)
      {
        strError = objValue.name+": Invalid characters found ";
      }//if
      return strError;
    }//if
        return ret;
}
function TestDontSelect(objValue,index,strError)
{
        var ret = '';
     if(objValue.selectedIndex == null)
     {
       return "BUG: dontselect command for non-select Item";
     }
         else
     if(objValue.selectedIndex == eval(index))
     {
      if(!strError || strError.length ==0)
       {
       strError = objValue.name+": Please Select one option ";
       }//if
                return strError;
      }
        return ret;
}

function TestSelectOneRadio(objValue,strError)
{
        var objradio = objValue.form.elements[objValue.name];
        var one_selected='';
        for(var r=0;r < objradio.length;r++)
        {
          if(objradio[r].checked == "1")
          {
                      one_selected=true;
                break;
          }
        }
        if(false == one_selected)
        {
      if(!strError || strError.length ==0)
       {
            strError = "Please select one option from "+objValue.name;
           }
          return strError;
        }
return one_selected;
}

function Testbetween(objValue,strBetwee,strError)
{
        var ret = '';
    var f=objValue.form.name;
    f=f.replace('frm_','');
    var celda=document.getElementById('celda_'+f+'_'+objValue.id);
    if (celda.style.display=='none')
    {
      return ret;
    }

        var andpos = strBetwee.search("and");
        var minval  = 0;
        var maxval  = 0;
        if(andpos >= 0)
        {
                minval  = strBetwee.substring(0,andpos);
            maxval = strBetwee.substr(andpos+3);
        }
        if(isNaN(objValue.value))
        {
                return objValue.name+": Should be a number ";
        }//if
        if(eval(objValue.value) <  eval(minval) ||  eval(objValue.value) >  eval(maxval))
        {
                   if(!strError || strError.length ==0)
                   {
                    strError = objValue.name + " : value should be entre  "+ strBetwee;
                   }//if
                        return strError;
        }//if

    return ret;
}

function _isInteger(val) {
        var digits="1234567890";
        for (var i=0; i < val.length; i++) {
                if (digits.indexOf(val.charAt(i))==-1) { return false; }
                }
        return true;
        }
function _getInt(str,i,minlength,maxlength) {
        for (var x=maxlength; x>=minlength; x--) {
                var token=str.substring(i,i+x);
                if (token.length < minlength) { return null; }
                if (_isInteger(token)) { return token; }
                }
        return null;
        }


function getDateFromFormat(val,format) {
        val=val+"";
        format=format+"";
        var i_val=0;
        var i_format=0;
        var c="";
        var token="";
        var token2="";
        var x,y;
        var now=new Date();
        var year=now.getYear();
        var month=now.getMonth()+1;
        var date=1;
        var hh=now.getHours();
        var mm=now.getMinutes();
        var ss=now.getSeconds();
        var ampm="";

        while (i_format < format.length) {
                // Get next token from format string
                c=format.charAt(i_format);
                token="";
                while ((format.charAt(i_format)==c) && (i_format < format.length)) {
                        token += format.charAt(i_format++);
                        }
                // Extract contents of value based on format token
                if (token=="yyyy" || token=="yy" || token=="y") {
                        if (token=="yyyy") { x=4;y=4; }
                        if (token=="yy")   { x=2;y=2; }
                        if (token=="y")    { x=2;y=4; }
                        year=_getInt(val,i_val,x,y);
                        if (year==null) { return 0; }
                        i_val += year.length;
                        if (year.length==2) {
                                if (year > 70) { year=1900+(year-0); }
                                else { year=2000+(year-0); }
                                }
                        }
                else if (token=="MMM"||token=="NNN"){
                        month=0;
                        for (var i=0; i<MONTH_NAMES.length; i++) {
                                var month_name=MONTH_NAMES[i];
                                if (val.substring(i_val,i_val+month_name.length).toLowerCase()==month_name.toLowerCase()) {
                                        if (token=="MMM"||(token=="NNN"&&i>11)) {
                                                month=i+1;
                                                if (month>12) { month -= 12; }
                                                i_val += month_name.length;
                                                break;
                                                }
                                        }
                                }
                        if ((month < 1)||(month>12)){return 0;}
                        }
                else if (token=="EE"||token=="E"){
                        for (var i=0; i<DAY_NAMES.length; i++) {
                                var day_name=DAY_NAMES[i];
                                if (val.substring(i_val,i_val+day_name.length).toLowerCase()==day_name.toLowerCase()) {
                                        i_val += day_name.length;
                                        break;
                                        }
                                }
                        }
                else if (token=="MM"||token=="M") {
                        month=_getInt(val,i_val,token.length,2);
                        if(month==null||(month<1)||(month>12)){return 0;}
                        i_val+=month.length;}
                else if (token=="dd"||token=="d") {
                        date=_getInt(val,i_val,token.length,2);
                        if(date==null||(date<1)||(date>31)){return 0;}
                        i_val+=date.length;}
                else if (token=="hh"||token=="h") {
                        hh=_getInt(val,i_val,token.length,2);
                        if(hh==null||(hh<1)||(hh>12)){return 0;}
                        i_val+=hh.length;}
                else if (token=="HH"||token=="H") {
                        hh=_getInt(val,i_val,token.length,2);
                        if(hh==null||(hh<0)||(hh>23)){return 0;}
                        i_val+=hh.length;}
                else if (token=="KK"||token=="K") {
                        hh=_getInt(val,i_val,token.length,2);
                        if(hh==null||(hh<0)||(hh>11)){return 0;}
                        i_val+=hh.length;}
                else if (token=="kk"||token=="k") {
                        hh=_getInt(val,i_val,token.length,2);
                        if(hh==null||(hh<1)||(hh>24)){return 0;}
                        i_val+=hh.length;hh--;}
                else if (token=="mm"||token=="m") {
                        mm=_getInt(val,i_val,token.length,2);
                        if(mm==null||(mm<0)||(mm>59)){return 0;}
                        i_val+=mm.length;}
                else if (token=="ss"||token=="s") {
                        ss=_getInt(val,i_val,token.length,2);
                        if(ss==null||(ss<0)||(ss>59)){return 0;}
                        i_val+=ss.length;}
                else if (token=="aa") {
                        if (val.substring(i_val,i_val+2).toLowerCase()=="am") {ampm="AM";}
                        else if (val.substring(i_val,i_val+2).toLowerCase()=="pm") {ampm="PM";}
                        else {return 0;}
                        i_val+=2;}
                else {
                        if (val.substring(i_val,i_val+token.length)!=token) {return 0;}
                        else {i_val+=token.length;}
                        }
                }
        // If there are any trailing characters left in the value, it doesn't match
        if (i_val != val.length) { return 0; }
        // Is date valid for month?
        if (month==2) {
                // Check for leap year
                if ( ( (year%4==0)&&(year%100 != 0) ) || (year%400==0) ) { // leap year
                        if (date > 29){ return 0; }
                        }
                else { if (date > 28) { return 0; } }
                }
        if ((month==4)||(month==6)||(month==9)||(month==11)) {
                if (date > 30) { return 0; }
                }
        // Correct hours value
        if (hh<12 && ampm=="PM") { hh=hh-0+12; }
        else if (hh>11 && ampm=="AM") { hh-=12; }
        var newdate=new Date(year,month-1,date,hh,mm,ss);
        return newdate.getTime();
        }


function TestDate(objValue,strFormat,strError)
{
    var ret = '';
    if(objValue.value.length <= 0) return ret;
    var date=getDateFromFormat(objValue.value,strFormat);
    if (date==0)
    {
            if(!strError || strError.length ==0)
            {
                strError = objValue.name + " : value should be entre  "+ strFormat;
            }//if
            return strError;
    }
    return ret;
}



//*  Checks each field in a form
function validateInput(strValidateStr,objID,strError,AlertLevel,formobj)
{
    var ret = '';
    var epos = strValidateStr.search(":");
    var  command  = "";
    var  cmdvalue = "";

    if (objID)
    {
      if (formobj)
      {
        var objValue = formobj[objID];
      }
      else var objValue = document.getElementById(objID);
    }
    else
    {
      var objValue = document.getElementById(objID);
    }

    if(epos >= 0)
    {
     command  = strValidateStr.substring(0,epos);
     cmdvalue = strValidateStr.substr(epos+1);
    }
    else
    {
     command = strValidateStr;
    }

    command = command.toLowerCase();

    switch(command)
    {
        case "req":
        case "requerido":
        case "required":
         {
            //alert(objValue.name +':'+objValue.value);
            ret = TestRequiredInput(objValue,strError)
           break;
         }
        case "maxlength":
        case "maxlen":
          {
          	ret = TestMaxLen(objValue,cmdvalue,strError)
            break;
          }
        case "minlength":
        case "minlen":
           {
                   ret = TestMinLen(objValue,cmdvalue,strError)
            break;
           }
        case "alnum":
        case "alphanumeric":
           {
                        ret = TestInputType(objValue,"[^A-Za-z0-9]",strError,
            objValue.name+": Only alpha-numeric characters allowed ");
                   break;
           }
        case "alnum_s":
        case "alphanumeric_space":
           {
                   ret = TestInputType(objValue,"[^A-Za-z0-9\\s]",strError,
                   objValue.name+": Only alpha-numeric characters and space allowed ");
                break;
           }
        case "num":
        case "numeric":
           {
           ret = TestInputType(objValue,"[^0-9]",strError,
           objValue.name+": Only digits allowed ");
           break;
           }
        case "dt":
        case "date":
           {
            if(cmdvalue=='') cmdvalue = 'dd/MM/yyyy';
            ret = TestDate(objValue,cmdvalue,strError);
            break;
           }

        case "unico":
           {

            ret = TestUnico(objValue,cmdvalue,strError);
            break;
           }

        case "alphabetic":
        case "alpha":
           {
                   ret = TestInputType(objValue,"[^A-Za-z]",strError,
                        objValue.name+": Only alphabetic characters allowed ");
            break;
           }
        case "alphabetic_space":
        case "alpha_s":
           {
                   ret = TestInputType(objValue,"[^A-Za-z\\s]",strError,
                objValue.name+": Only alphabetic characters and space allowed ");
            break;
           }
        case "email":
          {
                  ret = TestEmail(objValue,strError);
            break;
          }
        case "ls":
        case "<":
         {
                 ret = TestLessThan(objValue,cmdvalue,strError);
            break;
         }
        case "gt":
        case ">":
         {
                 ret = TestGreaterThan(objValue,cmdvalue,strError);
            break;
         }
         case "bt":
         case "between":
         {
            ret = Testbetween(objValue,cmdvalue,strError);
            break;
         }//case between

        case "regexp":
         {
                 ret = TestRegExp(objValue,cmdvalue,strError);
           break;
         }
        case "dontselect":
         {
                 ret = TestDontSelect(objValue,cmdvalue,strError)
            break;
         }
            case "dontselectchk":
            {
                    ret = TestDontSelectChk(objValue,cmdvalue,strError)
                break;
            }
            case "selmin":
            {
                    ret = TestSelMin(objValue,cmdvalue,strError);
                break;
            }
            case "selone":
            {
                    ret = TestSelectOneRadio(objValue,strError);
                break;
            }
            //Comparisons
            case "I=":
            case "I<":
            case "I<=":
            case "I>":
            case "I>=":
            case "I<>":
            {
                ret= TestComparison(objValue,cmdvalue,command,strError);
                break;
            }
    }//switch
    if(ret.length){
                if(AlertLevel)
                {
                        return confirm(ret +  "\nDesea continuar con el proceso")
                }
        else
        {
                alert(ret);
                return false;
        }
    }
    return true;
}

function isInteger(val)
{
        if (isBlank(val))
    {
            return false;
    }
    for(var i=0;i<val.length;i++)
    {
            if(!isDigit(val.charAt(i)))
        {
                return false;
        }
    }
    return true;
}

function isNumeric(val)
{
        return(parseFloat(val,10)==(val*1));
}

function isDigit(num)
{
        if (num.length>1)
    {
       return false;
    }
    var string="1234567890";
    if (string.indexOf(num)!=-1)
    {
            return true;
    }
    return false;
}

/*************** ENMASCARAMIENTO ***************  CODE CHAR *****************/

function inputMasking(evt)
{

    var evt  = window.event || e;
    var Elem = evt.srcElement || evt.target;


  if (isIE && IEVersion > 4)
  {
    if (window.event.altKey) return false;
    if (window.event.ctrlKey) return false;

    if (typeof(Elem.mascara) == "string")
    {
      var mask = Elem.mascara;
      var keycode = window.event.keyCode;
      Elem.value = applyMask(keycode, mask, Elem.value);
    }
    if(window.event.keyCode==13) handleEnter(Elem);
    return false;
  } else if (isNN && NNVersion<6)
  {
    if (evt.ALT_MASK) return false;
    if (evt.CONTROL_MASK) return false;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      this.value = applyMask(keycode, mask, this.value);
    }
    if(evt.which==13) handleEnter(this);
    return false;

    //return (evt.which==13?true:false);
  } else if (isNN && NNVersion==6)
  {

    if (evt.altKey) return false;
    if (evt.ctrlKey) return false;
    var cancelKey = evt.which==13;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      if (keycode >= 32)
        this.value = applyMaskToValue(mask, this.value);
    }
    if(cancelKey ==true &&  evt.which ==13){
         handleEnter(this);
         return false;
    }
    return cancelKey || evt.which==13;
  } else if (isNN && NNVersion==7)
  {
    if (evt.altKey) return false;
    if (evt.ctrlKey) return false;
    var cancelKey = evt.which==13;
    if (typeof(this.ccsInputMask) == "string")
    {
      var mask = this.ccsInputMask;
      var keycode = evt.which;
      cancelKey = keycode < 32;
      if (!cancelKey)
        this.value = applyMask(keycode, mask, this.value);
    }

    if(cancelKey ==true &&  evt.which ==13){
         handleEnter(this);
         return false;
    }
    return cancelKey || evt.which==13;
  } else
    return true;
}

function applyMaskToValue(mask, value) {
  var oldValue = String(value);
  var newValue = "";
  for (var i=0; i<oldValue.length; i++)
  {
    newValue = applyMask(oldValue.charCodeAt(i), mask, newValue);
  }
  return newValue;
}

function applyMask(keycode, mask, value) {
  var digit = (keycode >= 48 && keycode <= 57);
  var plus = (keycode == 43);
  var dash = (keycode == 45);
  var space = (keycode == 32);
  var uletter = (keycode >= 65 && keycode <= 90);
  var lletter = (keycode >= 97 && keycode <= 122);
  var pos = value.length;
  
  switch(mask.charAt(pos)) {
    case "0":
      if (digit)
        value += String.fromCharCode(keycode);
      break;
    case "A":
        value += String.fromCharCode(keycode);
      break;
    case "L":
      if (uletter || lletter)
        value += String.fromCharCode(keycode);
      break;
    default:
      var isMatchMask = (String.fromCharCode(keycode) == mask.charAt(pos));
      while (pos < mask.length && mask.charAt(pos) != "0" && mask.charAt(pos) != "L")
        value += mask.charAt(pos++);
      if (!isMatchMask && pos < mask.length)
        value = applyMask(keycode, mask, value);
  }
  
  return value;
}

function stringToRegExp(string, arg)
{
  var str = String(string);
  str = str.replace(/\\/g,"\\\\");
  str = str.replace(/\//g,"\\/");
  str = str.replace(/\./g,"\\.");
  str = str.replace(/\(/g,"\\(");
  str = str.replace(/\)/g,"\\)");
  str = str.replace(/\[/g,"\\[");
  str = str.replace(/\]/g,"\\]");
  return str;
}

function checkDate(dateValue, dateFormat)
{
  var DateMasks = new Array(
                    new Array("MMMM", "[a-z]+"),
                    new Array("mmmm", "[a-z]+"),
                    new Array("yyyy", "[0-9]{4}"),
                    new Array("MMM", "[a-z]+"),
                    new Array("mmm", "[a-z]+"),
                    new Array("HH", "([0-1][0-9]|2[0-4])"),
                    new Array("hh", "(0[1-9]|1[0-2])"),
                    new Array("dd", "([0-2][0-9]|3[0-1])"),
                    new Array("MM", "(0[1-9]|1[0-2])"),
                    new Array("mm", "(0[1-9]|1[0-2])"),
                    new Array("yy", "[0-9]{2}"),
                    new Array("nn", "[0-5][0-9]"),
                    new Array("ss", "[0-5][0-9]"),
                    new Array("w", "[1-7]"),
                    new Array("d", "([1-9]|[1-2][0-9]|3[0-1])"),
                    new Array("y", "([1-2][0-9]{0,2}|3([0-5][0-9]|6[0-5]))"),
                    new Array("H", "(00|0?[1-9]|1[0-9]|2[0-4])"),
                    new Array("h", "(0?[1-9]|1[0-2])"),
                    new Array("M", "(0?[1-9]|1[0-2])"),
                    new Array("m", "(0?[1-9]|1[0-2])"),
                    new Array("n", "[0-5]?[0-9]"),
                    new Array("s", "[0-5]?[0-9]"),
                    new Array("q", "[1-4]")
                  );
  var regExp = "^"+stringToRegExp(dateFormat)+"$";
  for (var i=0; i<DateMasks.length; i++)
  {
    regExp = regExp.replace(DateMasks[i][0], DateMasks[i][1]);
  }
  var regExp = new RegExp(regExp,"i");
  return String(dateValue).search(regExp)!=-1;
}

function GetValue(control) {
    if (typeof(control.value) == "string") {
        return control.value;
    }
    if (typeof(control.tagName) == "undefined" && typeof(control.length) == "number") {
        var j;
        for (j=0; j < control.length; j++) {
            var inner = control[j];
            if (typeof(inner.value) == "string" && (inner.type != "radio" || inner.status == true)) {
                return inner.value;
            }
        }
    }
    else {
        return GetValueRecursive(control);
    }
    return "";
}

function GetValueRecursive(control)
{
    if (typeof(control.value) == "string" && (control.type != "radio" || control.status == true)) {
        return control.value;
    }
    var i, val;
    for (i = 0; i<control.children.length; i++) {
        val = GetValueRecursive(control.children[i]);
        if (val != "") return val;
    }
    return "";
}


/****************************************************************************************/
//--------------------------------------- MASKING

/****************************************************************************************/


function _MaskAPI(){
        this.version = "0.4a";
        this.instances = 0;
        this.objects = {};
}
MaskAPI = new _MaskAPI();

function Mask(m, t){
        this.mask = m.toLowerCase();
        this.type = (typeof t == "string") ? t : "string";
        this.error = [];
        this.errorCodes = [];
        this.value = "";
        this.strippedValue = "";
        this.allowPartial = false;
        this.id = MaskAPI.instances++;
        this.ref = "MaskAPI.objects['" + this.id + "']";
        MaskAPI.objects[this.id] = this;
}

// define the attach(oElement) function
Mask.prototype.attach = function (o){
        $addEvent(o, "onkeydown", "return " + this.ref + ".isAllowKeyPress(event, this);", true);
        $addEvent(o, "onkeyup", "return " + this.ref + ".getKeyPress(event, this);", true);
        $addEvent(o, "onblur", "this.value = " + this.ref + ".format(this.value);", true);
}

Mask.prototype.isAllowKeyPress = function (e, o){

    if( this.type != "string" ) return true;
    var xe = new qEvent(e);

    if (o.readOnly && (xe.keyCode != 13) && (xe.keyCode != 27) && (xe.keyCode != 9))
    {
      if (window.event)
      {
        var evt = window.event || e;
        cancelaTecla(evt);
      }
    }

        if( ((xe.keyCode > 47) && (o.value.length >= this.mask.length)) && !xe.ctrlKey ) return false;
        return true;
}

Mask.prototype.getKeyPress = function (e, o, _u){
        this.allowPartial = true;
        var xe = new qEvent(e);

//        var k = String.fromCharCode(xe.keyCode);

        if( (xe.keyCode > 47) || (_u == true) || (xe.keyCode == 8 || xe.keyCode == 46) ){
                var v = o.value, d;
                if( xe.keyCode == 8 || xe.keyCode == 46 ) d = true;
                else d = false

                if( this.type == "number" ) this.value = this.setNumber(v, d);
                else if( this.type == "date" ) this.value = this.setDateKeyPress(v, d);
                else this.value = this.setGeneric(v, d);

                o.value = this.value;
        }
        /* */

        this.allowPartial = false;
        return true;
}

Mask.prototype.format = function (s){
        if( this.type == "number" ) this.value = this.setNumber(s);
        else if( this.type == "date" ) this.value = this.setDate(s);
        else this.value = this.setGeneric(s);
        return this.value;
}

Mask.prototype.throwError = function (c, e, v){
        this.error[this.error.length] = e;
        this.errorCodes[this.errorCodes.length] = c;
        if( typeof v == "string" ) return v;
        return true;
}

Mask.prototype.setGeneric = function (_v, _d){
        var v = _v, m = this.mask;
        var r = "x#*", rt = [], nv = "", t, x, a = [], j=0, rx = {"x": "A-Za-z", "#": "0-9", "*": "A-Za-z0-9" };

        // strip out invalid characters
        v = v.replace(new RegExp("[^" + rx["*"] + "]", "gi"), "");
        if( (_d == true) && (v.length == this.strippedValue.length) ) v = v.substring(0, v.length-1);
        this.strippedValue = v;
        var b=[];
        for( var i=0; i < m.length; i++ ){
                // grab the current character
                x = m.charAt(i);
                // check to see if current character is a mask, escape commands are not a mask character
                t = (r.indexOf(x) > -1);
                // if the current character is an escape command, then grab the next character
                if( x == "!" ) x = m.charAt(i++);
                // build a regex to test against
                if( (t && !this.allowPartial) || (t && this.allowPartial && (rt.length < v.length)) ) rt[rt.length] = "[" + rx[x] + "]";
                // build mask definition table
                a[a.length] = { "chr": x, "mask": t };
        }

        var hasOneValidChar = false;
        // if the regex fails, return an error
        if( !this.allowPartial && !(new RegExp(rt.join(""))).test(v) ) return this.throwError(1, "The value \"" + _v + "\" must be in the format " + this.mask + ".", _v);
        // loop through the mask definition, and build the formatted string
        else if( (this.allowPartial && (v.length > 0)) || !this.allowPartial ){
                for( i=0; i < a.length; i++ ){
                        if( a[i].mask ){
                                while( v.length > 0 && !(new RegExp(rt[j])).test(v.charAt(j)) ) v = (v.length == 1) ? "" : v.substring(1);
                                if( v.length > 0 ){
                                        nv += v.charAt(j);
                                        hasOneValidChar = true;
                                }
                                j++;
                        } else nv += a[i].chr;
                        if( this.allowPartial && (j > v.length) ) break;
                }
        }

        if( this.allowPartial && !hasOneValidChar ) nv = "";
        if( this.allowPartial ){
                if( nv.length < a.length ) this.nextValidChar = rx[a[nv.length].chr];
                else this.nextValidChar = null;
        }

        return nv;
}

Mask.prototype.setNumber = function(_v, _d){
        var v = String(_v).replace(/[^\d.-]*/gi, ""), m = this.mask;
        // make sure there's only one decimal point
        v = v.replace(/\./, "d").replace(/\./g, "").replace(/d/, ".");

        // check to see if an invalid mask operation has been entered
        if( !/^[\$]?((\$?[\+-]?([0#]{1,3},)?[0#]*(\.[0#]*)?)|([\+-]?\([\+-]?([0#]{1,3},)?[0#]*(\.[0#]*)?\)))$/.test(m) )
                return this.throwError(1, "An invalid mask was specified for the \nMask constructor.", _v);

        if( (_d == true) && (v.length == this.strippedValue.length) ) v = v.substring(0, v.length-1);

        if( this.allowPartial && (v.replace(/[^0-9]/, "").length == 0) ) return v;
        this.strippedValue = v;

        if( v.length == 0 ) v = NaN;
        var vn = Number(v);
        if( isNaN(vn) ) return this.throwError(2, "The value entered was not a number.", _v);

        // if no mask, stop processing
        if( m.length == 0 ) return v;

        // get the value before the decimal point
        var vi = String(Math.abs((v.indexOf(".") > -1 ) ? v.split(".")[0] : v));
        // get the value after the decimal point
        var vd = (v.indexOf(".") > -1) ? v.split(".")[1] : "";
        var _vd = vd;

        var isNegative = (vn != 0 && Math.abs(vn)*-1 == vn);

        // check for masking operations
        var show = {
                "$" : /^[\$]/.test(m),
                "(": (isNegative && (m.indexOf("(") > -1)),
                "+" : ( (m.indexOf("+") != -1) && !isNegative )
        }
        show["-"] = (isNegative && (!show["("] || (m.indexOf("-") != -1)));


        // replace all non-place holders from the mask
        m = m.replace(/[^#0.,]*/gi, "");

        /*
                make sure there are the correct number of decimal places
        */
        // get number of digits after decimal point in mask
        var dm = (m.indexOf(".") > -1 ) ? m.split(".")[1] : "";
        if( dm.length == 0 ){
                vi = String(Math.round(Number(vi)));
                vd = "";
        } else {
                // find the last zero, which indicates the minimum number
                // of decimal places to show
                var md = dm.lastIndexOf("0")+1;
                // if the number of decimal places is greater than the mask, then round off
                if( vd.length > dm.length ) vd = String(Math.round(Number(vd.substring(0, dm.length + 1))/10));
                // otherwise, pad the string w/the required zeros
                else while( vd.length < md ) vd += "0";
        }

        /*
                pad the int with any necessary zeros
        */
        // get number of digits before decimal point in mask
        var im = (m.indexOf(".") > -1 ) ? m.split(".")[0] : m;
        im = im.replace(/[^0#]+/gi, "");
        // find the first zero, which indicates the minimum length
        // that the value must be padded w/zeros
        var mv = im.indexOf("0")+1;
        // if there is a zero found, make sure it's padded
        if( mv > 0 ){
                mv = im.length - mv + 1;
                while( vi.length < mv ) vi = "0" + vi;
        }


        /*
                check to see if we need commas in the thousands place holder
        */
        if( /[#0]+,[#0]{3}/.test(m) ){
                // add the commas as the place holder
                var x = [], i=0, n=Number(vi);
                while( n > 999 ){
                        x[i] = "00" + String(n%1000);
                        x[i] = x[i].substring(x[i].length - 3);
                        n = Math.floor(n/1000);
                        i++;
                }
                x[i] = String(n%1000);
                vi = x.reverse().join(",");
        }


        /*
                combine the new value together
        */
        if( (vd.length > 0 && !this.allowPartial) || ((dm.length > 0) && this.allowPartial && (v.indexOf(".") > -1) && (_vd.length >= vd.length)) ){
                v = vi + "." + vd;
        } else if( (dm.length > 0) && this.allowPartial && (v.indexOf(".") > -1) && (_vd.length < vd.length) ){
                v = vi + "." + _vd;
        } else {
                v = vi;
        }

        if( show["$"] ) v = this.mask.replace(/(^[\$])(.+)/gi, "$") + v;
        if( show["+"] ) v = "+" + v;
        if( show["-"] ) v = "-" + v;
        if( show["("] ) v = "(" + v + ")";
        return v;
}

Mask.prototype.setDate = function (_v){
        var v = _v, m = this.mask;
        var a, e, mm, dd, yy, x, s;

        // split mask into array, to see position of each day, month & year
        a = m.split(/[^mdy]+/);
        // split mask into array, to get delimiters
        s = m.split(/[mdy]+/);
        // convert the string into an array in which digits are together
        e = v.split(/[^0-9]/);

        if( s[0].length == 0 ) s.splice(0, 1);

        for( var i=0; i < a.length; i++ ){
                x = a[i].charAt(0).toLowerCase();
                if( x == "m" ) mm = parseInt(e[i], 10)-1;
                else if( x == "d" ) dd = parseInt(e[i], 10);
                else if( x == "y" ) yy = parseInt(e[i], 10);
        }

        // if year is abbreviated, guess at the year
        if( String(yy).length < 3 ){
                yy = 2000 + yy;
                if( (new Date()).getFullYear()+20 < yy ) yy = yy - 100;
        }

        // create date object
        var d = new Date(yy, mm, dd);

        if( d.getDate() != dd ) return this.throwError(1, "An invalid day was entered.", _v);
        else if( d.getMonth() != mm ) return this.throwError(2, "An invalid month was entered.", _v);

        var nv = "";

        for( i=0; i < a.length; i++ ){
                x = a[i].charAt(0).toLowerCase();
                if( x == "m" ){
                        mm++;
                        if( a[i].length == 2 ){
                                mm = "0" + mm;
                                mm = mm.substring(mm.length-2);
                        }
                        nv += mm;
                } else if( x == "d" ){
                        if( a[i].length == 2 ){
                                dd = "0" + dd;
                                dd = dd.substring(dd.length-2);
                        }
                        nv += dd;
                } else if( x == "y" ){
                        if( a[i].length == 2 ) nv += d.getYear();
                        else nv += d.getFullYear();
                }

                if( i < a.length-1 ) nv += s[i];
        }

        return nv;
}

Mask.prototype.setDateKeyPress = function (_v, _d){
        var v = _v, m = this.mask, k = v.charAt(v.length-1);
        var a, e, c, ml, vl, mm = "", dd = "", yy = "", x, p, z;

        if( _d == true ){
                while( (/[^0-9]/gi).test(v.charAt(v.length-1)) ) v = v.substring(0, v.length-1);
                if( (/[^0-9]/gi).test(this.strippedValue.charAt(this.strippedValue.length-1)) ) v = v.substring(0, v.length-1);
                if( v.length == 0 ) return "";
        }

        // split mask into array, to see position of each day, month & year
        a = m.split(/[^mdy]/);
        // split mask into array, to get delimiters
        s = m.split(/[mdy]+/);
        // mozilla wants to add an empty array element which needs removed
        if( s[0].length == 0 ) s.splice(0,1);
        // convert the string into an array in which digits are together
        e = v.split(/[^0-9]/);
        // position in mask
        p = (e.length > 0) ? e.length-1 : 0;
        // determine what mask value the user is currently entering
        c = a[p].charAt(0);
        // determine the length of the current mask value
        ml = a[p].length;

        for( var i=0; i < e.length; i++ ){
                x = a[i].charAt(0).toLowerCase();
                if( x == "m" ) mm = parseInt(e[i], 10)-1;
                else if( x == "d" ) dd = parseInt(e[i], 10);
                else if( x == "y" ) yy = parseInt(e[i], 10);
        }


        var nv = "";
        var j=0;

        for( i=0; i < e.length; i++ ){
                x = a[i].charAt(0).toLowerCase();

                if( x == "m" ){
                        z = ((/[^0-9]/).test(k) && c == "m");
                        mm++;
                        if( (e[i].length == 2 && mm < 10) || (a[i].length == 2 && c != "m") || (mm > 1 && c == "m") || (z && a[i].length == 2) ){
                                mm = "0" + mm;
                                mm = mm.substring(mm.length-2);
                        }
                        vl = String(mm).length;
                        ml = 2;
                        nv += mm;
                } else if( x == "d" ){
                        z = ((/[^0-9]/).test(k) && c == "d");
                        if( (e[i].length == 2 && dd < 10) || (a[i].length == 2 && c != "d") || (dd > 3 && c == "d") || (z && a[i].length == 2) ){
                                dd = "0" + dd;
                                dd = dd.substring(dd.length-2);
                        }
                        vl = String(dd).length;
                        ml = 2;
                        nv += dd;
                } else if( x == "y" ){
                        z = ((/[^0-9]/).test(k) && c == "y");
                        if( c == "y" ) yy = String(yy);
                        else {
                                if( a[i].length == 2 ) yy = d.getYear();
                                else yy = d.getFullYear();
                        }
                        if( (e[i].length == 2 && yy < 10) || (a[i].length == 2 && c != "y") || (z && a[i].length == 2) ){
                                yy = "0" + yy;
                                yy = yy.substring(yy.length-2);
                        }
                        ml = a[i].length;
                        vl = String(yy).length;
                        nv += yy;
                }

                if( ((ml == vl || z) && (x == c) && (i < s.length)) || (i < s.length && x != c ) ) nv += s[i];
        }

        if( nv.length > m.length ) nv = nv.substring(0, m.length);

        this.strippedValue = (nv == "NaN") ? "" : nv;

        return this.strippedValue;
}

function qEvent(e){
        // routine for NS, Opera, etc DOM browsers
        if( window.Event ){
                var isKeyPress = (e.type.substring(0,3) == "key");

                this.keyCode = (isKeyPress) ? parseInt(e.which, 10) : 0;
                this.button = (!isKeyPress) ? parseInt(e.which, 10) : 0;
                this.srcElement = e.target;
                this.type = e.type;
                this.x = e.pageX;
                this.y = e.pageY;
                this.screenX = e.screenX;
                this.screenY = e.screenY;
                if( document.layers ){
                        this.altKey = ((e.modifiers & Event.ALT_MASK) > 0);
                        this.ctrlKey = ((e.modifiers & Event.CONTROL_MASK) > 0);
                        this.shiftKey = ((e.modifiers & Event.SHIFT_MASK) > 0);
                        this.keyCode = this.translateKeyCode(this.keyCode);
                } else {
                        this.altKey = e.altKey;
                        this.ctrlKey = e.ctrlKey;
                        this.shiftKey = e.shiftKey;
                }
        // routine for Internet Explorer DOM browsers
        } else {
                e = window.event;
                this.keyCode = parseInt(e.keyCode, 10);
                this.button = e.button;
                this.srcElement = e.srcElement;
                this.type = e.type;
                if( document.all ){
                        this.x = e.clientX + document.body.scrollLeft;
                        this.y = e.clientY + document.body.scrollTop;
                } else {
                        this.x = e.clientX;
                        this.y = e.clientY;
                }
                this.screenX = e.screenX;
                this.screenY = e.screenY;
                this.altKey = e.altKey;
                this.ctrlKey = e.ctrlKey;
                this.shiftKey = e.shiftKey;
        }
        if( this.button == 0 ){
                this.setKeyPressed(this.keyCode);
                this.keyChar = String.fromCharCode(this.keyCode);
        }
}

// this method will try to remap the keycodes so the keycode value
// returned will be consistent. this doesn't work for all cases,
// since some browsers don't always return a unique value for a
// key press.
qEvent.prototype.translateKeyCode = function (i){
        var l = {};
        // remap NS4 keycodes to IE/W3C keycodes
        if( !!document.layers ){
                if( this.keyCode > 96 && this.keyCode < 123 ) return this.keyCode - 32;
                l = {
                        96:192,126:192,33:49,64:50,35:51,36:52,37:53,94:54,38:55,42:56,40:57,41:48,92:220,124:220,125:221,
                        93:221,91:219,123:219,39:222,34:222,47:191,63:191,46:190,62:190,44:188,60:188,45:189,95:189,43:187,
                        61:187,59:186,58:186,
                        "null": null
                }
        }
        return (!!l[i]) ? l[i] : i;
}

// try to determine the actual value of the key pressed
qEvent.prototype.setKP = function (i, s){
        this.keyPressedCode = i;
        this.keyNonChar = (typeof s == "string");
        this.keyPressed = (this.keyNonChar) ? s : String.fromCharCode(i);
        this.isNumeric = (parseInt(this.keyPressed, 10) == this.keyPressed);
        this.isAlpha = ((this.keyCode > 64 && this.keyCode < 91) && !this.altKey && !this.ctrlKey);
        return true;
}

// try to determine the actual value of the key pressed
qEvent.prototype.setKeyPressed = function (i){
        var b = this.shiftKey;
        if( !b && (i > 64 && i < 91) ) return this.setKP(i + 32);
        if( i > 95 && i < 106 ) return this.setKP(i - 48);

        switch( i ){
                case 49: case 51: case 52: case 53: if( b ) i = i - 16; break;
                case 50: if( b ) i = 64; break;
                case 54: if( b ) i = 94; break;
                case 55: if( b ) i = 38; break;
                case 56: if( b ) i = 42; break;
                case 57: if( b ) i = 40; break;
                case 48: if( b ) i = 41; break;
                case 192: if( b ) i = 126; else i = 96; break;
                case 189: if( b ) i = 95; else i = 45; break;
                case 187: if( b ) i = 43; else i = 61; break;
                case 220: if( b ) i = 124; else i = 92; break;
                case 221: if( b ) i = 125; else i = 93; break;
                case 219: if( b ) i = 123; else i = 91; break;
                case 222: if( b ) i = 34; else i = 39; break;
                case 186: if( b ) i = 58; else i = 59; break;
                case 191: if( b ) i = 63; else i = 47; break;
                case 190: if( b ) i = 62; else i = 46; break;
                case 188: if( b ) i = 60; else i = 44; break;

                case 106: case 57379: i = 42; break;
                case 107: case 57380: i = 43; break;
                case 109: case 57381: i = 45; break;
                case 110: i = 46; break;
                case 111: case 57378: i = 47; break;

                case 8: return this.setKP(i, "[backspace]");
                case 9: return this.setKP(i, "[tab]");
                case 13: return this.setKP(i, "[enter]");
                case 16: case 57389: return this.setKP(i, "[shift]");
                case 17: case 57390: return this.setKP(i, "[ctrl]");
                case 18: case 57388: return this.setKP(i, "[alt]");
                case 19: case 57402: return this.setKP(i, "[break]");
                case 20: return this.setKP(i, "[capslock]");
                case 32: return this.setKP(i, "[space]");
                case 91: return this.setKP(i, "[windows]");
                case 93: return this.setKP(i, "[properties]");

                case 33: case 57371: return this.setKP(i*-1, "[pgup]");
                case 34: case 57372: return this.setKP(i*-1, "[pgdown]");
                case 35: case 57370: return this.setKP(i*-1, "[end]");
                case 36: case 57369: return this.setKP(i*-1, "[home]");
                case 37: case 57375: return this.setKP(i*-1, "[left]");
                case 38: case 57373: return this.setKP(i*-1, "[up]");
                case 39: case 57376: return this.setKP(i*-1, "[right]");
                case 40: case 57374: return this.setKP(i*-1, "[down]");
                case 45: case 57382: return this.setKP(i*-1, "[insert]");
                case 46: case 57383: return this.setKP(i*-1, "[delete]");
                case 144: case 57400: return this.setKP(i*-1, "[numlock]");
        }

        if( i > 111 && i < 124 ) return this.setKP(i*-1, "[f" + (i-111) + "]");

        return this.setKP(i);
}

// define the addEvent(oElement, sEvent, sCmd, bAppend) function
function $addEvent(o, _e, c, _b){
        var e = _e.toLowerCase(), b = (typeof _b == "boolean") ? _b : true, x = (o[e]) ? o[e].toString() : "";
        // strip out the body of the function
        x = x.substring(x.indexOf("{")+1, x.lastIndexOf("}"));
        x = ((b) ? (x + c) : (c + x)) + "\n";
        return o[e] = (!!window.Event) ? new Function("event", x) : new Function(x);
}

//***************************************************************************************

function applyFormat(e)
{
  var evt  = window.event || e;
  var Elem = evt.srcElement || evt.target;
  var j = Elem.value;
  Elem.value = Elem.mask.format(j);
};

/******** AGREGADO POR MARCO PARA EL EDITOR DE FECHAS AGOSTO 2008 *************/

_datesep = '/';
_nofecha = '  '+_datesep+'  '+_datesep+'    ';

_maxano  = 2100;
_released = true;

function setDateEditor(elem)
{
  elem.className = 'campo_fecha_inactivo';
  elem.size      = (elem.size < 10?10:elem.size);
  elem.maxLength = 10;
  elem.onfocus   = onfcs;
  elem.onkeydown = onkdn;
  elem.onkeyup   = onkup;
  elem.onmouseup = onmup;
  elem.onblur    = onblr;
  elem.released  = true;
  if (elem.value) setValue(elem,completaFecha(elem.value));
}

function setValue(elem, v)
{
  var val=patronDiaValido(v);
  if (!val)
  {
    elem.alt='';
    return false;
  }
  var d=new Date(val.dia,val.mes-1,val.ano);
  switch (d.getDay())
  {
    case 0:
      elem.alt='domingo';
      break;
    case 1:
      elem.alt='lunes';
      break;
    case 2:
      elem.alt='martes';
      break;
    case 3:
      elem.alt='miercoles';
      break;
    case 4:
      elem.alt='jueves';
      break;
    case 5:
      elem.alt='viernes';
      break;
    case 6:
      elem.alt='sabado';
      break;
    default:
      elem.alt='';
  }
}

function sel(elem,inicio,fin)
{
	if(elem.type=='date' || elem.type=='time') return;
  if(typeof document.selection != 'undefined' && document.selection)
  {
    tex=elem.value;
    elem.value='';
    try {elem.focus();} catch(e) {} finally {}
    var str = document.selection.createRange();
    elem.value=tex;
    str.move('character', inicio);
    str.moveEnd('character', fin-inicio);
    str.select();
  }
  else if(typeof elem.selectionStart != 'undefined')
  {
    elem.setSelectionRange(inicio,fin);
    elem.focus();
  }
}


function getCursorPos(campo)
{
  if (document.selection)
  {
     campo.focus();
     var oSel = document.selection.createRange();
     oSel.moveStart('character', -campo.value.length);
     campo.selectionEnd = oSel.text.length;
     oSel.setEndPoint('EndToStart', document.selection.createRange() );
     campo.selectionStart = oSel.text.length;
  }
  return {start: campo.selectionStart, end: campo.selectionEnd};
}

function getCharAtCursor(campo)
{
  var c=getCursorPos(campo);
  return campo.value.substr(c.start,1);
}

function onkdn(e)
{
  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;
  var KeyCode = evt.keyCode    || evt.which;
  var s;

  if (elem.readOnly) return false;

  v=elem.value;
  if (KeyCode==123&&(v==_nofecha||!v))
  {
    v = '';
    elem.value='';
    return false;
  }

  if (!_released)
  {
    onkup(e);
  }
  _released=false;

  cp=getCursorPos(elem);
  s=cp.start;

  if (KeyCode==8)
  {
    if (s<10) elem.value=elem.value.substr(0,s)+' '+elem.value.substring(s+1,10);
    else elem.value=elem.value.substr(0,elem.value.length-1)+' ';
    if (s==6||s==5||s==3) s--;
    sel(elem,s-1,s);
    cancelaTecla(evt);
    return false;
  }

  if (KeyCode==46)
  {
    var v=elem.value;
    v=v.substring(0,s);
    v=completaFecha(v);
    elem.value=v;
    sel(elem,s,s+1);
    cancelaTecla(evt);
    return false;
  }

  if (KeyCode==123)
  {
    onblr(e);
  }

  if ((KeyCode<48||KeyCode>57)&&KeyCode!=13&&(KeyCode<96||KeyCode>105)&&KeyCode!=39&&KeyCode!=37&&KeyCode!=36&&KeyCode!=35&&KeyCode!=9&&(KeyCode<112||KeyCode>123)&&KeyCode!=27)
  {
    if (KeyCode==107||KeyCode==187) fechaDif(elem,1);        /* signo (+) */
    else if (KeyCode==109||KeyCode==189) fechaDif(elem,-1);  /* signo (-) */
    cancelaTecla(evt);
  }

}


function onkup(e)
{
  _released=true;

  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;
  var KeyCode = evt.keyCode    || evt.which;

  if (elem.readOnly) return false;

  var p=getCursorPos(elem);
  var cp=p.start;
  var nd=p.end;

  v=elem.value;
  if (KeyCode==37) cp--;

  //document.selection.empty();
  clearSel();

  if (cp==2||cp==5)
  {
    if (KeyCode!=37) cp++;
    else cp--;
  }
  if (cp==10) cp--;
  sel(elem,cp,cp+1);
}

function onmup(e)
{
  var evt     = window.event   || e;
  var elem    = this;

  var cp = getCursorPos(elem).start-1;
  clearSel();
  if (cp==2||cp==5) cp++;
  if (cp==10) cp--;
  sel(elem,cp,cp+1);
}

function maxDays(m,y)
{
  var md=new Date(y,m+1,0).getDate();
  return parseFloat(md);
}

function patronDiaValido(v)
{
  if (v==_nofecha||!v) return false;
  var patron = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/
  var acomp = v.match(patron);
  if (acomp==null) return false;
  var dia=parseFloat(acomp[1]);
  var mes=parseFloat(acomp[3]);
  var ano=parseFloat(acomp[4]);
  if (mes<1||mes>12||dia<1||dia>31) return false;
  if (dia>maxDays(parseFloat(mes)-1,parseFloat(ano))) return false;
  return {'dia':dia,'mes':mes,'ano':ano};
}

function incDate(v,d,m,y)
{
  if (!d) d=0;
  if (!m) m=0;
  if (!y) y=0;

  if (!d&&!m&&!y) return false;
  var val=patronDiaValido(v);
  if (!val) return false;

  var dx=new Date(val.ano+y,val.mes-1+m,val.dia+d);

  dia=parseFloat(dx.getDate());
  mes=parseFloat(dx.getMonth())+1;
  ano=parseFloat(dx.getYear());

  if (dia<10) dia='0'+dia;
  if (mes<10) mes='0'+mes;

  return dia+_datesep+mes+_datesep+ano;
}

function fechaDif(elem,dd,mm,yy)
{
  var v=incDate(elem.value,dd,mm,yy);
  if (v)
  {
    elem.value=v;
    if (dd) sel(elem,0,1);
    if (mm) sel(elem,3,1);
    if (yy) sel(elem,6,1);
  }
}

function onblr(e)
{
  var evt  = window.event   || e;
  var elem = evt.srcElement || evt.target;
  var v=elem.value;
  if(elem.type='date') return;
  var xd=new Date();
  var dd=xd.getDate();
  var xm=xd.getMonth()+1;
  var xy=xd.getFullYear();

  if (v==_nofecha||!v)
  {
    v = '';
    elem.value='';
    elem.className='campo_fecha_inactivo';
    return false;
  }
  else
  {
    //-- corregir a�o
    var y=v.substr(6,4);
    y=parseFloat(y);
    if (isNaN(y)||y<0) y=xy;
    while (y>xy+100) y-=100;

    if (y<100)
    {
      if (y>20) y+=1900;
      else y+=2000;
    }
    else if (y<1000)
    {
      if (y>900) y=1000+y;
      else y=2000+y;
    }
    while (y>xy+100) y-=100;
    while (y<1900) y+=100;

    //-- corregir mes
    var m=parseFloat(v.substr(3,2));
    if (isNaN(m)||m<=0) m=0;
    if (m<10) m='0'+m;

    //-- corregir dia
    var d=parseFloat(v.substr(0,2));
    if (isNaN(d)||d<=0) d='0';

    if (d<10) d='0'+d;
    elem.value=d+_datesep+m+_datesep+y;

    var v=elem.value;
  }
  elem.className='campo_fecha_inactivo';
}

function completaFecha(v)
{
  while (v.length<3){v+=' ';if (v.length==2) v+=_datesep;} // primeros 2 espacios
  while (v.length<6){v+=' ';if (v.length==5) v+=_datesep;} // 2 espacios meses
  while (v.length<10) v+=' ';return v;
}

function onfcs(e)
{
  var evt  = window.event   || e;
  var elem = evt.srcElement || evt.target;
  elem.className='campo_fecha_activo';
  if(elem.type=='date') return;
  var v=elem.value;
  v=completaFecha(v);
  elem.value=v;
  sel(elem,0,1);
}

//--------------------------- HECHO POR MARCO: ENERO 2009 PARA EDICION DE HORAS --------

function setTimeEditor(obj)
{
  this.obj = obj;
  obj.size=5;
  obj.maxLength=5;
  obj.value=obj.value.substr(0,5);
  formateaHora(obj, true, true);
  obj.onkeyup=horakup;
  obj.onkeydown=horakdn;
  obj.onblur=horablr;
  //obj.onfocus=horafcs;
  obj.onmouseup=horamup;
  if (trim(obj.value)==':') obj.value='';
}


function formateaHora(e, init, ignFormat)
{
  if (trim(e.value) == ':' || trim(e.value) == '')
  {
    e.value='';
    return true;
  }

  e.value=e.value.substr(0,5);
  while (e.value.length<5) e.value+=' ';

  var v='';
  for (i=0;i<e.value.length;i++)
  {
    var c=e.value.substr(i,1);
    if (i!=2)
    {
      if (isDigit(c)) v+=c+'';
      else v+=' '+'';
    }
    else v+=':';
  }

  if (!ignFormat)
  {
    var nx=trim(v.substr(0,2));
    nx = parseFloat(nx);
    if (!nx) nx=0;
    if (nx<10) v='0'+nx+v.substr(2,3);
  }

  if (!ignFormat)
  {
    var nx=trim(v.substr(3,2));
    nx = parseFloat(nx);
    if (!nx) nx=0;
    if (nx<10) v=v.substr(0,3)+'0'+nx;
  }

  e.value=v;

  var h=v.substr(0,2);
  if (h>23)
  {
    sel(e,0,1);
    e.style.color='#FF0000';
    //escribeHora(e, true);
    return false;
  }

  var h=v.substr(3,2);
  if (h>59)
  {
    sel(e,3,4);
    e.style.color='#FF0000';
    //escribeHora(e, true);
    return false;
  }
  if (init)
  {
    sel(e,0,1);
  }
  //escribeHora(e);
  return true;
}

function horakup(e)
{
  _released=true;
  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;
  var KeyCode = evt.keyCode    || evt.which;

  var p=getCursorPos(elem);
  var cp=p.start;
  var nd=p.end;

  v=elem.value;

  if (KeyCode==37) cp--;
  clearSel();
  if (cp==2)
  {
    if (KeyCode!=37) cp++;
    else cp--;
  }
  if (cp>=5)
  {
    cp=4;
    if (!formateaHora(elem)) return;
  }
  sel(elem,cp,cp+1);
}

function horakdn(e)
{
  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;
  var KeyCode = evt.keyCode    || evt.which;
  var s;

  elem.style.color='#000000';

  v=elem.value;
  if (KeyCode==123)
  {
    if (!formateaHora(elem)) cancelaTecla(evt);
    return false;
  }

  if (!_released)
  {
    horakup(e);
  }
  _released=false;

  cp=getCursorPos(elem);
  s=cp.start;

  if (KeyCode==8)
  {
    if (s<5) elem.value=elem.value.substr(0,s)+' '+elem.value.substring(s+1,10);
    else elem.value=elem.value.substr(0,elem.value.length-1)+' ';
    if (s==6||s==5||s==3) s--;
    sel(elem,s-1,s);
    cancelaTecla(evt);
    return false;
  }

  if (KeyCode==46)
  {
    var v=elem.value;
    v=v.substring(0,s);
    elem.value=v;
    formateaHora(elem,false,true);
    if (elem.value=='') elem.value='  :  ';
    sel(elem,s,s+1);
    cancelaTecla(evt);
    return false;
  }

  if ((KeyCode<48||KeyCode>57)&&KeyCode!=13&&(KeyCode<96||KeyCode>105)&&KeyCode!=39&&KeyCode!=37&&KeyCode!=36&&KeyCode!=35&&KeyCode!=9&&(KeyCode<112||KeyCode>123)&&KeyCode!=27)
  {
    cancelaTecla(evt);
  }
}

function horablr(e)
{
  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;

  elem.className='campo_fecha_inactivo';
  elem.style.color='#000000';
  if (!formateaHora(elem)) elem.focus();
}

function horafcs(e)
{
  var evt     = window.event   || e;
  var elem    = evt.srcElement || evt.target;

  elem.className='campo_fecha_activo';
  if (trim(elem.value)=='')
  {
    elem.value='  :  ';
  }
  else
  {
    formateaHora(elem);
    if (elem.value=='') elem.value='  :  ';
  }
  sel(elem,0,1);
}

function horamup(e)
{
  _errhora = false;
  var evt     = window.event   || e;
  var elem    = this;

  elem.style.color='#000000';

  var cp = getCursorPos(elem).start-1;
  clearSel();
  if (cp==2) cp++;
  if (cp>=5) cp=4;
  sel(elem,cp,cp+1);
}


