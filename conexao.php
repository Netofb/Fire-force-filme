<?php
require_once __DIR__ . '/vendor/autoload.php';

// Configuração de ambiente
$isProduction = getenv('RENDER') !== false;

if (!$isProduction && file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// Obtenção das credenciais
$dbConfig = [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST'),
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '5432',
    'dbname' => $_ENV['DB_NAME'] ?? getenv('DB_NAME'),
    'user' => $_ENV['DB_USER'] ?? getenv('DB_USER'),
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')
];

// Verificação detalhada
foreach ($dbConfig as $key => $value) {
    if (empty($value)) {
        error_log("Variável faltando: DB_".strtoupper($key));
    }
}

try {
    // Conexão com SSL para Supabase
    $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s",
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['dbname']
    );
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::PGSQL_ATTR_SSL_MODE => PDO::PGSQL_SSL_MODE_REQUIRE
    ];
    
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], $options);
    
    // Teste de conexão
    $stmt = $pdo->query("SELECT 1");
    if ($stmt->fetchColumn() != 1) {
        throw new PDOException("Test query failed");
    }
    
    return $pdo;
} catch (PDOException $e) {
    error_log("ERRO DE CONEXÃO: " . $e->getMessage());
    error_log("Tentando conectar em: ".$dsn);
    throw new Exception("Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}