<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

$databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

if (!$databaseUrl) {
    die("Erro: DATABASE_URL não configurada.");
}

// Faz o parse da URL
$components = parse_url($databaseUrl);

if (!$components) {
    die("Erro: DATABASE_URL inválida.");
}

$host = $components['host'];
$port = $components['port'] ?? '5432';
$user = $components['user'];
$pass = $components['pass'];
$dbname = ltrim($components['path'], '/');

// DSN para PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Teste de conexão
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
