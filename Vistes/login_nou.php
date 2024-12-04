<?php
# Alberto González Benítez, 2n DAW, Pràctica 05 - Social Authentication & Miscel·lània
session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                  echo "<div class='alert alert-danger alert-custom' role='alert'>" . $_SESSION['missatge'] . "</div>";
                  unset($_SESSION['missatge']);
              }

              if (isset($_SESSION['missatge_exit'])) {
                  echo "<div class='alert alert-success alert-custom' role='alert'>" . $_SESSION['missatge_exit'] . "</div>";
                  unset($_SESSION['missatge_exit']);
              }
              ?>

              <form method="POST" action="../Login/login_controlador.php">
                  <input type="hidden" name="accion" value="login">
                  
                  <div class="form-outline mb-4">
                      <label class="form-label" for="usuari">Usuari</label>
                      <input type="text" id="usuari" name="usuari" class="form-control form-control-lg bg-dark text-white" value="<?php echo isset($_SESSION['usuari']) ? $_SESSION['usuari'] : ''; ?>" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="email">Correu</label>
                      <input type="email" id="email" name="email" class="form-control form-control-lg bg-dark text-white" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
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

                  <p class="mt-3">Has oblidat la contrasenya?
                    <a href="../Vistes/enviar_correu.php" class="text-decoration-none text-primary">Restablir contrasenya</a>
                  </p>

                  <?php if (isset($_SESSION['intentos_fallidos']) && $_SESSION['intentos_fallidos'] >= 3): ?>
                  <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="6LfI_IsqAAAAAI6klpIc6PmZ6iIOHqG-xflHDVNt"></div>
                  </div>
                  <?php endif; ?>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button><br>

                  <hr class="my-4">

                  <button class="login-with-google-btn" type="button" 
                    onclick="window.location.href='https://accounts.google.com/o/oauth2/v2/auth?client_id=1020951802216-pg83jpfqovemvuj6ufvtjup1c7gae90g.apps.googleusercontent.com&redirect_uri=http://localhost/Practiques/Practica-05/Controlador/soc_aut.php&response_type=code&scope=email profile'">
                    Entrar amb Google
                </button>

                <!-- Botón para iniciar sesión con GitHub -->
                <a href="../Controlador/hyb_aut.php" class="login-with-github-btn" type="button">
                    Entrar amb GitHub
                </a>

              </form>
          </div>
        </div>
      </div>
    </div>  
  </div>
</section>
</body>
</html>
