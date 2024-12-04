<?php
# Alberto González Benítez, 2n DAW, Pràctica 05 - Social Authentication & Miscel·lània
session_start();
require_once '../Model/UsuariModel.php';


// Verificar que les contrasenyes i el token s'han enviat
if (!isset($_POST['token'], $_POST['passnova'], $_POST['rptpass'])) {
    $_SESSION['missatge'] = "Falten dades per restablir la contrasenya.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=" . ($_POST['token'] ?? ''));
    exit();
}

$token = $_POST['token'];
$novaContrasenya = $_POST['passnova'];
$repetirContrasenya = $_POST['rptpass'];

// Verificar que les contrasenyes coincideixen
if ($novaContrasenya !== $repetirContrasenya) {
    $_SESSION['missatge'] = "Les contrasenyes no coincideixen.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=$token");
    exit();
}

// Verificar que la contrasenya sigui segura
if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $novaContrasenya)) {
    $_SESSION['missatge'] = "La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=$token");
    exit();
}

// Verificar que el token sigui vàlid
$usuari = obtenirUsuariPerTokenRec($token);

if (!$usuari) {
    $_SESSION['missatge'] = "Token invàlid o caducat.";
    header("Location: ../Vistes/restablir_contrasenya.php?token=$token");
    exit();
}

// Actualitzar la contrasenya
$hashed_password = password_hash($novaContrasenya, PASSWORD_DEFAULT);
if (actualitzarContrasenyaUsuari($usuari['id'], $hashed_password)) {
    // Eliminar el token de recuperació
    eliminarTokenRecuperacio($usuari['id']);

    $_SESSION['missatge_exit'] = "Contrasenya restablerta correctament.";
} else {
    $_SESSION['missatge'] = "Error en restablir la contrasenya.";
}

header("Location: ../Vistes/login_nou.php");
exit();
