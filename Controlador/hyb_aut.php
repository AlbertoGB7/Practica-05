<?php
# Alberto González Benítez, 2n DAW, Pràctica 05 - Social Authentication & Miscel·lània
require_once '../vendor/autoload.php';
var_dump(class_exists('Hybridauth\Hybridauth'));
require_once '../Model/UsuariModel.php';

// Carguem el fitxer de configuració de HybridAuth
$config = require_once 'hybridauth_config.php';

use Hybridauth\Hybridauth;
// Inicialitzem la llibreria HybridAuth
try {
    $hybridauth = new Hybridauth($config);

    // Autenticació amb GitHub
    $adapter = $hybridauth->authenticate('github');

    // Obtenir el perfil de l'usuari
    $userProfile = $adapter->getUserProfile();

    // Dades de l'usuari
    $email = $userProfile->email;
    $name = $userProfile->firstName . ' ' . $userProfile->lastName;
    $nombreUsuario = 'Usuari' . rand(1000, 9999); // Nom d'usuari per defecte aleatori

    // Comprovar si l'usuari ja existeix a la base de dades
    $existingUser = obtenirUsuariPerCorreu($email);
    
    if (!$existingUser) {
        // Si l'usuari no existeix, l'afegim a la base de dades
        $hashedPassword = null; //  No es necessita contrasenya per a usuaris socials
        inserirUsuariGoogle($nombreUsuario, $hashedPassword, $email, true); // true indica que és un usuari social
    }

    // Iniciar sessió amb l'usuari
    session_start();
    $_SESSION['usuari'] = $existingUser ? $existingUser['usuari'] : $nombreUsuario;
    header('Location: ../Vistes/index_usuari.php');
    exit;

} catch (Exception $e) {
    // Error durant l'autenticació
    die('Error durante la autenticación: ' . $e->getMessage());
}
