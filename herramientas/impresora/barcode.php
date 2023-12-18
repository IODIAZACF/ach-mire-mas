<?
    require_once('code128barcode.class.php');
  
        if (isset($_REQUEST['process'])) {

        $code = $_REQUEST['code'];
        $string = $code;

        $barcode = new code128barcode();
  
        $code = $barcode->output($code);
        //
        // to add function codes, use an array like:
        // 
        // $code = $barcode->output(array('FNC4',$code,'SHIFT',"end\n"));
        //                                              SHIFT =>End
    if ($code) {
        $height = intval(strlen($code) / 3);
        $width = strlen($code);
        $im = imagecreate($width,intval(($height * 1.33) + 6));
        $white = ImageColorAllocate ($im, 255, 255, 255);
        $black = ImageColorAllocate ($im, 0,0,0);
    
        $code = preg_split('##',$code);
        array_pop($code);
        array_shift($code);
        for($i = 0 ; array_key_exists($i,$code) ; $i++)
            if ($code[$i] == 0)
                imageline($im,$i,0,$i,$height,$white);
            else
                imageline($im,$i,0,$i,$height,$black);

        $font=ImagePsLoadFont("css/URWGothicL-Demi.pfb");
        imagepsencodefont ($font,'css/latin1.enc');
        $size = ImagePSBbox($string,$font,($height/3));
        $h = ($width - $size[2]) / 2;
        $v = ($height + 18);
        ImagePsText($im, $string,$font, $height/3, $black, $white, $h, $v);
        
        header ("Content-type: image/png");
        header ("Content-disposition: inline; filename=barcode.png");
        ImagePng ($im);    
        }

    exit();
    }

?>