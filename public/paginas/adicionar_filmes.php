<?php
require_once __DIR__ . '/../includes/conexao.php';

$titulo = $_POST['titulo'];
$capa_url = $_POST['capa_url'];
$link = $_POST['link'];
$genero = $_POST['genero'];
$categorias = $_POST['categorias'] ?? []; // Array de categorias

try {
    // Inicia a transação
    $pdo->beginTransaction();

    // Inserir o filme na tabela filmes
    $stmt = $pdo->prepare("INSERT INTO filmes (titulo, capa_url, link, genero) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titulo, $capa_url, $link, $genero]);

    // Obter o ID do filme recém-inserido
    $filmeId = $pdo->lastInsertId('filmes_id_seq'); // Verifique se o nome da sequência está correto no seu banco

    // Inserir as categorias selecionadas na tabela filme_categoria
    if (!empty($categorias)) {
        $stmtCategoria = $pdo->prepare("INSERT INTO filme_categoria (id_filme, id_categoria) VALUES (?, ?)");

        foreach ($categorias as $categoriaId) {
            if (!empty($categoriaId)) {
                $stmtCategoria->execute([$filmeId, $categoriaId]);
            }
        }
    }

    // Commit da transação
    $pdo->commit();

    header('Location: ../painel.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    // Se der erro, desfaz a transação
    $pdo->rollBack();
    echo "Erro ao adicionar filme: " . $e->getMessage();
    exit;
}
?>
