<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carrega .env apenas em desenvolvimento
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();  // safeLoad() ignora se o arquivo estiver vazio
}

// Obtém configurações com fallback
$dbConfig = [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST'),
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '5432',
    'dbname' => $_ENV['DB_NAME'] ?? getenv('DB_NAME'),
    'user' => $_ENV['DB_USER'] ?? getenv('DB_USER'),
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')
];

// Verificação robusta
$missing = array_filter($dbConfig, fn($value) => empty($value));
if (!empty($missing)) {
    header('Content-Type: application/json');
    die(json_encode([
        'error' => 'Database configuration incomplete',
        'missing' => array_keys($missing)
    ]));
}

// Conexão com tratamento de erros
try {
    $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s",
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['dbname']
    );
    
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Teste de conexão
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Database connection error');
}