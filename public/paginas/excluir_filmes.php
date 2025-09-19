<?php
require_once __DIR__ . '/../includes/conexao.php';

// Verifica se o ID foi passado e é válido
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id || !is_numeric($id)) {
    die("ID inválido.");
}

try {
    // Exclui registros na tabela de relacionamento primeiro (se houver)
    $stmt = $pdo->prepare("DELETE FROM filme_categoria WHERE id_filme = :id");
    $stmt->execute(['id' => $id]);

    // Exclui o filme na tabela principal
    $stmt = $pdo->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: ../painel.php?sucesso=1");
    exit;
} catch (PDOException $e) {
    error_log("Erro ao excluir filme: " . $e->getMessage());
    header("Location: ../painel.php?erro=1");
    exit;
}
?>
