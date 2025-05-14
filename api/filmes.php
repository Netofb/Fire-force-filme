<?php
header('Content-Type: application/json');

$host = "localhost";
$port = "5432";
$dbname = "fff"; 
$user = "postgres";       
$password = "fabio99248033";  

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(['erro' => 'Erro na conexão com o banco']);
    exit;
}

// Alterando a consulta para buscar filmes com destaque = true (ou false, dependendo do caso)
$query = "SELECT titulo AS nome, capa_url AS capa, link FROM filmes WHERE destaque = $1";
$result = pg_query_params($conn, $query, [true]); // Aqui estamos buscando filmes com destaque TRUE

if (!$result) {
    echo json_encode(['erro' => 'Erro na consulta']);
    exit;
}

$filmes = pg_fetch_all($result);

// Verifica se o resultado não é falso e retorna os filmes, senão retorna um array vazio
echo json_encode($filmes ? $filmes : []); 
exit;
