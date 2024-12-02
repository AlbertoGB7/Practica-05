<?php
include 'verificar_sessio.php';
include "../Vistes/navbar_view.php";
require_once "../Model/ArticlesModel.php";
require_once "../Model/connexio.php";

$connexio = connectarBD();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php"); 
    exit();
}

$usuari_id = $_SESSION['user_id'];
$articles_per_pagina = isset($_GET['articles_per_pagina']) ? (int)$_GET['articles_per_pagina'] : 5;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$terme_cerca = isset($_GET['terme']) ? trim($_GET['terme']) : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'data_desc';

if ($pagina_actual < 1) {
    header("Location: ?pagina=1");
    exit();
}

// Obtenir el número total d'articles per aquest usuari
$total_articles = obtenirTotalArticlesUsuariCercar($usuari_id, $terme_cerca, $connexio);
$total_pagines = ceil($total_articles / $articles_per_pagina);

if ($pagina_actual > $total_pagines && $total_pagines > 0) {
    header("Location: ?pagina=1");
    exit();
}

$offset = ($pagina_actual - 1) * $articles_per_pagina;

// Obtenir els articles del usuari amb el terme de cerca
$resultats = obtenirArticlesPaginatsCercar($usuari_id, $offset, $articles_per_pagina, $terme_cerca, $connexio);

// Ordenar els resultats segons l'opció seleccionada
if ($orden === 'titol_asc') {
    usort($resultats, fn($a, $b) => strcmp($a['titol'], $b['titol']));
} elseif ($orden === 'titol_desc') {
    usort($resultats, fn($a, $b) => strcmp($b['titol'], $a['titol']));
} elseif ($orden === 'data_asc') {
    usort($resultats, fn($a, $b) => strcmp($a['data'], $b['data']));
} else { // data_desc
    usort($resultats, fn($a, $b) => strcmp($b['data'], $a['data']));
}

// Funció per mostrar els articles
function mostrarTaula($resultats) {
    echo "<div class='table-wrapper'>";
    echo "<table class='fl-table'>
    <tr>
    <th>ID</th>
    <th>Títol</th>
    <th>Cos</th>
    </tr>";

    foreach($resultats as $res) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($res['ID']) . "</td>";
        echo "<td>" . htmlspecialchars($res['titol']) . "</td>";
        echo "<td>" . htmlspecialchars($res['cos']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}

// Funció per mostrar la paginació
function mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina, $orden, $terme_cerca) {
    echo "<div class='pagination'>";
    $encoded_term = urlencode($terme_cerca);

    if ($pagina_actual > 1) {
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina&orden=$orden&terme=$encoded_term'>&laquo;</a>";
        echo "<a href='?pagina=" . ($pagina_actual - 1) . "&articles_per_pagina=$articles_per_pagina&orden=$orden&terme=$encoded_term'>&lsaquo;</a>";
    }

    for ($i = 1; $i <= $total_pagines; $i++) {
        $class = $i === $pagina_actual ? "class='active'" : "";
        echo "<a $class href='?pagina=$i&articles_per_pagina=$articles_per_pagina&orden=$orden&terme=$encoded_term'>$i</a>";
    }

    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=" . ($pagina_actual + 1) . "&articles_per_pagina=$articles_per_pagina&orden=$orden&terme=$encoded_term'>&rsaquo;</a>";
        echo "<a href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina&orden=$orden&terme=$encoded_term'>&raquo;</a>";
    }

    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../CSS/estils.css">
    <title>Taula d'articles</title>
</head>
<body>
    <p class="titol">Taula d'articles</p><br><br>

            <!-- Barra de cerca -->
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="hidden" name="pagina" value="1">
                    <input type="hidden" name="articles_per_pagina" value="<?php echo $articles_per_pagina; ?>">
                    <input type="text" class="boton-search" name="terme" placeholder="Titol..." value="<?php echo htmlspecialchars($terme_cerca); ?>">
                    <button type="submit" class="boton-lupa"><i class="fas fa-search"></i></button>
                </form>
            </div>

    <br><br>

    <div class="articulos">
        <h2>
            <?php mostrarTaula($resultats); ?>
        </h2>
    </div>

    <?php mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina, $orden, $terme_cerca); ?>

    <div class="box">
        <select id="articles" onchange="location = this.value;">
            <option value="?pagina=1&articles_per_pagina=5&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 5) ? 'selected' : ''; ?>>5 articles</option>
            <option value="?pagina=1&articles_per_pagina=10&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 10) ? 'selected' : ''; ?>>10 articles</option>
            <option value="?pagina=1&articles_per_pagina=15&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 15) ? 'selected' : ''; ?>>15 articles</option>
        </select>
    </div>

    <div class="box_ord">
        <select id="articles" onchange="location = this.value;">
            <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=data_desc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'data_desc') ? 'selected' : ''; ?>>Data (DESC)</option>
            <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=data_asc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'data_asc') ? 'selected' : ''; ?>>Data (ASC)</option>
            <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=titol_desc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'titol_desc') ? 'selected' : ''; ?>>Títol (DESC)</option>
            <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=titol_asc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'titol_asc') ? 'selected' : ''; ?>>Títol (ASC)</option>
        </select>
    </div>

    <div>
            <a href="../Vistes/index_usuari.php">
                <button type="button" class="tornar" role="button">Anar enrere</button>
            </a>
        </div>

</body>

</html>
