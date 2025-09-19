<?php

function carregarEnv($caminhoBase) {
    $caminhoEnv = $caminhoBase . '/.env';
    
    if (!file_exists($caminhoEnv)) {
        return false;
    }
    
    $linhas = file($caminhoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($linhas as $linha) {
        // Pula comentários
        if (strpos(trim($linha), '#') === 0) {
            continue;
        }
        
        // Divide chave=valor
        if (strpos($linha, '=') !== false) {
            list($chave, $valor) = explode('=', $linha, 2);
            $chave = trim($chave);
            $valor = trim($valor);
            
            // Remove aspas se existirem
            if (preg_match('/^"(.*)"$/', $valor, $matches) || preg_match('/^\'(.*)\'$/', $valor, $matches)) {
                $valor = $matches[1];
            }
            
            // Define nas variáveis de ambiente
            $_ENV[$chave] = $valor;
            $_SERVER[$chave] = $valor;
        }
    }
    
    return true;
}

// Carrega o .env
carregarEnv(dirname(__DIR__));

// Configuração da conexão com Supabase
$host = $_ENV['DB_HOST'] ?? 'aws-0-sa-east-1.pooler.supabase.com';
$port = $_ENV['DB_PORT'] ?? '6543';
$dbname = $_ENV['DB_NAME'] ?? 'postgres';
$user = $_ENV['DB_USER'] ?? 'postgres.wfyvvnoxncvawnojgkul';
$password = $_ENV['DB_PASSWORD'] ?? 'lBk8rZ6rR3GJbTIr';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 10
    ]);
    
} catch (PDOException $e) {
    die("❌ Erro de conexão: " . $e->getMessage());
}
?>