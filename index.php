<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();
require_once "Model/UsuariModel.php"; // Asegúrate de que esta ruta sea correcta

// Comprobar si ya hay una sesión activa
if (isset($_SESSION['usuari']) && isset($_SESSION['user_id'])) {
    header("Location: Vistes/index_usuari.php");
    exit();
}

// Comprobar si existe la cookie 'remember_me_token'
if (isset($_COOKIE['remember_me_token'])) {
    $token = $_COOKIE['remember_me_token'];

    // Función que busca un usuario basado en el token
    $user = obtenirUsuariPerToken($token);

    if ($user) {
        // Login automático exitoso: establecer sesión
        $_SESSION['usuari'] = $user['usuari'];
        $_SESSION['user_id'] = $user['id'];

        // Regenerar el token para mayor seguridad
        $nuevoToken = bin2hex(random_bytes(32));
        guardarToken($user['id'], $nuevoToken); // Actualizar en la base de datos
        setcookie('remember_me_token', $nuevoToken, time() + 60 * 60 * 24 * 30, '/', '', true, true); // Establecer nueva cookie

        header("Location: Vistes/index_usuari.php");
        exit();
    }
}

// Si no hay sesión ni cookie válida, mostrar la página de inicio de sesión o index
// header("Location: index.php");
// exit();


include "Login/missatge_logout.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Pràctica 5 - Social Authentication & Miscel·lània</title>
    <link rel="stylesheet" type="text/css" href="CSS/estils.css">
</head>
<body>
    <form method="POST" action="../Database/connexio.php">
        <h2>
            <p class="titol">Selecciona una opció</p><br>
            <input type="submit" value="Mostrar articles" class="boto" name="select" formaction="Controlador/mostrar.php">
        </h2>
    </form>

    <a href='Vistes/login_nou.php'>
        <button class="login" role="button">Login/Sign up</button>
    </a>
</body>
</html>
