<?

error_reporting(E_ALL);
ini_set('display_errors', '1');

$variables = leer_vars("c_");

echo "<pre>";
print_r ( $_REQUEST );
$variables = leer_vars("c_");
print_r ( $variables );


function leer_vars($prefijo="")
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        foreach($_POST as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
                $items['valor']     = $value;
                if($nombre) $variables[] = $items;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        foreach($_GET as $key => $value) {
            if(preg_match ("/^" . $prefijo . "\S+$/i", $key))
            {
                $nombre = str_replace($prefijo, '', $key);
                $items['nombre']    = $nombre;
                $items['valor']     = $value;
				$items['tvalor']     = utf8_encode ( $value );
                if($nombre) $variables[] = $items;
            }
        }
    }
    return $variables;
}

function Mayuscula( $s ){
	$s = strtoupper ( $s );
	$search  = array('à', 'è', 'ì', 'ò', 'ù', 'á', 'é', 'í', 'ó', 'ú', 'ñ');
	$replace = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
	return str_replace($search, $replace, $s);
}




?>
