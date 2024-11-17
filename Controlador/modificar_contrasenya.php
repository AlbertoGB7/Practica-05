<?php
session_start();
require_once '../Model/UsuariModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'canvi_pass') {
    $usuari = $_SESSION['usuari']; // Usuario autenticado
    $passant = $_POST['passant'];
    $passnova = $_POST['passnova'];
    $rptpass = $_POST['rptpass'];
    
    $errors = [];

    // Obtener el usuario actual de la base de datos
    $usuariBD = obtenirUsuariPerNom($usuari);

    // Verificar que la contraseña antigua sea correcta
    if (!$usuariBD || !password_verify($passant, $usuariBD['contrasenya'])) {
        $errors[] = "La contrasenya antiga no és correcta.";
    }

    // Verificar que la nueva contraseña y la repetida coincidan
    if ($passnova !== $rptpass) {
        $errors[] = "La contrasenya nova i la confirmació no coincideixen.";
    }

    // Verificar que la nueva contraseña sea segura
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $passnova)) {
        $errors[] = "La contrasenya ha de contenir 8 caràcters, una majúscula, un número i un símbol.";
    }

    // Si no hay errores, actualizamos la contraseña
    if (empty($errors)) {
        $novaContrasenyaHashed = password_hash($passnova, PASSWORD_DEFAULT);
        if (actualitzarContrasenya($usuari, $novaContrasenyaHashed)) {
            $_SESSION['missatge_exit'] = "Contrasenya actualitzada correctament.";
        } else {
            $_SESSION['missatge'] = "Error en actualitzar la contrasenya. Torna-ho a intentar.";
        }
    } else {
        $_SESSION['missatge'] = implode('<br>', $errors);
    }

    // Redirigir al formulario de cambio de contraseña
    header('Location: ../Vistes/modificar_contrasenya.php');
    exit;
}
?>