<?
$rjson = json_decode('[{"h":false,"identifier":"NICK","key":"Nombre","value":"luis manuel nu\u00f1ez"}]');

for($f=0;$f<count($rjson);$f++){
	if($rjson[$f]->identifier=='NICK'){
		$param['nick'] = utf8_encode($rjson[$f]->value);				
	}	
}

print_r($param);
		
?>