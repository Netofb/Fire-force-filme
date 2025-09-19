<?php
// includes/conexao.php - Compatível com Docker

// Carrega variáveis de ambiente do Docker
$host = getenv('DB_HOST') ?: 'aws-0-sa-east-1.pooler.supabase.com';
$port = getenv('DB_PORT') ?: '6543';
$dbname = getenv('DB_NAME') ?: 'postgres';
$user = getenv('DB_USER') ?: 'postgres.wfyvvnoxncvawnojgkul';
$password = getenv('DB_PASSWORD') ?: 'lBk8rZ6rR3GJbTIr';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
} catch (PDOException $e) {
    die("❌ Erro de conexão: " . $e->getMessage());
}
?>