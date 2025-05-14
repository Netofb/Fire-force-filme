<?php
// CONEXÃO COM O BANCO DE DADOS PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "fff"; 
$user = "postgres";       
$password = "fabio99248033";  

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (!$conn) {
    die("Erro ao conectar ao banco de dados.");
}

pg_set_client_encoding($conn, "UTF8");

// FILTRO DE CATEGORIA
$categoriaSelecionada = $_GET['categoria'] ?? null;

// Consulta base
$query = "
    SELECT f.id, f.titulo, f.capa_url, f.link, f.genero, c.nome AS categoria
    FROM filmes f
    JOIN filme_categoria fc ON f.id = fc.id_filme
    JOIN categorias c ON fc.id_categoria = c.id
";

// Se tiver uma categoria selecionada, adiciona o filtro
if ($categoriaSelecionada) {
    $query .= " WHERE c.nome = $1";
    $result = pg_query_params($conn, $query, [$categoriaSelecionada]);
} else {
    $query .= " ORDER BY c.nome, f.titulo";
    $result = pg_query($conn, $query);
}

if (!$result) {
    die("Erro na consulta SQL: " . pg_last_error($conn));
}

$filmes = pg_fetch_all($result);

// Organiza por categoria (se não tiver filtro)
$filmesPorCategoria = [];

if ($filmes) {
    if ($categoriaSelecionada) {
        // Se estiver filtrando, coloca tudo em uma categoria só
        $filmesPorCategoria[$categoriaSelecionada] = $filmes;
    } else {
        foreach ($filmes as $filme) {
            $categoria = $filme['categoria'];
            $filmesPorCategoria[$categoria][] = $filme;
        }
    }
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
                        <li><a href="?categoria=Acao">Ação</a></li>
                        <li><a href="?categoria=Terror">Terror</a></li>
                        <li><a href="?categoria=Comedia">Comédia</a></li>
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
            <p>Nenhum filme encontrado.</p>
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
