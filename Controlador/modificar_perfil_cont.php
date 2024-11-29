<?php
session_start();
require_once '../Model/UsuariModel.php';

if (!isset($_SESSION['usuari'])) {
    header("Location: ../Login/login.php");
    exit();
}

$usuari = $_SESSION['usuari'];
$dadesUsuari = obtenirUsuariPerNom($usuari);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouNom = $_POST['nou_nom'] ?? null;
    $novaImatge = $_FILES['nova_imatge'] ?? null;

    // Validar nuevo nombre
    if ($nouNom && $nouNom !== $dadesUsuari['usuari']) {
        actualitzarNomUsuari($usuari, $nouNom);
        $_SESSION['usuari'] = $nouNom; // Actualizamos la sesión
    }

    // Validar y procesar nueva imagen
    if ($novaImatge && $novaImatge['error'] === UPLOAD_ERR_OK) {
        $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'webp'];
        $extensio = strtolower(pathinfo($novaImatge['name'], PATHINFO_EXTENSION));

        if (in_array($extensio, $extensionesPermitidas)) {
            $directori = "../Imatges/";
            $nomImatge = uniqid("perfil_") . "." . $extensio;
            $rutaImatge = $directori . $nomImatge;

            if (move_uploaded_file($novaImatge['tmp_name'], $rutaImatge)) {
                actualitzarImatgeUsuari($usuari, $rutaImatge);
            }
        }
    }

    header("Location: ../Vistes/modificar_perfil.php");
    exit();
}
