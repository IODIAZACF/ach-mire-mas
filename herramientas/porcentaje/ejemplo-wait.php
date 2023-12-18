<?php

define('Server_Path','../../');
include_once (Server_Path . 'herramientas/utiles/comun.php');


encabezado('EJEMPLO DE PORCENTAJE Y WAITEXEC');

echo '<body onload="ocultaCarga();" style="background-color: #7baac6;">' . "\n";
cargando();
javascript('utiles,porcentaje');

?>

<script type="text/javascript">

var valor=0;

/* caso 1: Con llamado procedimental */

var porc;


function iniciaConteo(){
    valor=0;
    waitExec();
    porc = new porcentaje({
        padre: ".wait-cuadro", //<-- clase del cuadro de waitExec
        texto: true /* true = muestra porcentaje; otro texto = muestra el texto */
    });
    porc.inicializa();
    conteo();
}

function conteo(){
    if (valor>=100) valor=100;

    porc.setValue(valor); //<-- actualiza el porcentaje
    
    if (valor==100){
        setTimeout(()=>{
            porc.inicializado=false; // para que se vuelva a crear la barra de % si se vuelve a llamar
            waitClose(); //<--- se usa para cerrarlo, y destruir la barra de porcentaje
        },50);
    }
    else setTimeout(()=>{
        valor+=5;
        conteo();
    },200);
}

function espera(){
    waitExec("Por favor Espere 3 segundos...", "", 3000);
}

function esperaCierre(){
    waitExec("En 5 segundos se cerrara esta espera...");
    setTimeout(()=>{
        waitClose();
    }, 5000);
}

</script>

<button onclick="iniciaConteo();">Prueba de porcentaje</button>
<button onclick="espera();">Prueba de Espera con tiempo</button>
<button onclick="esperaCierre();">Prueba de Espera tradicional con cierre (waitClose)</button>

</body>
