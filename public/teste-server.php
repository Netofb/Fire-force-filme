<?php
echo "<h1>🩺 Diagnóstico do Servidor</h1>";

// Informações do servidor
echo "<h2>📋 Informações do Servidor:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";

// Permissões
echo "<h2>🔐 Permissões:</h2>";
$paths = [
    '.',
    'index.php',
    '.htaccess',
    'styles/',
    'scripts/',
    'img/'
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        $perms = fileperms($path);
        echo "$path: " . substr(sprintf('%o', $perms), -4) . "<br>";
    } else {
        echo "$path: ❌ Não existe<br>";
    }
}

// Teste de escrita
echo "<h2>✍️ Teste de Escrita:</h2>";
$testFile = 'teste_write.txt';
if (file_put_contents($testFile, 'Teste de escrita ' . date('Y-m-d H:i:s'))) {
    echo "✅ Escrita permitida<br>";
    unlink($testFile);
} else {
    echo "❌ Escrita não permitida<br>";
}

// Variáveis de ambiente
echo "<h2>🌐 Variáveis de Ambiente:</h2>";
echo "PORT: " . ($_ENV['PORT'] ?? 'Não definida') . "<br>";
echo "DATABASE_URL: " . (!empty($_ENV['DATABASE_URL']) ? 'Definida' : 'Não definida') . "<br>";
?>