<?php
session_start();
require_once '../Model/UsuariModel.php';

// Verificar que se ha recibido el token y las contraseñas
if (!isset($_POST['token'], $_POST['passnova'], $_POST['rptpass'])) {
    $_SESSION['missatge'] = "Falten dades per restablir la contrasenya.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=" . ($_POST['token'] ?? ''));
    exit();
}

$token = $_POST['token'];
$novaContrasenya = $_POST['passnova'];
$repetirContrasenya = $_POST['rptpass'];

// Validar que las contraseñas coincidan
if ($novaContrasenya !== $repetirContrasenya) {
    $_SESSION['missatge'] = "Les contrasenyes no coincideixen.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=$token");
    exit();
}

// Validar el token y obtener el usuario
$usuari = obtenirUsuariPerTokenRec($token);

if (!$usuari) {
    $_SESSION['missatge'] = "Token invàlid o caducat.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=$token");
    exit();
}

// Actualizar la contraseña del usuario
$hashed_password = password_hash($novaContrasenya, PASSWORD_DEFAULT);
if (actualitzarContrasenyaUsuari($usuari['id'], $hashed_password)) {  // Pasar la contraseña encriptada
    // Eliminar el token después de actualizar la contraseña
    eliminarTokenRecuperacio($usuari['id']);  // Esta función la deberías crear

    $_SESSION['missatge_exit'] = "Contrasenya restablerta correctament.";
} else {
    $_SESSION['missatge'] = "Error en restablir la contrasenya.";
}

header("Location: ../Vistes/restablir_contrasenya.php");
exit();
