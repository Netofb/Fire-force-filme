<?php
require_once '../conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id || !is_numeric($id)) {
    die("ID inválido.");
}

// Buscar informações do filme
$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = :id");
$stmt->execute(['id' => $id]);
$filme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$filme) {
    die("Filme não encontrado.");
}

// Buscar todas as categorias
$stmt = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar categorias associadas ao filme
$stmt = $pdo->prepare("SELECT id_categoria FROM filme_categoria WHERE id_filme = :id_filme");
$stmt->execute(['id_filme' => $id]);
$categorias_filme = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $capa_url = $_POST['capa_url'] ?? '';
    $link = $_POST['link'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $destaque = isset($_POST['destaque']) ? $_POST['destaque'] : null;
    $categoriasSelecionadas = $_POST['categorias'] ?? [];

    if (empty($titulo) || empty($capa_url) || empty($link) || empty($genero)) {
        die("Por favor, preencha todos os campos obrigatórios.");
    }

    // Atualizar filme
    $update = "UPDATE filmes SET titulo = :titulo, capa_url = :capa_url, link = :link, genero = :genero, destaque = :destaque WHERE id = :id";
    $stmt = $pdo->prepare($update);
    $stmt->execute([
        'titulo' => $titulo,
        'capa_url' => $capa_url,
        'link' => $link,
        'genero' => $genero,
        'destaque' => $destaque,
        'id' => $id
    ]);

    // Limpar categorias antigas
    $stmt = $pdo->prepare("DELETE FROM filme_categoria WHERE id_filme = :id_filme");
    $stmt->execute(['id_filme' => $id]);

    // Inserir categorias novas
    if (!empty($categoriasSelecionadas)) {
        $insert = $pdo->prepare("INSERT INTO filme_categoria (id_filme, id_categoria) VALUES (:id_filme, :id_categoria)");
        foreach ($categoriasSelecionadas as $categoria) {
            $insert->execute([
                'id_filme' => $id,
                'id_categoria' => $categoria
            ]);
        }
    }

    header("Location: ../painel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Filme</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="painel_geral">
    <main class="painel_container">
        <h1 class="painel_title">Editar Filme</h1>

        <form class="painel_form" method="POST">
            <label class="painel_label" for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($filme['titulo']) ?>" required>

            <label class="painel_label" for="capa_url">URL da Capa:</label>
            <input type="text" name="capa_url" id="capa_url" value="<?= htmlspecialchars($filme['capa_url']) ?>" required>

            <label class="painel_label" for="link">Link do Filme:</label>
            <input type="text" name="link" id="link" value="<?= htmlspecialchars($filme['link']) ?>" required>

            <label class="painel_label" for="genero">Gênero:</label>
            <select name="genero" id="genero" required>
                <option value="">Selecione o gênero</option>
                <option value="Ação" <?= $filme['genero'] === 'Ação' ? 'selected' : '' ?>>Ação</option>
                <option value="Terror" <?= $filme['genero'] === 'Terror' ? 'selected' : '' ?>>Terror</option>
                <option value="Comédia" <?= $filme['genero'] === 'Comédia' ? 'selected' : '' ?>>Comédia</option>
                <option value="Drama" <?= $filme['genero'] === 'Drama' ? 'selected' : '' ?>>Drama</option>
            </select>

            <label class="painel_label" for="categorias">Categorias:</label>
            <select name="categorias[]" id="categorias" multiple>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= in_array($categoria['id'], $categorias_filme) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="painel_label" for="destaque">Destaque:</label>
            <select name="destaque" id="destaque">
                <option value="" <?= $filme['destaque'] === null ? 'selected' : '' ?>>Não</option>
                <option value="1" <?= $filme['destaque'] == 1 ? 'selected' : '' ?>>Sim</option>
            </select>

            <button type="submit">Salvar Alterações</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            <a href="../painel.php" style="color: #ff4500;">← Voltar para o painel</a>
        </p>
    </main>
</body>
</html>
