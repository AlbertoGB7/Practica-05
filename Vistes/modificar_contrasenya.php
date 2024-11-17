<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
</head>
<body class="fons_canvi_pass">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="titol_canvi_pass">Canviar contrasenya</h3>
              

              <?php
              session_start();
              if (isset($_SESSION['missatge'])) {
                  echo "<p style='color: red; font-family: \"Calligraffitti\", cursive;'>" . $_SESSION['missatge'] . "</p>";
                  unset($_SESSION['missatge']);
              }
              if (isset($_SESSION['missatge_exit'])) {
                  echo "<p style='color: green; font-family: \"Calligraffitti\", cursive;'>" . $_SESSION['missatge_exit'] . "</p>";
                  unset($_SESSION['missatge_exit']);
              }
              echo '<br>';
              ?>

              <form method="POST" action="../Controlador/modificar_contrasenya.php">
                  <input type="hidden" name="accion" value="canvi_pass">
                  
                  <div class="form-outline mb-4">
                      <label class="lletra_canvi_pass" for="passant">Password antiga</label>
                      <input type="password" id="passant" name="passant" class="form-control form-control-lg bg-dark text-white" value="<?php echo isset($_SESSION['passant']) ? htmlspecialchars($_SESSION['passant']) : ''; ?>" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="lletra_canvi_pass" for="passnova">Password nova</label>
                      <input type="password" id="passnova" name="passnova" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="lletra_canvi_pass" for="rptpass">Repetir password</label>
                      <input type="password" id="rptpass" name="rptpass" class="form-control form-control-lg bg-dark text-white" />
                  </div>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Canviar contrasenya</button>

                  <hr class="my-4">
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
