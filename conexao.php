<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carrega .env apenas se existir (para desenvolvimento)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Configuração segura com fallback
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
$dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$user = $_ENV['DB_USER'] ?? getenv('DB_USER');
$password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

// Verifica se todas as variáveis existem
if (!$host || !$port || !$dbname || !$user || !$password) {
    die("Erro: Configuração do banco de dados incompleta");
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}