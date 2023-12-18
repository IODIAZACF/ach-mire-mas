<?php
set_time_limit(0);
header("Content-Type: text/html; charset=utf8");

$title=isset($_REQUEST["title"])?$_REQUEST["title"]:"Reporte";
$url = $_SERVER["QUERY_STRING"];
$url = explode("url=", $url);

$sURL 	= explode( '?', $url[1] )[0];
$sPARAM = explode( '?', $url[1] )[1];
echo  $sURL . PHP_EOL ;
echo  $sPARAM . PHP_EOL ;

parse_str ( $sPARAM, $param);
$SEP = '|';

$log = date('Y-m-d H:i:s');
$log.= isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $SEP . $_SERVER['HTTP_X_FORWARDED_FOR'] : $SEP . $_SERVER['SERVER_ADDR'];
$log.= $SEP. $param['log_SISTEMA']; 
$log.= $SEP. $param['log_SISTEMA_DB'];
$log.= $SEP. $param['log_USER_ID'];
$log.= $SEP. $param['log_USER_NAME'];
$log.= $SEP. $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$log.= PHP_EOL ;

unset ( $param['log_SISTEMA'] );
unset ( $param['log_SISTEMA_DB'] );
unset ( $param['log_USER_ID'] );
unset ( $param['log_USER_NAME'] );

file_put_contents ( __DIR__ . '/log/__' . date('Y-m-d') . '.log', $log , FILE_APPEND );
file_put_contents ( __DIR__ . '/log/__' . date('Y-m-d') . '.log', print_r ( $param , true ) , FILE_APPEND );
file_put_contents ( __DIR__ . '/log/__' . date('Y-m-d') . '.log', PHP_EOL  , FILE_APPEND );


die();

if (!$url[1]) die("error");
$url = $url[1];

?>
<html lang="es">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="/herramientas/jquery/javascript/jquery.js?d3af738f2006ef30293f717c47edf4fc"></script>
<script type="text/javascript" src="/herramientas/jquery/javascript/plugin/jquery.fileDownload.js?d3af738f2006ef30293f717c47edf4fc"></script>

<title><?=$title;?></title>
<style>
html,body{
    margin:0;
    ----background-color: #000;
    overflow:hidden;
}
#view {
    position:absolute;
    width: 100%;
    height:100%;
	display:none;
}

#loader{
    position: absolute;
    z-index:100;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
}
#loader .img{
	text-align: center;
    display: block;
}
#loader .espere{
	font-family: verdana;
	text-align: center;
    visibility: hidden;
}
#error{
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
    display:none;
    font-family: verdana;
    text-align: center;
    padding: 12px;
}
#error > .err {
    color: #CC0000;
    font-size: 24px;
}
#error > .ref {
    color: #CC0000;
    font-size: 12px;
    padding: 8px;
}
</style>
</head>

<body>
<div id="loader">
<div class="img"><img src="/reporte/animat-printer-color.gif" height="96"></div>
<div class="espere">Por favor espere... Es posible que el reporte sea largo</div>
</div>

<div id="error">
	<div class="err">Error!</div>
	<div class="msg">No se pudo generar el reporte</div>
	<!--div class="ref"><a href="<?=preg_replace("/o24report\:/i","http:",$url);?>" target="_new">Referencia</a></div-->
	<br>
</div>

<iframe id="view" class="view" frameborder="no" src="about:blank" ></iframe>
<script>

	var ifr = document.querySelector("#view");
	ifr.onload=function(){
		//ifr.style.display="block";
		ifr.contentWindow.focus();
	};
	function exportXLS(){
		var csvWeb = "herramientas/impresora/impresora_csv.php";				
		var params = location.href.split('origen')[1];
		var url=new URL("", location.href.split('?')[1].replace('url=','') );
		var sistema=url.pathname.split("/")[1];
		var Url    = location.origin  + "/"+ sistema + '/' + csvWeb +'?origen'+ params;
		
		$.fileDownload(Url);
	}
	

	var tespera = setTimeout(() => {
		document.querySelector("#loader .espere").style.visibility="visible";
	}, 20000);
	var printPdf = '<?=$_REQUEST['viewer'];?>';
	fetch('request.php?title=<?=$title;?>&url=<?=$url;?>')
		.then(response => response.json())
		.then(data => {
			if (data && data["pdf"]) {
				clearTimeout(tespera);
				setTimeout(function(){
						document.querySelector("#loader").style.display="none";
				},1000);
				ifr.style.display="block";
				if(printPdf=='1'){
					ifr.src="/reporte/web/viewer.php?file=../"+data.pdf+"#locale=es-MX";					
				}else{
					ifr.src="/reporte/"+data.pdf;
				}
			}
			else {
				document.querySelector("#loader").style.display="none";
				var obj = document.querySelector("#error");
				
				obj.style.display="block";
				//var msg = obj.querySelector('.msg');				
				//msg.innerHTML = data['error'] || 'No se pudo cargar el reporte';
				console.error('Reporte: ' + data['error'] || 'Reporte: ' + 'No se pudo cargar el reporte');
				
			}
		})
		.catch(function(e){
			document.querySelector("#loader").style.display="none";
    		document.querySelector("#error").style.display="block";
		});

</script>
</body>
</html>
