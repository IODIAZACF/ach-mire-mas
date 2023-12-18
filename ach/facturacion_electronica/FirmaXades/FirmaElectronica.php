<?php 
/** 
* Copyright (c)2018 - EN Systems Apps
* @abstract Firma, Valida, Envia al SRI y Autoriza en el SRI
* @author Erik Niebla
* @mail ep_niebla@hotmail.com, ep.niebla@gmail.com
* @version 1.0
* Fecha de creación  2018-02-27
* http://ensystems.ddns.net
*/
require_once dirname(__FILE__)."/nuSoap/nusoap.php"; // Libreria para el uso del cliente soap

class FirmaElectronica
{       	
	private $ws;	
	private $data=array(
		'access_key'=>null,
		'file_p12'=>null,
        'pass_p12'=>null,
        'file_to_sign'=>null,
        'file_signed'=>null,        
        'file_autorized'=>null
    );
	private $xsd_docs=array(
		'FACTURA'=>'Factura',
		'NOTACREDITO'=>'Nota_Credito',
		'NOTADEBITO'=>'Nota_Debito',
		'GUIAREMISION'=>'Guias_de_Remision',
		'COMPROBANTERETENCION'=>'COMPROBANTERETENCION'
	);
	private $java_bridge="http://localhost:8080/FirmaXades/java/Java.inc";  //Ruta Tomcat con JavaBridge
    private $signer="https://exa-xadesbes.herokuapp.com/FirmaXades/firmaXadesBes.wsdl"; // WS de firmado, EN Systems
	// 0=Online, 1=Offline
    private $development=array(
		0=>array(
			'recept'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl",
			'autori'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl"
		),
		1=>array(
			'recept'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl",
			'autori'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
		)
    );
    private $production=array(
		0=>array(
			'recept'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl",
			'autori'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl"
		),
		1=>array(
			'recept'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl",
			'autori'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
		)
    );   
    
    public function __construct(array $config = array()){
		$this->setConfig($config);
        $this->setAmbiente(true,true,null);
    }
	public function setConfig($config=array()){
		 $this->data=array_merge($this->data,$config);
	}
	public function setAmbiente($online=true,$production=true,$signer=null){
		$k=$online==true?0:1;
		$this->ws=($production==true)?$this->production[$k]:$this->development[$k];
        $this->ws['signer']=($signer==null)?$this->signer:$signer;
	}	
	public function setJavaBridge($jb){
        if(!empty($jb)) $this->java_bridge=$jb;
    }
    public function setFileToSignPath($file_to_sign){
        if(!empty($file_to_sign)) $this->data['file_to_sign']=$file_to_sign;
    }
    public function setFileSignedPath($file_signed){
        if(!empty($file_signed)) $this->data['file_signed']=$file_signed;
    }
    public function setFileAutorized($file_autorized){
        if(!empty($file_autorized)) $this->data['file_autorized']=$file_autorized;
    }
   
