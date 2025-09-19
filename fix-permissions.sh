#!/bin/bash
# fix-permissions.sh - Corrige permissões para Render.com

echo "🔧 Ajustando permissões..."

# Dar permissões de leitura para todos os arquivos
find . -type f -exec chmod 644 {} \;

# Dar permissões de execução para diretórios
find . -type d -exec chmod 755 {} \;

# Permissões especiais para scripts PHP
chmod 755 public/
chmod 644 public/.htaccess
chmod 644 public/index.php
chmod 644 public/*.php

echo "✅ Permissões ajustadas!"