<?php 
require_once "../Model/ArticlesModel.php";
require_once "../Model/connexio.php";

// Obtenim la connexió a la base de dades
$connexio = connectarBD();

// Variables inicials
$terme_cerca = isset($_GET['terme']) ? trim($_GET['terme']) : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'data_desc';
$articles_per_pagina = isset($_GET['articles_per_pagina']) ? (int)$_GET['articles_per_pagina'] : 5;

// Página actual
if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int)$_GET['pagina'];
} else {
    $pagina_actual = 1;
}

// Si intenten posar una pàgina inferior a 1, redirigeix
if ($pagina_actual < 1) {
    header("Location: ?pagina=1");
    exit();
}

// Variables per als resultats i la paginació
$resultats = [];
$total_articles = 0;
$total_pagines = 0;

if ($terme_cerca !== '') {
    // Cerca articles pel terme
    $resultats_totals = cercarArticles($terme_cerca, $connexio);
    $total_articles = count($resultats_totals);

    // Ordenació dels resultats trobats
    if ($orden === 'titol_asc') {
        usort($resultats_totals, fn($a, $b) => strcmp($a['titol'], $b['titol']));
    } elseif ($orden === 'titol_desc') {
        usort($resultats_totals, fn($a, $b) => strcmp($b['titol'], $a['titol']));
    } elseif ($orden === 'data_asc') {
        usort($resultats_totals, fn($a, $b) => strcmp($a['data'], $b['data']));
    } else { // data_desc
        usort($resultats_totals, fn($a, $b) => strcmp($b['data'], $a['data']));
    }

    // Paginació dels resultats
    $total_pagines = ceil($total_articles / $articles_per_pagina);
    $offset = ($pagina_actual - 1) * $articles_per_pagina;
    $resultats = array_slice($resultats_totals, $offset, $articles_per_pagina);
} else {
    // Obtenir el número total d'articles
    $total_articles = obtenirTotalArticles($connexio);
    $total_pagines = ceil($total_articles / $articles_per_pagina);

    // Si intenten posar una pàgina superior al total, redirigeix
    if ($pagina_actual > $total_pagines && $total_pagines > 0) {
        header("Location: ?pagina=1");
        exit();
    }

    // Calcula l'offset per a la consulta
    $offset = ($pagina_actual - 1) * $articles_per_pagina;

    // Obtenir els articles segons l'ordenació
    switch ($orden) {
        case 'titol_asc':
            $resultats = obtenirArticlesOrdenatsPerTitolAsc($offset, $articles_per_pagina, $connexio);
            break;
        case 'titol_desc':
            $resultats = obtenirArticlesOrdenatsPerTitolDesc($offset, $articles_per_pagina, $connexio);
            break;
        case 'data_asc':
            $resultats = obtenirArticlesOrdenatsPerDataAsc($offset, $articles_per_pagina, $connexio);
            break;
        default: // data_desc
            $resultats = obtenirArticlesOrdenatsPerDataDesc($offset, $articles_per_pagina, $connexio);
            break;
    }
}

// Funció per mostrar els articles
function mostrarTaula($resultats){
    echo "<div class='table-wrapper'>";
    echo "<table class='fl-table'>
    <tr>
    <th>Títol</th>
    <th>Cos</th>
    </tr>";

    foreach($resultats as $res) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($res['titol']) . "</td>";
        echo "<td>" . htmlspecialchars($res['cos']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}

// Generar la paginació
function mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina, $orden) {
    echo "<div class='pagination'>";

    if ($pagina_actual > 1) {
        echo "<a href='?pagina=1&articles_per_pagina=$articles_per_pagina&orden=$orden'>&laquo;</a>";
        echo "<a href='?pagina=" . ($pagina_actual - 1) . "&articles_per_pagina=$articles_per_pagina&orden=$orden'>&lsaquo;</a>";
    }

    for ($i = 1; $i <= $total_pagines; $i++) {
        if ($i == $pagina_actual) {
            echo "<a class='active' href='?pagina=$i&articles_per_pagina=$articles_per_pagina&orden=$orden'>$i</a>";
        } else {
            echo "<a href='?pagina=$i&articles_per_pagina=$articles_per_pagina&orden=$orden'>$i</a>";
        }
    }

    if ($pagina_actual < $total_pagines) {
        echo "<a href='?pagina=" . ($pagina_actual + 1) . "&articles_per_pagina=$articles_per_pagina&orden=$orden'>&rsaquo;</a>";
        echo "<a href='?pagina=$total_pagines&articles_per_pagina=$articles_per_pagina&orden=$orden'>&raquo;</a>";
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
    <p class="titol">Taula d'articles</p>
    
    <a href='../Vistes/login_nou.php'>
        <button class="login" role="button">Login/Sign up</button>
    </a>

    <a href='../index.php'>
        <button class="tornar_mostrar" role="button">Anar enrere</button>
    </a>


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

    <!-- Paginació -->
    <?php if ($total_articles > 0) mostrarPaginacio($pagina_actual, $total_pagines, $articles_per_pagina, $orden); ?>

    <div class="box">
    <select id="articles" onchange="location = this.value;">
        <option value="?pagina=<?php echo $pagina_actual; ?>&articles_per_pagina=5&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 5) ? 'selected' : ''; ?>>5 articles</option>
        <option value="?pagina=<?php echo $pagina_actual; ?>&articles_per_pagina=10&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 10) ? 'selected' : ''; ?>>10 articles</option>
        <option value="?pagina=<?php echo $pagina_actual; ?>&articles_per_pagina=15&orden=<?php echo $orden; ?>&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($articles_per_pagina == 15) ? 'selected' : ''; ?>>15 articles</option>
    </select>
</div>

<div class="box_ord">
    <select id="articles" onchange="location = this.value;">
        <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=data_desc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'data_desc') ? 'selected' : ''; ?>>Data (DESC)</option>
        <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=data_asc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'data_asc') ? 'selected' : ''; ?>>Data (ASC)</option>
        <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=titol_desc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'titol_desc') ? 'selected' : ''; ?>>Alfabèticament (DESC)</option>
        <option value="?pagina=1&articles_per_pagina=<?php echo $articles_per_pagina; ?>&orden=titol_asc&terme=<?php echo urlencode($terme_cerca); ?>" <?php echo ($orden === 'titol_asc') ? 'selected' : ''; ?>>Alfabèticament (ASC)</option>
    </select>
</div>


</body>
</html>