    public function signXml($xml_path=null, $key_path=null, $pass=null, $file_signed=null, $echo=false){
		if(!empty($xml_path)) $this->data['file_to_sign']=$xml_path; 
		if(!empty($key_path)) $this->data['file_p12']=$key_path; 
		if(!empty($pass)) 	  $this->data['pass_p12']=$pass; 
		if(!empty($file_signed))$this->data['file_signed']=$file_signed; 
        try {
            if(empty($this->data['file_to_sign'])||!is_readable($this->data['file_to_sign'])) return array('success'=>false,'message'=>'Revise la ruta del xml!','type'=>'application'); 
            if(empty($this->data['file_p12'])||!is_readable($this->data['file_p12'])) return array('success'=>false,'message'=>'Revise la ruta del xml!','type'=>'application'); 
            if(empty($this->data['pass_p12'])) return array('success'=>false,'message'=>'La password no puede estar en blanco!','type'=>'application'); 
            $xml=file_get_contents($this->data['file_to_sign']);
            $xml_64=base64_encode((!mb_detect_encoding($xml, 'UTF-8', true))?utf8_encode($xml):$xml);
            $key_64=base64_encode(file_get_contents($this->data['file_p12']));
            $pass_64=base64_encode($this->data['pass_p12']);
            //var_dump($key_64);
			//var_dump($pass_64);exit();
			require_once($this->java_bridge);
			$firma = new java("Program");
			$xml_f = $firma->getXmlSigned($xml_64,$key_64,$pass_64);	
			$result=array('success'=>java_values($xml_f->success),'message'=>java_values($xml_f->message),'xml'=>java_values($xml_f->xml));
			// todo correcto 
			if($result['success']==true)
				if(!empty($this->data['file_signed'])&&!empty($result['xml'])){
					$file=$this->data['file_signed'];
					$exist=file_exists($file);                   
					$fp = fopen($file, 'wb');
					fwrite($fp,(!mb_detect_encoding($result['xml'], 'UTF-8', true))?utf8_encode($result['xml']):$result['xml'] ); fclose($fp);
					if(!$exist) chmod($file, 0777);
				}
			if(empty($result['xml'])) $result['success']=false;
			return $result;
        } catch (Exception $e) { 
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return array('success'=>false,'message'=>$e->getMessage(),'type'=>'application'); 
        }    
    }
    public function sendToSign($xml_path=null, $key_path=null, $pass=null, $file_signed=null, $echo=false){
		if(!empty($xml_path)) $this->data['file_to_sign']=$xml_path; 
		if(!empty($key_path)) $this->data['file_p12']=$key_path; 
		if(!empty($pass)) 	  $this->data['pass_p12']=$pass; 
		if(!empty($file_signed))$this->data['file_signed']=$file_signed; 
        try {
            if(empty($this->data['file_to_sign'])||!is_readable($this->data['file_to_sign'])) return array('success'=>false,'message'=>'Revise la ruta del xml!','type'=>'application'); 
            if(empty($this->data['file_p12'])||!is_readable($this->data['file_p12'])) return array('success'=>false,'message'=>'Revise la ruta de archivo .p12!','type'=>'application'); 
            if(empty($this->data['pass_p12'])) return array('success'=>false,'message'=>'La password no puede estar en blanco!','type'=>'application'); 
            $xml=file_get_contents($this->data['file_to_sign']);
            $xml_64=base64_encode((!mb_detect_encoding($xml, 'UTF-8', true))?utf8_encode($xml):$xml);
            $key_64=base64_encode(file_get_contents($this->data['file_p12']));
            $pass_64=base64_encode($this->data['pass_p12']);         
            
            $cliente = new nusoap_client($this->ws['signer'], true);            
            $error = $cliente->getError();
            if($error) return array('success'=>false,'message'=>$error,'type'=>'constructor');
            
            $result = $cliente->call("getFileSigned", array("file" => $xml_64,'key'=>$key_64, 'pass'=>$pass_64));
            
            if ($cliente->fault) return array('success'=>false,'message'=>'Fault','type'=>'call','data'=>$result);        
            $error = $cliente->getError();
            if($error){ var_dump($error); return array('success'=>false,'message'=>$error,'type'=>'response'); }
            // todo correcto 
            if(!empty($this->data['file_signed'])&&!empty($result['xml'])){
                $file=$this->data['file_signed'];
                $exist=file_exists($file);                   
                $fp = fopen($file, 'wb');
                fwrite($fp,(!mb_detect_encoding($result['xml'], 'UTF-8', true))?utf8_encode($result['xml']):$result['xml'] ); fclose($fp);
                if(!$exist) chmod($file, 0777);
            }
			if(empty($result['xml'])) $result['success']=false;
            return $result;
        } catch (Exception $e) { 
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return array('success'=>false,'message'=>$e->getMessage(),'type'=>'application'); 
        }     
    }
	public function validaXml($file_signed=null, $echo=false){
		if(!empty($file_signed)) $this->data['file_signed']=$file_signed;
		function libxml_display_error($error){
			$return = "<br/>\n";
			switch ($error->level) {
				case LIBXML_ERR_WARNING: $return .= "<b>Warning $error->code</b>: "; break;
				case LIBXML_ERR_ERROR: $return .= "<b>Error $error->code</b>: "; break;
				case LIBXML_ERR_FATAL: $return .= "<b>Fatal Error $error->code</b>: "; break;
			}
			$return .= trim($error->message); //if ($error->file) { $return .= " in <b>$error->file</b>"; }
			$return .= " on line <b>$error->line</b>\n";
			return $return;
		}
		function libxml_display_errors() {
			$html="<br/>\n";
			$errors = libxml_get_errors();    
			if(isset($errors)&&!empty($errors)){
				foreach ($errors as $error) {
					$html.=libxml_display_error($error);
				} libxml_clear_errors();
			} return $html;
		}
		libxml_use_internal_errors(true);
		try {
			if(empty($this->data['file_signed'])||!is_readable($this->data['file_signed'])) return array('success'=>false,'message'=>'No se especifico la ruta del archivo firmado!','type'=>'setter');
			$xml=file_get_contents($this->data['file_signed']);
			
			$doc = new DOMDocument('1.0', 'utf-8');
			$doc->formatOutput = true;
			$doc->loadXml((!mb_detect_encoding($xml, 'UTF-8', true))?utf8_encode($xml):$xml);
						
			$doc_name   =strtoupper($doc->documentElement->nodeName);    
			$doc_version=str_replace('.','_',$doc->documentElement->getAttribute('version'));			
			
			if(isset($this->xsd_docs[$doc_name])) $doc_name=$this->xsd_docs[$doc_name];
			$doc_name = strtoupper($doc_name);
			$xsd=dirname(__FILE__)."/xsd/{$doc_version}/{$doc_name}_V_{$doc_version}.xsd";    
			if(!file_exists($xsd)) throw new Exception("No existe Schema de Validacion para el documento $doc_name version $doc_version!");
	
			if(!$doc->schemaValidate($xsd)){ 
				throw new Exception("El XML no paso el Schema de Validacion del SRI, <b>XSD:{$doc_name}_V_{$doc_version}.xsd</b>! ".libxml_display_errors());
			}
			return array('success'=>true,'message'=>'XSD ha validado correctamente!','type'=>'application'); 
		} catch (Exception $e) { 
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return array('success'=>false,'message'=>$e->getMessage(),'type'=>'application'); 
        }  
	}
    public function sendToSri($file_signed=null, $echo=false){
        try {
            if(!empty($file_signed)) $this->data['file_signed']=$file_signed; 
            if(empty($this->data['file_signed'])||!is_readable($this->data['file_signed'])) return array('success'=>false,'message'=>'No se especifico la ruta del archivo firmado!','type'=>'setter');
            if(!is_readable($this->data['file_signed'])) return array('success'=>false,'message'=>'No se encontro el archivo xml!','type'=>'setter');
            $cliente = new nusoap_client($this->ws['recept'], true);
            
            $error = $cliente->getError();
            if($error) return array('success'=>false,'message'=>$error,'type'=>'constructor');
            
            $base_convert=base64_encode(file_get_contents($this->data['file_signed']));
            $result = $cliente->call("validarComprobante", array("xml" =>$base_convert));
            //print_r(htmlspecialchars($cliente->response, ENT_QUOTES));
            if ($cliente->fault) return array('success'=>false,'message'=>'Fault','type'=>'call','data'=>$result);        
            $error = $cliente->getError();
            if($error) return array('success'=>false,'message'=>$error,'type'=>'response');
            // todo correcto            
            $resp = array('success'=>$result['RespuestaRecepcionComprobante']['estado']=='DEVUELTA'?false:true,'message'=>'','informacionAdicional'=>'','type'=>'response');
            
            if($resp['success']==false){
				$comp=$result['RespuestaRecepcionComprobante']['comprobantes']['comprobante']; 
				$resp['claveAcceso']=$comp['claveAcceso'];
                $msgs=$comp['mensajes'];
                foreach($msgs as $msg){
                    if($resp['success']==false && $msg['identificador']*1==43) $resp['success']=true;
                    $resp['message'].=((isset($msg['tipo'])?"$msg[tipo] ":'')."$msg[identificador]: $msg[mensaje]!, ");
                    if(isset($msg['informacionAdicional'])) $resp['informacionAdicional'].="$msg[informacionAdicional]!, ";
                }
                $resp['message']=substr($resp['message'], 0, -2);
                if(!empty($resp['informacionAdicional']))$resp['informacionAdicional']=substr($resp['informacionAdicional'], 0, -2);
            }
            return $resp;
        } catch (Exception $e) { 
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return array('success'=>false,'message'=>$e->getMessage(),'type'=>'application'); 
        }     
    }
    public function autorizarSri($claveAcceso=null, $file_autorized=null, $echo=false){
        try {
			if(!empty($claveAcceso)) $this->data['access_key']=$claveAcceso;
            if(!empty($file_autorized)) $this->data['file_autorized']=$file_autorized;
            if(empty($this->data['access_key'])) return array('success'=>false,'message'=>'No se especifico una Clave de Acceso','type'=>'setter');
            $cliente = new nusoap_client($this->ws['autori'], true);
            
            $error = $cliente->getError();
            if($error) return array('success'=>false,'message'=>$error,'type'=>'constructor');
            
            $result = $cliente->call("autorizacionComprobante", array("claveAccesoComprobante" =>$this->data['access_key']));
            
            if ($cliente->fault) return array('success'=>false,'message'=>'Fault','type'=>'call','data'=>$result);        
            $error = $cliente->getError();
            if($error) return array('success'=>false,'message'=>$error,'type'=>'response');
            // todo correcto
            $aut=$result['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']; 
            $autorizado=(trim(''.$aut['estado'])=='AUTORIZADO');
            $resp=array('success'=>$autorizado,'xml'=>'','message'=>'','informacionAdicional'=>'');
            if($autorizado){
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->formatOutput = true;   
                $autorizacion=$doc->createElement('autorizacion'); 
                $estado=$doc->createElement('estado',$aut['estado']); 
                $numeroAutorizacion=$doc->createElement('numeroAutorizacion',$aut['numeroAutorizacion']); 
                $fechaAutorizacion=$doc->createElement('fechaAutorizacion',$aut['fechaAutorizacion']);        
                $cdata = $doc->createCDATASection((!mb_detect_encoding($aut['comprobante'], 'UTF-8', true))?utf8_encode($aut['comprobante']):$aut['comprobante']);            
                $comprobante=$doc->createElement('comprobante');
                $comprobante->appendChild($cdata);

                $fechaAutorizacion->setAttribute('class','fechaAutorizacion');
                $autorizacion->appendChild($estado);
                $autorizacion->appendChild($numeroAutorizacion);
                $autorizacion->appendChild($fechaAutorizacion);
                $autorizacion->appendChild($comprobante);
                $doc->appendChild($autorizacion);
                if(!empty($this->data['file_autorized']))
                    $doc->save($this->data['file_autorized']);
                $resp['xml']=$doc->saveXML();
				$resp['numeroAutorizacion']=$aut['numeroAutorizacion'];
				//$resp['fechaAutorizacion']=str_replace('T',' ',$aut['fechaAutorizacion']);
				$resp['fechaAutorizacion']=$aut['fechaAutorizacion'];
            }else{                
                $msgs=$aut['mensajes'];				
                foreach($msgs as $msg){                    
                    $resp['message'].=((isset($msg['tipo'])?"$msg[tipo] ":'')."$msg[identificador]: $msg[mensaje]!, ");
                    if(isset($msg['informacionAdicional'])) $resp['informacionAdicional'].="$msg[informacionAdicional]!, ";
                }
                $resp['message']=substr($resp['message'], 0, -2);
                if(!empty($resp['informacionAdicional']))$resp['informacionAdicional']=substr($resp['informacionAdicional'], 0, -2);                
            }
            return $resp;
        } catch (Exception $e) { 
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return array('success'=>false,'message'=>$e->getMessage(),'type'=>'application','informacionAdicional'=>''); 
        }
    }
	/* FUNCIONA BIEN, REQUIERE OpenSSL Y CLASES DSig
    public function validateSignature($config = array(), $echo=false ){
        try {
            if(!empty($config) && is_array($config)) $this->data = array_merge($this->data,$config);        
            if( empty($this->data['file_signed']) || !is_readable($this->data['file_signed']) ) return false;        
            $options = array('id_name' => 'id', 'id_ns_prefix' => 'xml', 'id_prefix_ns' => 'http://www.w3.org/XML/1998/namespace');
            //var_dump(file_get_contents($this->data['file_signed']));
            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->loadXML (file_get_contents($this->data['file_signed']));
            $signature = DSig::locateSignature($doc);
            $firma=DSig::verifyDocumentSignature($signature,$this->key);
            $referencias=DSig::verifyReferences($signature, $options);
            if($echo) echo '<br/>'.
                        '<b>FIRMA       :</b>'.($firma?'Valida':'Invalida').'<br/>'.
                        '<b>REFERENCIAS :</b>'.($referencias?'Validas':'Invalidas').'<br/>';            
            return ($firma && $referencias);
        } catch (Exception $e) {
            if($echo) echo 'Excepción Capturada: ',  $e->getMessage(), "\n";
            return false;
        }    
    }
    */   
}        
        
         
    