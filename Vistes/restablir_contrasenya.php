<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
    <title>Restablir Contrasenya</title>
</head>
<body class="fons_canvi_pass">
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="titol_canvi_pass">Restablir Contrasenya</h3>

              <?php
              session_start();
              if (isset($_SESSION['missatge'])) {
                  echo "<p style='color: red;'>" . $_SESSION['missatge'] . "</p>";
                  unset($_SESSION['missatge']);
              }
              ?>

              <form method="POST" action="../Controlador/restablir_contrasenya_cont.php">
                  <div class="form-outline mb-4">
                      <label for="correu" class="form-label">Correu electrònic</label>
                      <input type="email" id="correu" name="correu" class="form-control bg-dark text-white" required>
                  </div>

                  <button class="btn btn-primary btn-lg btn-block" type="submit">Enviar</button>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
