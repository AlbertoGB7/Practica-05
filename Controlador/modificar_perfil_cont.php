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
    $errors = []; // Array para almacenar los errores

    // Validar nuevo nombre
    if ($nouNom && $nouNom !== $dadesUsuari['usuari']) {
        // Intentar actualizar el nombre de usuario
        try {
            actualitzarNomUsuari($usuari, $nouNom);
            $_SESSION['usuari'] = $nouNom; // Actualizamos la sesión
            $_SESSION['missatge_exit'] = "Nom d'usuari modificat amb èxit!";
        } catch (PDOException $e) {
            // Capturar el error de duplicado de usuario
            if ($e->getCode() == 23000) { // Código de error para restricción de clave única
                $errors[] = "Aquest nom d'usuari ja està en ús.";
            } else {
                $errors[] = "Error en actualitzar el nom d'usuari: " . $e->getMessage();
            }
        }
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
            } else {
                $errors[] = "Error en pujar la imatge de perfil.";
            }
        } else {
            $errors[] = "Format d'imatge no permès.";
        }
    }

    // Si hay errores, los almacenamos en la sesión
    if (count($errors) > 0) {
        $_SESSION['missatge'] = implode('<br>', $errors); // Unimos los errores en una cadena
    }

    // Redirigir a la página de modificación de perfil
    header("Location: ../Vistes/modificar_perfil.php");
    exit();
}
