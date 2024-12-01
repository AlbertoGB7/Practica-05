<?php
session_start();
require_once "../Model/UsuariModel.php";

if (!isset($_SESSION['usuari']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Vistes/login_nou.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id) {
        // Eliminar articles associats a l'usuari
        eliminarArticlesDeUsuari($id);

        // Eliminar l'usuari
        eliminarUsuari($id);

        $_SESSION['missatge_exit'] = "Usuari eliminat correctament.";
    }
}

header("Location: ../Vistes/gestionar_usuaris.php");
exit();
