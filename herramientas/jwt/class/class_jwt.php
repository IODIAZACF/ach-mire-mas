<?php

class jwt
{
	var $pkey 	= 'las36horas';
    var $skey 	= '1234567890las36horas';
    var $key	= '';

    function jwt()
    {

    }

	function hashPassword()
    {
	    $this->key = hash("SHA512", base64_encode(str_rot13(hash("SHA512", str_rot13($this->skey . $this->pkey)))));
	}

    function encode($data)
    {
        $data = json_encode($data);
        $resp = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->key), $data, MCRYPT_MODE_CBC, md5(md5($this->key))));
        //return urlencode($resp);
        return str_replace('+', '@',$resp);
    }

    function decode($data)
    {
        $xdata = str_replace('@', '+',$data);
     	$xrep = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($xdata), MCRYPT_MODE_CBC, md5(md5($this->key))), "\0");
        return json_decode($xrep);
    }
}


?>


