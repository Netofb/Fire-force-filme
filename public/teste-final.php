<?php
require_once __DIR__ . '/../includes/conexao.php';

echo "<h1>✅ Teste de Conexão Completo</h1>";
echo "Variáveis carregadas do .env:<br>";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'Não carregado') . "<br>";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'Não carregado') . "<br>";

try {
    $stmt = $pdo->query("SELECT NOW() as tempo, version() as versao");
    $resultado = $stmt->fetch();
    
    echo "🕒 Hora do banco: " . $resultado['tempo'] . "<br>";
    echo "🐘 Versão PostgreSQL: " . $resultado['versao'] . "<br>";
    echo "🎯 Conexão com Supabase funcionando perfeitamente!";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>