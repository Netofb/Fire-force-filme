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

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Verificar se o ID é válido (número)
if (!$id || !is_numeric($id)) {
    die("ID inválido.");
}

// Consultar o filme
$query = "SELECT * FROM filmes WHERE id = $1";
$result = pg_query_params($conn, $query, [$id]);
$filme = pg_fetch_assoc($result);

if (!$filme) {
    die("Filme não encontrado.");
}

// Verificar se a chave 'genero' está definida no array antes de usá-la
$generoSelecionado = isset($filme['genero']) ? $filme['genero'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $capa_url = $_POST['capa_url'] ?? '';
    $link = $_POST['link'] ?? '';
    $genero = $_POST['genero'] ?? ''; // Alterado para 'genero'
    $destaque = $_POST['destaque'] ?? null;  // Capturando o valor de destaque (opcional)
    $categorias = $_POST['categorias'] ?? []; // Capturando categorias (IDs)

    // Validar campos obrigatórios
    if (empty($titulo) || empty($capa_url) || empty($link) || empty($genero)) {
        die("Por favor, preencha todos os campos obrigatórios.");
    }

    // Atualizar as informações do filme
    $update = "UPDATE filmes SET titulo = $1, capa_url = $2, link = $3, genero = $4, destaque = $5 WHERE id = $6"; // Alterado para 'genero'
    pg_query_params($conn, $update, [$titulo, $capa_url, $link, $genero, $destaque, $id]);

    // Limpar categorias associadas
    $delete_categories = "DELETE FROM filme_categoria WHERE id_filme = $1";
    pg_query_params($conn, $delete_categories, [$id]);

    // Reassociar as categorias selecionadas
    if (!empty($categorias)) {
        foreach ($categorias as $categoria) {
            $insert_category = "INSERT INTO filme_categoria (id_filme, id_categoria) VALUES ($1, $2)";
            pg_query_params($conn, $insert_category, [$id, $categoria]);
        }
    }

    pg_set_client_encoding($conn, "UTF8");
    header("Location: ../painel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Filme</title>
  <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="painel_geral">
  <main class="painel_container">
    <h1 class="painel_title">Editar Filme</h1>

    <form class="painel_form" method="POST">
      <label class="painel_label" for="titulo">Título:</label>
      <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($filme['titulo']) ?>" required>

      <label class="painel_label" for="capa_url">URL da Capa:</label>
      <input type="text" name="capa_url" id="capa_url" value="<?= htmlspecialchars($filme['capa_url']) ?>" required>

      <label class="painel_label" for="link">Link do Filme:</label>
      <input type="text" name="link" id="link" value="<?= htmlspecialchars($filme['link']) ?>" required>

      <label class="painel_label" for="genero">Gênero:</label>
      <select name="genero" id="genero" required>
        <option value="filme" <?= $generoSelecionado == 'filme' ? 'selected' : '' ?>>Filme</option>
        <!-- Adicione outros gêneros, se necessário -->
      </select>

      <label class="painel_label" for="categorias">Categorias:</label>
      <select name="categorias[]" id="categorias" multiple>
        <?php
        // Buscar todas as categorias
        $query_categorias = "SELECT id, nome FROM categorias ORDER BY nome";
        $categorias_result = pg_query($conn, $query_categorias);

        // Buscar categorias associadas ao filme
        $query_categorias_filme = "SELECT id_categoria FROM filme_categoria WHERE id_filme = $1";
        $categorias_filme_result = pg_query_params($conn, $query_categorias_filme, [$id]);
        $categorias_filme = [];
        while ($row = pg_fetch_assoc($categorias_filme_result)) {
            $categorias_filme[] = $row['id_categoria'];
        }

        while ($categoria = pg_fetch_assoc($categorias_result)) {
            $selected = in_array($categoria['id'], $categorias_filme) ? 'selected' : '';
            echo "<option value=\"{$categoria['id']}\" $selected>{$categoria['nome']}</option>";
        }
        ?>
      </select>

      <button type="submit">Salvar Alterações</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
      <a href="../painel.php" style="color: #ff4500;">← Voltar para o painel</a>
    </p>
  </main>
</body>
</html>
