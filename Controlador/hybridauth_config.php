<?php
return [
    'callback' => 'http://localhost/Practiques/Practica-05/Controlador/hyb_aut.php',
    'debug_mode' => true,
    'debug_file' => __DIR__ . '/hybridauth.log', // Ruta donde se guardará el log
    'providers' => [
        'github' => [
            'enabled' => true,
            'keys' => [
                'id' => 'Ov23liQKstdFfgFGP9Bo',
                'secret' => '127ec9f98bb7f93ebe1eac040b15d6d6dc16aa86',
            ],
            'scope' => 'user:email',
        ],
    ],
];

?>