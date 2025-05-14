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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = pg_escape_string($_POST['titulo']);
    $capa_url = pg_escape_string($_POST['capa_url']);
    $link = pg_escape_string($_POST['link']);
    $genero = pg_escape_string($_POST['genero']);
    $categorias = $_POST['categorias']; // Este campo é um array

    // Inserir o filme na tabela de filmes
    $query_filme = "INSERT INTO filmes (titulo, capa_url, link, genero) VALUES ($1, $2, $3, $4) RETURNING id";
    $result_filme = pg_query_params($conn, $query_filme, [$titulo, $capa_url, $link, $genero]);

    if ($result_filme) {
        $filme = pg_fetch_assoc($result_filme);
        $id_filme = $filme['id'];

        // Inserir categorias, se houver
        if (!empty($categorias)) {
            foreach ($categorias as $categoria_id) {
                $query_categoria = "INSERT INTO filme_categoria (id_filme, id_categoria) VALUES ($1, $2)";
                $result_categoria = pg_query_params($conn, $query_categoria, [$id_filme, $categoria_id]);
                
                if (!$result_categoria) {
                    echo "Erro ao adicionar categoria: " . pg_last_error($conn);
                }
            }
        }
        header("Location: ../painel.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao adicionar filme: " . pg_last_error($conn);
        header("Location: ../painel.php?erro=1");
        exit();
    }
}
?>
