<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
// ✅ Captura da categoria selecionada na URL, se existir
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;

try {
    $sql = "
        SELECT 
            filmes.id, 
            filmes.titulo, 
            filmes.capa_url, 
            filmes.link, 
            filmes.genero,
            array_agg(categorias.nome) AS categorias
        FROM filmes
        LEFT JOIN filme_categoria ON filmes.id = filme_categoria.id_filme
        LEFT JOIN categorias ON categorias.id = filme_categoria.id_categoria
        GROUP BY filmes.id
        ORDER BY filmes.id ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $filmes = $stmt->fetchAll();

    // ✅ Filtra os filmes pela categoria, se alguma foi selecionada
    $filmesPorCategoria = [];

    foreach ($filmes as $filme) {
        $categorias = $filme['categorias'] ? explode(',', str_replace(['{','}'], '', $filme['categorias'])) : [];

        if ($categoriaSelecionada) {
            if (in_array($categoriaSelecionada, $categorias)) {
                $filmesPorCategoria[$categoriaSelecionada][] = $filme;
            }
        } else {
            foreach ($categorias as $categoria) {
                $filmesPorCategoria[$categoria][] = $filme;
            }
        }
    }

} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="shortcut icon" href="./img/icon.png" type="image/x-icon">
    <title>FIRE FORCE FILMES</title>
</head>
<body>

    <header>
        <a href="index.php"><img src="img/logofire.svg" alt=""></a>
        <nav class="nav">
            <button data-menu="button" id="btn" aria-expanded="false" aria-controls="menu">Menu</button>
            <ul data-menu="List" id="menu">
                <li data-dropdown>
                    <a href="#" class="titulos-nomes">Categorias</a>
                    <ul class="dropdown-menu">
                        <li><a href="?categoria=Ação">Ação</a></li>
                        <li><a href="?categoria=Terror">Terror</a></li>
                        <li><a href="?categoria=Comédia">Comédia</a></li>
                        <li><a href="?categoria=Drama">Drama</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <section class="lancamentos">
        <div class="lancamentos-texto">
            <h1>
                <?php if ($categoriaSelecionada): ?>
                    <?= htmlspecialchars($categoriaSelecionada) ?>
                <?php else: ?>
                    Filmes
                <?php endif; ?>
                <span class="retangulo"></span>
            </h1>
        </div>

        <?php if (!empty($filmesPorCategoria)): ?>
            <?php foreach ($filmesPorCategoria as $categoria => $filmes): ?>
                <section class="slide-wrapper">
                    
                    <ul class="slide">
                        <?php foreach ($filmes as $filme): ?>
                            <li class="container_card">
                                <a href="<?= htmlspecialchars($filme['link']) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($filme['capa_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>">
                                </a>
                                <h2 class="titulo_card"><?= htmlspecialchars($filme['titulo']) ?></h2>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="film_search">NÃO TEMOS FILMES DESSA CATEGORIA NO MOMENTO</p>
        <?php endif; ?>

        <div class="divisoria"></div>
    </section>

    <footer>
        <h1 class="direitos"> &copy; TODOS DIREITOS RESERVADOS A FIRE FORCE FILMES</h1>
    </footer>

    <script type="module" src="scripts/script.js"></script>
    <script type="module" src="scripts/filmes.js"></script>

</body>
</html>
