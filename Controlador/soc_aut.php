<?php
require_once '../vendor/autoload.php';
require_once '../Model/UsuariModel.php';

// Configuració de l'API de Google
$clientID = '1020951802216-pg83jpfqovemvuj6ufvtjup1c7gae90g.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/Practiques/Practica-05/Controlador/soc_aut.php';

// Creem el client de Google
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    try {

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);
        
        // Obtenim l'informació de l'usuari
        // Dona error però funciona correctament:
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $nombreCompleto = $google_account_info->name;

        // Generem un nom d'un usuari aleatori
        $nombreUsuario = 'Usuari' . rand(1000, 9999); // Esto generará un nombre como "Usuari2341"

        // Guardem l'usuari a la base de dades si no existeix
        $existingUser = obtenirUsuariPerCorreu($email);
        if (!$existingUser) {
            // Si l'usuari no existeix, l'afegim a la base de dades
            inserirUsuariGoogle($nombreUsuario, null, $email, true);
        }

        session_start();
        $_SESSION['usuari'] = $existingUser ? $existingUser['usuari'] : $nombreUsuario;
        header('Location: ../Vistes/index_usuari.php'); // Redirigim a la pàgina d'inici
        exit;
    } catch (Exception $e) {
        die('Error durante la autenticación: ' . $e->getMessage());
    }
} else {
    
    header('Location: ../Vistes/login_nou.php');
    exit;
}
?>
