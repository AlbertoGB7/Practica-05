<?php
# Alberto González Benítez, 2n DAW, Pràctica 04 - Inici d'usuaris i registre de sessions
require_once '../Model/UsuariModel.php';
if (isset($_SESSION['usuari'])) {
    $usuari = $_SESSION['usuari'];
} else {
    $usuari = "Invitat";
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <!-- Text de benvinguda -->
    <nav class="navbar bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand">
      <?php 
      $usuari = $_SESSION['usuari'];
      $dadesUsuari = obtenirUsuariPerNom($usuari);
      $imatgePerfil = $dadesUsuari['imatge'] ?? '../Imatges/def_user.jpeg';
      ?>
      <img src="<?= htmlspecialchars($imatgePerfil) ?>" alt="Avatar" class="rounded-circle" width="50" height="50">
    </a>
  </div>
  </nav>
    <a class="navbar-brand">Benvingut <?php echo htmlspecialchars($usuari); ?></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="d-flex ms-auto" role="search">
      <div class="btn-group">
    <button class="btn btn-secondary btn-lg dropdown-toggle mx-5" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?php echo htmlspecialchars($usuari); ?>
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="modificar_perfil.php">Modificar perfil</a></li>
      <?php if ($dadesUsuari['aut_social'] === 'no'): ?>
      <li><a class="dropdown-item" href="modificar_contrasenya.php">Canvi de contrasenya</a></li>
      <?php endif; ?>
      <li><hr class="dropdown-divider"></li>
      <input type="hidden" name="logout" value="1">
      <li><button class="dropdown-item" type="submit">Logout</button></li>
    </ul>
  </div>
<div class="btn-group">
</form>

      <?php
      // Si se ha enviat el formulari de logout
      if (isset($_POST['logout'])) {
          $current_page = $_SERVER['SCRIPT_NAME'];
          if (strpos($current_page, 'Vistes/') !== false || strpos($current_page, 'Controlador/') !== false) {
              // Si estem a la carpeta "Vistes:"
              header('Location: ../Login/logout.php');
          } else {
              // Qualsevol altra pàgina
              header('Location: Login/logout.php');
          }
          exit;
      }
      ?>
    </div>
  </div>

  
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>
