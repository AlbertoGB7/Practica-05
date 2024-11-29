<?php
require '../Model/UsuariModel.php';
require '../lib/vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correu'])) {
    $correu = filter_var($_POST['correu'], FILTER_VALIDATE_EMAIL);
    if (!$correu) {
        $_SESSION['missatge'] = 'Correu electrònic no vàlid.';
        header('Location: ../Vistes/restablir_contrasenya.php');
        exit;
    }

    // Verificar si el correo existe en la base de datos
    $usuari = buscarUsuariPerCorreu($correu);
    if (!$usuari) {
        $_SESSION['missatge'] = 'No hi ha cap usuari registrat amb aquest correu.';
        header('Location: ../Vistes/restablir_contrasenya.php');
        exit;
    }

    // Generar un token
    $token = bin2hex(random_bytes(16));
    guardarTokenRecuperacio($usuari['id'], $token);

    // Enviar el correu amb PHPMailer
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'a.gonzalez7@sapalomera.cat';
        $mail->Password = 'wrjb rfpy gpbf nhsa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('a.gonzalez7@sapalomera.cat', 'Administrador');
        $mail->addAddress($correu);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperació de contrasenya';
        $mail->Body = "Prem el següent link per restablir la contrasenya: 
        <a href='http://localhost/Vistes/confirmar_contrasenya.php?token=$token'>Restablir contrasenya</a>";

        $mail->send();
        $_SESSION['missatge_exit'] = 'Correu enviat correctament.';
    } catch (Exception $e) {
        $_SESSION['missatge'] = 'No s\'ha pogut enviar el correu. Error: ' . $mail->ErrorInfo;
    }

    header('Location: ../Vistes/restablir_contrasenya.php');
}
