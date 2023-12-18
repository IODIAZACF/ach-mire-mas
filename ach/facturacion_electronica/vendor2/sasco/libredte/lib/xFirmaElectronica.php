<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

namespace sasco\LibreDTE;

/**
 * Clase para trabajar con firma electrónica, permite firmar y verificar firmas.
 * Provee los métodos: sign(), verify(), signXML() y verifyXML()
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2019-02-12
 */
class FirmaElectronica
{

    private $config; ///< Configuración de la firma electrónica
    private $certs; ///< Certificados digitales de la firma
    private $data; ///< Datos del certificado digial

    /**
     * Constructor para la clase: crea configuración y carga certificado digital
     *
     * Si se desea pasar una configuración específica para la firma electrónica
     * se debe hacer a través de un arreglo con los índices file y pass, donde
     * file es la ruta hacia el archivo .p12 que contiene tanto la clave privada
     * como la pública y pass es la contraseña para abrir dicho archivo.
     * Ejemplo:
     *
     * \code{.php}
     *   $firma_config = ['file'=>'/ruta/al/certificado.p12', 'pass'=>'contraseña'];
     *   $firma = new \sasco\LibreDTE\FirmaElectronica($firma_config);
     * \endcode
     *
     * También se permite que en vez de pasar la ruta al certificado p12 se pase
     * el contenido del certificado, esto servirá por ejemplo si los datos del
     * archivo están almacenados en una base de datos. Ejemplo:
     *
     * \code{.php}
     *   $firma_config = ['data'=>file_get_contents('/ruta/al/certificado.p12'), 'pass'=>'contraseña'];
     *   $firma = new \sasco\LibreDTE\FirmaElectronica($firma_config);
     * \endcode
     *
     * @param config Configuración para la clase, si no se especifica se tratará de determinar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2019-12-04
     */
    public function __construct(array $config = [])
    {
        // crear configuración
        if (!$config) {
            if (class_exists('\sowerphp\core\Configure')) {
                $config = (array)\sowerphp\core\Configure::read('firma_electronica.default');
            } else {
                $config = [];
            }
        }
        $this->config = array_merge([
            'file' => null,
            'pass' => null,
            'wordwrap' => 76,
        ], $config);
        // leer datos de la firma electrónica desde configuración con índices: cert y pkey
        if (!empty($this->config['cert']) and !empty($this->config['pkey'])) {
            $this->certs = [
                'cert' => $this->config['cert'],
                'pkey' => $this->config['pkey'],
            ];
            unset($this->config['cert'], $this->config['pkey']);
        }
        // se pasó el archivo de la firma o bien los datos de la firma
        else {
            // cargar firma electrónica desde el contenido del archivo .p12 si no
            // se pasaron como datos del arreglo de configuración
            if (empty($this->config['data']) and $this->config['file']) {
                if (is_readable($this->config['file'])) {
                    $this->config['data'] = file_get_contents($this->config['file']);
                } else {
                    return $this->error('Archivo de la firma electrónica '.basename($this->config['file']).' no puede ser leído');
                }
            }
            // leer datos de la firma desde el archivo que se ha pasado
            if (!empty($this->config['data'])) {
                if (openssl_pkcs12_read($this->config['data'], $this->certs, $this->config['pass'])===false) {
                    return $this->error('No fue posible leer los datos de la firma electrónica (verificar la contraseña)');
                }
                unset($this->config['data']);
            }
        }
		
		//print_r($this->certs['pkey']);
		
		//$this->certs['pkey'] =  file_get_contents($this->config['fkey']); 
		
        $this->data = openssl_x509_parse($this->certs['cert']);
    }

    /**
     * Método para generar un error usando una excepción de SowerPHP o terminar
     * el script si no se está usando el framework
     * @param msg Mensaje del error
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2017-08-04
     */
    private function error($msg)
    {
        if (class_exists('\sasco\LibreDTE\Estado') and class_exists('\sasco\LibreDTE\Log')) {
            $msg = \sasco\LibreDTE\Estado::get(\sasco\LibreDTE\Estado::FIRMA_ERROR, $msg);
            \sasco\LibreDTE\Log::write(\sasco\LibreDTE\Estado::FIRMA_ERROR, $msg);
            return false;
        } else {
            throw new \Exception($msg);
        }
    }

