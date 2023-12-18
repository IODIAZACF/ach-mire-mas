document.write('<a id="re" href=""></a>');

function clearFocuses()
{
  var re=document.getElementById('re');
  re.focus();
}

function loadRichEditor(id, xwidth, xheight, xvalue)
{

  var str='<'+'OBJECT id="'+id+'" classid="clsid:14CF205D-22A6-4F40-B08E-7D429D27A885" '+
	      'codebase="editorrtf.ocx#version=1,0,0,0" '+
	      ' width='+xwidth+
	      ' height='+(xheight+50)+
	      ' align=center '+
	      ' hspace=0 '+
	      ' vspace=0 >'+
	      '<'+'/OBJECT'+'>';

  return str;
//  document.write(str);

}