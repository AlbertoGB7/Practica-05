<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
</head>
<body>
<section class="fons_registre">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card card-blur shadow-2-strong text-white">
          <div class="card-body p-5 text-center">
              <h3 class="mb-5">Registre</h3>

              <form method="POST" action="../Login/login_controlador.php">
                  <input type="hidden" name="accion" value="registro"> <!-- Añadir acción para el controlador -->
                  
                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeUsuari">Usuari</label>
                      <input type="text" id="typeUsuari" name="usuari_reg" class="form-control form-control-sm bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeEmail">Correu</label>
                      <input type="email" id="typeEmail" name="email" class="form-control form-control-sm bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typePassword">Password</label>
                      <input type="password" id="typePassword" name="pass" class="form-control form-control-sm bg-dark text-white" />
                  </div>

                  <div class="form-outline mb-4">
                      <label class="form-label" for="typeConfirmPassword">Confirmar Password</label>
                      <input type="password" id="typeConfirmPassword" name="confirm_pass" class="form-control form-control-sm bg-dark text-white" />
                  </div>

                  <button class="btn btn-primary btn-sm btn-block mt-3" type="submit">Registrarse</button>

                  <p class="mt-3">Ja tens compte? 
                    <a href="../Vistes/login_nou.php" class="text-decoration-none text-primary">Login</a>
                  </p>

                  <hr class="my-3 text-white">

                  <button class="btn btn-sm btn-block" style="background-color: #dd4b39; color: white;" type="button">
                      <img src="../Imatges/googleG.svg.png" alt="Google" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                      Sign in with Google
                  </button>

                  <button class="btn btn-sm btn-block mb-2" style="background-color: #3b5998; color: white;" type="button">
                      <img src="../Imatges/facebookF.svg.png" alt="Facebook" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                      Sign in with Facebook
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
