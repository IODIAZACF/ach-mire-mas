<?php
//Clave del cliente = ck_3a45a7336cae341f5b3a51412076831156c5cc1a
//Clave secreta de cliente = cs_11169bcc2733a28863de1362124572b041f378d9

require __DIR__ . '/vendor/autoload.php'; 

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'https://tuennosotros.com', 
    'ck_3a45a7336cae341f5b3a51412076831156c5cc1a', 
    'cs_11169bcc2733a28863de1362124572b041f378d9',
    [
        'version' => 'wc/v2',
		'verify_ssl' => false
    ]
);

//print_r( $woocommerce );

$data = [
    'name' => 'Camisa Nautica De Hombre',
    'type' => 'simple',
    'regular_price' => '55.00',
    'description' => 'CAMISA DE HOMBRE MARCA NAUTICA TALLA MEDIUM 100% ORIGINAL',
    'short_description' => 'CAMISA DE HOMBRE MARCA NAUTICA TALLA MEDIUM 100% ORIGINAL.',
    'categories' => [
        [
            'id' => 9
        ],
        [
            'id' => 14
        ]
    ],
    'images' => [
        [
            'src' => 'https://i.pinimg.com/736x/76/78/79/767879584ec8d3fc2fe45c29e43ae77d.jpg'
        ],
        [
            'src' => 'https://i.pinimg.com/736x/76/78/79/767879584ec8d3fc2fe45c29e43ae77d.jpg'
        ]
    ]
];




file_put_contents(__DIR__ .'/products_create.json', json_encode( $woocommerce->post('products', $data) ));
//print_r($woocommerce->post('products', $data));

//file_put_contents(__DIR__ .'/products_list.json', json_encode($woocommerce->get('products')));

?>