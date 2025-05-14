<?php
$host = "localhost";
$port = "5432";
$dbname = "fff"; 
$user = "postgres";       
$password = "fabio99248033";  

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Erro na conexão com o banco de dados.");
}
pg_set_client_encoding($conn, "UTF8");
// Verifica se o ID foi passado e é válido
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($id) {
    // Realiza a exclusão do filme
    $query = "DELETE FROM filmes WHERE id = $1";
    $result = pg_query_params($conn, $query, [$id]);

    // Verifica se a exclusão foi realizada com sucesso
    if ($result) {
        header("Location: ../painel.php?sucesso=1");
    } else {
        header("Location: ../painel.php?erro=1");
    }
} else {
    header("Location: ../painel.php?erro=1");
}

exit;
