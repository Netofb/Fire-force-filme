<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// 🔥 CORREÇÃO: Use $_SERVER em vez de $_ENV para Dotenv
$databaseUrl = $_SERVER['DATABASE_URL'] ?? $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

// Se ainda não encontrar, use valores padrão
if (!$databaseUrl) {
    // Valores fallback diretos (use suas credenciais)
    $databaseUrl = 'pgsql://postgres.wfyvvnoxncvawnojgkul:lBk8rZ6rR3GJbTIr@aws-0-sa-east-1.pooler.supabase.com:6543/postgres';
    
    // Ou use variáveis individuais como fallback
    if (!$databaseUrl) {
        $host = $_SERVER['DB_HOST'] ?? $_ENV['DB_HOST'] ?? 'aws-0-sa-east-1.pooler.supabase.com';
        $port = $_SERVER['DB_PORT'] ?? $_ENV['DB_PORT'] ?? '6543';
        $user = $_SERVER['DB_USER'] ?? $_ENV['DB_USER'] ?? 'postgres.wfyvvnoxncvawnojgkul';
        $password = $_SERVER['DB_PASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? 'lBk8rZ6rR3GJbTIr';
        $dbname = $_SERVER['DB_NAME'] ?? $_ENV['DB_NAME'] ?? 'postgres';
        
        $databaseUrl = "pgsql://$user:$password@$host:$port/$dbname";
    }
}

if (!$databaseUrl) {
    die("Erro: DATABASE_URL não configurada.");
}

// Resto do código permanece igual
$components = parse_url($databaseUrl);

if (!$components || !isset($components['host'])) {
    die("Erro: DATABASE_URL inválida.");
}

$host = $components['host'] ?? '';
$port = $components['port'] ?? '5432';
$user = $components['user'] ?? '';
$pass = $components['pass'] ?? '';
$path = $components['path'] ?? '/postgres';
$dbname = ltrim($path, '/');

// DSN para PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 10
    ]);

    // Teste de conexão
    $pdo->query("SELECT 1");
    
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>