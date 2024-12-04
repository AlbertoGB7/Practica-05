<?php
# Alberto González Benítez, 2n DAW, Pràctica 05 - Social Authentication & Miscel·lània
session_start();
require_once '../Model/UsuariModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'canvi_pass') {
    $usuari = $_SESSION['usuari'];
    $passant = $_POST['passant'];
    $passnova = $_POST['passnova'];
    $rptpass = $_POST['rptpass'];
    
    $errors = [];

    // Obtenir l'usuari de la base de dades
    $usuariBD = obtenirUsuariPerNom($usuari);

    // Verifiquem que la contrasenya antiga sigui correcta
    if (!$usuariBD || !password_verify($passant, $usuariBD['contrasenya'])) {
        $errors[] = "La contrasenya antiga no és correcta.";
    }

    // Verificar que la nova contraseña y la confirmació coincideixin
    if ($passnova !== $rptpass) {
        $errors[] = "La contrasenya nova i la confirmació no coincideixen.";
    }

    // Contrasenya segura
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $passnova)) {
        $errors[] = "La contrasenya ha de contenir 8 caràcters, una majúscula, un número i un símbol.";
    }

    // Si no hi ha errors, actualitzem la contrasenya
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

    header('Location: ../Vistes/modificar_contrasenya.php');
    exit;
}
?>