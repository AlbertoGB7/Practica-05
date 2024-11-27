<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions

session_start();
require_once "../Model/UsuariModel.php"; // Incloem el model d'usuaris

$errors = [];
$usuari = $password = $confirm_password = "";
$email_reg = "";
$usuari_reg = "";
$recordar = false;

if (isset($_COOKIE['remember_me_token'])) {
    $token = $_COOKIE['remember_me_token'];
    $user = obtenirUsuariPerToken($token);

    if ($user) {
        // Login automático exitoso
        $_SESSION['usuari'] = $user['usuari'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../Vistes/index_usuari.php");
        exit();
    }
}

// Verifiquem si s'ha enviat el formulari:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion']; // Obtenim l'acció (login o registre)
    $usuari = trim($_POST['usuari']);
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);
    $recordar = isset($_POST['recordar']); // Verificar si el checkbox "recordar" està activat


    // Verificar si el reCAPTCHA es necesario
    if (isset($_SESSION['intentos_fallidos']) && $_SESSION['intentos_fallidos'] >= 3) {
        // Verificar reCAPTCHA solo si los intentos fallidos superan 3
        if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha_response = $_POST['g-recaptcha-response'];
            $recaptcha_secret = '6LfI_IsqAAAAABgjh--FUWx4JxaGfUNrUFX33o4z'; // Clave secreta de reCAPTCHA
    
            // Verificar el reCAPTCHA con la API de Google
            $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
            $response = file_get_contents($recaptcha_verify_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $response_keys = json_decode($response, true);
    
            if (intval($response_keys["success"]) !== 1) {
                $_SESSION['missatge'] = "Si us plau, verifica que no ets un robot.";
                header("Location: ../Vistes/login_nou.php");
                exit();
            }
        }
    }

    if ($accion == 'login') {
        // Processar login
        if (empty($usuari)) {
            $errors[] = "El camp 'Usuari' és obligatori.";
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori.";
        }

        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors);
            $_SESSION['usuari'] = $usuari;
            $_SESSION['email'] = $email;

            $_SESSION['intentos_fallidos'] = isset($_SESSION['intentos_fallidos']) ? $_SESSION['intentos_fallidos'] + 1 : 1;
        } else {
            // Verificar que l'usuari existeix
            $user = obtenirUsuariPerNom($usuari); // Fem servir la funció del model

            if ($user && password_verify($password, $user['contrasenya'])) {
                // Autenticació exitosa
                $_SESSION['usuari'] = $user['usuari'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['start_time'] = time();
                $_SESSION['intentos_fallidos'] = 0; // Resetear intentos fallidos
                header("Location: ../Vistes/index_usuari.php");
                exit();

                // Configurar cookie para mantener la sesión activa si el usuario vol
                if ($recordar) {
                    $token = bin2hex(random_bytes(16)); // Generar un token aleatori
                    guardarToken($usuari, $token); // Guardar token al model
                    setcookie('remember_me_token', $token, time() + (60 * 60 * 24 * 7), '/'); // Cookie de 7 días
                } else {
                    // Si no se marca la casilla "recordar", borrar la cookie
                    setcookie('remember_me_token', '', time() - 3600, '/');
                }

                setcookie('login_exitos', '1', time() + 60, '/');
                header("Location: ../Vistes/index_usuari.php");
                exit();
            } else {
                // Credencials incorrectes
                $_SESSION['missatge'] = "Usuari o contrasenya incorrectes";
                $_SESSION['usuari'] = $usuari;
                $_SESSION['email'] = $email;

                $_SESSION['intentos_fallidos'] = isset($_SESSION['intentos_fallidos']) ? $_SESSION['intentos_fallidos'] + 1 : 1;
            }
        }


    } elseif ($accion == 'registro') {
        // Processar registre
        $confirm_password = trim($_POST['confirm_pass']);
        $usuari_reg = trim($_POST['usuari_reg']);
        $email_reg = trim($_POST['email_reg']);

        if (empty($usuari_reg)) {
            $errors[] = "El camp 'Usuari' és obligatori.";
        }
        if (empty($password)) {
            $errors[] = "El camp 'Contrasenya' és obligatori.";
        }
        if (empty($email_reg) || !filter_var($email_reg, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correu ha de ser vàlid.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les contrasenyes no coincideixen.";
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
            $errors[] = "La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol.";
        }

        if (!empty($errors)) {
            $_SESSION['missatge'] = implode("<br>", $errors);
            $_SESSION['usuari_reg'] = $usuari_reg;
            $_SESSION['email_reg'] = $email_reg;
        } else {
            // Verifiquem si l'usuari o el correu ja existeixen
            $existing_user = obtenirUsuariPerNom($usuari_reg);
            $existing_email = obtenirUsuariPerCorreu($email_reg);

            if ($existing_user) {
                $_SESSION['missatge'] = "El nom d'usuari ja està agafat";
                $_SESSION['usuari_reg'] = $usuari_reg;
            } elseif ($existing_email) {
                $_SESSION['missatge'] = "Aquest correu ja està registrat";
            } else {
                // Insertem el nou usuari
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                inserirUsuari($usuari_reg, $hashed_password); // Funció del model

                $_SESSION['missatge_exit'] = "Registrat amb èxit!";
                $_SESSION['usuari_reg'] = "";
                $_SESSION['email_reg'] = "";
            }
        }
    }

    header("Location: ../Vistes/login_nou.php");
    exit();
}

// Netejar les variables de sessió al carregar la pàgina per primera vegada
if (!isset($_SESSION['usuari'])) {
    $_SESSION['usuari'] = "";
}
if (!isset($_SESSION['usuari_reg'])) {
    $_SESSION['usuari_reg'] = "";
}
?>
