<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = pg_connect("host=localhost port=5432 dbname=fff user=postgres password=fabio99248033");
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (nome, email, senha) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $query, [$nome, $email, $senha]);

    if ($result) {
        header("Location: login.php");
        exit;
    } else {
        echo "Erro ao registrar usuário.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - FireForce Filmes</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <main class="main_login">
        <form method="POST" class="container_login" >
        <div class="container_title_login">
                <img src="img/logofire.svg" alt="" width="200px">
             <h2 class="Title_login">CRIE SUA CONTA</h2>
            </div>
            
            <input class="input_login" type="text" name="nome" placeholder="Nome" required>
            <input class="input_login" type="email" name="email" placeholder="E-mail" required>
            <input class="input_login" type="password" name="senha" placeholder="Senha" required>
            <div>
                <button class="btn_login" type="submit">Registrar</button>
                <a class="btn_register" href="login.php">login</a>
            </div>
        </form>
    </main>
</body>
</html>