    /**
     * Método que agrega el inicio y fin de un certificado (clave pública)
     * @param cert Certificado que se desea normalizar
     * @return Certificado con el inicio y fin correspondiente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-08-20
     */
    private function normalizeCert($cert)
    {
        if (strpos($cert, '-----BEGIN CERTIFICATE-----')===false) {
            $body = trim($cert);
            $cert = '-----BEGIN CERTIFICATE-----'."\n";
            $cert .= wordwrap($body, $this->config['wordwrap'], "\n", true)."\n";
            $cert .= '-----END CERTIFICATE-----'."\n";
        }
        return $cert;
    }

    /**
     * Método que entrega el RUN/RUT asociado al certificado
     * @return RUN/RUT asociado al certificado en formato: 11222333-4
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-02-12
     */
    public function getID()
    {
        // RUN/RUT se encuentra en la extensión del certificado, esto de acuerdo
        // a Ley 19.799 sobre documentos electrónicos y firma electrónica
        $x509 = new \phpseclib\File\X509();
        $cert = $x509->loadX509($this->certs['cert']);
        if (isset($cert['tbsCertificate']['extensions'])) {
            foreach ($cert['tbsCertificate']['extensions'] as $e) {
                if ($e['extnId']=='id-ce-subjectAltName') {
                    return ltrim($e['extnValue'][0]['otherName']['value']['ia5String'], '0');
                }
            }
        }
        // se obtiene desde serialNumber (esto es sólo para que funcione la firma para tests)
        if (isset($this->data['subject']['serialNumber'])) {
            return ltrim($this->data['subject']['serialNumber'], '0');
        }
        // no se encontró el RUN
        return $this->error('No fue posible obtener el ID de la firma');
    }

    /**
     * Método que entrega el CN del subject
     * @return CN del subject
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-02-12
     */
    public function getName()
    {
        if (isset($this->data['subject']['CN']))
            return $this->data['subject']['CN'];
        return $this->error('No fue posible obtener el Name (subject.CN) de la firma');
    }

    /**
     * Método que entrega el emailAddress del subject
     * @return emailAddress del subject
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-02-12
     */
    public function getEmail()
    {
        if (isset($this->data['subject']['emailAddress'])) {
            return $this->data['subject']['emailAddress'];
        }
        return $this->error('No fue posible obtener el Email (subject.emailAddress) de la firma');
    }

    /**
     * Método que entrega desde cuando es válida la firma
     * @return validFrom_time_t
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-22
     */
    public function getFrom()
    {
        return date('Y-m-d H:i:s', $this->data['validFrom_time_t']);
    }

    /**
     * Método que entrega hasta cuando es válida la firma
     * @return validTo_time_t
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-22
     */
    public function getTo()
    {
        return date('Y-m-d H:i:s', $this->data['validTo_time_t']);
    }

    /**
     * Método que entrega los días totales que la firma es válida
     * @return int Días totales en que la firma es válida
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2019-02-12
     */
    public function getTotalDays()
    {
        $start = new \DateTime($this->getFrom());
        $end = new \DateTime($this->getTo());
        $diff = $start->diff($end);
        return $diff->format('%a');
    }

    /**
     * Método que entrega los días que faltan para que la firma expire
     * @return int Días que faltan para que la firma expire
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2019-02-12
     */
    public function getExpirationDays($desde = null)
    {
        if (!$desde) {
            $desde = date('Y-m-d H:i:s');
        }
        $start = new \DateTime($desde);
        $end = new \DateTime($this->getTo());
        $diff = $start->diff($end);
        return $diff->format('%a');
    }

    /**
     * Método que entrega el nombre del emisor de la firma
     * @return CN del issuer
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-22
     */
    public function getIssuer()
    {
        return $this->data['issuer']['CN'];
    }

