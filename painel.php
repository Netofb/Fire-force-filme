<?php
require_once __DIR__ . '/conexao.php';

header('Content-Type: text/html; charset=utf-8');

// Buscar filmes
$filmes = [];
try {
    $query = "
        SELECT 
            filmes.id, 
            filmes.titulo, 
            filmes.capa_url, 
            filmes.link, 
            filmes.genero, 
            COALESCE(STRING_AGG(categorias.nome, ', '), 'Sem categoria') AS categoria
        FROM filmes
        LEFT JOIN filme_categoria ON filmes.id = filme_categoria.id_filme
        LEFT JOIN categorias ON categorias.id = filme_categoria.id_categoria
        GROUP BY filmes.id
        ORDER BY filmes.id DESC
    ";
    $stmt = $pdo->query($query);
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar filmes: " . $e->getMessage();
    exit;
}

// Buscar categorias
$categorias = [];
try {
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar categorias: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Adição de Filmes</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 100px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function confirmarExclusao(idFilme) {
            const modal = document.getElementById('modal-confirm');
            const confirmarBtn = document.getElementById('confirmar-exclusao');
            const cancelarBtn = document.getElementById('cancelar-exclusao');

            modal.style.display = 'block';

            confirmarBtn.onclick = function() {
                window.location.href = `paginas/excluir_filmes.php?id=${idFilme}`;
            };

            cancelarBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modal-confirm');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <main class="painel_container">
        <div>
            <img src="img/logofire.svg" alt="Logo">
            <h1 class="painel_title">Painel</h1>
        </div>

        <form class="painel_form" action="paginas/adicionar_filmes.php" method="POST">
            <label class="painel_label" for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label class="painel_label" for="capa_url">URL da Capa:</label>
            <input type="text" name="capa_url" id="capa_url" required>

            <label class="painel_label" for="link">Link do Filme:</label>
            <input type="url" name="link" id="link" required>

            <label class="painel_label" for="genero">Gênero:</label>
            <select name="genero" id="genero" required>
                <option value="filme">Filme</option>
                <!-- <option value="serie">Série</option> -->
            </select>

            <label class="painel_label" for="categorias">Categorias:</label>
            <select name="categorias[]" id="categorias" multiple>
                <option value="">Nenhum</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['id']) ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Adicionar Filme</button>
        </form>

        <?php if (isset($_GET['sucesso'])): ?>
            <p style="color: green;">Filme adicionado com sucesso!</p>
        <?php elseif (isset($_GET['erro'])): ?>
            <p style="color: red;">Erro ao adicionar o filme.</p>
        <?php endif; ?>

        <h2>Filmes Cadastrados</h2>
        <table class="painel_table">
            <thead>
                <tr class="painel_tr">
                    <th>ID</th>
                    <th>Título</th>
                    <th>Capa</th>
                    <th>Gênero</th>
                    <th>Categorias</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filmes as $filme): ?>
                    <tr class="painel_tr">
                        <td><?= htmlspecialchars($filme['id']) ?></td>
                        <td><?= htmlspecialchars($filme['titulo']) ?></td>
                        <td><img class="painel_capa" src="<?= htmlspecialchars($filme['capa_url']) ?>" alt="Capa"></td>
                        <td><?= htmlspecialchars($filme['genero']) ?></td>
                        <td><?= htmlspecialchars($filme['categoria'] ?? 'Sem categoria') ?></td>
                        <td>
                            <a class="painel_edit" href="paginas/editar_filmes.php?id=<?= htmlspecialchars($filme['id']) ?>">
                                <img src="img/pen.svg" alt="">Editar
                            </a> 
                            <a class="painel_delet" href="#" onclick="confirmarExclusao(<?= $filme['id'] ?>)">
                                <img src="img/trash.svg" alt="">Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <div id="modal-confirm" class="modal">
        <div class="modal-content">
            <h4>Confirmar Exclusão</h4>
            <p>Tem certeza que deseja excluir este filme?</p>
            <button id="confirmar-exclusao">Sim</button>
            <button id="cancelar-exclusao">Cancelar</button>
        </div>
    </div>
</body>
</html>
