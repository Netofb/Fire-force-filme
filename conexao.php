<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

$dbConfig = [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST'),
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '5432',
    'dbname' => $_ENV['DB_NAME'] ?? getenv('DB_NAME'),
    'user' => $_ENV['DB_USER'] ?? getenv('DB_USER'),
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')
];

// Verifica configuração
foreach ($dbConfig as $key => $value) {
    if (empty($value)) {
        die("Erro: Configuração DB_".strtoupper($key)." faltando");
    }
}

try {
    // Conexão com SSL via string DSN
    $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};sslmode=require";
    
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Teste de conexão
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}