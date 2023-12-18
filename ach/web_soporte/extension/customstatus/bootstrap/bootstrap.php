<?php 

function qURL($Url, $data_string){
	$curlConn = curl_init();
	curl_setopt($curlConn, CURLOPT_URL, $Url);
	curl_setopt($curlConn, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($curlConn, CURLINFO_HEADER_OUT, true);
	curl_setopt($curlConn, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curlConn, CURLOPT_SSL_VERIFYHOST, false);	
	curl_setopt($curlConn, CURLOPT_POST, 1);
	curl_setopt($curlConn, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($curlConn, CURLOPT_HTTPHEADER, array(
		'Ocp-Apim-Subscription-Key: 90855f5813c448858954de5a18b165f8',
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);

	return curl_exec($curlConn);
	
}	

class erLhcoreClassExtensionCustomstatus {

	public function __construct() {
		
	}
	
	public function run(){				
		$dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
		// Attatch event listeners
		//$dispatcher->listen('chat.close',array($this,'chatClosed'));							
		$dispatcher->listen('chat.chat_started',array($this,'proaddmsguser'));
		$dispatcher->listen('chat.addmsguser',array($this,'proaddmsguser'));
		
		
		/*
		$dispatcher->listen('chat.startchat_data_fields',array($this,'f1'));
		$dispatcher->listen('chat.chat_offline_request_presend',array($this,'f2'));
		$dispatcher->listen('chat.chat_offline_request',array($this,'f3'));
		$dispatcher->listen('chat.startchat',array($this,'f4'));
		//$dispatcher->listen('chat.addmsguser',array($this,'f5'));
		//$dispatcher->listen('chat.addmsguser',array($this,'f6'));
		
		*/
		//$dispatcher->listen('chat.web_add_msg_admin',array($this,'proaweb_add_msg_admin'));
		//$dispatcher->listen('chat.startchat_data_fields',array($this,'prostartchat_data_fields'));
		
	}
	
	public function f1($params) {
		file_put_contents(__DIR__ . '/f1', print_r($params, true), FILE_APPEND);				
	}
	
	public function f2($params) {
		file_put_contents(__DIR__ . '/f2', print_r($params, true), FILE_APPEND);				
	}
	public function f3($params) {
		file_put_contents(__DIR__ . '/f3', print_r($params, true), FILE_APPEND);				
	}
	public function f4($params) {
		file_put_contents(__DIR__ . '/f4', print_r($params, true), FILE_APPEND);				
	}
	public function f5($params) {
		file_put_contents(__DIR__ . '/f5', print_r($params, true), FILE_APPEND);				
	}
	public function f6($params) {
		file_put_contents(__DIR__ . '/f6', print_r($params, true), FILE_APPEND);				
	}

	public function prostartchat_data_fields($params) {
		file_put_contents(__DIR__ . '/startchat_data_fields', print_r($params, true), FILE_APPEND);				
	}

	public function prosevabot($params) {
		file_put_contents(__DIR__ . '/chat_started', print_r($params, true), FILE_APPEND);				
	}

	public function proaddmsguser($params) {		
		$db = ezcDbInstance::get();
		$stmt = $db->prepare('SELECT * from lh_chat  where id=' . $params['msg']->chat_id);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		$param['nick'] = utf8_encode($rows[0]['nick']);
		
		$rjson = json_decode($rows[0]['additional_data']);
		for($f=0;$f<count($rjson);$f++){
			if($rjson[$f]->identifier=='NICK'){
				$param['nick'] = utf8_encode(utf8_decode($rjson[$f]->value));				
			}	
		}
		
		$param['additional_data'] = $rows[0]['additional_data'];
		foreach ($params['msg'] as $c => $v) {
			$param[$c] = utf8_encode($v);
		}	

		
		
		file_put_contents(__DIR__ . '/proaddmsguser', print_r($param, true) , FILE_APPEND);	
		$data_string = json_encode($param);
		$Url='https://127.0.0.1/'. basename(dirname(__DIR__ ,4)) .'/notificaciones_monitor/www_receptor.php';		
		$xResp =  qURL($Url, $data_string);	

	}
	public function proaweb_add_msg_admin($params) {
		file_put_contents(__DIR__ . '/web_add_msg_admin', print_r($params, true), FILE_APPEND);				
		foreach ($params['msg'] as $c => $v) {
			$text.=$c .'=' . $v . "\n";
		}
		file_put_contents(__DIR__ . '/web_add_msg_admin', $text , FILE_APPEND);	
	}

	public function chatClosed($params) {
		// 
		// 'chat' => & $chat, 		// Chat which was closed
		// 'user_data' => $operator // Operator who has closed a chat
		// 
		// 
		//
	}

}


