<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
</head>
<body class="fons_canvi_pass">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="titol_canvi_pass">Restablir contrasenya</h3><br><br>

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

              <form method="POST" action="../Controlador/restablir_contrasenya_cont.php">
                  <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

                  <div class="form-outline mb-4 text-dark">
                      <label for="passnova" class="lletra_canvi_pass">Nova contrasenya</label>
                      <input type="password" id="passnova" name="passnova" class="form-control form-control-lg bg-dark text-white" required />
                  </div>

                  <div class="form-outline mb-4 text-dark">
                      <label for="rptpass " class="lletra_canvi_pass">Repetir contrasenya</label>
                      <input type="password" id="rptpass" name="rptpass" class="form-control form-control-lg bg-dark text-white" required />
                  </div>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Restablir contrasenya</button>
                  <a href="../Vistes/login_nou.php">
                        <button type="button" class="btn btn-primary btn-lg btn-block" role="button">Anar enrere</button>
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
