<?php
header('Content-Type: application/json');

// Carrega variáveis de ambiente
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Conexão usando DATABASE_URL (recomendado pelo Supabase)
    $dbUrl = $_ENV['DATABASE_URL'];
    $conn = pg_connect($dbUrl);
    
    if (!$conn) {
        throw new Exception("Falha na conexão: " . pg_last_error());
    }

    // Consulta parametrizada (proteção contra SQL Injection)
    $query = "SELECT titulo AS nome, capa_url AS capa, link FROM filmes WHERE destaque = $1";
    $result = pg_query_params($conn, $query, [true]);

    if (!$result) {
        throw new Exception("Erro na consulta: " . pg_last_error());
    }

    $filmes = pg_fetch_all($result) ?: []; // Operador null coalescing
    
    // Resposta padrão API
    $response = [
        'status' => 'success',
        'data' => $filmes,
        'timestamp' => time()
    ];

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'code' => 500
    ];
    
    http_response_code(500);
} finally {
    if (isset($conn)) pg_close($conn);
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);