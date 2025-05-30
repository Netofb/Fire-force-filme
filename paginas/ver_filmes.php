<?php
require_once '../conexao.php';

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

} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Filmes Cadastrados</title>
    <style>
        body { font-family: sans-serif; text-align: center; background: #f9f9f9; }
        table { margin: 30px auto; border-collapse: collapse; width: 90%; max-width: 1000px; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        img { width: 80px; }
        a.btn { padding: 6px 12px; margin: 0 4px; text-decoration: none; border-radius: 5px; }
        .edit { background: orange; color: white; }
        .delete { background: red; color: white; }
        .add { background: green; color: white; margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>🎬 Lista de Filmes</h1>
    <a class="btn add" href="../painel.php">➕ Adicionar Novo Filme</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Capa</th>
            <th>Link</th>
            <th>Tipo</th>
            <th>Categorias</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($filmes as $filme): ?>
            <tr>
                <td><?= htmlspecialchars($filme['id']) ?></td>
                <td><?= htmlspecialchars($filme['titulo']) ?></td>
                <td>
                    <img src="<?= htmlspecialchars($filme['capa_url']) ?>" alt="<?= htmlspecialchars($filme['titulo']) ?>">
                </td>
                <td>
                    <a href="<?= htmlspecialchars($filme['link']) ?>" target="_blank">Assistir</a>
                </td>
                <td><?= htmlspecialchars($filme['tipo']) ?></td>
                <td>
                    <?= !empty($filme['categorias']) && $filme['categorias'][0] != null 
                        ? implode(", ", $filme['categorias']) 
                        : 'Sem categoria' ?>
                </td>
                <td>
                    <a class="btn edit" href="editar-filme.php?id=<?= $filme['id'] ?>">Editar</a>
                    <a class="btn delete" href="excluir-filme.php?id=<?= $filme['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</body>
</html>

