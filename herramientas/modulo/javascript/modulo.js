function modulo()
{

}
modulo.prototype.actualizarPie=function(xml, rama)
{
    if(!rama) rama = 'registro';

    var a = XML2Array(xml, rama);
    for (var x in a[0])
    {
        var xtipo = $(document).find('#PIE' + x).data('tipo');
        if(!xtipo) continue;
        var xvalor = a[0][x];
        switch (xtipo)
        {
            case '%':
              xvalor=parseFloat(xvalor);
              if (xvalor>=100)
              {
                xvalor=100;
                xcolor='#00FF00';
              }
              else if(xvalor<50) xcolor='#FF3333';
              else xcolor='#FFBB55';

              xvalor = htmlPorcentaje(meds[j]-4, 8, xvalor, xcolor, '#888888');
              break;
            case 'H':
              if(this.hora==12) xvalor = mask_Hora(xvalor);
              xvalor = '<center>'+xvalor+'</center>';
              break;

            case 'I': // ENTERO
              var tmp = xvalor.split(".");
              if (tmp.length>0) xvalor = tmp[0];
              break;

            case 'N': // 2 DECIMALES
              xvalor = parseFloat(unformat(xvalor)).toFixed(2);
              break;

            case 'N3':
              xvalor = parseFloat(unformat(xvalor)).toFixed(3);
              break;

            case 'N4':
              xvalor = parseFloat(unformat(xvalor)).toFixed(4);
              break;

            case 'N5':
              xvalor = parseFloat(unformat(xvalor)).toFixed(5);
              break;

            case 'N6': // 3 DECIMALES
              xvalor = parseFloat(unformat(xvalor)).toFixed(6);
              break;

            default:
              break;
          }
          $('#PIE' + x).html(xvalor);
    }
}
