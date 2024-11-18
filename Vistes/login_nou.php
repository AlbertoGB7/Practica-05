<?php
// Iniciar la sesión para mostrar los mensajes de error
session_start();

$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$password = ''; // No rellenamos automáticamente el campo de contraseña

// Verificar si hay un token "remember me" en la cookie
if (isset($_COOKIE['remember_me_token'])) {
  // Incluir el modelo de usuarios
  require_once "../Model/UsuariModel.php";

  // Obtener el usuario utilizando el token
  $user = obtenirUsuariPerToken($_COOKIE['remember_me_token']);

  // Si el usuario existe, rellenar los campos username y email
  if ($user) {
    $username = $user['usuari'];
    $email = $user['email'];
  }
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
</head>
<body class="fons_login">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="mb-5">Login</h3>

              <?php
              // Mostrar mensajes de error o éxito si existen
              if (isset($_SESSION['missatge'])) {
                  echo "<p style='color: red; font-family: \"Calligraffitti\", cursive;'>" . $_SESSION['missatge'] . "</p>";
                  unset($_SESSION['missatge']);
              }
              ?>

              <form method="POST" action="../Login/login_controlador.php">
                  <input type="hidden" name="accion" value="login">
                  
                  <div class="form-outline mb-4">
                      <label class="form-label" for="usuari">Usuari</label>
                      <input type="text" id="usuari" name="usuari" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="email">Correu</label>
                      <input type="email" id="email" name="email" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="pass">Password</label>
                      <input type="password" id="pass" name="pass" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <label>
                      <input type="checkbox" name="recordar"> Recordar-me
                  </label>

                  <p class="mt-3">Et vols registrar? 
                    <a href="../Vistes/registre_nou.php" class="text-decoration-none text-primary">Registrarse</a>
                  </p>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>

                  <hr class="my-4">

                  <button class="btn btn-lg btn-block btn-primary" style="background-color: #dd4b39;" type="button">
                      <img src="../Imatges/googleG.svg.png" alt="Google" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                      Sign in amb Google
                  </button>

                  <button class="btn btn-lg btn-block btn-primary mb-2" style="background-color: #3b5998;" type="button">
                      <img src="../Imatges/facebookF.svg.png" alt="Facebook" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                      Sign in amb Facebook
                  </button>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
