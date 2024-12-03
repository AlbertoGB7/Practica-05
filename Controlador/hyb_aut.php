<?php
require_once '../vendor/autoload.php';
var_dump(class_exists('Hybridauth\Hybridauth'));
require_once '../Model/UsuariModel.php'; // Tu modelo para manejar la base de datos

// Cargar la configuración de HybridAuth
$config = require_once 'hybridauth_config.php';

use Hybridauth\Hybridauth;
// Iniciar HybridAuth con la configuración
try {
    $hybridauth = new Hybridauth($config);

    // Obtener el adaptador para GitHub
    $adapter = $hybridauth->authenticate('github');

    // Obtener la información del usuario de GitHub
    $userProfile = $adapter->getUserProfile();

    // Obtener correo y generar nombre de usuario
    $email = $userProfile->email;
    $name = $userProfile->firstName . ' ' . $userProfile->lastName; // Puedes personalizarlo como desees
    $nombreUsuario = 'Usuari' . rand(1000, 9999); // Generar nombre de usuario aleatorio

    // Comprobar si el usuario ya existe en la base de datos
    $existingUser = obtenirUsuariPerCorreu($email);
    
    if (!$existingUser) {
        // Si el usuario no existe, creamos uno nuevo
        $hashedPassword = null; // Si es login social, no necesitamos una contraseña
        inserirUsuariGoogle($nombreUsuario, $hashedPassword, $email, true); // Inserción para usuarios sociales (modifica el nombre si es necesario)
    }

    // Crear la sesión y redirigir al usuario
    session_start();
    $_SESSION['usuari'] = $existingUser ? $existingUser['usuari'] : $nombreUsuario;
    header('Location: ../Vistes/index_usuari.php');
    exit;

} catch (Exception $e) {
    // Manejo de errores en la autenticación
    die('Error durante la autenticación: ' . $e->getMessage());
}