    /**
     * Método que entrega los datos del certificado
     * @return Arreglo con todo los datos del certificado
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-11
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Método que obtiene el módulo de la clave privada
     * @return Módulo en base64
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-12-07
     */
    public function getModulus()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->certs['pkey']));
		
        return wordwrap(base64_encode($details['rsa']['n']), $this->config['wordwrap'], "\n", true);
    }

    /**
     * Método que obtiene el exponente público de la clave privada
     * @return Exponente público en base64
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-12-06
     */
    public function getExponent()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->certs['pkey']));
        return wordwrap(base64_encode($details['rsa']['e']), $this->config['wordwrap'], "\n", true);
    }

    /**
     * Método que entrega el certificado de la firma
     * @return Contenido del certificado, clave pública del certificado digital, en base64
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-08-24
     */
    public function getCertificate($clean = false)
    {
		//xD($this->certs['extracerts'][0], __LINE__); 
		$this->certs['cert'] = $this->certs['extracerts'][0];
        if ($clean) {
            return trim(str_replace(
                ['-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----'],
                '',
                $this->certs['cert']
            ));
        } else {
            return $this->certs['cert'];
        }
    }

    /**
     * Método que entrega la clave privada de la firma
     * @return Contenido de la clave privada del certificado digital en base64
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-08-24
     */
    public function getPrivateKey($clean = false)
    {
        if ($clean) {
            return trim(str_replace(
                ['-----BEGIN PRIVATE KEY-----', '-----END PRIVATE KEY-----'],
                '',
                $this->certs['pkey']
            ));
        } else {
            return $this->certs['pkey'];
        }
    }

    /**
     * Método para realizar la firma de datos
     * @param data Datos que se desean firmar
     * @param signature_alg Algoritmo que se utilizará para firmar (por defect SHA1)
     * @return Firma digital de los datos en base64 o =false si no se pudo firmar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-12-08
     */
    public function sign($data, $signature_alg = OPENSSL_ALGO_SHA1)
    {
        $signature = null;
        if (openssl_sign($data, $signature, $this->certs['pkey'], $signature_alg)==false) {
            return $this->error('No fue posible firmar los datos');
        }
        return base64_encode($signature);
    }

    /**
     * Método que verifica la firma digital de datos
     * @param data Datos que se desean verificar
     * @param signature Firma digital de los datos en base64
     * @param pub_key Certificado digital, clave pública, de la firma
     * @param signature_alg Algoritmo que se usó para firmar (por defect SHA1)
     * @return =true si la firma está ok, =false si está mal o no se pudo determinar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2014-12-08
     */
    public function verify($data, $signature, $pub_key = null, $signature_alg = OPENSSL_ALGO_SHA1)
    {
        if ($pub_key === null)
            $pub_key = $this->certs['cert'];
        $pub_key = $this->normalizeCert($pub_key);
        return openssl_verify($data, base64_decode($signature), $pub_key, $signature_alg) == 1 ? true : false;
    }

    /**
     * Método que firma un XML utilizando RSA y SHA1
     *
     * Referencia: http://www.di-mgt.com.au/xmldsig2.html
     *
     * @param xml Datos XML que se desean firmar
     * @param reference Referencia a la que hace la firma
     * @return XML firmado o =false si no se pudo fimar
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2017-10-22
     */
	 
	 
    public function signXML($xml, $reference = '', $tag = null, $xmlns_xsi = false)
    {		
		$pxml = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml);
		
		$sep = "\n";
        $doc = new XML();		
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;		
        $doc->loadXML($pxml);
		
		$certificateX509_der_hash = base64_encode(sha1($doc->C14N(), true));		
		
		$sha1_factura = base64_encode(sha1($xml, true));
		//var sha1_factura = sha1_base64(factura.replace('<?xml version="1.0" encoding="UTF-8"n', ''));		
		$xmlns = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
		
		$Certificate_number = p_obtener_aleatorio(); //1562780 en el ejemplo del SRI
        $Signature_number = p_obtener_aleatorio(); //620397 en el ejemplo del SRI
        $SignedProperties_number = p_obtener_aleatorio(); //24123 en el ejemplo del SRI
        
        //numeros fuera de los hash:
        $SignedInfo_number = p_obtener_aleatorio(); //814463 en el ejemplo del SRI
        $SignedPropertiesID_number = p_obtener_aleatorio(); //157683 en el ejemplo del SRI
        $Reference_ID_number = p_obtener_aleatorio(); //363558 en el ejemplo del SRI
        $SignatureValue_number = p_obtener_aleatorio(); //398963 en el ejemplo del SRI
        $Object_number = p_obtener_aleatorio(); //231987 en el ejemplo del SRI
		
        $SignedProperties =  add('<etsi:SignedProperties Id="Signature' . $Signature_number . '-SignedProperties' . $SignedProperties_number . '">');
        $SignedProperties .= add( '	<etsi:SignedSignatureProperties>');
        $SignedProperties .= add( '		<etsi:SigningTime>');
		$SignedProperties .= add( date("Y-m-d\TH:i:sZ"));
		$SignedProperties .= add( '	</etsi:SigningTime>');
        $SignedProperties .= add( '	<etsi:SigningCertificate>');
        $SignedProperties .= add( '		<etsi:Cert>');
		$SignedProperties .= add( '			<etsi:CertDigest>');
		$SignedProperties .= add( '				<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">');
		$SignedProperties .= add( '				</ds:DigestMethod>');
		$SignedProperties .= add( '				<ds:DigestValue>');
		$SignedProperties .= add( $certificateX509_der_hash);
		$SignedProperties .= add( '				</ds:DigestValue>');
		$SignedProperties .= add( '			</etsi:CertDigest>');
		$SignedProperties .= add( '			<etsi:IssuerSerial>');
		$SignedProperties .= add( '				<ds:X509IssuerName>');
		$SignedProperties .= add( 'CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC');
		$SignedProperties .= add( '				</ds:X509IssuerName>');
		$SignedProperties .= add( '				<ds:X509SerialNumber>');
		
	$X509SerialNumber ='1532878624';
		
		$SignedProperties .= add($X509SerialNumber);
		$SignedProperties .= add('				</ds:X509SerialNumber>');
		$SignedProperties .= add('				</etsi:IssuerSerial>');
		$SignedProperties .= add('		</etsi:Cert>');
		$SignedProperties .= add('	</etsi:SigningCertificate>');
		$SignedProperties .= add('</etsi:SignedSignatureProperties>');
		$SignedProperties .= add('<etsi:SignedDataObjectProperties>');
		$SignedProperties .= add('	<etsi:DataObjectFormat ObjectReference="#Reference-ID-' . $Reference_ID_number . '">');
		$SignedProperties .= add('		<etsi:Description>');
		$SignedProperties .= add('			contenido comprobante');
		$SignedProperties .= add('		</etsi:Description>');
		$SignedProperties .= add('		<etsi:MimeType>');
		$SignedProperties .= add('			text/xml');
		$SignedProperties .= add('		</etsi:MimeType>');
		$SignedProperties .= add('	</etsi:DataObjectFormat>');
		$SignedProperties .= add('</etsi:SignedDataObjectProperties>');
		$SignedProperties .= add('</etsi:SignedProperties>');
		
		$SignedProperties_para_hash = str_replace('<etsi:SignedProperties', '<etsi:SignedProperties ' . $xmlns, $SignedProperties);		
		$sha1_SignedProperties = base64_encode(sha1($SignedProperties_para_hash, true));
		

		$certificado 	= $this->getCertificate(true);
		$certificado  = wordwrap(eregi_replace( "[\n]",'',$certificado), $this->config['wordwrap'], "\n", true); 
		
		
		$modulus 		= $this->getModulus();
		$exponent 		= $this->getExponent();

		$KeyInfo  = add('<ds:KeyInfo Id="Certificate' . $Certificate_number . '">') .$sep;
		$KeyInfo .= add('	<ds:X509Data>') .$sep;
		$KeyInfo .= add('		<ds:X509Certificate>').$sep;
		$KeyInfo .= $certificado .$sep;
		$KeyInfo .= add('		</ds:X509Certificate>') .$sep;
		$KeyInfo .= add('	</ds:X509Data>') .$sep;
		$KeyInfo .= add('	<ds:KeyValue>') .$sep;
		$KeyInfo .= add('		<ds:RSAKeyValue>') .$sep;
		$KeyInfo .= add('			<ds:Modulus>') .$sep;
		$KeyInfo .= $modulus .$sep;	
		$KeyInfo .= add('			</ds:Modulus>') .$sep;
		$KeyInfo .= add('			<ds:Exponent>');	
		$KeyInfo .= $exponent;
		$KeyInfo .= add('			</ds:Exponent>') .$sep;
		$KeyInfo .= add('		</ds:RSAKeyValue>') .$sep;
		$KeyInfo .= add('	</ds:KeyValue>').$sep;
		$KeyInfo .= add('</ds:KeyInfo>');
		
        $KeyInfo_para_hash = str_replace('<ds:KeyInfo', '<ds:KeyInfo ' . $xmlns, $KeyInfo);
		
		$sha1_certificado = base64_encode(sha1($KeyInfo_para_hash, true));
		
		

		$SignedInfo  = add('<ds:SignedInfo Id="Signature-SignedInfo' . $SignedInfo_number . '">') . $sep;
		$SignedInfo .= add('	<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></ds:CanonicalizationMethod>') .$sep;
		//$SignedInfo .= add('	</ds:CanonicalizationMethod>');
		$SignedInfo .= add('	<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></ds:SignatureMethod>') . $sep;
		//$SignedInfo .= add('	</ds:SignatureMethod>');
		$SignedInfo .= add('	<ds:Reference Id="SignedPropertiesID' . $SignedPropertiesID_number . '" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature' . $Signature_number . '-SignedProperties' . $SignedProperties_number . '">') . $sep;
		$SignedInfo .= add('		<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>') . $sep;
		//$SignedInfo .= add('		</ds:DigestMethod>');
		$SignedInfo .= add('		<ds:DigestValue>');

		$SignedInfo .= $sha1_SignedProperties;

		$SignedInfo .= add('		</ds:DigestValue>') . $sep;
		$SignedInfo .= add('	</ds:Reference>') . $sep;
		$SignedInfo .= add('	<ds:Reference URI="#Certificate' . $Certificate_number . '">') .$sep;
		$SignedInfo .= add('		<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>') .$sep;
		//$SignedInfo .= add('		</ds:DigestMethod>');
		$SignedInfo .= add('		<ds:DigestValue>');

		$SignedInfo .= $sha1_certificado;

		$SignedInfo .= add('		</ds:DigestValue>') .$sep;
		$SignedInfo .= add('	</ds:Reference>') .$sep;
		$SignedInfo .= add('	<ds:Reference Id="Reference-ID-' . $Reference_ID_number . '" URI="#comprobante">') . $sep;
		$SignedInfo .= add('		<ds:Transforms>') .$sep;
		$SignedInfo .= add('			<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></ds:Transform>') .$sep;
		//$SignedInfo .= add('		</ds:Transform>');
		$SignedInfo .= add('		</ds:Transforms>') . $sep;
		$SignedInfo .= add('		<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" />') .$sep;
		//$SignedInfo .= add('		</ds:DigestMethod>');
		$SignedInfo .= add('		<ds:DigestValue>');

		$SignedInfo .= $sha1_factura;

		$SignedInfo .= add('		</ds:DigestValue>') .$sep;
		$SignedInfo .= add('	</ds:Reference>') . $sep;
		$SignedInfo .= add('</ds:SignedInfo>');		
		
		$SignedInfo_para_firma = str_replace('<ds:SignedInfo', '<ds:SignedInfo ' . $xmlns, $SignedInfo);
		//echo "\n" . $SignedInfo_para_firma . "\n";
		
