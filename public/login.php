<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario'] = $usuario['nome'];
                header("Location: index.php");
                exit;
            } else {
                echo "Senha incorreta!";
            }
        } else {
            echo "Usuário não encontrado!";
        }
    } catch (PDOException $e) {
        echo "Erro no login: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - FireForce Filmes</title>
    <link rel="stylesheet" href="styles/style.css">
</head>



<body>

    <div id="intro" class="intro-container">
    <img src="img/logofire.svg" alt="Logo de abertura" />
    </div>


    <main class="main_login" id="main-content" style="display: none;">
        <form method="POST" class="container_login">
            <div class="container_title_login">
                <img src="img/logofire.svg" alt="" width="200px">
             <h2 class="Title_login">Login</h2>
            </div>
            <input class="input_login" type="email" name="email" placeholder="E-mail" required>
            <input class="input_login" type="password" name="senha" placeholder="Senha" required>
            <div>
               
                <button class="btn_login" type="submit">Entrar</button>
                <a class="btn_register" href="registro.php">registro</a>
            </div>
            
        </form>
        </main>
</body>
<script>
  // Evita rolagem enquanto a intro está ativa
  document.body.classList.add("no-scroll");

  setTimeout(() => {
    const intro = document.getElementById("intro");
    const main = document.getElementById("main-content");

    intro.style.display = "none";
    main.style.display = "flex";
    document.body.classList.remove("no-scroll"); // libera rolagem
  }, 1500); // 3s de exibição + 1s de animação
</script>

</html>