/*
		$xdoc = new XML();		
		$xdoc->preserveWhiteSpace = false;
		$xdoc->formatOutput = true;		
        $xdoc->loadXML($SignedInfo_para_firma);
		echo $xdoc->saveHTML();
		
*/


        $firma = $this->sign($SignedInfo_para_firma);
        $signature = wordwrap($firma, $this->config['wordwrap'], "\n", true);


		//INICIO DE LA FIRMA DIGITAL 
		$xades_bes = add('<ds:Signature ' . $xmlns . ' Id="Signature' . $Signature_number . '">') . $sep;
		$xades_bes .= $SignedInfo .$sep;
		$xades_bes .= add('<ds:SignatureValue Id="SignatureValue' . $SignatureValue_number . '">') . $sep;

		//VALOR DE LA FIRMA (ENCRIPTADO CON LA LLAVE PRIVADA DEL CERTIFICADO DIGITAL) 
		$xades_bes .= $signature . $sep;

		$xades_bes .= add('</ds:SignatureValue>') . $sep;

		$xades_bes .= $KeyInfo .$sep;

		$xades_bes .= add('<ds:Object Id="Signature' . $Signature_number . '-Object' . $Object_number . '">');
		$xades_bes .= add('<etsi:QualifyingProperties Target="#Signature' . $Signature_number . '">');

			//ELEMENTO <etsi:SignedProperties>';
		$xades_bes .= $SignedProperties;

		$xades_bes .= add('</etsi:QualifyingProperties>');
		$xades_bes .= add('</ds:Object>');
		$xades_bes .= add('</ds:Signature>');	

		$resXML = str_replace('</factura>', $xades_bes . '</factura>', $xml );
		return $resXML;
		/*
		
		$xdoc = new XML();		
		$xdoc->preserveWhiteSpace = true;
		$xdoc->formatOutput = true;		
        $xdoc->loadXML($resXML);
		return $xdoc->saveHTML();

		*/
		
	}
	public function xxxxsignXML($xml, $reference = '', $tag = null, $xmlns_xsi = false)
    {
		
		$Certificate_number = p_obtener_aleatorio(); //1562780 en el ejemplo del SRI
        $Signature_number = p_obtener_aleatorio(); //620397 en el ejemplo del SRI
        $SignedProperties_number = p_obtener_aleatorio(); //24123 en el ejemplo del SRI
        
        //numeros fuera de los hash:
        $SignedInfo_number = p_obtener_aleatorio(); //814463 en el ejemplo del SRI
        $SignedPropertiesID_number = p_obtener_aleatorio(); //157683 en el ejemplo del SRI
        $Reference_ID_number = p_obtener_aleatorio(); //363558 en el ejemplo del SRI
        $SignatureValue_number = p_obtener_aleatorio(); //398963 en el ejemplo del SRI
        $Object_number = p_obtener_aleatorio(); //231987 en el ejemplo del SRI
        
		
        // normalizar 4to parámetro que puede ser boolean o array
        if (is_array($xmlns_xsi)) {
            $namespace = $xmlns_xsi;
            $xmlns_xsi = false;
        } else {
            $namespace = null;
        }
        // obtener objeto del XML que se va a firmar
        $doc = new XML();
		
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;

		
        $doc->loadXML($xml);
        if (!$doc->documentElement) {
            return $this->error('No se pudo obtener el documentElement desde el XML a firmar (posible XML mal formado)');
        }
        // crear nodo para la firma
        $Signature = $doc->importNode((new XML())->generate([
            'ds:Signature' => [
                '@attributes' => $namespace ? false : [
                    'xmlns:ds' => 'http://www.w3.org/2000/09/xmldsig#',
					'xmlns:etsi' => 'http://uri.etsi.org/01903/v1.3.2#',
					'Id' => 'Signature'. $Signature_number,
                ],
                'ds:SignedInfo' => [
                    '@attributes' => $namespace ? false : [
                        'Id:' => 'Signature-SignedInfo'. $SignedInfo_number,
                    ],
                    'ds:CanonicalizationMethod' => [
                        '@attributes' => [
                            'Algorithm' => 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315',
                        ],
                    ],
                    'ds:SignatureMethod' => [
                        '@attributes' => [
                            'Algorithm' => 'http://www.w3.org/2000/09/xmldsig#rsa-sha1',
                        ],
                    ],
                    'ds:Reference' => [
                        '@attributes' => [
							'Id' => 'SignedPropertiesID' . $SignedPropertiesID_number ,
							'Type' => 'http://uri.etsi.org/01903#SignedProperties',
                            'URI' => '#Signature'. $Signature_number . '-SignedProperties' . $SignedProperties_number,
                        ],
                        'ds:DigestMethod' => [
                            '@attributes' => [
                                'Algorithm' => 'http://www.w3.org/2000/09/xmldsig#sha1',
                            ],
                        ],											
                        'ds:DigestValue' => null,
                    ],
					
                    'ds:Reference' => [
                        '@attributes' => [
							'Id' => '#Certificate' . $Certificate_number  ,
                        ],
                        'ds:DigestMethod' => [
                            '@attributes' => [
                                'Algorithm' => 'http://www.w3.org/2000/09/xmldsig#sha1',
                            ],
                        ],											
                        'ds:DigestValue' => null,
                    ],

					
                ],
                'ds:SignatureValue' => null,
                'ds:KeyInfo' => [
                    'ds:KeyValue' => [
                        'ds:RSAKeyValue' => [
                            'Modulus' => null,
                            'Exponent' => null,
                        ],
                    ],
                    'ds:X509Data' => [
                        'ds:X509Certificate' => null,
                    ],
                ],
            ],
        ], $namespace)->documentElement, true);
        // calcular DigestValue
        if ($tag) {
            $item = $doc->documentElement->getElementsByTagName($tag)->item(0);
            if (!$item) {
                return $this->error('No fue posible obtener el nodo con el tag '.$tag);
            }
            $digest = base64_encode(sha1($item->C14N(), true));
        } else {
            $digest = base64_encode(sha1($doc->C14N(), true));
        }
        $Signature->getElementsByTagName('ds:DigestValue')->item(0)->nodeValue = $digest;
        // calcular SignatureValue
        $SignedInfo = $doc->saveHTML($Signature->getElementsByTagName('ds:SignedInfo')->item(0));
        $firma = $this->sign($SignedInfo);
        if (!$firma)
            return false;
        $signature = wordwrap($firma, $this->config['wordwrap'], "\n", true);
        // reemplazar valores en la firma de
        $Signature->getElementsByTagName('ds:SignatureValue')->item(0)->nodeValue = $signature;
        $Signature->getElementsByTagName('ds:Modulus')->item(0)->nodeValue = $this->getModulus();
        $Signature->getElementsByTagName('ds:Exponent')->item(0)->nodeValue = $this->getExponent();
        $Signature->getElementsByTagName('ds:X509Certificate')->item(0)->nodeValue = $this->getCertificate(true);
        // agregar y entregar firma
        $doc->documentElement->appendChild($Signature);
        return $doc->saveXML();
    }

    /**
     * Método que verifica la validez de la firma de un XML utilizando RSA y SHA1
     * @param xml_data Archivo XML que se desea validar
     * @return =true si la firma del documento XML es válida o =false si no lo es
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-02
     */
    public function verifyXML($xml_data, $tag = null)
    {
        $doc = new XML();
        $doc->loadXML($xml_data);
        // preparar datos que se verificarán
        $SignaturesElements = $doc->documentElement->getElementsByTagName('Signature');
        $Signature = $doc->documentElement->removeChild($SignaturesElements->item($SignaturesElements->length-1));
        $SignedInfo = $Signature->getElementsByTagName('SignedInfo')->item(0);
        $SignedInfo->setAttribute('xmlns', $Signature->getAttribute('xmlns'));
        $signed_info = $doc->saveHTML($SignedInfo);
        $signature = $Signature->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $pub_key = $Signature->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        // verificar firma
        if (!$this->verify($signed_info, $signature, $pub_key))
            return false;
        // verificar digest
        $digest_original = $Signature->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        if ($tag) {
            $digest_calculado = base64_encode(sha1($doc->documentElement->getElementsByTagName($tag)->item(0)->C14N(), true));
        } else {
            $digest_calculado = base64_encode(sha1($doc->C14N(), true));
        }
        return $digest_original == $digest_calculado;
    }

    /**
     * Método que obtiene la clave asociada al módulo y exponente entregados
     * @param modulus Módulo de la clave
     * @param exponent Exponente de la clave
     * @return Entrega la clave asociada al módulo y exponente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2015-09-19
     */
    public static function getFromModulusExponent($modulus, $exponent)
    {
        $rsa = new \phpseclib\Crypt\RSA();
        $modulus = new \phpseclib\Math\BigInteger(base64_decode($modulus), 256);
        $exponent = new \phpseclib\Math\BigInteger(base64_decode($exponent), 256);
        $rsa->loadKey(['n' => $modulus, 'e' => $exponent]);
        $rsa->setPublicKey();
        return $rsa->getPublicKey();
    }

}
function add($info){
	return trim($info);
}
function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}


	function p_obtener_aleatorio() {
		srand(make_seed());
		return rand(365421, 999999);		
	}
	
